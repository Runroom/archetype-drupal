import filter from 'gulp-filter';
import gulp from 'gulp';
import gulpIf from 'gulp-if';
import named from 'vinyl-named';
import path from 'path';
import plumber from 'gulp-plumber';
import rename from 'gulp-rename';
import webpack from 'webpack';
import webpackStream from 'webpack-stream';

import { STYLES_SRC, STYLES_DEST, VIEWS_DEST } from '../config/routes';
import themes from '../config/themes';
import { WEBPACK_CONFIG } from '../config/webpack';
import errorAlert from '../config/fn.error.alert';

const STYLES_FILES = `${STYLES_SRC}/**/*.scss`;

const stylesCompilation = (themeName) => {
  return gulp
    .src([`${STYLES_SRC}/themes/${themeName}/*.scss`, `${STYLES_SRC}/themes/${themeName}/crp/*.scss`], { base: STYLES_SRC })
    .pipe(named(function (file) {
      return path.relative(file.base, file.path).slice(0, -5);
    }))
    .pipe(plumber({ errorHandler: errorAlert }))
    .pipe(webpackStream(WEBPACK_CONFIG, webpack))
    .pipe(plumber.stop())
    .pipe(filter('**/*.css'))
    .pipe(rename(path => {
      path.dirname = path.dirname.indexOf('crp') >= 0 ? 'crp' : '';
      path.extname = '.min.css';
    }))
    .pipe(gulpIf(function (file) {
      return file.path.includes('crp');
    },
      gulp.dest(VIEWS_DEST.replace('%t', themeName)),
      gulp.dest(STYLES_DEST.replace('%t', themeName))
    ));
}

const styles = () => themes(stylesCompilation);

export { STYLES_FILES };
export default styles;
