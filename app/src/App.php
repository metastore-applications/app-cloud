<?php

namespace MetaStore\App\Cloud;

use MetaStore\App\Kernel\{Session, Token, Request, Parser, View};
use MetaStore\App\Cloud\{Ticket\Ticket_Send, File\File_Upload};

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

		if ( ! Session::get( '_metaToken' ) ) {
			Session::set( '_metaToken', Token::generator() );
		}

		if ( ! Session::get( '_metaCaptcha' ) ) {
			Session::set( '_metaCaptcha', [ random_int( 1000000000, 9999999999 ), random_int( 10000, 99999 ) ] );
		}
	}

	/**
	 * @return string
	 */
	public static function getType() {
		$get = Request::getParam( 'get' );
		$out = Parser::normalizeData( $get );

		return $out;
	}

	/**
	 * @return bool
	 * @throws \Exception
	 */
	public static function getPage() {
		switch ( self::getType() ) {
			case 'form.ticket.create':
				View::get( 'form.ticket.create', 'page' );
				break;
			case 'form.file.upload':
				View::get( 'form.file.upload', 'page' );
				break;
			case 'form.file.download':
				View::get( 'form.file.download', 'page' );
				break;
			case 'action.ticket.send':
				Ticket_Send::saveForm();
				Ticket_Send::sendMail();
				break;
			case 'action.file.upload':
				break;
			default:
				View::get( 'home', 'page' );
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