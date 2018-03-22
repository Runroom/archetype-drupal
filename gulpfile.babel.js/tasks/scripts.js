'use strict';

import browserSync from 'browser-sync';
import browserify from 'browserify';
import glob from 'glob';
import gulp from 'gulp';
import gulpLoadPlugins from 'gulp-load-plugins';
import friendlyFormatter from 'eslint-friendly-formatter';
import source from 'vinyl-source-stream';
import buffer from 'vinyl-buffer';

import routes from '../config/routes';
import fn from '../config/functions';

const $ = gulpLoadPlugins({ camelize: true });
const COMPONENT_FILES = routes.src.js + '/components/**/*.js';
const APP_FILE = routes.src.js + '/*.js';

function scripts(entry_point, destination) {

    return browserify(entry_point)
        .transform('babelify', { presets: ['es2015'] })
        .bundle()
        .on('error', fn.errorAlert)
        .pipe(source(destination))
        .pipe(buffer())
        .pipe($.sourcemaps.init({ loadMaps: true }))
        .pipe($.uglify())
        .pipe($.sourcemaps.write('./'))
        .pipe($.size({ title: 'Scripts' }))
        .pipe(gulp.dest(routes.dist.js));
}

function multipleScripts(entry_elems, destination){

    glob(entry_elems, function(err, files) {
        if(err) done(err);

        var tasks = files.map(function(file){
            const reg = new RegExp('^(.+)/([^/]+)$', 'g');
            const filename = reg.exec(file)[2];
            return browserify({ entries: [file] })
                .transform('babelify', { presets: ['es2015'] })
                .bundle()
                .on('error', fn.errorAlert)
                .pipe(source(filename))
                .pipe(buffer())
                .pipe($.uglify())
                .pipe($.rename({
                    extname: '.min.js'
                }))
                .pipe($.size({ title: 'Scripts' }))
                .pipe(gulp.dest(routes.dist.js));
        });
    });
}

gulp.task('scripts:lint', () => {
    return gulp.src([APP_FILE, COMPONENT_FILES])
        .pipe($.eslint())
        .pipe($.eslint.format(friendlyFormatter))
        .pipe($.eslint.failAfterError());
});

gulp.task('scripts:watch', () => {
    gulp.watch([APP_FILE, COMPONENT_FILES], ['scripts', browserSync.reload]);
});

// gulp.task('scripts', ['scripts:lint'], () => {
//     return scripts(APP_FILE, 'app.min.js');
// });

gulp.task('scripts', () => {
    // return scripts(APP_FILE, 'app.min.js');
    return multipleScripts(APP_FILE, 'app.min.js');
});
