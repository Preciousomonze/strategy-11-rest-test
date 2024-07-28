const path = require( 'path' );

module.exports = {
    mode: 'development',
    entry: {
        frontend: './assets/js/src/frontend.js',
        admin: './assets/js/src/admin.js',
    },
    output: {
        path: path.resolve( __dirname, 'assets/js/build' ),
        filename: '[name].js',
    },
    module: {
        rules: [
            {
                test: /\.(js|jsx)?$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: [ '@babel/preset-env', '@babel/preset-react' ],
                    },
                },
            },
        ],
    },
    resolve: {
        extensions: [ '.js' ],
    },
    externals: {
        react: 'React',
        'react-dom': 'ReactDOM',
        '@wordpress/element': 'wp.element',
        '@wordpress/i18n': 'wp.i18n',
        '@wordpress/api-fetch': 'wp.apiFetch',
    },
};
