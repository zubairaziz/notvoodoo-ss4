const path = require('path')
const webpack = require('webpack')
const merge = require('webpack-merge')
const CleanWebpackPlugin = require('clean-webpack-plugin')
const CopyWebpackPlugin = require('copy-webpack-plugin')
const FriendlyErrorsWebpackPlugin = require('friendly-errors-webpack-plugin')
const ImageminPlugin = require('imagemin-webpack-plugin').default
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin')
const SVGSpritemapPlugin = require('svg-spritemap-webpack-plugin')
const TerserPlugin = require('terser-webpack-plugin')
const WebpackAssetsManifest = require('webpack-assets-manifest')
const WriteFilePlugin = require('write-file-webpack-plugin')
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin

const PROD_MODE = process.env.NODE_ENV === 'production'
const DEV_MODE = !PROD_MODE
const CLIENT_PATH = 'app/client'

const PATHS = {
  SRC: path.resolve(__dirname, CLIENT_PATH, 'src'),
  DIST: path.resolve(__dirname, CLIENT_PATH, 'dist'),
  PUBLIC: `/resources/${CLIENT_PATH}/dist/`,
  MODULES: path.resolve(__dirname, 'node_modules')
}

let config = {
  mode: process.env.NODE_ENV,

  entry: {
    app: path.join(PATHS.SRC, 'app.js')
  },

  output: {
    path: PATHS.DIST,
    filename: '[name].js',
    publicPath: PATHS.PUBLIC,
    hotUpdateChunkFilename: 'hot-update.js',
    hotUpdateMainFilename: 'hot-update.json'
  },

  resolve: {
    extensions: ['.js', '.json', '.scss'],
    alias: {
      scripts: path.resolve(PATHS.SRC, 'scripts'),
      styles: path.resolve(PATHS.SRC, 'styles'),
      images: path.resolve(PATHS.SRC, 'images')
    }
  },

  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: [PATHS.MODULES],
        use: [
          'babel-loader',
          'eslint-loader'
        ]
      },
      {
        test: /\.s?css$/,
        use: [
          'css-hot-loader',
          {
            loader: MiniCssExtractPlugin.loader
          },
          {
            loader: 'css-loader',
            options: {
              importLoaders: 1,
              sourceMap: true
            }
          },
          {
            loader: 'postcss-loader',
            ident: 'postcss',
            options: {
              plugins: () => [
                require('autoprefixer')(),
                require('postcss-object-fit-images'),
                require('postcss-pxtorem')({
                  propList: ['*']
                })
              ],
              sourceMap: true
            }
          },
          {
            loader: 'sass-loader',
            options: {
              sourceMap: true
            }
          }
        ]
      },
      {
        test: /\.(jpe?g|ico|gif|png|svg)$/,
        use: {
          loader: 'url-loader',
          options: {
            name: 'images/[name].[hash].[ext]',
            limit: 1024 * 8
          }
        }
      },
      {
        test: /\.(ttf|eot|woff|woff2)$/,
        use: {
          loader: 'file-loader',
          options: {
            name: 'fonts/[name].[ext]',
          }
        },
      }
    ]
  },

  plugins: [
    new CleanWebpackPlugin([PATHS.DIST]),

    new CopyWebpackPlugin([
      { from: 'images', to: 'images/' },
      { from: 'favicon', to: 'favicon/' }
    ], {
      context: PATHS.SRC,
      ignore: ['.DS_Store']
    }),

    new SVGSpritemapPlugin(path.join(PATHS.SRC, 'icons/**/*.svg'), {
      styles: {
        filename: '~spritemap.scss'
      },
      output: {
        svgo: {
          plugins: [
            { convertColors: { currentColor: true }}
          ]
        }
      }
    }),

    new MiniCssExtractPlugin({ filename: '[name].css' }),

    new WebpackAssetsManifest({
      entrypoints: true
    }),

    new webpack.ProvidePlugin({
      Selectors: [path.resolve(PATHS.SRC, 'scripts/common/selectors.js'), 'default'],
      ajax: [path.resolve(PATHS.SRC, 'scripts/common/ajax.js'), 'default']
    }),

    new webpack.IgnorePlugin(/^\.\/locale$/, /moment$/),
  ],

  optimization: {
    splitChunks: {
      cacheGroups: {
        vendor: {
          chunks: 'all',
          test: /[\\/]node_modules[\\/]/,
          name: 'vendor'
        }
      }
    }
  },

  devServer: {
    hot: true,
    overlay: true,
    compress: true,
    disableHostCheck: true,
    headers: {
      'Access-Control-Allow-Origin': '*'
    },
    stats: {
      builtAt: false,
      children: false,
      colors: true,
      entrypoints: false,
      hash: false,
      modules: false,
      version: false
    }
  }
}

if (DEV_MODE) {
  module.exports = merge(config, {
    devtool: 'inline-cheap-module-source-map',
    optimization: {
      noEmitOnErrors: true
    },
    plugins: [
      new webpack.HotModuleReplacementPlugin(),
      new WriteFilePlugin(),
      new FriendlyErrorsWebpackPlugin({
        clearConsole: true
      })
    ]
  })
}

if (PROD_MODE) {
  module.exports = merge(config, {
    devtool: 'source-map',
    plugins: [
      new ImageminPlugin({
        test: ['images/**'],
        svgo: null
      }),
      new BundleAnalyzerPlugin({
        analyzerMode: 'disabled',
        generateStatsFile: true,
        statsOptions: { source: false }
      })
    ],
    optimization: {
      minimizer: [
        new TerserPlugin({
          cache: true,
          parallel: true,
          sourceMap: true
        }),
        new OptimizeCSSAssetsPlugin()
      ]
    }
  })
}
