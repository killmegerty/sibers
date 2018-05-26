var gulp        = require('gulp');
var browserSync = require('browser-sync').create();
var sass        = require('gulp-sass');
// Static Server + watching scss/html files
gulp.task('serve', function() {

    browserSync.init({
      proxy:'skytecgames.local'
    });

});

gulp.task('sass', function () {
  return gulp.src('./src/assets/sass/*.scss')
    .pipe(sass.sync().on('error', sass.logError))
    .pipe(gulp.dest('./webroot/css'));
});


gulp.task('watchers', function() {
  gulp.watch('./src/assets/sass/*.scss', ['sass']);
  gulp.watch('./src/assets/sass/*.scss').on('change', browserSync.reload);
  gulp.watch('./src/View/**/*.php').on('change', browserSync.reload);
});

gulp.task('default', ['serve', 'watchers']);
