import filter from 'gulp-filter';
import gulp from 'gulp';
import gulpIf from 'gulp-if';
import named from 'vinyl-named';
import path from 'path';
import plumber from 'gulp-plumber';
import rename from 'gulp-rename';
import gulpPromise from 'gulp-stream-to-promise';
import { argv } from 'yargs';
import webpack from 'webpack';
import webpackStream from 'webpack-stream';

import { AVAILABLE_THEMES, STYLES_SRC, STYLES_DEST, VIEWS_DEST } from '../config/routes';
import { WEBPACK_CONFIG } from '../config/webpack';
import errorAlert from '../config/fn.error.alert';

const STYLES_FILES = `${STYLES_SRC}/**/*.scss`;
const THEME_NAME = argv.theme;

const stylesCompilation = (themeName) => {
  return gulp
    .src([`${STYLES_SRC}/themes/${themeName}.scss`, `${STYLES_SRC}/crp/themes/${themeName}/*.scss`], { base: STYLES_SRC })
    .pipe(named(function (file) {
      return path.relative(file.base, file.path).slice(0, -5);
    }))
    .pipe(plumber({ errorHandler: errorAlert }))
    .pipe(webpackStream(WEBPACK_CONFIG, webpack))
    .pipe(plumber.stop())
    .pipe(filter('**/*.css'))
    .pipe(gulpIf(function (file) {
      return !file.path.includes('crp');
    },
      rename({ basename: 'styles' })
    ))
    .pipe(rename(path => {
      path.dirname = path.dirname.indexOf('crp') >= 0 ? 'crp' : '';
      path.extname = '.min.css';
    }))
    .pipe(gulpIf(function (file) {
      return file.path.includes('crp');
    },
      gulp.dest(VIEWS_DEST.replace('%s', themeName)),
      gulp.dest(STYLES_DEST.replace('%s', themeName))
    ));
}

const styles = () => {
  if (THEME_NAME) {
    return stylesCompilation(THEME_NAME);
  } else {
    const promises = [];

    AVAILABLE_THEMES.map(theme => promises.push(gulpPromise(stylesCompilation(theme))));

    return Promise.all(promises);
  }
};

export { STYLES_FILES };
export default styles;
