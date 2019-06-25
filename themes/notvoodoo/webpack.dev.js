const path = require('path');
const browserSync = require('browser-sync-webpack-plugin');
const merge = require('webpack-merge');
const common = require('./webpack.common.js');

module.exports = merge(common, {
    mode: 'development',
    watch: true,
    devtool: 'inline-source-map',
    plugins: [
        new browserSync({
            host: 'localhost',
            port: 3000,
            proxy: 'http://notvoodoo.test:8080',
            notify: false,
        }),
    ],
});
