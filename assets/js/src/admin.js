import React, { useEffect, useState } from 'react';
import { createRoot } from '@wordpress/element';
import domReady from '@wordpress/dom-ready';
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';

/**
 * AdminDataTable component that fetches and displays data in a table format with a refresh button.
 */
const AdminDataTable = () => {
    const [ data, setData ] = useState( null );

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
        return <div>{ __( 'Loading... oooo', 'strategy-11-rest-test' ) }</div>;
    }

    return (
        <div>
            <table>
                <thead>
                    <tr>
                        { Object.keys( data[0] ).map( key => (
                            <th key={ key }>{ key }</th>
                        ) ) }
                    </tr>
                </thead>
                <tbody>
                    { data.map( ( row, index ) => (
                        <tr key={ index }>
                            { Object.values( row ).map( ( value, i ) => (
                                <td key={ i }>{ value }</td>
                            ) ) }
                        </tr>
                    ) ) }
                </tbody>
            </table>
            <button onClick={ refreshData } className="button">{ __( 'Refresh Data', 'strategy-11-rest-test' ) }</button>
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
