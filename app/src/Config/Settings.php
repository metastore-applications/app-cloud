<?php

namespace MetaStore\App\Cloud\Config;

use MetaStore\App\Kernel\Config;

/**
 * Class Settings
 * @package MetaStore\App\Cloud\Config
 */
class Settings {

	/**
	 * @return mixed
	 */
	public static function getMail() {
		$out = Config::getFile( 'mail' );

		return $out['mail'];
	}

	/**
	 * @param $status
	 *
	 * @return mixed
	 */
	public static function getAuth( $status ) {
		$get = self::getMail();

		if ( ! isset( $get['auth'][ $status ] ) ) {
			return false;
		}

		$out = $get['auth'][ $status ];

		return $out;
	}
}