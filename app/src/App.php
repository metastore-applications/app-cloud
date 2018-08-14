<?php

namespace MetaStore\App\Cloud;

use MetaStore\App\Kernel;
use MetaStore\App\Cloud\{Ticket\Ticket_Send, File\File_Upload};

/**
 * Class App
 * @package MetaStore\App\Cloud
 */
class App {

	/**
	 * @return string
	 */
	public static function getType() {
		$type = Kernel\Request::getParam( 'type' );
		$out  = Kernel\Parser::normalizeData( $type );

		return $out;
	}

	/**
	 * @return bool|File_Upload|Ticket_Send|mixed
	 * @throws \Exception
	 */
	public static function getPage() {
		switch ( self::getType() ) {
			case 'form.ticket.create':
				$out = Kernel\View::get( 'form.ticket.create', 'page' );
				break;
			case 'form.file.upload':
				$out = Kernel\View::get( 'form.file.upload', 'page' );
				break;
			case 'form.file.download':
				$out = Kernel\View::get( 'form.file.download', 'page' );
				break;
			case 'action.ticket.send':
				$out = new Ticket_Send();
				$out->saveForm();
				$out->sendMail();
				break;
			case 'action.file.upload':
				$out = new File_Upload();
				$out->uploadFile();
				break;
			default:
				return false;
		}

		return $out;
	}

	/**
	 * @return bool|File_Upload|Ticket_Send|mixed
	 * @throws \Exception
	 */
	public static function runApp() {
		$out = self::getPage() ? self::getPage() : Kernel\View::get( 'home', 'page' );

		return $out;
	}
}