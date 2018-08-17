<?php

namespace MetaStore\App\Cloud\Config;

use MetaStore\App\Kernel;

/**
 * Class Upload
 * @package MetaStore\App\Cloud\Config
 */
class Upload {

	/**
	 * @return mixed
	 */
	public static function getConfig() {
		$out = Kernel\Config::getFile( 'service.file.upload' );

		return $out['upload'];
	}

	/**
	 * @param $status
	 *
	 * @return mixed
	 */
	public static function getMime( $status ) {
		$get = self::getConfig();

		if ( ! isset( $get['mime'][ $status ] ) ) {
			return false;
		}

		$out = $get['mime'][ $status ];

		return $out;
	}
}