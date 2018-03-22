'use strict';

import browserSync from 'browser-sync';
import gulp from 'gulp';

gulp.task('browserSync', () => {
    browserSync({
        proxy: 'localhost',
        port: 5000,
        ui: { port: 5001 },
        options: { reloadDelay: 250 },
        open: false,
        notify: false
    });
});
