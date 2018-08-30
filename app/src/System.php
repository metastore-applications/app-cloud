<?php

namespace MetaStore\App\Cloud;

use MetaStore\App\Kernel;

/**
 * Class System
 * @package MetaStore\App\Cloud
 */
class System {

	/**
	 * @throws \Exception
	 */
	public static function createToken() {
		session_start();

		if ( ! isset( $_SESSION['_metaToken'] ) ) {
			$_SESSION['_metaToken'] = Kernel\Token::generator();
		}

		if ( ! isset( $_SESSION['_ticketID'] ) ) {
			$_SESSION['_ticketID'] = Kernel\Hash::generator();
		}

		if ( ! isset( $_SESSION['_metaCaptcha'] ) ) {
			$_SESSION['_metaCaptcha'] = [
				Kernel\Random::number( 1000000000, 9999999999 ),
				Kernel\Random::number( 10000, 99999 )
			];
		}

		if ( ! isset( $_SESSION['_uploadDir'] ) ) {
			$_SESSION['_uploadDir'] = Kernel\Hash::generator( 'sha1' );
		}
	}

	/**
	 *
	 */
	public static function destroyToken() {
		unset( $_SESSION['_ticketID'], $_SESSION['_uploadDir'] );
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

	/**
	 * @param $service
	 */
	public static function checkPrivateIP( $service ) {
		$ip     = Kernel\Route::REMOTE_ADDR();
		$filter = filter_var(
			$ip,
			FILTER_VALIDATE_IP,
			FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
		);

		if ( Config\General::getService( $service )['private'] && $filter ) {
			http_response_code( 403 );
			die ( 'Forbidden' );
		}
	}

	/**
	 * @param $file
	 * @param $service
	 */
	public static function checkAuth( $file, $service ) {
		$cfg  = Kernel\Config::getFile( $file );
		$auth = $cfg['service'][ $service ]['auth'];

		$auth_user = Kernel\Parser::clearData( $_SERVER['PHP_AUTH_USER'] ?? '' ?: '' );
		$auth_pass = Kernel\Parser::clearData( $_SERVER['PHP_AUTH_PW'] ?? '' ?: '' );


		$user = $auth[ $auth_user ] ?? '' ?: [];
		$pass = $user['password'] ?? '' ?: '';


		$validated = ( in_array( $auth_user, $user ) ) && ( $auth_pass === $pass );

		if ( ! $validated ) {
			header( 'WWW-Authenticate: Basic realm="Cloud System"' );
			http_response_code( 401 );
			die ( 'Not authorized' );
		}
	}
}