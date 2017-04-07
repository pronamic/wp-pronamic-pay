<?php

$data       = file_get_contents( __DIR__ . '/../extensions.json' );
$extensions = json_decode( $data );

?>
| Name | Author | WordPress.org | GitHub | Requires at least | Tested up to |
| ---- | ------ | ------------- | ------ | ----------------- | ------------ |
<?php foreach ( $extensions as $extension ) : ?>
| <?php

echo '[' . $extension->name . '](' . $extension->url . ')';

echo ' | ';

if ( isset( $extension->author, $extension->author_url ) ) {
	echo '[' . $extension->author . '](' . $extension->author_url . ')';
}

echo ' | ';

if ( isset( $extension->wp_org_url ) ) {
	echo '[WordPress.org](' . $extension->wp_org_url . ')';
}

echo ' | ';

if ( isset( $extension->github_url ) ) {
	echo '[GitHub](' . $extension->github_url . ')';
}

echo ' | ';

if ( isset( $extension->requires_at_least ) ) {
	echo $extension->requires_at_least;
}

echo ' | ';

echo $extension->tested_up_to;

?> |
<?php endforeach; ?>
