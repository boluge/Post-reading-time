var gulp = require('gulp'),
    sass = require('gulp-sass');

var browserSync = require('browser-sync');

gulp.task('watch', function() {

    browserSync({
        notify: false,
        proxy:  "localhost/PostsReadingTime"
    });

    gulp.watch(['**/*.php'], browserSync.reload);
});