<?php

namespace MetaStore\App\Cloud\Ticket;

use MetaStore\App\Kernel\{Request, Cookie, Parser, View};
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
		unset ( $_SESSION['_metaToken'], $_SESSION['_metaCaptcha'], $_SESSION['_ticketID'] );
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
		];

		return compact( $out );
	}

	/**
	 *
	 */
	public static function checkToken() {
		if ( ( ! Request::setParam( '_metaToken' ) )
		     || Request::setParam( '_metaToken' ) !== $_SESSION['_metaToken'] ) {
			self::destroyToken();
			View::get( 'error', 'status' );
			exit( 0 );
		}
	}

	/**
	 * @throws \Exception
	 */
	public static function checkMailAddress() {
		$form = self::getFormData();

		if ( Settings::getAuth( 'allow' ) && ! in_array( $form['getUserMail'], Settings::getAuth( 'allow' ) ) ) {
			self::destroyToken();
			View::get( 'warning.auth', 'status' );
			exit( 0 );
		}

		if ( Settings::getAuth( 'deny' ) && in_array( $form['getUserMail'], Settings::getAuth( 'deny' ) ) ) {
			self::destroyToken();
			View::get( 'warning.auth', 'status' );
			exit( 0 );
		}
	}

	/**
	 *
	 */
	public static function checkFormField() {
		if ( empty( Request::setParam( 'userMailFrom' ) )
		     || empty( Request::setParam( 'fileLocation' ) )
		     || empty( Request::setParam( 'fileDestination' ) )
		     || empty( Request::setParam( 'fileDescription' ) ) ) {
			self::destroyToken();
			View::get( 'warning.field', 'status' );
			exit( 0 );
		}
	}

	/**
	 *
	 */
	public static function checkCaptcha() {
		if ( ( ! Request::setParam( '_metaCaptcha' ) )
		     || ( Request::setParam( '_metaCaptcha' ) != $_SESSION['_metaCaptcha'][1] ) ) {
			self::destroyToken();
			View::get( 'warning.captcha', 'status' );
			exit( 0 );
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

		$out .= '<tr><td>Ticket ID:</td><td>' . $_SESSION['_ticketID'] . '</td></tr>';
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
		self::checkToken();
		self::checkFormField();
		self::checkCaptcha();
		self::checkMailAddress();

		$mail = new PHPMailer ( true );

		try {
			$mail->setFrom( 'cloud-' . $_SESSION['_ticketID'] . '@web.aoesp.ru' );
			$mail->addAddress( 'esp.cloudbox@gmail.com' );
			$mail->addAddress( 'dunaev_y@aoesp.ru' );
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