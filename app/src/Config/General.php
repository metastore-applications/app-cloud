<?php

namespace MetaStore\App\Cloud\Config;

use MetaStore\App\Kernel;

/**
 * Class Ticket
 * @package MetaStore\App\Cloud\Config
 */
class General {

	/**
	 * @return mixed
	 */
	public static function getConfig() {
		$out = Kernel\Config::getFile( 'general' );

		return $out['general'];
	}

	public static function getService( $service ) {
		$get = self::getConfig();

		if ( ! isset( $get['service'][ $service ] ) ) {
			return false;
		}

		$out = $get['service'][ $service ];

		return $out;
	}
}