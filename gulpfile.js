var gulp = require('gulp'),
    autoprefixer = require('gulp-autoprefixer'),
    sass = require('gulp-sass');

var browserSync = require('browser-sync');

gulp.task('public_sass', function() {
    return gulp.src("public/scss/*.scss")
        .pipe(sass({
            onError: console.error.bind(console, 'SASS Error')
        })).pipe(autoprefixer({
            browsers: ["ie >= 8", "ie_mob >= 10", "ff >= 30", "chrome >= 34", "safari >= 7", "android >= 4.4", "bb >= 10"]
        })).pipe(gulp.dest('public/css/'))
        .pipe(browserSync.reload({
            stream: true
        }));
});

gulp.task('default', ['public_sass'], function() {
    browserSync({
        notify: false,
        proxy:  "localhost/sponsorship"
    });
    gulp.watch(['public/css/*.css'], browserSync.reload);
    gulp.watch(['**/*.php'], browserSync.reload);
    return gulp.watch("public/scss/*.scss", ['public_sass']);
});