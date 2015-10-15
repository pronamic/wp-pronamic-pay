<?php

$data = file( 'convert.md', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );

foreach ( $data as $line ) {
	if ( '## ' === substr( $line, 0, 3 ) ) {
		$version = substr( $line, 3 ); ?>
		]
	},
	{
		"version": "<?php echo $version; ?>",
		"date": "",
		"changes": [
<?php
	} else {
		if ( '*	' === substr( $line, 0, 2 ) ) {
			$description = substr( $line, 2 ); ?>
			"<?php echo $description; ?>",
<?php
		}
	}
}
