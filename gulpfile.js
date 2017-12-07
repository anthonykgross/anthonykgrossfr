var gulp = require('gulp'),
        uglify = require('gulp-uglify'),
        header = require('gulp-header'),
        concat = require('gulp-concat'),
        paths = {
            main: [
                './node_modules/jquery/jquery.min.js',
                './assets/js/jquery-migrate.min.js',
                './node_modules/bootstrap/dist/js/bootstrap.min.js',
                './assets/js/jquery-easing.js',
                './assets/js/ios-orientationchange-fix.js',
                './assets/js/jquery.stickem.js',
                './assets/js/ddscrollspy.js',
                './assets/js/jquery.jticker.js',
                './node_modules/jquery-backstretch/jquery.backstretch.min.js',
                './node_modules/magnific-popup/dist/jquery.magnific-popup.min.js',
                './node_modules/jwplayer/jwplayer.js',
                './node_modules/jwplayer/jwplayer.html5.js',
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
                './node_modules/prismjs/plugins/file-highlight/prism-file-highlight.js',
                './assets/js/main.js'
            ]
        };


gulp.task('build', [], function () {
    gulp.src([
        './node_modules/jwplayer/jwplayer.flash.swf'
    ])
    .pipe(gulp.dest('public/build'));

    return gulp.src(paths.main)
        .pipe(header('\n/* **********************************************\n' +
                '     Anthonykgross.fr Begin <%= file.relative %>\n' +
                '********************************************** */\n\n'))
        .pipe(concat('public/build/js/app.js'))
        .pipe(uglify())
        .pipe(gulp.dest('./'))
    ;
});

gulp.task('default', ['build']);
