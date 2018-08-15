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

	/**
	 * @param $param
	 *
	 * @return mixed
	 */
	public static function getSystem( $param ) {
		$get = self::getConfig();

		if ( ! isset( $get['system'][ $param ] ) ) {
			return false;
		}

		$out = $get['system'][ $param ];

		return $out;
	}

	/**
	 * @param $service
	 *
	 * @return mixed
	 */
	public static function getService( $service ) {
		$get = self::getConfig();

		if ( ! isset( $get['service'][ $service ] ) ) {
			return false;
		}

		$out = $get['service'][ $service ];

		return $out;
	}
}