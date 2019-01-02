var gulp = require('gulp');
var bower = require('gulp-bower');

var config = {
    bowerDir: './bower_components'
}

var vendors = {
    'bower_components/chart.js/dist/**/*':'chart.js',
    'bower_components/select2/dist/**/*':'select2',
};

// Install ow Bower Components
gulp.task('bower', function() {
    return bower()
        .pipe(gulp.dest(config.bowerDir))
});


gulp.task('vendor',function(){

    for(k in vendors)
    {
        gulp.src(k)
            .pipe(gulp.dest('assets/vendors/'+vendors[k]));
    }
    return gulp;
});

gulp.task('default', ['bower', 'vendor' ]);