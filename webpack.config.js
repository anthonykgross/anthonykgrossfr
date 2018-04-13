var Encore = require('@symfony/webpack-encore');

Encore
    // the project directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableSassLoader()

    // uncomment to define the assets of the project
    // .addStyleEntry('css/app', [
    //     './assets/scss/bootstrap.scss',
    //     './node_modules/font-awesome/css/font-awesome.min.css',
    //     './node_modules/leaflet/dist/leaflet.css',
    //     './node_modules/magnific-popup/dist/magnific-popup.css',
    //     './node_modules/noty/lib/noty.css',
    //     './node_modules/noty/lib/themes/bootstrap-v4.css',
    //     './node_modules/prismjs/themes/prism-okaidia.css',
    //     './assets/scss/app.scss'
    // ])
;

module.exports = Encore.getWebpackConfig();
