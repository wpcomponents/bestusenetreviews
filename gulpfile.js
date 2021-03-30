const gulp   = require( 'gulp' );
const potomo = require( 'gulp-potomo-js' );

gulp.task( 'default', () =>
	gulp.src( 'languages/*.po' )
		.pipe( potomo() )
		.pipe( gulp.dest( 'languages' ) )
);
