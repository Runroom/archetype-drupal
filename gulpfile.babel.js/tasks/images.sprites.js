import cheerio from 'gulp-cheerio';
import gulp from 'gulp';
import imagemin from 'gulp-imagemin';
import rename from 'gulp-rename';

import { SVGO } from '../config/params';
import { SPRITES_SRC, SPRITES_DEST } from '../config/routes';
import themes from '../config/themes';

const SPRITES_FILES = `${SPRITES_SRC}/*.svg`;

const spritesCompilation = (themeName) => {
  return gulp
    .src(SPRITES_FILES)
    .pipe(rename({ prefix: 'icon-' }))
    .pipe(imagemin([imagemin.svgo(SVGO)]))
    .pipe(cheerio({
      run: $ => {
        $('[fill]').removeAttr('fill');
      },
      parserOptions: { xmlMode: true }
    }))
    .pipe(gulp.dest(SPRITES_DEST.replace('%t', themeName)));
};

const sprites = () => themes(spritesCompilation);

export { SPRITES_FILES };
export default sprites;
