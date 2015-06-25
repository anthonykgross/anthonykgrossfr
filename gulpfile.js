var gulp = require('gulp'),
        rename = require('gulp-rename'),
        uglify = require('gulp-uglify'),
        header = require('gulp-header'),
        concat = require('gulp-concat'),
        bower = require('gulp-bower'),
        paths = {
            main: [
                'web/libs/prismjs/components/prism-core.js',
                'web/libs/prismjs/components/prism-markup.js',
                'web/libs/prismjs/components/prism-css.js',
                'web/libs/prismjs/components/prism-clike.js',
                'web/libs/prismjs/components/prism-javascript.js',
                'web/libs/prismjs/components/prism-html.js',
                'web/libs/prismjs/components/prism-bash.js',
                'web/libs/prismjs/components/prism-php.js',
                'web/libs/prismjs/plugins/file-highlight/prism-file-highlight.js'
            ]
        };

gulp.task('bower', function () {
        return bower();
});

gulp.task('build', ['bower'], function () {
    return gulp.src(paths.main)
            .pipe(header('\n/* **********************************************\n' +
                    '     Anthonykgross.fr Begin <%= file.relative %>\n' +
                    '********************************************** */\n\n'))
            .pipe(concat('web/libs/prismjs/prism.js'))
            .pipe(uglify())
            .pipe(gulp.dest('./'));
});

gulp.task('default', ['build']);
