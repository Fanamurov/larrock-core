//Install Yarn:
//https://yarnpkg.com/en/docs/install
//brew install yarn
//Install Gulp:
//yarn add gulp-cli gulp gulp-sass gulp-csso gulp-autoprefixer gulp-bless gulp-concat gulp-notify gulp-removelogs gulp-uglify gulp-rename gulp-changed gulp-filesize gulp-imagemin imagemin-pngquant

var project = 'larrock'; //Название проекта

var gulp = require('gulp');
var sass = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
var bless = require('gulp-bless');
var concat = require('gulp-concat');
var notify = require("gulp-notify");
var removeLogs = require('gulp-removelogs');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');
var changed = require('gulp-changed');
var size = require('gulp-filesize');
var imagemin = require('gulp-imagemin');
var pngquant = require('imagemin-pngquant');
var csso = require('gulp-csso');

gulp.task('default', function() {
    gulp.start('watch');
});

gulp.task('reload', function() {
    gulp.start('sass_uikit_admin', 'sass', 'sass_uikit', 'javascript_admin', 'javascript_front', 'libs_styles');
});

gulp.task('watch', function () {
    gulp.watch('./public_html/_assets/_admin/_css/**/*.scss', ['sass_uikit_admin']);
    gulp.watch('./public_html/_assets/_front/_css/**/**/*.scss', ['sass']);
    gulp.watch('./public_html/_assets/bower_components/uikit/scss/**/**/*.scss', ['sass_uikit']);
    gulp.watch(['./public_html/_assets/_admin/_js/**/*.js', '!./public_html/_assets/_admin/_js/min/*'], ['javascript_admin']);
    gulp.watch(['./public_html/_assets/_front/_js/**/*.js', '!./public_html/_assets/_front/_js/min/*'], ['javascript_front']);
    gulp.watch(['./public_html/_assets/bower_components/**/**/**/**'], ['libs_styles']);
});

gulp.task('sass', function () {
    gulp.src([
        './public_html/_assets/_front/_css/layout.scss'
        ])
        .pipe(changed('./public_html/_assets/_front/_css/**/**/*.scss'))
        .pipe(sass.sync().on('error', sass.logError))
        .pipe(autoprefixer({
            browsers: ['last 2 versions'],
            cascade: true
        }))
        .pipe(bless())
        .pipe(csso())
        .pipe(rename({suffix: '.min'} ))
        .pipe(concat('front.min.css'))
        .pipe(size({showFiles : true}))
        .pipe(gulp.dest('./public_html/_assets/_front/_css/_min'))
        .pipe(removeLogs())
        .pipe(notify("Scss reload: ./public_html/_assets/_front/_css/_min/<%= file.relative %>! "+ project));
});

gulp.task('sass_uikit', function () {
    gulp.src(['./public_html/_assets/bower_components/uikit/scss/uikit.scss'])
        .pipe(changed('./public_html/_assets/bower_components/uikit/scss/**/**/*.scss'))
        .pipe(sass.sync().on('error', sass.logError))
        .pipe(autoprefixer({
            browsers: ['last 2 versions'],
            cascade: true
        }))
        .pipe(bless())
        .pipe(csso())
        .pipe(rename({suffix: '.min'} ))
        .pipe(concat('uikit.min.css'))
        .pipe(size({showFiles : true}))
        .pipe(gulp.dest('./public_html/_assets/_front/_css/_min'))
        .pipe(removeLogs())
        .pipe(notify("Scss reload: ./public_html/_assets/_front/_css/_min/<%= file.relative %>! "+ project));
});

/**
 * Generate styles bower libs and copy resources (png, gif, etc.)
 */
gulp.task('libs_styles', function () {
    gulp.src([
        './public_html/_assets/bower_components/fancybox/dist/jquery.fancybox.css',
        './public_html/_assets/bower_components/pickadate/lib/compressed/themes/default.css',
        './public_html/_assets/bower_components/pickadate/lib/compressed/themes/default.date.css'
    ])
        .pipe(changed('./public_html/_assets/bower_components/**/**/**/**/*.scss'))
        .pipe(sass.sync().on('error', sass.logError))
        .pipe(autoprefixer({
            browsers: ['last 2 versions'],
            cascade: true
        }))
        .pipe(bless())
        .pipe(csso())
        .pipe(rename({suffix: '.min'} ))
        .pipe(concat('libs.min.css'))
        .pipe(size({showFiles : true}))
        .pipe(gulp.dest('./public_html/_assets/_front/_css/_min'))
        .pipe(removeLogs())
        .pipe(notify("Scss reload: ./public_html/_assets/_front/_css/_min/<%= file.relative %>! "+ project));
});

gulp.task('sass_uikit_admin', function () {
    gulp.src([
        './public_html/_assets/_admin/_css/*.scss'
        ])
        .pipe(changed('./public_html/_assets/_admin/_css/**/*.scss'))
        .pipe(sass.sync().on('error', sass.logError))
        .pipe(autoprefixer({
            browsers: ['last 2 versions'],
            cascade: true
        }))
        .pipe(bless())
        .pipe(csso())
        .pipe(rename({suffix: '.min'} ))
        .pipe(concat('admin.min.css'))
        .pipe(size({showFiles : true}))
        .pipe(gulp.dest('./public_html/_assets/_admin/_css/min'))
        .pipe(removeLogs())
        .pipe(notify("Scss reload: ./public_html/_assets/_admin/_css/min/<%= file.relative %>! "+ project));
});

gulp.task('javascript_admin', function() {
    return gulp.src([
            './public_html/_assets/bower_components/pickadate/lib/compressed/picker.js',
            './public_html/_assets/bower_components/pickadate/lib/compressed/picker.date.js',
            './public_html/_assets/bower_components/noty/lib/noty.js',
            './public_html/_assets/bower_components/jquery.cookie/jquery.cookie.js',
            './public_html/_assets/_admin/_js/backend.js',
            './public_html/_assets/_admin/_js/plugin_images.js',
            './public_html/_assets/_admin/_js/plugin_files.js'
        ])
        .pipe(concat('back_core.min.js'))
        .pipe(removeLogs())
        .pipe(notify("Js reload: ./public_html/_assets/_admin/_js/min/<%= file.relative %>! "+ project))
        .pipe(size({showFiles : true}))
        .pipe(gulp.dest('./public_html/_assets/_admin/_js/min'));
});

gulp.task('javascript_front', function() {
    return gulp.src([
            './public_html/_assets/bower_components/jquery.cookie/jquery.cookie.js',
            './public_html/vendor/jsvalidation/js/jsvalidation.min.js',
            './public_html/_assets/bower_components/pickadate/lib/compressed/picker.js',
            './public_html/_assets/bower_components/pickadate/lib/compressed/picker.date.js',
            './public_html/_assets/_front/_js/frontend.js'
        ])
        .pipe(concat('front_core.min.js'))
        .pipe(uglify())
        .pipe(removeLogs())
        .pipe(notify("Js reload: ./public_html/_assets/_front/_js/min/<%= file.relative %>! "+ project))
        .pipe(size({showFiles : true}))
        .pipe(gulp.dest('./public_html/_assets/_front/_js/min'));
});

gulp.task('imagemin', function () {
    return gulp.src('./public_html/media/**/**/*')
        .pipe(imagemin({
            progressive: true,
            svgoPlugins: [{removeViewBox: false}],
            optimizationLevel: 4,
            use: [pngquant()]
        }))
        .pipe(gulp.dest('./public_html/media/'));
});