import gulp from 'gulp';

import { FONTS_SRC, FONTS_DEST } from '../config/routes';
import themes from '../config/themes';

const fontsCompilation = () => gulp.src(`${FONTS_SRC}/**/*`).pipe(gulp.dest(FONTS_DEST));

const fonts = () => themes(fontsCompilation);

export default fonts;
