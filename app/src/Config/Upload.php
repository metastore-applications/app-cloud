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

		return $out['service']['upload'];
	}

	/**
	 * @param $status
	 *
	 * @return array
	 */
	public static function getMime( $status ) {
		$get = self::getConfig();

		$out = $get['mime'][ $status ] ?? '' ?: [];

		return $out;
	}
}