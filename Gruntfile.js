module.exports = function( grunt ) {
	// Project configuration.
	grunt.initConfig( {
		pkg: grunt.file.readJSON( 'package.json' ),
		jshint: {
			files: ['Gruntfile.js', 'admin/js/*.js' ],
			options: {
				// options here to override JSHint defaults
				globals: {
					jQuery: true,
					console: true,
					module: true,
					document: true
				}
			}
	    }
	} );

	grunt.loadNpmTasks( 'grunt-contrib-jshint' );

	// Default task(s).
	grunt.registerTask( 'default', [ 'jshint' ] );
};