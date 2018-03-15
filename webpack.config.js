var Encore = require('@symfony/webpack-encore');

Encore
    // the project directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())

    // uncomment to define the assets of the project
    .addStyleEntry('css/app', [
        './node_modules/font-awesome/css/font-awesome.min.css',
        './node_modules/magnific-popup/dist/magnific-popup.css',
        './node_modules/prismjs/themes/prism-okaidia.css'
    ])
;

module.exports = Encore.getWebpackConfig();
