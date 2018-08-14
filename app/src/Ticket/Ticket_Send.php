<?php

namespace MetaStore\App\Cloud\Ticket;

use MetaStore\App\Kernel\{Request, Session, Cookie, Parser, View, Hash};
use MetaStore\App\Cloud\Config\Mail;
use PHPMailer\PHPMailer\{PHPMailer, Exception};

/**
 * Class Ticket_Send
 * @package MetaStore\App\Cloud\Ticket
 */
class Ticket_Send {

	/**
	 *
	 */
	public static function destroyTokens() {
		Session::destroy( '_metaToken' );
		Session::destroy( '_metaCaptcha' );
	}

	/**
	 * @param $userFirstName
	 * @param $userLastName
	 * @param $userMiddleName
	 * @param $userMail
	 * @param $userPhone
	 * @param $userComment
	 * @param $fileLocation
	 * @param $fileSaveTime
	 * @param $hash
	 *
	 * @return string
	 */
	public static function mailBody( $userFirstName, $userLastName, $userMiddleName, $userMail, $userPhone, $userComment, $fileLocation, $fileSaveTime, $hash ) {
		$out = '<table>';
		$out .= '<tr><td>ФИО:</td><td>' . $userLastName . ' ' . $userFirstName . ' ' . $userMiddleName . '</td></tr>';
		$out .= '<tr><td>E-mail:</td><td>' . $userMail . '</td></tr>';
		$out .= '<tr><td>Телефон:</td><td>' . $userPhone . '</td></tr>';
		$out .= '<tr><td>Файл:</td><td><code>' . $fileLocation . '</code></td></tr>';
		$out .= '<tr><td>Время:</td><td><strong>' . $fileSaveTime . '</strong></td></tr>';

		if ( ! empty( $userComment ) ) {
			$out .= '<tr><td>Комментарий:</td><td>' . $userComment . '</td></tr>';
		}

		$out .= '</table>';

		return $out;
	}

	/**
	 * Form: save fields.
	 */
	public static function saveForm() {
		Cookie::set( 'userFirstName', 'userFirstName', 'form' );
		Cookie::set( 'userLastName', 'userLastName', 'form' );
		Cookie::set( 'userMiddleName', 'userMiddleName', 'form' );
		Cookie::set( 'userMailFrom', 'userMailFrom', 'form' );
		Cookie::set( 'userPhone', 'userPhone', 'form' );

		return true;
	}

	/**
	 * Mail: send.
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public static function sendMail() {
		if ( ( ! Request::setParam( '_metaToken' ) )
		     || Request::setParam( '_metaToken' ) !== Session::get( '_metaToken' ) ) {
			self::destroyTokens();
			throw new \Exception( View::get( 'error', 'status' ) );
		}

		if ( empty( Request::setParam( 'userMailFrom' ) )
		     || empty( Request::setParam( 'fileLocation' ) )
		     || empty( Request::setParam( 'fileDestination' ) )
		     || empty( Request::setParam( 'fileDescription' ) ) ) {
			self::destroyTokens();
			throw new \Exception( View::get( 'warning.field', 'status' ) );
		}

		if ( ( ! Request::setParam( '_metaCaptcha' ) )
		     || ( Request::setParam( '_metaCaptcha' ) != Session::get( '_metaCaptcha' )[1] ) ) {
			self::destroyTokens();
			throw new \Exception( View::get( 'warning.captcha', 'status' ) );
		}

		$getUserMail = Parser::normalizeData( Request::setParam( 'userMailFrom' ) );

		/*if ( ! in_array( $getUserMail, Mail::getAuth( 'allow' ) ) ) {
			unset( $_SESSION['_metaToken'], $_SESSION['_metaCaptcha'] );

			throw new \Exception( View::get( 'warning.auth', 'status' ) );
		} else if ( in_array( $getUserMail, Mail::getAuth( 'deny' ) ) ) {
			unset( $_SESSION['_metaToken'], $_SESSION['_metaCaptcha'] );

			throw new \Exception( View::get( 'warning.auth', 'status' ) );
		}*/

		$getUserFirstName  = Request::setParam( 'userFirstName' );
		$getUserLastName   = Request::setParam( 'userLastName' );
		$getUserMiddleName = Request::setParam( 'userMiddleName' );
		$getUserPhone      = Request::setParam( 'userPhone' );
		$getUserComment    = Request::setParam( 'userComment' );
		$getFileLocation   = Request::setParam( 'fileLocation' );
		$getFileSaveTime   = Request::setParam( 'fileSaveTime' );
		$getHash           = Hash::generator();

		$mail = new PHPMailer ( true );

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

		try {
			$mail->setFrom( 'cloud-' . $getHash . '@web.aoesp.ru' );
			$mail->addAddress( 'esp.cloudbox@gmail.com' );
			//$mail->addAddress( $getUserMail );
			$mail->isHTML( true );
			$mail->CharSet = 'utf-8';
			$mail->Subject = '[CLOUD-OPEN] Загрузка в облако от: ' . $getUserFirstName . ' ' . $getUserLastName;
			$mail->Body    = self::mailBody( $getUserFirstName, $getUserLastName, $getUserMiddleName, $getUserMail, $getUserPhone, $getUserComment, $getFileLocation, $getFileSaveTime, $getHash );
			$mail->send();
			View::get( 'success', 'status' );
		} catch ( \Exception $e ) {
			View::get( 'error', 'status' );
		}

		Session::destroy( '_metaToken' );
		Session::destroy( '_metaCaptcha' );

		return true;
	}
}