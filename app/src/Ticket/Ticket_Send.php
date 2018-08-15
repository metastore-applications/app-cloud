<?php

namespace MetaStore\App\Cloud\Ticket;

use MetaStore\App\Kernel\{Request, Session, Cookie, Parser, View, Hash};
use MetaStore\App\Cloud\Config\Settings;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Class Ticket_Send
 * @package MetaStore\App\Cloud\Ticket
 */
class Ticket_Send {

	/**
	 *
	 */
	public static function destroyToken() {
		Session::destroy( '_metaToken' );
		Session::destroy( '_metaCaptcha' );
	}

	/**
	 * @return array
	 * @throws \Exception
	 */
	public static function getFormData() {
		$getUserFirstName  = Request::setParam( 'userFirstName' );
		$getUserLastName   = Request::setParam( 'userLastName' );
		$getUserMiddleName = Request::setParam( 'userMiddleName' );
		$getUserMail       = Parser::normalizeData( Request::setParam( 'userMailFrom' ) );
		$getUserPhone      = Request::setParam( 'userPhone' );
		$getUserComment    = Request::setParam( 'userComment' );
		$getFileLocation   = Request::setParam( 'fileLocation' );
		$getFileSaveTime   = Request::setParam( 'fileSaveTime' );
		$getHash           = Hash::generator();

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
			'getUserFirstName',
			'getUserLastName',
			'getUserMiddleName',
			'getUserMail',
			'getUserPhone',
			'getUserComment',
			'getFileLocation',
			'getFileSaveTime',
			'getHash',
		];

		return compact( $out );
	}

	/**
	 * @throws \Exception
	 */
	public static function checkToken() {
		if ( ( ! Request::setParam( '_metaToken' ) )
		     || Request::setParam( '_metaToken' ) !== Session::get( '_metaToken' ) ) {
			self::destroyToken();
			throw new \Exception(
				View::get( 'error', 'status' )
			);
		}
	}

	/**
	 * @throws \Exception
	 */
	public static function checkMailAddress() {
		$form = self::getFormData();

		if ( Settings::getAuth( 'allow' ) && ! in_array( $form['getUserMail'], Settings::getAuth( 'allow' ) ) ) {
			self::destroyToken();
			throw new \Exception(
				View::get( 'warning.auth', 'status' )
			);
		}

		if ( Settings::getAuth( 'deny' ) && in_array( $form['getUserMail'], Settings::getAuth( 'deny' ) ) ) {
			self::destroyToken();
			throw new \Exception(
				View::get( 'warning.auth', 'status' )
			);
		}
	}

	/**
	 * @throws \Exception
	 */
	public static function checkFormField() {
		if ( empty( Request::setParam( 'userMailFrom' ) )
		     || empty( Request::setParam( 'fileLocation' ) )
		     || empty( Request::setParam( 'fileDestination' ) )
		     || empty( Request::setParam( 'fileDescription' ) ) ) {
			self::destroyToken();
			throw new \Exception(
				View::get( 'warning.field', 'status' )
			);
		}
	}

	/**
	 * @throws \Exception
	 */
	public static function checkCaptcha() {
		if ( ( ! Request::setParam( '_metaCaptcha' ) )
		     || ( Request::setParam( '_metaCaptcha' ) != Session::get( '_metaCaptcha' )[1] ) ) {
			self::destroyToken();
			throw new \Exception(
				View::get( 'warning.captcha', 'status' )
			);
		}
	}

	/**
	 * @return string
	 * @throws \Exception
	 */
	public static function mailSubject() {
		$form = self::getFormData();

		$out = '[CLOUD-OPEN] Загрузка в облако от: ' . $form['getUserFirstName'] . ' ' . $form['getUserLastName'];

		return $out;
	}

	/**
	 * @return string
	 * @throws \Exception
	 */
	public static function mailBody() {
		$form = self::getFormData();

		$out = '<table>';
		$out .= '<tr><td>ФИО:</td><td>' . $form['getUserLastName'] . ' ' . $form['getUserFirstName'] . ' ' . $form['getUserMiddleName'] . '</td></tr>';
		$out .= '<tr><td>E-mail:</td><td>' . $form['getUserMail'] . '</td></tr>';
		$out .= '<tr><td>Телефон:</td><td>' . $form['getUserPhone'] . '</td></tr>';
		$out .= '<tr><td>Файл:</td><td><code>' . $form['getFileLocation'] . '</code></td></tr>';
		$out .= '<tr><td>Время:</td><td><strong>' . $form['getFileSaveTime'] . '</strong></td></tr>';

		if ( ! empty( $form['getUserComment'] ) ) {
			$out .= '<tr><td>Комментарий:</td><td>' . $form['getUserComment'] . '</td></tr>';
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
		$form = self::getFormData();

		self::checkToken();
		self::checkFormField();
		self::checkCaptcha();
		self::checkMailAddress();

		$mail = new PHPMailer ( true );

		try {
			$mail->setFrom( 'cloud-' . $form['getHash'] . '@web.aoesp.ru' );
			$mail->addAddress( 'esp.cloudbox@gmail.com' );
			$mail->isHTML( true );
			$mail->CharSet = 'utf-8';
			$mail->Subject = self::mailSubject();
			$mail->Body    = self::mailBody();
			$mail->send();
			View::get( 'success', 'status' );
		} catch ( \Exception $e ) {
			View::get( 'error', 'status' );
		}

		self::destroyToken();

		return true;
	}
}