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
const concat = require('gulp-concat')
const uglify = require('gulp-uglify')
const imagemin = require('gulp-imagemin')

// Paths
const pathJs = './src/resources/js'
const pathJsProd = './public/assets/js'
const pathScss = './src/resources/scss'
const pathCss = './public/assets/css'
const pathImg = './src/resources/img'
const pathImgProd = './public/assets/img'

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

const jsScript = ['alert']
const jsFiles = jsScript.map(file => `${pathJs}/${file}.js`)

gulp.task('js', _ => (
  gulp.src(jsFiles)
  .pipe(sourcemaps.init())
  .pipe(concat('script.js'))
  .pipe(uglify())
  .pipe(sourcemaps.write('.'))
  .pipe(gulp.dest(pathJs))
))

gulp.task('clean', _ => gulp.src([`./public/*`, `./public/.*`], { read: false }).pipe(clean()))

gulp.task('copy', _ => (
  gulp.src(['./src/**/*.php', './src/**/*.html', './src/.*']).pipe(gulp.dest('./public'))
))

gulp.task('scss-prod', _ => (
  gulp.src(`${pathScss}/*.scss`)
    .pipe(sass({ outputStyle: 'compressed' }))
    .pipe(postcss(confPostCss))
    .pipe(gulp.dest(pathCss))
))

gulp.task('js-prod', _ => (
  gulp.src(jsFiles)
  .pipe(concat('script.js'))
  .pipe(uglify())
  .pipe(gulp.dest(pathJsProd))
))

gulp.task('img', _ => (
  gulp.src(`${pathImg}/**/*`)
    .pipe(imagemin())
    .pipe(gulp.dest(pathImgProd))
))

gulp.task('prod', _ => sequence('clean', ['copy', 'scss-prod', 'js-prod', 'img']))

gulp.task('default', ['scss', 'js'], _ => {
  gulp.watch(`${pathScss}/**/*.scss`, ['scss'])
  gulp.watch(jsFiles, ['js'])
})
