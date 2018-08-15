<?php

namespace MetaStore\App\Cloud\Ticket;

use MetaStore\App\Kernel;
use MetaStore\App\Cloud\Config;
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
		$getUserFirstName   = Kernel\Request::setParam( 'userFirstName' );
		$getUserLastName    = Kernel\Request::setParam( 'userLastName' );
		$getUserMiddleName  = Kernel\Request::setParam( 'userMiddleName' );
		$getUserMail        = Kernel\Parser::normalizeData( Kernel\Request::setParam( 'userMailFrom' ) );
		$getUserPhone       = Kernel\Request::setParam( 'userPhone' );
		$getUserComment     = Kernel\Request::setParam( 'userComment' );
		$getFileLocation    = Kernel\Request::setParam( 'fileLocation' );
		$getFileDestination = Kernel\Request::setParam( 'fileDestination' );
		$getFileDescription = Kernel\Request::setParam( 'fileDescription' );
		$getFileSaveTime    = Kernel\Request::setParam( 'fileSaveTime' );

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
			'getFileDestination',
			'getFileDescription',
			'getFileSaveTime',
		];

		return compact( $out );
	}

	/**
	 *
	 */
	public static function checkToken() {
		if ( ( ! Kernel\Request::setParam( '_metaToken' ) )
		     || Kernel\Request::setParam( '_metaToken' ) !== $_SESSION['_metaToken'] ) {
			self::destroyToken();
			Kernel\View::get( 'error', 'status' );
			exit( 0 );
		}
	}

	/**
	 * @throws \Exception
	 */
	public static function checkMailAddress() {
		$form = self::getFormData();

		if ( Config\Ticket::getMailFrom( 'allow' ) && ! in_array( $form['getUserMail'], Config\Ticket::getMailFrom( 'allow' ) ) ) {
			self::destroyToken();
			Kernel\View::get( 'warning.auth', 'status' );
			exit( 0 );
		}

		if ( Config\Ticket::getMailFrom( 'deny' ) && in_array( $form['getUserMail'], Config\Ticket::getMailFrom( 'deny' ) ) ) {
			self::destroyToken();
			Kernel\View::get( 'warning.auth', 'status' );
			exit( 0 );
		}
	}

	/**
	 *
	 */
	public static function checkFormField() {
		if ( empty( Kernel\Request::setParam( 'userMailFrom' ) )
		     || empty( Kernel\Request::setParam( 'fileLocation' ) )
		     || empty( Kernel\Request::setParam( 'fileDestination' ) )
		     || empty( Kernel\Request::setParam( 'fileDescription' ) ) ) {
			self::destroyToken();
			Kernel\View::get( 'warning.field', 'status' );
			exit( 0 );
		}
	}

	/**
	 *
	 */
	public static function checkCaptcha() {
		if ( ( ! Kernel\Request::setParam( '_metaCaptcha' ) )
		     || ( Kernel\Request::setParam( '_metaCaptcha' ) != $_SESSION['_metaCaptcha'][1] ) ) {
			self::destroyToken();
			Kernel\View::get( 'warning.captcha', 'status' );
			exit( 0 );
		}
	}

	/**
	 * @return string
	 * @throws \Exception
	 */
	public static function mailSubject() {
		$form = self::getFormData();

		$out = '[CLOUD-' . mb_strtoupper( $_SESSION['_ticketID'] ) . '-OPEN] Загрузка в облако от: ' . $form['getUserFirstName'] . ' ' . $form['getUserLastName'];

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
		$out .= '<tr><td>Место назначения:</td><td>' . $form['getFileDestination'] . '</td></tr>';
		$out .= '<tr><td>Описание:</td><td>' . $form['getFileDescription'] . '</td></tr>';
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
		Kernel\Cookie::set( 'userFirstName', 'userFirstName', 'form' );
		Kernel\Cookie::set( 'userLastName', 'userLastName', 'form' );
		Kernel\Cookie::set( 'userMiddleName', 'userMiddleName', 'form' );
		Kernel\Cookie::set( 'userMailFrom', 'userMailFrom', 'form' );
		Kernel\Cookie::set( 'userPhone', 'userPhone', 'form' );

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
			$mail->setFrom( 'cloud-' . $_SESSION['_ticketID'] . '@web.aoesp.ru' );
			$addresses = Config\Ticket::getMailTo();

			foreach ($addresses as $address) {
				$mail->addAddress( $address );
			}

			$mail->addAddress( $form['getUserMail'] );
			$mail->isHTML( true );
			$mail->CharSet = 'utf-8';
			$mail->Subject = self::mailSubject();
			$mail->Body    = self::mailBody();
			$mail->send();
			Kernel\View::get( 'success', 'status' );
		} catch ( \Exception $e ) {
			Kernel\View::get( 'error', 'status' );
		}

		self::destroyToken();

		return true;
	}
}