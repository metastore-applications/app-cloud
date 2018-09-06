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

		return $out['service']['ticket'];
	}

	/**
	 * @param $status
	 *
	 * @return mixed
	 */
	public static function getMailFrom( $status ) {
		$get = self::getConfig();

		$out = $get['mail']['from'][ $status ] ?? '' ?: [];

		return $out;
	}

	/**
	 * @return mixed
	 */
	public static function getMailTo() {
		$get = self::getConfig();

		$out = $get['mail']['to'] ?? '' ?: [];

		return $out;
	}

	/**
	 * @return array
	 */
	public static function getSecurityMail() {
		$get = self::getConfig();
		$out = $get['security']['mail'] ?? '' ?: [];

		return $out;
	}

	/**
	 * @return array
	 */
	public static function getSecurityBypass() {
		$get = self::getConfig();
		$out = $get['security']['bypass'] ?? '' ?: [];

		return $out;
	}

}