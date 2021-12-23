const gulp = require('gulp');
const imagemin = require('gulp-imagemin');
var sass = require('gulp-sass')(require('sass'));
const autoprefixer = require('gulp-autoprefixer');
var rtlcss = require('gulp-rtlcss');


// Task 1: Converting SASS to CSS
function sassify() {
    return gulp.src('assets/sass/style.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest('assets/css'));
}

gulp.task('sass_task', async function() {
    sassify();
});

// Task2: Image Optimization
function img() {
    return gulp
        .src("./uploads/images/*")
        .pipe(imagemin())
        .pipe(gulp.dest("./uploads/minified/images"));
}

gulp.task("img_task", img);

// Task 3: Browser Prefixes
function prefixify() {
    gulp.src('assets/css/style.css')
        .pipe(autoprefixer({
            browsers: ['last 99 versions'],
            cascade: false
        }))
        .pipe(gulp.dest('assets/css/'))
}

gulp.task('prefix_task', async function() {
    prefixify()
});

// Task 4 to generate rtl styles with gulp
function rtl() {
    return gulp.src('assets/css/style.css')
        .pipe(rtlcss())
        .pipe(gulp.dest('assets/css/rtl/'));
}

gulp.task('rtl_task', rtl);

// Watching for changes
gulp.task("watch", () => {
    gulp.watch("./assets/images/*", img);
    gulp.watch("./assets/sass/**/*", sassify).on('change', function() {
        gulp.watch("./assets/css/**/*", prefixify);
    });
});

gulp.task("default", gulp.series("img_task", "sass_task", "prefix_task", "watch"));