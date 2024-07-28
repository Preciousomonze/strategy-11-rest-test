import React, { useEffect, useState } from 'react';
import { createRoot } from 'react-dom/client';
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';

/**
 * DataTable component that fetches and displays data in a table format.
 */
const DataTable = () => {
    const [ data, setData ] = useState( null );

    useEffect( () => {
        // Fetch data from the custom REST API endpoint.
        apiFetch( { path: '/strategy11/v1/data' } )
            .then( setData )
            .catch( error => console.error( 'Error fetching data:', error ) );
    }, [] );

    if ( ! data ) {
        return <div>{ __( 'Loading... 🚦', 'strategy-11-rest-test' ) }</div>;
    }

    return (
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
    );
};

document.addEventListener( 'DOMContentLoaded', () => {
    const container = document.getElementById( 'cx-strategy11-data-table' );
    if ( container ) {
        const root = createRoot( container );
        root.render( <DataTable /> );
    } else {
        console.error( 'Element with ID "cx-strategy11-data-table" not found.' );
    }
});
