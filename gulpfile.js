var args = require('yargs').argv,
    gulp = require('gulp'),
    exec = require('gulp-exec'),
    bump = require('gulp-bump');

var semver = args.semver || 'patch';

gulp.task('bump', function () {
    return gulp.src(['./package.json', './composer.json'])
        .pipe(bump())
        .pipe(gulp.dest('./'));
});

gulp.task('release', ['bump'], function () {
    var options = {
        err:    true,
        stderr: true,
        stdout: true 
    };
    var version = 'v' + require('./package.json').version;
    var message = 'Release ' + version;

    return gulp.src('./', { read: false })
        .pipe(exec('git add . -A'))
        .pipe(exec.reporter(options))
        .pipe(exec('git commit -m "' + message + '"'))
        .pipe(exec.reporter(options))
        .pipe(exec('git tag ' + version))
        .pipe(exec.reporter(options))
        .pipe(exec('git push origin master'))
        .pipe(exec.reporter(options))
        .pipe(exec('git push origin --tags'))
        .pipe(exec.reporter(options));
});