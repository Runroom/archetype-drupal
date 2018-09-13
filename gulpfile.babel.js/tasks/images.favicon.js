import gulp from 'gulp';

import { WEB_PATH, IMAGES_DEST } from '../config/routes';

const favicon = () => gulp.src(`${IMAGES_DEST}/favicon/favicon.ico`).pipe(gulp.dest(`${IMAGES_DEST}/favicon/`));

export default favicon;
