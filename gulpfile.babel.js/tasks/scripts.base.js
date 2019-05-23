import gulp from 'gulp';
import plumber from 'gulp-plumber';
import rename from 'gulp-rename';
import named from 'vinyl-named';
import webpack from 'webpack';
import webpackStream from 'webpack-stream';

import { SCRIPTS_SRC, SCRIPTS_DEST } from '../config/routes';
import themes from '../config/themes';
import { WEBPACK_CONFIG } from '../config/webpack';
import errorAlert from '../config/fn.error.alert';

const SCRIPTS_FILES = `${SCRIPTS_SRC}/**/*.js`;

const scriptsCompilation = (themeName) => {
  return gulp
    .src(`${SCRIPTS_SRC}/*.js`)
    .pipe(named())
    .pipe(plumber({ errorHandler: errorAlert }))
    .pipe(webpackStream(WEBPACK_CONFIG, webpack))
    .pipe(plumber.stop())
    .pipe(rename({ extname: '.min.js' }))
    .pipe(gulp.dest(SCRIPTS_DEST.replace('%t', themeName)));
}

const scripts = () => themes(scriptsCompilation);

export { SCRIPTS_FILES };
export default scripts;
