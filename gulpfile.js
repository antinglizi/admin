var gulp = require('gulp');
var uglify = require('gulp-uglify');
var jshint = require('gulp-jshint');
var revision = require('gulp-revision');

gulp.task('minify', function() {
  gulp.src('public/assets/js/*.js')
      .pipe(uglify())
      .pipe(revision())
      .pipe(gulp.dest('public/build'))
});

gulp.task('lint', function(){
  gulp.src('public/assets/js/*.js')
      .pipe(jshint())
      .pipe(jshint.reporter('default'))
});
