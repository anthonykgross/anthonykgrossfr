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
        './node_modules/bootstrap/dist/css/bootstrap.min.css',
        './node_modules/bootstrap/dist/css/bootstrap-theme.min.css',
        './node_modules/font-awesome/css/font-awesome.min.css',
        './node_modules/magnific-popup/dist/magnific-popup.css',
        './assets/css/layout.css',
        './assets/css/colors/grey.css',
        './node_modules/prismjs/themes/prism-okaidia.css'
    ])
    .addStyleEntry('css/no-js','./assets/css/no-js.css')

    .createSharedEntry('js/prismjs', [
        './node_modules/prismjs/components/prism-core.js',
        './node_modules/prismjs/components/prism-markup.js',
        './node_modules/prismjs/components/prism-css.js',
        './node_modules/prismjs/components/prism-clike.js',
        './node_modules/prismjs/components/prism-javascript.js',
        './node_modules/prismjs/components/prism-bash.js',
        './node_modules/prismjs/components/prism-php.js',
        './node_modules/prismjs/components/prism-php-extras.js',
        './node_modules/prismjs/components/prism-docker.js',
        './node_modules/prismjs/components/prism-yaml.js',
        './node_modules/prismjs/components/prism-sql.js',
        './node_modules/prismjs/components/prism-rest.js',
        './node_modules/prismjs/components/prism-python.js',
        './node_modules/prismjs/components/prism-nginx.js',
        './node_modules/prismjs/components/prism-java.js',
        './node_modules/prismjs/components/prism-git.js',
        './node_modules/prismjs/plugins/file-highlight/prism-file-highlight.js'
    ])

    // uncomment for legacy applications that require $/jQuery as a global variable
    // .autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
