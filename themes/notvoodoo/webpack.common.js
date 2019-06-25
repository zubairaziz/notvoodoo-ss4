const path = require('path');
const webpack = require('webpack');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const ImageminPlugin = require('imagemin-webpack-plugin').default;
const imageminMozjpeg = require('imagemin-mozjpeg');
const imageminPngquant = require('imagemin-pngquant');

const themeName = 'notvoodoo';

module.exports = {
    entry: './src/index.js',
    output: {
        // eslint-disable-next-line prettier/prettier
        path: path.resolve(
            __dirname,
            `../../public/resources/themes/${themeName}/dist`
        ),
        filename: 'js/index.js',
    },
    plugins: [
        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery',
        }),
        new MiniCssExtractPlugin({
            filename: 'styles/[name].css',
        }),
        new CopyWebpackPlugin([
            {
                from: path.join(path.resolve(__dirname, 'images')),
                to: 'images',
                ignore: ['**/.DS_Store'],
            },
        ]),
        new ImageminPlugin({
            test: 'images/**',
            plugins: [
                imageminMozjpeg({
                    quality: 90,
                    progressive: true,
                }),
                console.log('Compressing Images'),
            ],
        }),
        new ImageminPlugin({
            test: 'images/**',
            minFileSize: 2000000,
            plugins: [
                imageminMozjpeg({
                    quality: 50,
                    progressive: true,
                }),
                console.log('Compressing Larger Images'),
            ],
        }),
        new ImageminPlugin({
            test: 'images/*.png',
            plugins: [
                imageminPngquant({
                    quality: 90,
                }),
                console.log('Compressing PNGs'),
            ],
        }),
        // new CleanWebpackPlugin(),
    ],
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                },
            },
            {
                test: /\.s?css$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    {
                        loader: 'css-loader',
                        options: { sourceMap: true },
                    },
                    {
                        loader: 'postcss-loader',
                        options: { sourceMap: true },
                    },
                    {
                        loader: 'sass-loader',
                        options: {
                            sourceMap: true,
                            includePaths: ['node_modules/bulma/bulma.sass'],
                        },
                    },
                ],
            },
        ],
    },
    resolve: {
        extensions: ['.js', '.scss'],
    },
};
