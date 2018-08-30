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
				System::checkPrivateIP( 'ticket' );

				if ( ! Config\General::getService( 'ticket' )['enable'] ) {
					exit( 0 );
				}

				Kernel\View::get( 'form.ticket.create', 'page' );
				break;
			case 'form.file.upload':
				System::checkPrivateIP( 'file.upload' );
				System::checkAuth( 'service.file.upload', 'upload' );

				if ( ! Config\General::getService( 'file.upload' )['enable'] ) {
					exit( 0 );
				}

				Kernel\View::get( 'form.file.upload', 'page' );
				break;
			case 'form.file.download':
				System::checkPrivateIP( 'file.download' );
				System::checkAuth( 'service.file.download', 'download' );

				if ( ! Config\General::getService( 'file.download' )['enable'] ) {
					exit( 0 );
				}

				Kernel\View::get( 'form.file.download', 'page' );
				break;
			case 'action.ticket.send':
				Ticket\Create::saveForm();
				Ticket\Create::sendMail();
				System::destroyToken();
				break;
			case 'action.file.upload':
				File\Upload::uploadFile();
				File\Upload::sendMail();
				System::destroyToken();
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
		System::createToken();
		self::getPage();
	}
}