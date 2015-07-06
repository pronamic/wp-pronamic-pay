/* jshint node:true */
module.exports = function( grunt ) {
	// Project configuration.
	grunt.initConfig( {
		pkg: grunt.file.readJSON( 'package.json' ),

		// PHPLint
		phplint: {
			options: {
				phpArgs: {
					'-lf': null
				}
			},
			all: [
				'**/*.php',
				'!deploy/**',
				'!includes/icepay/**',
				'!includes/xmlseclibs/**',
				'!node_modules/**',
				'!vendor/**',
				'!wp-content/**'
			]
		},

		// PHP Code Sniffer
		phpcs: {
			application: {
				src: [
					'**/*.php',
					'!deploy/**',
					'!includes/icepay/**',
					'!includes/xmlseclibs/**',
					'!node_modules/**',
					'!vendor/**',
					'!wp-content/**'
				]
			},
			options: {
				standard: 'phpcs.ruleset.xml',
				showSniffCodes: true
			}
		},

		// PHPUnit
		phpunit: {
			classes: {}
		},

		// JSHint
		jshint: {
			options: grunt.file.readJSON( '.jshintrc' ),
			grunt: [ 'Gruntfile.js' ],
			plugin: [
				'src/js/*.js'
			]
		},

		// Check WordPress version
		checkwpversion: {
			options: {
				readme: 'readme.txt',
				plugin: 'pronamic-ideal.php'
			},
			check: {
				version1: 'plugin',
				version2: 'readme',
				compare: '=='
			},
			check2: {
				version1: 'plugin',
				version2: '<%= pkg.version %>',
				compare: '=='
			}
		},
		
		// Check textdomain errors
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
			files: {
				src:  [
					'**/*.php',
					'!deploy/**',
					'!node_modules/**',
					'!tests/**',
					'!wp-content/**'
				],
				expand: true
			}
		},

		// Make POT
		makepot: {
			target: {
				options: {
					domainPath: 'languages',
					type: 'wp-plugin',
					updatePoFiles: true,
					exclude: [
						'deploy/.*',
						'node_modules/.*',
						'wp-content/.*'
					]
				}
			}
		},

		// Imagemin
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

		// Shell
		shell: {
			// PlantUML
			plantuml: {
				command: 'plantuml ./documentation/*.plantuml'
			},

			// WordPress test environment
			test: {
				command: 'bash tests/setup.sh'
			}
		},

		// Copy
		copy: {
			styles: {
				files: [
					{ // CSS
						expand: true,
						cwd: 'src/css/',
						src: [ '**' ],
						dest: 'css'
					},
				]
			},
			scripts: {
				files: [
					{ // JS
						expand: true,
						cwd: 'src/js/',
						src: [ '**' ],
						dest: 'js'
					},
				]
			},
			assets: {
				files: [
					{ // Flot - http://www.flotcharts.org/
						expand: true,
						cwd: 'bower_components/flot/',
						src: [
							'jquery.flot.js',
							'jquery.flot.time.js',
							'jquery.flot.resize.js',
						],
						dest: 'assets/flot'
					},
					{ // accounting.js - http://openexchangerates.github.io/accounting.js
						expand: true,
						cwd: 'bower_components/accounting.js/',
						src: [
							'accounting.js'
						],
						dest: 'assets/accounting'
					}
				]
			},
			deploy: {
				src: [
					'**',
					'!composer.json',
					'!composer.lock',
					'!Gruntfile.js',
					'!package.json',
					'!phpunit.xml',
					'!phpunit.xml.dist',
					'!phpcs.ruleset.xml',
					'!CHANGELOG.md',
					'!README.md',
					'!deploy/**',
					'!documentation/**',
					'!node_modules/**',
					'!src/**',
					'!tests/**',
					'!vendor/*/*/test/**',
					'!vendor/*/*/tests/**',
					'!vendor/bin/**',
					'!vendor/guzzle/**',
					'!vendor/psr/**',
					'!vendor/satooshi/**',
					'!vendor/symfony/**',
					'!wp-content/**'
				],
				dest: 'deploy/latest',
				expand: true
			}
		},

		// Compass
		compass: {
			build: {
				options: {
					sassDir: 'src/sass',
					cssDir: 'src/css'
				}
			}
		},

		// Autoprefixer
		autoprefixer: {
			options: {
		 		browsers: [ 'last 2 version', 'ie 8', 'ie 9' ]
			},
			admin: {
				src: 'src/css/admin.css'
			},
		},

		// CSS min
		cssmin: {
			styles: {
				files: {
					'css/admin.min.css': 'src/css/admin.css',
				}
			},
			assets: {
				files: {
					
				}
			}
		},


		// Uglify
		uglify: {
			scripts: {
				files: {
					'js/admin.min.js': 'src/js/admin.js',
				}
			},
			assets: {
				files: {
					
				}
			}
		},

		// Clean
		clean: {
			deploy: {
				src: [ 'deploy/latest' ]
			}
		},

		// Compress
		compress: {
			deploy: {
				options: {
					archive: 'deploy/archives/<%= pkg.name %>.<%= pkg.version %>.zip'
				},
				expand: true,
				cwd: 'deploy/latest',
				src: ['**/*'],
				dest: '<%= pkg.name %>/'
			}
		},

		// Git checkout
		gitcheckout: {
			tag: {
				options: {
					branch: 'tags/<%= pkg.version %>'
				}
			},
			develop: {
				options: {
					branch: 'develop'
				}
			}
		},

		// S3
		aws_s3: {
			options: {
				region: 'eu-central-1'
			},
			deploy: {
				options: {
					bucket: 'downloads.pronamic.eu',
					differential: true
				},
				files: [
					{
						expand: true,
						cwd: 'deploy/archives/',
						src: '<%= pkg.name %>.<%= pkg.version %>.zip',
						dest: 'plugins/<%= pkg.name %>/'
					}
				]
			}
		},
		
		// WordPress deploy
		rt_wp_deploy: {
			app: {
				options: {
					svnUrl: 'http://plugins.svn.wordpress.org/pronamic-ideal/',
					svnDir: 'deploy/wp-svn',
					svnUsername: 'pronamic',
					deployDir: 'deploy/latest',
					version: '<%= pkg.version %>'
				}
			}
		}
	} );

	grunt.loadNpmTasks( 'grunt-autoprefixer' );
	grunt.loadNpmTasks( 'grunt-phplint' );
	grunt.loadNpmTasks( 'grunt-phpcs' );
	grunt.loadNpmTasks( 'grunt-phpunit' );
	grunt.loadNpmTasks( 'grunt-composer' );
	grunt.loadNpmTasks( 'grunt-contrib-compass' );
	grunt.loadNpmTasks( 'grunt-contrib-cssmin' );
	grunt.loadNpmTasks( 'grunt-contrib-clean' );
	grunt.loadNpmTasks( 'grunt-contrib-copy' );
	grunt.loadNpmTasks( 'grunt-contrib-compress' );
	grunt.loadNpmTasks( 'grunt-contrib-imagemin' );
	grunt.loadNpmTasks( 'grunt-contrib-jshint' );
	grunt.loadNpmTasks( 'grunt-contrib-uglify' );
	grunt.loadNpmTasks( 'grunt-checktextdomain' );
	grunt.loadNpmTasks( 'grunt-checkwpversion' );
	grunt.loadNpmTasks( 'grunt-wp-i18n' );
	grunt.loadNpmTasks( 'grunt-shell' );
	grunt.loadNpmTasks( 'grunt-git' );
	grunt.loadNpmTasks( 'grunt-aws-s3' );
	grunt.loadNpmTasks( 'grunt-rt-wp-deploy' );

	// Default task(s).
	grunt.registerTask( 'default', [ 'jshint', 'phplint', 'phpunit', 'checkwpversion' ] );
	grunt.registerTask( 'assets', [ 'compass', 'autoprefixer', 'copy:styles', 'copy:scripts', 'copy:assets' ] );
	grunt.registerTask( 'min', [ 'cssmin:styles', 'uglify:scripts', 'imagemin' ] );
	grunt.registerTask( 'plantuml', [ 'shell:plantuml' ] );
	grunt.registerTask( 'pot', [ 'makepot' ] );

	grunt.registerTask( 'deploy', [
		'default',
		'composer:update',
		'composer:dump-autoload:optimize',
		'clean:deploy',
		'copy:deploy',
		'compress:deploy'
	] );

	grunt.registerTask( 'wp-deploy', [
		'gitcheckout:tag',
		'deploy',
		'rt_wp_deploy',
		'gitcheckout:develop'
	] );
	
	grunt.registerTask( 's3-deploy', [
		'gitcheckout:tag',
		'deploy',
		'aws_s3:deploy',
		'gitcheckout:develop'
	] );
};
