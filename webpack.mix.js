const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

//  // BABEL config
// mix.webpackConfig({
//    module: {
//      rules: [
//        {
//          test: /\.jsx?$/,
//          use: {
//            loader: 'babel-loader',
//            options: {
//              presets: ['es2015'] // default = env
//            }
//          }
//        }
//      ]
//    }
//  })

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css');
