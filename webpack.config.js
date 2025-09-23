const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');

module.exports = (env, argv) => {
  const isProduction = argv.mode === 'production';

  return {
    entry: {
      main: './js/main.js',
      style: './css/style.css'
    },
    output: {
      path: path.resolve(__dirname, 'assets/dist'),
      filename: 'js/[name].js',
      clean: true
    },
    module: {
      rules: [
        {
          test: /\.js$/,
          exclude: /node_modules/,
          use: {
            loader: 'babel-loader',
            options: {
              presets: ['@babel/preset-env']
            }
          }
        },
        {
          test: /\.css$/,
          use: [
            MiniCssExtractPlugin.loader,
            'css-loader',
            {
              loader: 'postcss-loader',
              options: {
                postcssOptions: {
                  plugins: [
                    ['autoprefixer']
                  ]
                }
              }
            }
          ]
        },
        {
          test: /\.(png|jpg|jpeg|gif|svg|woff|woff2|ttf|eot)$/,
          type: 'asset/resource',
          generator: {
            filename: 'assets/[name][ext]'
          }
        }
      ]
    },
    plugins: [
      new CleanWebpackPlugin(),
      new MiniCssExtractPlugin({
        filename: 'css/[name].min.css'
      })
    ],
    optimization: {
      minimizer: [
        new TerserPlugin({
          terserOptions: {
            compress: {
              drop_console: isProduction
            }
          }
        }),
        new CssMinimizerPlugin()
      ]
    },
    devtool: isProduction ? false : 'source-map',
    stats: {
      children: false,
      entrypoints: false,
      modules: false
    }
  };
};