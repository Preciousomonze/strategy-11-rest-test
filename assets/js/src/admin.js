import React, { useEffect, useState } from 'react';
import { createRoot } from '@wordpress/element';
import domReady from '@wordpress/dom-ready';
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';
import { Notice, Button } from '@wordpress/components';

// Store data row count.
let rowCount = 0;
 
/**
 * AdminDataTable component that fetches and displays data in a table format with a refresh button.
 */
const AdminDataTable = () => {
    const [ data, setData ] = useState( null );
    const [ dataLoaded, setDataLoaded ] = useState( false );
    const [ sortConfig, setSortConfig ] = useState( { key: 'id', direction: 'ascending' } );

    // State to track which rows are expanded.
    const [ expandedRows, setExpandedRows ] = useState( {} );

    /**
     * Fetches data from the custom REST API endpoint and sets the state.
     */
    const loadData = () => {
        setDataLoaded( false );
        apiFetch( { path: '/strategy11/v1/data' } )
            .then( ( result ) => {
                setData( result );

                // Update the rowCount.
                rowCount = result && result.data && result.data.rows ? Object.keys( result.data.rows ).length : 0;
                updateCounter( rowCount );
            } )
            .catch( error => console.error( 'Error fetching data:', error ) )
            .finally( () => {
                setDataLoaded( true );
            } );
    };

    /**
     * Refreshes the data by making an AJAX request to a custom admin action and reloads the data.
     */
    const refreshData = () => {
        setDataLoaded( false );
        apiFetch( {
            path: ajaxurl,
            method: 'POST',
            data: {
                action: 'cx_strategy11_refresh_data',
            },
        } )
        .then( loadData )
        .finally( () => {
            setDataLoaded( true );
        } );
    };

    /**
     * Handler to toggle row expansion.
     *
     * @param {number} id - The ID of the row to toggle.
     */
    const handleToggle = ( id ) => {
        setExpandedRows( ( prevExpandedRows ) => ( {
            ...prevExpandedRows,
            [ id ]: !prevExpandedRows[ id ]
        }));
    };

    /**
     * Update our counter Element
     * 
     * @param {int} count 
     */
    const updateCounter = ( count ) => {
        // Update the counter display after data is loaded.
        const counterElement = document.querySelector( '.cx-s-counter-card .counter' );
        if ( counterElement ) {
            counterElement.textContent = dataLoaded ? count : __( 'Loading... 🚦',  'strategy-11-rest-test' );
        }
    };

    // Run our effect, oporrr.
    useEffect( () => {
        loadData();
    }, [] );

    if ( ! data ) {
        updateCounter( rowCount );

        if ( dataLoaded ) {
            return <>
                <Notice>{ __( 'No Data Available At The Moment. 😪', 'strategy-11-rest-test' ) }</Notice>
                <Button onCLick={ loadData }>Try Again</Button>
            </>;
        } else {
            return <></>;
        }
    }

    /**
     * Sorts the rows based on the sort configuration.
     */
    const sortedRows = Object.values( data.data.rows ).sort( ( a, b ) => {
        if ( a[ sortConfig.key ] < b[ sortConfig.key ] ) {
            return sortConfig.direction === 'ascending' ? -1 : 1;
        }
        if ( a[ sortConfig.key ] > b[ sortConfig.key ] ) {
            return sortConfig.direction === 'ascending' ? 1 : -1;
        }
        return 0;
    } );

    /**
     * Handles sorting by updating the sort configuration.
     */
    const requestSort = key => {
        let direction = 'ascending';
        if ( sortConfig.key === key && sortConfig.direction === 'ascending' ) {
            direction = 'descending';
        }
        setSortConfig( { key, direction } );
    };

    updateCounter( rowCount );
    
    return (
            <div className="cx-s-dashboard-widget cx-s-justify-between">
                <div className="cx-s-before-table">
                    <h2>{ data.title }</h2> 
                    <button onClick={ refreshData } className="button cx-s-button-primary cx-s-widget-cta">
                        { __( 'Refresh Data', 'strategy-11-rest-test' ) }
                    </button>
                </div>
                <table className="wp-list-table widefat table-view-list fixed striped">
                    <thead>
                        <tr>
                        { data.data.headers.map( header => (
                            <th key={ header }>
                                { header }
                                <span title="sort" className="dashicons dashicons-sort" onClick={ () => requestSort( header.toLowerCase().replace( / /g, '' ) ) }></span>
                            </th>
                        ) ) }
                        </tr>
                    </thead>
                    <tbody id="the-list">
                    { sortedRows.map( ( row, index ) => ( 
                        <tr key={ row.id } className={ expandedRows[ row.id ] ? 'cx-s-is-expanded' : '' }>
                            <td>{ row.id }
                            <button type="button" className="toggle-row" onClick={() => handleToggle( row.id )}>
                                <span className="screen-reader-text">Show more details</span>
                            </button>
                            </td>
                            <td className={ expandedRows[ row.id ] ? 'show' : 'hide' }>
                                <span className="cx-s-mobile-theader">{ data.data.headers[1] }</span>
                                <span className="cx-s-tdata">{ row.fname }</span>
                            </td>
                            <td className={ expandedRows[ row.id ] ? 'show' : 'hide' }>
                                <span className="cx-s-mobile-theader">{ data.data.headers[2] }</span>
                                <span className="cx-s-tdata">{ row.lname }</span>
                            </td>
                            <td className={ expandedRows[ row.id ] ? 'show' : 'hide' }>
                                <span className="cx-s-mobile-theader">{ data.data.headers[3] }</span>
                                <span className="cx-s-tdata">{ row.email }</span>
                            </td>
                            <td className={ expandedRows[ row.id ] ? 'show' : 'hide' }>
                                <span className="cx-s-mobile-theader">{ data.data.headers[4] }</span>
                                <span className="cx-s-tdata">{ new Date( row.date * 1000 ).toLocaleDateString( __( 'en-US', 'strategy-11-rest-test' ), { year: 'numeric', month: 'long', day: 'numeric' } ) }</span>
                            </td>
                        </tr>
                    ) ) }
                    </tbody>
                </table>
            </div>
        );
};

domReady( () => {
    const container = document.getElementById( 'cx-strategy11-admin-data-table' );
    if ( container ) { // Properly handle if container is found or not.
        const root = createRoot( container );
        root.render( <AdminDataTable /> );
    } else { // Cause I dislike seeing default console errors :(.
        console.error( 'Element with ID "cx-strategy11-admin-data-table" not found.' );
    }
});
