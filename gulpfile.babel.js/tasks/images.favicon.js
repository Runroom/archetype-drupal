import gulp from 'gulp';

import { WEB_PATH, IMAGES_DEST } from '../config/routes';
import themes from '../config/themes';

const faviconCompilation = (themeName) => gulp.src(`${IMAGES_DEST.replace('%t', themeName)}/favicon/favicon.ico`).pipe(gulp.dest(WEB_PATH));

const favicon = () => themes(faviconCompilation);

export default favicon;
