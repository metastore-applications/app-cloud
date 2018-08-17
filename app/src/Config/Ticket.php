<?php

namespace MetaStore\App\Cloud\Config;

use MetaStore\App\Kernel;

/**
 * Class Ticket
 * @package MetaStore\App\Cloud\Config
 */
class Ticket {

	/**
	 * @return mixed
	 */
	public static function getConfig() {
		$out = Kernel\Config::getFile( 'service.ticket' );

		return $out['ticket'];
	}

	/**
	 * @param $status
	 *
	 * @return mixed
	 */
	public static function getMailFrom( $status ) {
		$get = self::getConfig();

		if ( ! isset( $get['mail']['from'][ $status ] ) ) {
			return false;
		}

		$out = $get['mail']['from'][ $status ];

		return $out;
	}

	/**
	 * @return mixed
	 */
	public static function getMailTo() {
		$get = self::getConfig();

		if ( ! isset( $get['mail']['to'] ) ) {
			return false;
		}

		$out = $get['mail']['to'];

		return $out;
	}

}