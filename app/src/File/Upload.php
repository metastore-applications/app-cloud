<?php

namespace MetaStore\App\Cloud\File;

use MetaStore\App\Kernel;
use MetaStore\App\Cloud\{System, Config};
use PHPMailer\PHPMailer\PHPMailer;


/**
 * Class Upload
 * @package MetaStore\App\Cloud\File
 */
class Upload {

	/**
	 * @throws \Exception
	 */
	public static function checkToken() {
		System::checkToken();
	}

	/**
	 * @throws \Exception
	 */
	public static function checkCaptcha() {
		System::checkCaptcha();
	}

	/**
	 * @throws \Exception
	 */
	public static function checkFormField() {
		System::checkFormField( [ 'userMailTo', 'ticketID' ] );
	}

	/**
	 * @param $meta
	 *
	 * @return mixed
	 */
	public static function getFileInfo( $meta ) {
		$out = Kernel\Storage::fileInfo( 'getFile', $meta );

		return $out;
	}

	/**
	 * @throws \Exception
	 */
	public static function checkFileExt() {
		$valid = Config\Upload::getMime( 'allow' );
		$file  = self::getFileInfo( 'tmp_name' ) ?? self::getFileInfo( 'tmp_name' ) ?: '';

		if ( ! empty( $file ) ) {
			$info = finfo_open( FILEINFO_MIME_TYPE );
			$type = finfo_file( $info, $file );
			finfo_close( $info );
		} else {
			$type = '';
		}

		if ( ! in_array( $type, $valid ) ) {
			throw new \Exception( 'File Ext' );
		}
	}

	/**
	 * @return string
	 */
	public static function setFileName() {
		$rule = 'Any-Latin; NFD; [:Nonspacing Mark:] Remove; NFC; Lower();';
		$out  = Kernel\Translit::get( self::getFileInfo( 'name' ), $rule );

		return $out;
	}

	/**
	 * @return array
	 */
	public static function getFormData() {
		$getTicketID     = Kernel\Request::setParam( 'ticketID' );
		$getUserMailTo   = Kernel\Parser::normalizeData( Kernel\Request::setParam( 'userMailTo' ) );
		$getUserComment  = Kernel\Request::setParam( 'userComment' );
		$getFileSaveTime = Kernel\Request::setParam( 'fileSaveTime' );

		switch ( $getFileSaveTime ) {
			case 'days_03':
				$getFileSaveTime = '3 дня';
				break;
			case 'days_10':
				$getFileSaveTime = '10 дней';
				break;
			default:
				$getFileSaveTime = '';
				break;
		}

		$out = [
			'getTicketID',
			'getUserMailTo',
			'getUserComment',
			'getFileSaveTime',
		];

		return compact( $out );
	}

	/**
	 * @return string
	 */
	public static function mailSubject() {
		$form = self::getFormData();
		$out  = '[CLOUD-' . mb_strtoupper( $form['getTicketID'] ) . '-UPLOAD] Загрузка в облако';

		return $out;
	}

	/**
	 * @return string
	 */
	public static function mailBody() {
		$form = self::getFormData();
		$url  = self::getStorage( 1 );
		$file = self::setFileName();

		$out = '<table>';
		$out .= '<tr><td>Ticket ID:</td><td>' . mb_strtoupper( $form['getTicketID'] ) . '</td></tr>';

		if ( ! empty( $form['getUserComment'] ) ) {
			$out .= '<tr><td>Комментарий:</td><td>' . $form['getUserComment'] . '</td></tr>';
		}

		$out .= '<tr><td>Ссылка:</td><td><strong>' . $url . '/' . $file . '</strong></td></tr>';
		$out .= '<tr><td>Время:</td><td><strong>' . $form['getFileSaveTime'] . '</strong></td></tr>';

		$out .= '</table>';

		return $out;
	}

	/**
	 * @param int $url
	 *
	 * @return string
	 */
	public static function getStorage( $url = 1 ) {
		$root = ( $url ) ? Kernel\Request::getScheme() . Kernel\Route::HTTP_HOST() . '/' : Kernel\Route::DOCUMENT_ROOT();
		$days = Kernel\Request::setParam( 'fileSaveTime' );
		$hash = $_SESSION['_uploadDir'];
		$out  = $root . 'storage' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . $days . DIRECTORY_SEPARATOR . $hash;

		return $out;
	}

	/**
	 * @throws \Exception
	 */
	public static function uploadFile() {
		self::checkToken();
		self::checkCaptcha();
		self::checkFormField();
		self::checkFileExt();

		try {
			$storage = self::getStorage( 0 );
			$file    = self::setFileName();

			mkdir( $storage, 0755, true );
			move_uploaded_file( self::getFileInfo( 'tmp_name' ), $storage . '/' . $file );
		} catch ( \Exception $e ) {
			throw new \Exception( 'Upload File' );
		}
	}

	/**
	 * Mail: send.
	 *
	 * @throws \Exception
	 */
	public static function sendMail() {
		self::checkToken();
		self::checkCaptcha();
		self::checkFormField();

		try {
			$form = self::getFormData();
			$mail = new PHPMailer ( true );
			$mail->setFrom( 'cloud-' . mb_strtolower( $form['getTicketID'] ) . '@web.aoesp.ru' );
			$addresses = Config\Ticket::getMailTo();

			foreach ( $addresses as $address ) {
				$mail->addAddress( $address );
			}

			$mail->addAddress( $form['getUserMailTo'] );
			$mail->isHTML( true );
			$mail->CharSet = 'utf-8';
			$mail->Subject = self::mailSubject();
			$mail->Body    = self::mailBody();
			$mail->send();
		} catch ( \Exception $e ) {
			throw new \Exception( 'Send Mail' );
		}
	}
}