//Install Gulp:
//npm install --global gulp-cli *if need*
//npm install --save-dev gulp
//npm install --save-dev gulp-sass -- NOT
//npm install --save-dev gulp-sass gulp-cssnano gulp-autoprefixer gulp-bless gulp-concat gulp-notify gulp-removelogs gulp-uglify gulp-rename gulp-changed gulp-filesize gulp-imagemin imagemin-pngquant

var project = 'mchs'; //Название проекта

var gulp = require('gulp');
var sass = require('gulp-sass');
var nano = require('gulp-cssnano');
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

gulp.task('default', function() {
    gulp.start('sass_uikit_admin', 'sass', 'sass_uikit', 'javascript_admin', 'javascript_front','watch', 'libs_styles');
});

gulp.task('watch', function () {
    gulp.watch('./public_html/_assets/_admin/_css/**/*.scss', ['sass_uikit_admin']);
    gulp.watch('./public_html/_assets/_front/_css/**/**/*.scss', ['sass']);
    gulp.watch('./public_html/_assets/bower_components/uikit/scss/**/**/*.scss', ['sass_uikit']);
    gulp.watch(['./resources/assets/admin/js/**/*.js', '!./resources/assets/admin/js/min/*'], ['javascript_admin']);
    gulp.watch(['./resources/assets/front/js/**/*.js', '!./resources/assets/front/js/min/*'], ['javascript_front']);
    gulp.watch(['./public_html/_assets/bower_components/**/**/**/**'], ['libs_styles']);
});

gulp.task('sass', function () {
    gulp.src([
        './public_html/_assets/_front/_css/*.scss'
        ])
        .pipe(changed('./public_html/_assets/_front/_css/**/**/*.scss'))
        .pipe(sass.sync().on('error', sass.logError))
        .pipe(autoprefixer({
            browsers: ['last 2 versions'],
            cascade: true
        }))
        .pipe(bless())
        .pipe(nano())
        .pipe(rename({suffix: '.min'} ))
        .pipe(concat('front.min.css'))
        .pipe(size({showFiles : true}))
        .pipe(gulp.dest('./public_html/_assets/_front/_css/_min'))
        .pipe(removeLogs())
        .pipe(notify("Scss reload: <%= file.relative %>! "+ project));
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
        .pipe(nano())
        .pipe(rename({suffix: '.min'} ))
        .pipe(concat('uikit.min.css'))
        .pipe(size({showFiles : true}))
        .pipe(gulp.dest('./public_html/_assets/_front/_css/_min'))
        .pipe(removeLogs())
        .pipe(notify("Scss reload: <%= file.relative %>! "+ project));
});

/**
 * Generate styles bower libs and copy resources (png, gif, etc.)
 */
gulp.task('libs_styles', function () {
    gulp.src([
        './public_html/_assets/bower_components/fancybox/source/jquery.fancybox.css',
        './public_html/_assets/bower_components/fancybox/source/helpers/jquery.fancybox-buttons.css',
        './public_html/_assets/bower_components/fancybox/source/helpers/jquery.fancybox-thumbs.css',
        './public_html/_assets/bower_components/pickadate/lib/compressed/themes/default.css',
        './public_html/_assets/bower_components/pickadate/lib/compressed/themes/default.date.css',
        './public_html/_assets/bower_components/pickadate/lib/themes/classic.css',
        './public_html/_assets/bower_components/pickadate/lib/themes/classic.date.css'
    ])
        .pipe(changed('./public_html/_assets/bower_components/**/**/**/**/*.scss'))
        .pipe(sass.sync().on('error', sass.logError))
        .pipe(autoprefixer({
            browsers: ['last 2 versions'],
            cascade: true
        }))
        .pipe(bless())
        .pipe(nano())
        .pipe(rename({suffix: '.min'} ))
        .pipe(concat('libs.min.css'))
        .pipe(size({showFiles : true}))
        .pipe(gulp.dest('./public_html/_assets/_front/_css/_min'))
        .pipe(removeLogs())
        .pipe(notify("Scss reload: <%= file.relative %>! "+ project));

    gulp.src([
        './public_html/_assets/bower_components/fancybox/source/**/**/*.png',
        './public_html/_assets/bower_components/fancybox/source/**/**/*.gif'
    ])
        .pipe(changed('./public_html/_assets/bower_components/fancybox/source/**/**/**'))
        .pipe(gulp.dest('./public_html/_assets/_front/_css/_min'));
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
        .pipe(nano())
        .pipe(rename({suffix: '.min'} ))
        .pipe(concat('admin.min.css'))
        .pipe(size({showFiles : true}))
        .pipe(gulp.dest('./public_html/_assets/_admin/_css/min'))
        .pipe(removeLogs())
        .pipe(notify("Scss reload: <%= file.relative %>! "+ project));
});

gulp.task('javascript_admin', function() {
    return gulp.src([
            './public_html/_assets/bower_components/pickadate/lib/compressed/picker.js',
            './public_html/_assets/bower_components/pickadate/lib/compressed/picker.date.js',
            './public_html/_assets/bower_components/jquery.cookie/jquery.cookie.js',
            './resources/assets/admin/js/backend.js',
            './resources/assets/admin/js/plugin_images.js',
            './resources/assets/admin/js/plugin_files.js'
        ])
        .pipe(concat('back_core.min.js'))
        .pipe(removeLogs())
        .pipe(notify("Js reload: <%= file.relative %>! "+ project))
        .pipe(size({showFiles : true}))
        .pipe(gulp.dest('./public_html/_assets/_admin/_js'));
});

gulp.task('javascript_front', function() {
    return gulp.src([
            './public_html/_assets/bower_components/jquery.cookie/jquery.cookie.js',
            './public_html/_assets/bower_components/fancybox/lib/jquery.mousewheel-3.0.6.pack.js',
            './public_html/_assets/bower_components/fancybox/source/jquery.fancybox.pack.js',
            './public_html/_assets/bower_components/fancybox/source/helpers/jquery.fancybox-thumbs.js',
            './public_html/_assets/bower_components/fancybox/source/helpers/jquery.fancybox-media.js',
            './public_html/_assets/bower_components/fancybox/source/helpers/jquery.fancybox-buttons.js',
            './public_html/_assets/bower_components/jquery.spinner/js/jquery.spinner.js',
            './public_html/vendor/jsvalidation/js/jsvalidation.min.js',
            './public_html/_assets/bower_components/pickadate/lib/compressed/picker.js',
            './public_html/_assets/bower_components/pickadate/lib/compressed/picker.date.js',
            './resources/assets/front/js/frontend.js'
        ])
        .pipe(concat('front_core.min.js'))
        .pipe(uglify())
        .pipe(removeLogs())
        .pipe(notify("Js reload: <%= file.relative %>! "+ project))
        .pipe(size({showFiles : true}))
        .pipe(gulp.dest('./public_html/_assets/_front/_js'));
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