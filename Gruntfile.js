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

		// Sass Lint.
		sasslint: {
			options: {
				configFile: '.sass-lint.yml'
			},
			target: [
				'src/sass/**/*.scss'
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
			scripts: {
				files: [
					{ // JS.
						expand: true,
						cwd: 'src/js/',
						src: '**',
						dest: 'js/'
					}
				]
			},
			assets: {
				files: [
					{ // Flot - http://www.flotcharts.org/.
						expand: true,
						cwd: 'node_modules/Flot/',
						src: [
							'jquery.flot.js',
							'jquery.flot.time.js',
							'jquery.flot.resize.js'
						],
						dest: 'assets/flot'
					},
					{ // accounting.js - http://openexchangerates.github.io/accounting.js/.
						expand: true,
						cwd: 'node_modules/accounting/',
						src: 'accounting.js',
						dest: 'assets/accounting'
					},
					{ // Tippy.js - https://atomiks.github.io/tippyjs/.
						expand: true,
						cwd: 'node_modules/tippy.js/dist',
						src: 'tippy.all.js',
						dest: 'assets/tippy.js'
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

		// SASS.
		sass: {
			options: {
				style: 'expanded'
			},
			build: {
				files: [ {
					expand: true,
					cwd: 'src/sass',
					src: '*.scss',
					dest: 'src/css',
					ext: '.css'
				} ]
			}
		},

		// PostCSS.
		postcss: {
			options: {
				map: false
			},
			prefix: {
				options: {
					processors: [
						require( 'autoprefixer' )(),
						require( 'postcss-eol' )()
					]
				},
				files: [ {
					expand: true,
					cwd: 'src/css/',
					src: '*.css',
					dest: 'css/'
				} ]
			},
			min: {
				options: {
					processors: [
						require( 'cssnano' )(),
						require( 'postcss-eol' )()
					]
				},
				files: [ {
					expand: true,
					cwd: 'css/',
					src: [
						'*.css',
						'!*.min.css'
					],
					dest: 'css/',
					ext: '.min.css'
				} ]
			}
		},

		// Uglify.
		uglify: {
			scripts: {
				files: {
					// Pronamic Pay.
					'js/admin.min.js': 'src/js/admin.js',
					'js/admin-reports.min.js': 'src/js/admin-reports.js',
					'js/admin-tour.min.js': 'src/js/admin-tour.js',
					// Accounting.
					'assets/accounting/accounting.min.js': 'assets/accounting/accounting.js',
					// Flot.
					'assets/flot/jquery.flot.min.js': 'assets/flot/jquery.flot.js',
					'assets/flot/jquery.flot.resize.min.js': 'assets/flot/jquery.flot.resize.js',
					'assets/flot/jquery.flot.time.min.js': 'assets/flot/jquery.flot.time.js',
					// Tippy.js.
					'assets/tippy.js/tippy.all.min.js': 'assets/tippy.js/tippy.all.js'
				}
			}
		},

		// Clean.
		clean: {
			assets: {
				src: [
					'assets',
					'css',
					'images',
					'js'
				]
			}
		},
		
		// Webfont.
		webfont: {
			icons: {
				src: 'src/fonts/images/*.svg',
				dest: 'fonts',
				options: {
					font: 'pronamic-pay-icons',
					fontFamilyName: 'Pronamic Pay Icons',
					normalize: true,
					stylesheets: [ 'css' ],
					templateOptions: {
						baseClass: 'pronamic-pay-icon',
						classPrefix: 'pronamic-pay-icon-'
					},
					types: [ 'eot', 'woff2', 'woff', 'ttf', 'svg' ],
					fontHeight: 768,
					customOutputs: [ {
						template: 'src/fonts/templates/variables.scss',
						dest: 'src/fonts/_variables.scss'
					} ]
				}
			}
		}
	} );

	// Default task(s).
	grunt.registerTask( 'default', [ 'jshint', 'phplint', 'phpcs', 'phpunit' ] );
	grunt.registerTask( 'assets', [ 'sasslint', 'sass', 'postcss', 'copy:scripts', 'copy:assets', 'copy:other' ] );
	grunt.registerTask( 'min', [ 'uglify', 'imagemin' ] );
	grunt.registerTask( 'plantuml', [ 'shell:plantuml' ] );
	grunt.registerTask( 'pot', [ 'checktextdomain', 'shell:makepot', 'shell:msgmerge' ] );

	grunt.registerTask( 'build_docs', [
		'shell:readme_txt',
		'shell:readme_md',
		'shell:changelog_md'
	] );

	grunt.registerTask( 'build_assets', [
		'clean:assets',
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
