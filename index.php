<?php

namespace MetaStore\App\Cloud;

use MetaStore\App\Kernel\Parser;

require_once( __DIR__ . '/vendor/autoload.php' );

try {
	App::runApp();
} catch ( \Exception $e ) {
	echo Parser::json( [
		'error' => [
			'msg'  => $e->getMessage(),
			'code' => $e->getCode(),
		],
	], 1 );
}
