// Packages node
const sequence = require('run-sequence')
const gulp = require('gulp')
const clean = require('gulp-clean')
const plumber = require('gulp-plumber')
const notify = require('gulp-notify')
const sass = require('gulp-sass')
const sourcemaps = require('gulp-sourcemaps')
const postcss = require('gulp-postcss')
const autoprefixer = require('autoprefixer')
const mq = require('css-mqpacker')

// Paths
const pathScss = './src/resources/scss'
const pathCss = './public/assets/css'

const confPostCss = [
  autoprefixer({ browsers: ['last 2 versions', '> 1%', 'Firefox ESR', 'Safari > 6', 'ie > 8'] }),
  mq()
]

gulp.task('scss', _ => (
  gulp.src(`${pathScss}/*.scss`)
    .pipe(plumber({
      errorHandler (err) {
        notify.onError({
          title: 'Gulp Sass',
          message: 'Error: <%= error.message %>',
          sound: 'Beep'
        })(err)
        this.emit('end')
      }
    }))
    .pipe(sourcemaps.init())
    .pipe(sass({ outputStyle: 'expanded' }))
    .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest(pathScss))
))

gulp.task('clean', _ => gulp.src(`./public/**`, { read: false }).pipe(clean()))

gulp.task('copy', _ => (
  gulp.src(['./src/**/*.php', './src/**/*.html', './src/.*']).pipe(gulp.dest('./public'))
))

gulp.task('scss-prod', _ => {
  gulp.src(`${pathScss}/*.scss`)
    .pipe(sass({ outputStyle: 'compressed' }))
    .pipe(postcss(confPostCss))
    .pipe(gulp.dest(pathCss))
})

gulp.task('prod', _ => sequence('clean', ['copy', 'scss-prod']))

gulp.task('default', ['scss'], _ => {
  gulp.watch(`${pathScss}/**/*.scss`, ['scss'])
})
