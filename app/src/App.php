<?php

namespace MetaStore\App\Cloud;

use MetaStore\App\Kernel;
use MetaStore\App\Cloud\{Ticket, File};

/**
 * Class App
 * @package MetaStore\App\Cloud
 */
class App {

	/**
	 * @throws \Exception
	 */
	public static function setSession() {
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
	}

	/**
	 * @return string
	 */
	public static function getType() {
		$get = Kernel\Request::getParam( 'get' );
		$out = Kernel\Parser::normalizeData( $get );

		return $out;
	}

	/**
	 * @return bool
	 * @throws \Exception
	 */
	public static function getPage() {
		switch ( self::getType() ) {
			case 'form.ticket.create':
				if ( ! Config\General::getService( 'ticket' )['enable'] ) {
					exit( 0 );
				}

				Kernel\View::get( 'form.ticket.create', 'page' );
				break;
			case 'form.file.upload':
				if ( ! Config\General::getService( 'file.upload' )['enable'] ) {
					exit( 0 );
				}

				Kernel\View::get( 'form.file.upload', 'page' );
				break;
			case 'form.file.download':
				if ( ! Config\General::getService( 'file.download' )['enable'] ) {
					exit( 0 );
				}

				Kernel\View::get( 'form.file.download', 'page' );
				break;
			case 'action.ticket.send':
				Ticket\Create::saveForm();
				Ticket\Create::sendMail();
				break;
			case 'action.file.upload':
				File\Upload::uploadFile();
				break;
			default:
				Kernel\View::get( 'home', 'page' );
		}

		return true;
	}

	/**
	 * @throws \Exception
	 */
	public static function runApp() {
		self::setSession();
		self::getPage();
	}
}