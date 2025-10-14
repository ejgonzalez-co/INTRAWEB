const mix = require('laravel-mix');
const TerserPlugin = require('terser-webpack-plugin');

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

mix.ts('resources/js/app.ts', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .version(); // Agregar versionado para cache busting;


/*
* Comprime el app.ts de todos los componentes y dependencias en el archivo public/js/app.js,
* minimizando espacio, comentarios y código, aumenta el rendimiento de la app.
*/
// mix.ts('resources/js/app.ts', 'public/js')
//     .sass('resources/sass/app.scss', 'public/css')
//     .webpackConfig({
//         plugins: [
//             new TerserPlugin({
//                 terserOptions: {
//                     compress: {
//                         drop_console: true, // Elimina los registros de la consola
//                     },
//                     mangle: true, // Oculta los nombres de variables, funciones y otras propiedades en el código, haciéndolo más pequeño pero menos legible.
//                     ecma: 2015, // Especifica que el estándar ECMAScript 2015 (ES6) se utilizará como base para la compresión.
//                     module: true // Le indica a Terser que el código está escrito como un módulo, lo que permite utilizar mejores técnicas de compresión.
//                 },
//             }),
//         ],
//     });
