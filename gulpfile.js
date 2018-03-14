var gulp = require('gulp'),
        uglify = require('gulp-uglify'),
        concat = require('gulp-concat'),
        sass = require('gulp-sass'),
        uglifycss = require('gulp-uglifycss'),
        paths = {
            main: [
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
            ]
        };


gulp.task('build', [], function () {
    gulp.src(['./node_modules/jwplayer/**/*'])
    .pipe(gulp.dest('public/libs/jwplayer'));

    gulp.src(['./node_modules/ckeditor/**/*'])
    .pipe(gulp.dest('public/libs/ckeditor'));

    gulp.src(['./node_modules/bootstrap/**/*'])
        .pipe(gulp.dest('public/libs/bootstrap'));

    gulp.src(['./node_modules/jquery/**/*'])
        .pipe(gulp.dest('public/libs/jquery'));

    gulp.src(['./node_modules/popper.js/**/*'])
        .pipe(gulp.dest('public/libs/popper.js'));

    gulp.src(['./node_modules/font-awesome/**/*'])
        .pipe(gulp.dest('public/libs/font-awesome'));

    gulp.src(['./node_modules/leaflet/**/*'])
        .pipe(gulp.dest('public/libs/leaflet'));

    gulp.src(['./node_modules/jticker/**/*'])
        .pipe(gulp.dest('public/libs/jticker'));

    gulp.src(['./node_modules/magnific-popup/**/*'])
        .pipe(gulp.dest('public/libs/magnific-popup'));

    gulp.src(['./node_modules/codemirror/**/*'])
        .pipe(gulp.dest('public/libs/codemirror'));

    gulp.src(['./node_modules/clipboard/**/*'])
        .pipe(gulp.dest('public/libs/clipboard'));

    gulp.src(['./node_modules/urijs/**/*'])
        .pipe(gulp.dest('public/libs/urijs'));

    gulp.src(['./node_modules/noty/**/*'])
        .pipe(gulp.dest('public/libs/noty'));

    gulp.src(['./node_modules/algoliasearch/**/*'])
        .pipe(gulp.dest('public/libs/algoliasearch'));

    gulp.src(['./node_modules/prismjs/**/*'])
        .pipe(gulp.dest('public/libs/prismjs'));

    gulp.src(paths.main)
        .pipe(concat('public/build/js/prism.js'))
        .pipe(uglify())
        .pipe(gulp.dest('./'))

    gulp.src(['./assets/js/admin.js'])
    .pipe(concat('public/build/js/admin.js'))
    .pipe(uglify())
    .pipe(gulp.dest('./'));

    gulp.src(['./assets/js/main.js'])
        .pipe(concat('public/build/js/main.js'))
        .pipe(uglify())
        .pipe(gulp.dest('./'));
});

gulp.task('sass', function () {
    gulp.src('./assets/scss/**/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest('public/build/css'));
});

gulp.task('sass:watch', function () {
    gulp.watch('./assets/scss/**/*.scss', ['sass']);
});

gulp.task('default', ['build', 'sass']);
