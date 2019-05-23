import gulp from 'gulp';
import imagemin from 'gulp-imagemin';
import mozjpeg from 'imagemin-mozjpeg';

import { IMAGES_SRC, IMAGES_DEST } from '../config/routes';
import themes from '../config/themes';

const IMAGES_FILES = [`${IMAGES_SRC}/**/*`, `!${IMAGES_SRC}/**/*.svg`];

const imagesCompilation = (themeName) => {
  return gulp
    .src(IMAGES_FILES)
    .pipe(imagemin([
      mozjpeg(),
      imagemin.gifsicle({ interlaced: true }),
      imagemin.optipng({ optimizationLevel: 5 }),
      imagemin.svgo()
    ]))
    .pipe(gulp.dest(IMAGES_DEST.replace('%t', themeName)));
};

const images = () => themes(imagesCompilation);

export { IMAGES_FILES };
export default images;
