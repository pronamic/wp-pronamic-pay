/* jshint node:true */
module.exports = function( grunt ) {
	require( 'load-grunt-tasks' )( grunt );

	// Project configuration.
	grunt.initConfig( {
		pkg: grunt.file.readJSON( 'package.json' ),

		// PHPLint
		phplint: {
			plugin: [
				'**/*.php',
				'!build/**',
				'!deploy/**',
				'!includes/xmlseclibs/**',
				'!node_modules/**',
				'!repositories/**',
				'!vendor/**',
				'!wordpress/**',
				'!wp-content/**'
			],
			wp_pay: [
				'vendor/wp-pay/**/*.php',
				'vendor/wp-pay-extensions/**/*.php',
				'vendor/wp-pay-gateways/**/*.php'
			]
		},

		// PHP Code Sniffer.
		phpcs: {
			core: {
				src: [
					'admin/**/*.php',
					'includes/**/*.php',
					'!includes/updates/**',
					'views/**/*.php',
					'pronamic-ideal.php',
					'uninstall.php'
				]
			},
			options: {
				bin: 'vendor/bin/phpcs',
				standard: 'phpcs.xml.dist',
				showSniffCodes: true
			}
		},

		// PHPUnit.
		phpunit: {
			options: {
				bin: 'vendor/bin/phpunit'
			},
			classes: {
				
			}
		},

		// JSHint.
		jshint: {
			options: grunt.file.readJSON( '.jshintrc' ),
			grunt: [ 'Gruntfile.js' ],
			plugin: [
				'src/js/*.js'
			]
		},

		// Check textdomain errors.
		checktextdomain: {
			options:{
				text_domain: 'pronamic_ideal',
				keywords: [
					'__:1,2d',
					'_e:1,2d',
					'_x:1,2c,3d',
					'esc_html__:1,2d',
					'esc_html_e:1,2d',
					'esc_html_x:1,2c,3d',
					'esc_attr__:1,2d',
					'esc_attr_e:1,2d',
					'esc_attr_x:1,2c,3d',
					'_ex:1,2c,3d',
					'_n:1,2,4d',
					'_nx:1,2,4c,5d',
					'_n_noop:1,2,3d',
					'_nx_noop:1,2,3c,4d'
				]
			},
			plugin: {
				src: [
					'**/*.php',
					'!build/**',
					'!deploy/**',
					'!node_modules/**',
					'!repositories/**',
					'!vendor/**',
					'!wordpress/**',
					'!wp-content/**'
				],
				expand: true
			},
			wp_pay: {
				src: [
					'vendor/wp-pay/**/*.php',
					'vendor/wp-pay-gateways/**/*.php',
					'vendor/wp-pay-extensions/**/*.php'
				],
				expand: true
			}
		},

		// Imagemin.
		imagemin: {
			build: {
				files: [
					{ // Images
						expand: true,
						cwd: 'src/images/',
						src: ['**/*.{png,jpg,gif,svg,ico}'],
						dest: 'images/'
					}
				]
			}
		},

		// Shell.
		shell: {
			// Make POT.
			makepot: {
				command: 'wp pronamic i18n make-pot . languages/pronamic_ideal.pot --slug="pronamic-ideal"'
			},

			msgmerge: {
				command: 'find languages/*.po -type f -exec msgmerge --update {} languages/pronamic_ideal.pot \\;'
			},

			// PlantUML.
			plantuml: {
				command: 'plantuml ./documentation/*.plantuml'
			},

			// Generate readme.txt.
			readme_txt: {
				command: 'php src/readme-txt/readme.php > readme.txt'
			},

			// Generate README.md.
			readme_md: {
				command: 'php src/readme-md/README.php > README.md'
			},

			// Generate CHANGELOG.md.
			changelog_md: {
				command: 'php src/changelog-md/CHANGELOG.php > CHANGELOG.md'
			}
		},

		// Copy.
		copy: {
			images: {
				files: [
					{ // images.
						expand: true,
						cwd: 'src/images/',
						src: [
							'**/*'
						],
						dest: 'images/'
					}
                ]
			},
			other: {
				files: [
					{ // extensions.json.
						expand: true,
						cwd: 'src/',
						src: [
							'extensions.json'
						],
						dest: 'other/'
					}
				]
			}
		},

		// Clean.
		clean: {
			images: {
				src: [ 'images' ]
			}
		}
	} );

	// Default task(s).
	grunt.registerTask( 'default', [ 'jshint', 'phplint', 'phpcs', 'phpunit' ] );
	grunt.registerTask( 'assets', [ 'copy:images', 'copy:other' ] );
	grunt.registerTask( 'min', [ 'imagemin' ] );
	grunt.registerTask( 'plantuml', [ 'shell:plantuml' ] );
	grunt.registerTask( 'pot', [ 'checktextdomain', 'shell:makepot', 'shell:msgmerge' ] );

	grunt.registerTask( 'build_docs', [
		'shell:readme_txt',
		'shell:readme_md',
		'shell:changelog_md'
	] );

	grunt.registerTask( 'build_assets', [
		'clean:images',
		'assets',
		'min'
	] );

	grunt.registerTask( 'build', [
		'build_docs',
		'default',
		'build_assets',
		'pot'
	] );
};
