<?php

namespace MetaStore\App\Cloud\File;

use MetaStore\App\Kernel;
use MetaStore\App\Cloud\{System, Config};


/**
 * Class Upload
 * @package MetaStore\App\Cloud\File
 */
class Upload {

	public static function destroyToken() {
		System::destroyToken();
	}

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
	 * @param $userMail
	 * @param $userComment
	 * @param $fileLocation
	 * @param $fileSaveTime
	 *
	 * @return string
	 */
	public static function mailBody( $userMail, $userComment, $fileLocation, $fileSaveTime ) {
		$body = '<table>';
		$body .= '<tr><td>E-mail:</td><td>' . $userMail . '</td></tr>';
		$body .= '<tr><td>Файл:</td><td><code>' . $fileLocation . '</code></td></tr>';
		$body .= '<tr><td>Время:</td><td><strong>' . $fileSaveTime . '</strong></td></tr>';
		$body .= '<tr><td>Комментарий:</td><td>' . $userComment . '</td></tr>';
		$body .= '</table>';

		return $body;
	}

	/**
	 * @return string
	 * @throws \Exception
	 */
	public static function getStorage() {
		$root = Kernel\Route::DOCUMENT_ROOT();
		$days = Kernel\Request::setParam( 'fileSaveTime' );
		$hash = Kernel\Hash::generator( 'sha1' );
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
			$storage = self::getStorage();
			$file    = self::setFileName();

			mkdir( $storage, 0755, true );
			move_uploaded_file( self::getFileInfo( 'tmp_name' ), $storage . '/' . $file );
		} catch ( \Exception $e ) {
			throw new \Exception( 'Upload File' );
		}

		self::destroyToken();
	}
}