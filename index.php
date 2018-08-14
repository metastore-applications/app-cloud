<?php

namespace MetaStore\App\Cloud;

require_once( __DIR__ . '/vendor/autoload.php' );

try {
	App::runApp();
} catch ( \Exception $e ) {
	echo 'Message: ' . $e->getMessage();
}
