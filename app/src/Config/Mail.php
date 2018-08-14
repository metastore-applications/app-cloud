<?php

namespace MetaStore\App\Cloud\Config;

use MetaStore\App\Kernel;

class Mail {

	/**
	 * @return mixed
	 */
	public static function getMail() {
		$out = Kernel\Config::getFile( 'mail' );

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