<?php

namespace MetaStore\App\Cloud;

use MetaStore\App\Kernel\{Hash, Random, Token, Request, Parser, View};
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

		if ( ! isset( $_SESSION['_metaToken'] ) ) {
			$_SESSION['_metaToken'] = Token::generator();
		}

		if ( ! isset( $_SESSION['_ticketID'] ) ) {
			$_SESSION['_ticketID'] = Hash::generator();
		}

		if ( ! isset( $_SESSION['_metaCaptcha'] ) ) {
			$_SESSION['_metaCaptcha'] = [
				Random::number( 1000000000, 9999999999 ),
				Random::number( 10000, 99999 )
			];
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