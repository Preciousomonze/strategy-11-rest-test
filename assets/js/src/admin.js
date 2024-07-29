import React, { useEffect, useState } from 'react';
import { createRoot } from '@wordpress/element';
import domReady from '@wordpress/dom-ready';
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';
import { Panel, PanelBody, PanelRow, Notice, Button } from '@wordpress/components';
import '../../scss/admin.scss';
 
/**
 * AdminDataTable component that fetches and displays data in a table format with a refresh button.
 */
const AdminDataTable = () => {
    const [ data, setData ] = useState( null );
    const [ sortConfig, setSortConfig ] = useState( { key: 'id', direction: 'ascending' } );

    /**
     * Fetches data from the custom REST API endpoint and sets the state.
     */
    const loadData = () => {
        apiFetch( { path: '/strategy11/v1/data' } )
            .then( setData )
            .catch( error => console.error( 'Error fetching data:', error ) );
    };

    /**
     * Refreshes the data by making an AJAX request to a custom admin action and reloads the data.
     */
    const refreshData = () => {
        apiFetch( {
            path: '/wp-admin/admin-ajax.php',
            method: 'POST',
            data: {
                action: 'strategy11_refresh_data',
            },
        } ).then( loadData );
    };

    useEffect( () => {
        loadData();
    }, [] );

    if ( ! data ) {
        return <>
            <Notice>{ __( 'Loading... 🚦', 'strategy-11-rest-test' ) }</Notice>
            <Button></Button>
        </>;
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

    return (
        <Panel>
            <PanelBody 
                title={ __( 'CodeXplorer Strategy 11 Data Table', 'strategy-11-rest-test' ) }
                initialOpen={ true }
             >
                <PanelRow>
                <div>
                    <h2>{ data.title }</h2>
            <table>
                <thead>
                    <tr>
                        { data.data.headers.map( header => (
                            <th key={ header } onClick={ () => requestSort( header.toLowerCase().replace( / /g, '' ) ) }>
                                { header }
                            </th>
                        ) ) }
                    </tr>
                </thead>
                <tbody>
                    { sortedRows.map( ( row, index ) => (
                        <tr key={ index }>
                            <td>{ row.id }</td>
                            <td>{ row.fname }</td>
                            <td>{ row.lname }</td>
                            <td>{ row.email }</td>
                            <td>{ new Date( row.date * 1000 ).toLocaleDateString() }</td>
                        </tr>
                    ) ) }
                </tbody>
            </table>
            <button onClick={ refreshData } className="button">
                { __( 'Refresh Data', 'strategy-11-rest-test' ) }
            </button>
        </div>

                </PanelRow>
                <PanelRow>
                    <div>Placeholder for display control</div>
                </PanelRow>
            </PanelBody>
            </Panel>
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
