const mix = require('laravel-mix');

const resolve = require('./webpack/resolve.js')

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
    .js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.sass', 'public/css', {implementation: require('node-sass')}, [
        require('tailwindcss'),
        require('autoprefixer'),
    ])
    .webpackConfig({
        output: {
          assetModuleFilename: 'images/[hash][ext][query]'
        },
        resolve,
        module: {
            rules: [
              {
                test: /\.svg/,
                type: 'asset/resource'
              }
            ]
          },
    });
