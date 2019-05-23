import gulp from 'gulp';
import imagemin from 'gulp-imagemin';

import { SVGO } from '../config/params';
import { IMAGES_SRC, SPRITES_SRC, SPRITES_DEST } from '../config/routes';
import themes from '../config/themes';

const SVG_FILES = [`${IMAGES_SRC}/*.svg`, `!${SPRITES_SRC}/*`];

const compileSvg = (themeName) => {
  return gulp
    .src(SVG_FILES)
    .pipe(imagemin([imagemin.svgo(SVGO)]))
    .pipe(gulp.dest(SPRITES_DEST.replace('%t', themeName)));
}

const svg = () => themes(compileSvg);

export { SVG_FILES };
export default svg;
