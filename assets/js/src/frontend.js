import React, { useEffect, useState } from 'react';
import { createRoot } from '@wordpress/element';
import domReady from '@wordpress/dom-ready';
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';
import { Notice, Button } from '@wordpress/components';

/**
 * DataTable component that fetches and displays data in a table format with a refresh button.
 */
const DataTable = () => {
    const [ data, setData ] = useState( null );
    const [ dataLoaded, setDataLoaded ] = useState( false );
 
    /**
     * Fetches data from the custom REST API endpoint and sets the state.
     */
    const loadData = () => {
        setDataLoaded( false );
        apiFetch( { path: '/strategy11/v1/data' } )
            .then( ( result ) => {
                setData( result );
            } )
            .catch( error => console.error( 'Error fetching data:', error ) )
            .finally( () => {
                setDataLoaded( true );
            } );
    };

    // Run our effect, oporrr.
    useEffect( () => {
        loadData();
    }, [] );

    if ( ! data ) {
        if ( dataLoaded ) {
            return <>
                <Notice>{ __( 'No Data Available At The Moment. 😪', 'strategy-11-rest-test' ) }</Notice>
                <Button onCLick={ loadData }>Try Again</Button>
            </>;
        } else {
            return <></>;
        }
    }
    return (
                <table>
                    <thead>
                        <tr>
                        { data.data.headers.map( header => (
                            <th key={ header }>
                                { header }
                            </th>
                        ) ) }
                        </tr>
                    </thead>
                    <tbody>
                    { Object.values( data.data.rows ).map( ( row, index ) => ( 
                        <tr key={ row.id }>
                            <td>{ row.id }</td>
                            <td>{ row.fname }</td>
                            <td>{ row.lname }</td>
                            <td>{ row.email }</td>
                            <td>{ new Date( row.date * 1000 ).toLocaleDateString( __( 'en-US', 'strategy-11-rest-test' ), { year: 'numeric', month: 'long', day: 'numeric' } ) }</td>
                        </tr>
                    ) ) }
                    </tbody>
                </table>
        );
};

domReady( () => {
    const container = document.getElementById( 'cx-strategy11-data-table' );
    if ( container ) { // Properly handle if container is found or not.
        const root = createRoot( container );
        root.render( <DataTable /> );
    } else { // Cause I dislike seeing default console errors :(.
        console.error( 'Element with ID "cx-strategy11-data-table" not found.' );
    }
});
