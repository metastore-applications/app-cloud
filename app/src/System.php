<?php

namespace MetaStore\App\Cloud;

use MetaStore\App\Kernel;

/**
 * Class System
 * @package MetaStore\App\Cloud
 */
class System {

	public static function destroyToken() {
		//unset( $_SESSION['_metaToken'], $_SESSION['_metaCaptcha'] );
		unset( $_SESSION['_ticketID'] );
	}

	/**
	 * @throws \Exception
	 */
	public static function checkToken() {
		if ( Kernel\Request::setParam( '_metaToken' ) !== $_SESSION['_metaToken'] ) {
			throw new \Exception( 'Token' );
		}
	}

	/**
	 * @throws \Exception
	 */
	public static function checkCaptcha() {
		if ( ( ! Kernel\Request::setParam( '_metaCaptcha' ) )
		     || ( Kernel\Request::setParam( '_metaCaptcha' ) != $_SESSION['_metaCaptcha'][1] ) ) {
			throw new \Exception( 'Captcha' );
		}
	}

	/**
	 * @param $fields
	 *
	 * @throws \Exception
	 */
	public static function checkFormField( $fields ) {
		foreach ( $fields as $field ) {
			if ( empty( Kernel\Request::setParam( $field ) ) ) {
				throw new \Exception( 'Form Field' );
			}
		}
	}
}