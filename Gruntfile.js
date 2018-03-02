/* jshint node:true */
module.exports = function( grunt ) {
	require( 'load-grunt-tasks' )( grunt );

	// Project configuration.
	grunt.initConfig( {
		pkg: grunt.file.readJSON( 'package.json' ),

		// PHPLint
		phplint: {
			core: [
				'**/*.php',
				'!build/**',
				'!deploy/**',
				'!includes/xmlseclibs/**',
				'!node_modules/**',
				'!repositories/**',
				'!vendor/**',
				'!wp-content/**'
			],
			wp_pay: [
				'vendor/wp-pay/**/*.php',
				'vendor/wp-pay-extensions/**/*.php',
				'vendor/wp-pay-gateways/**/*.php'
			]
		},

		// PHP Code Sniffer
		phpcs: {
			core: {
				src: [
					'admin/**/*.php',
					'classes/**/*.php',
					'includes/**/*.php',
					'!includes/updates/**',
					'!includes/xmlseclibs/**',
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

		// PHPUnit
		phpunit: {
			options: {
				bin: 'vendor/bin/phpunit'
			},
			classes: {
				
			}
		},

		// JSHint
		jshint: {
			options: grunt.file.readJSON( '.jshintrc' ),
			grunt: [ 'Gruntfile.js' ],
			plugin: [
				'src/js/*.js'
			]
		},

		// Sass Lint
		sasslint: {
			options: {
				configFile: '.sass-lint.yml'
			},
			target: [
				'src/sass/**/*.scss'
			]
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
					'!build/**',
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
					cwd: 'deploy/latest',
					domainPath: 'languages',
					type: 'wp-plugin',
					mainFile: 'pronamic-ideal.php',
					updatePoFiles: true,
					updateTimestamp: false
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

			// Check versions
			check_versions: {
				command: 'php src/check-versions.php'
			},

			// PlantUML
			plantuml: {
				command: 'plantuml ./documentation/*.plantuml'
			},

			// WordPress test environment
			test: {
				command: 'bash tests/setup.sh'
			},

			// Generate readme.txt
			readme_txt: {
				command: 'php src/readme-txt/readme.php > readme.txt'
			},

			// Generate README.md
			readme_md: {
				command: 'php src/readme-md/README.php > README.md'
			},

			// Generate CHANGELOG.md
			changelog_md: {
				command: 'php src/changelog-md/CHANGELOG.php > CHANGELOG.md'
			},

			// Composer
			deploy: {
				command: [
					'cd deploy/latest',
					'composer install --no-dev --prefer-dist'
				].join( '&&' )
			}
		},

		// Copy
		copy: {
			scripts: {
				files: [
					{ // JS
						expand: true,
						cwd: 'src/js/',
						src: '**',
						dest: 'js/'
					}
				]
			},
			assets: {
				files: [
					{ // Flot - http://www.flotcharts.org/
						expand: true,
						cwd: 'node_modules/flot/',
						src: [
							'jquery.flot.js',
							'jquery.flot.time.js',
							'jquery.flot.resize.js'
						],
						dest: 'assets/flot'
					},
					{ // accounting.js - http://openexchangerates.github.io/accounting.js/
						expand: true,
						cwd: 'node_modules/accounting/',
						src: 'accounting.js',
						dest: 'assets/accounting'
					},
					{ // Tippy.js - https://atomiks.github.io/tippyjs/
						expand: true,
						cwd: 'node_modules/tippy.js/dist',
						src: 'tippy.all.js',
						dest: 'assets/tippy.js'
					}
				]
			},
			other: {
				files: [
					{ // extensions.json
						expand: true,
						cwd: 'src/',
						src: [
							'extensions.json'
						],
						dest: 'other/'
					}
				]
			},
			deploy: {
				expand: true,
				src: [
					'**',
					'!composer.lock',
					'!Gruntfile.js',
					'!package.json',
					'!package-lock.json',
					'!phpunit.xml',
					'!phpunit.xml.dist',
					'!phpcs.xml.dist',
					'!CHANGELOG.md',
					'!README.md',
					'!yarn.lock',
					'!build/**',
					'!deploy/**',
					'!etc/**',
					'!documentation/**',
					'!node_modules/**',
					'!repositories/**',
					'!src/**',
					'!tests/**',
					'!vendor/**',
					'!wp-content/**'
				],
				dest: 'deploy/latest/'
			},
			pot_to_dev: {
				expand: true,
				cwd: 'deploy/latest/languages/',
				src: '**',
				dest: 'languages/'
			}
		},

		// Composer
		composer : {
			options : {

			},
			some_target: {
            	options : {
                	cwd: 'deploy/latest'
				}
			}
		},

		// SASS
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

		// PostCSS
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

		// Uglify
		uglify: {
			scripts: {
				files: {
					// Pronamic Pay
					'js/admin.min.js': 'src/js/admin.js',
					'js/admin-reports.min.js': 'src/js/admin-reports.js',
					'js/admin-tour.min.js': 'src/js/admin-tour.js',
					// Accounting
					'assets/accounting/accounting.min.js': 'assets/accounting/accounting.js',
					// Flot
					'assets/flot/jquery.flot.min.js': 'assets/flot/jquery.flot.js',
					'assets/flot/jquery.flot.resize.min.js': 'assets/flot/jquery.flot.resize.js',
					'assets/flot/jquery.flot.time.min.js': 'assets/flot/jquery.flot.time.js',
					// Tippy.js
					'assets/tippy.js/tippy.all.min.js': 'assets/tippy.js/tippy.all.js'
				}
			}
		},

		// Clean
		clean: {
			assets: {
				src: [
					'assets',
					'css',
					'images',
					'js'
				]
			},
			deploy: {
				src: [ 'deploy/latest' ]
			},
			deploy_composer: {
				src: [
					'deploy/latest/vendor/wp-pay*/*/bin/**',
					'deploy/latest/vendor/wp-pay*/*/documentation',
					'deploy/latest/vendor/wp-pay*/*/test/**',
					'deploy/latest/vendor/wp-pay*/*/tests/**',
					'deploy/latest/vendor/wp-pay*/*/.gitignore',
					'deploy/latest/vendor/wp-pay*/*/.travis.yml',
					'deploy/latest/vendor/wp-pay*/*/Gruntfile.js',
					'deploy/latest/vendor/wp-pay*/*/package.json',
					'deploy/latest/vendor/wp-pay*/*/package-lock.json',
					'deploy/latest/vendor/wp-pay*/*/phpcs.ruleset.xml',
					'deploy/latest/vendor/wp-pay*/*/phpmd.ruleset.xml',
					'deploy/latest/vendor/wp-pay*/*/phpunit.xml.dist'
				]
			},
			deploy_wp_content: {
				src: [
					'deploy/latest/wp-content'
				]
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
					svnUrl: 'http://plugins.svn.wordpress.org/<%= pkg.name %>/',
					svnDir: 'deploy/wp-svn',
					svnUsername: 'pronamic',
					deployDir: 'deploy/latest',
					version: '<%= pkg.version %>'
				}
			}
		},

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
	grunt.registerTask( 'default', [ 'jshint', 'phplint', 'phpcs', 'phpunit', 'shell:check_versions' ] );
	grunt.registerTask( 'assets', [ 'sasslint', 'sass', 'postcss', 'copy:scripts', 'copy:assets', 'copy:other' ] );
	grunt.registerTask( 'min', [ 'uglify', 'imagemin' ] );
	grunt.registerTask( 'plantuml', [ 'shell:plantuml' ] );
	grunt.registerTask( 'pot', [ 'build_latest', 'makepot', 'copy:pot_to_dev' ] );

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

	grunt.registerTask( 'build_latest', [
		'clean:deploy',
		'copy:deploy',
		'shell:deploy',
		'clean:deploy_composer',
		'clean:deploy_wp_content'
	] );

	grunt.registerTask( 'deploy', [
		'default',
		'build_docs',
		'build_assets',
		'build_latest',
		'makepot',
		'copy:pot_to_dev',
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
