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

		$out = $get['system'][ $param ] ?? '' ?: [];

		return $out;
	}

	/**
	 * @param $service
	 *
	 * @return mixed
	 */
	public static function getService( $service ) {
		$get = self::getConfig();

		$out = $get['service'][ $service ] ?? '' ?: [];

		return $out;
	}
}