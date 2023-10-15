//load plugins
var browserSync     = require('browser-sync').create(),
    gulp            = require('gulp'),
    sass            = require('gulp-sass'),
    cleancss        = require('gulp-clean-css'),
    uglify          = require('gulp-uglify'),
    rename          = require('gulp-rename'),
    concat          = require('gulp-concat'),
    notify          = require('gulp-notify'),
    plumber         = require('gulp-plumber'),
    postCSS			= require('gulp-postcss'),
    autoprefixer    = require('autoprefixer'),
    discardDups		= require('postcss-discard-duplicates'),
    assets			= require('postcss-assets'),
    flexbugFixes    = require('postcss-flexbugs-fixes'),
    inlineSvg       = require('postcss-inline-svg'),
    svgo            = require('postcss-svgo'),
    spritesmith     = require('gulp.spritesmith'),
    sourcemaps      = require('gulp-sourcemaps'),
    argv            = require('yargs').argv,
    webpack         = require('webpack-stream'),
    runSequence     = require('run-sequence').use(gulp),
    path            = require('path');

var config = {
    browserSync: {
        proxy: 'skylotec.test',
        port: 32800,
        ui: {
            port: 32801
        },
        rewriteRules: [
            {
                "match": ".skylotec.test",
                "replace": ""
            }
        ],
        files: [
            "css/*.css",
            "../design/template/**/*.phtml",
            "../design/layout/**/*.xml",
            "../design/locale/**/*.csv"
        ],
        ignore: [
            "images/sprites/*.png"
        ],
        reloadDelay: 150
    }
};

var plumberErrorHandler = {
    errorHandler: notify.onError({
        title: 'Gulp',
        message: "Error: <%= error.message %>"
    })
};

//styles
gulp.task('styles', function() {
    var styles = gulp.src(['scss/**/*.scss'])
        .pipe(plumber(plumberErrorHandler))
        .pipe(sourcemaps.init())
        .pipe(sass({
            includePaths:   [
                './node_modules/bootstrap-sass/assets/stylesheets',
                './node_modules'
            ]
        }))
        .pipe(postCSS([
            assets({
                cachebuster: true,
                relative: true,
                loadPaths: ['images/']
            }),
            inlineSvg({
                path: 'images/'
            }),
            flexbugFixes(),
            discardDups(),
            autoprefixer({ browsers: ['last 30 version']})
        ]));

    if (!argv.production) {
        styles.pipe(sourcemaps.write());
    }

    if (argv.production) {
        styles
            .pipe(cleancss());
    }

    styles.pipe(gulp.dest('css'));

    return styles;
});

gulp.task('sprites', function () {
    var spriteData = gulp.src('images/default/*.png').pipe(spritesmith({
        imgName: 'sprites/default.png',
        cssName: '_default.scss',
        cssFormat: 'scss',
        algorithm: 'top-down',
        cssVarMap: function(sprite) {
            sprite.name = 's-' + sprite.name
        }
    }));
    spriteData.img.pipe(gulp.dest('images/'));
    spriteData.css.pipe(gulp.dest('scss/sprites/'))
});

//scripts
gulp.task('scripts', function() {
    return;
    return gulp.src('js/**/*.js')
        .pipe(plumber(plumberErrorHandler))
        .pipe(webpack({
            config: require('./webpack.config.js')
        }))
        .pipe(gulp.dest('dist'));
});

gulp.task('sprites_styles', function () {
    runSequence('sprites', 'styles');
});

//watch
gulp.task('watch', function() {
    browserSync.init(config.browserSync);
    gulp.watch([
        'scss/**/*.scss',
        'images/**/*'
    ], ['sprites_styles']);
    gulp.watch([
        'js/**/*.js'
    ], ['scripts']);
});

gulp.task('default', ['styles', 'scripts']);
