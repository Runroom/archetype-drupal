import gulpPromise from 'gulp-stream-to-promise';
import { argv } from 'yargs';

import { AVAILABLE_THEMES } from '../config/routes';

const THEME_NAME = argv.theme;

const themes = (cb) => {
  if (THEME_NAME) {
    return cb(THEME_NAME);
  } else {
    const promises = [];

    AVAILABLE_THEMES.map(theme => promises.push(gulpPromise(cb(theme))));

    return Promise.all(promises);
  }
};

export default themes;
