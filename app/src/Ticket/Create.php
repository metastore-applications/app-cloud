<?php

namespace MetaStore\App\Cloud\Ticket;

use MetaStore\App\Cloud\System;
use MetaStore\App\Kernel;
use MetaStore\App\Cloud\Config;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Class Create
 * @package MetaStore\App\Cloud\Ticket
 */
class Create {

	/**
	 * @return array
	 * @throws \Exception
	 */
	public static function getFormData() {
		$getUserFirstName   = Kernel\Request::setParam( 'userFirstName' );
		$getUserLastName    = Kernel\Request::setParam( 'userLastName' );
		$getUserMiddleName  = Kernel\Request::setParam( 'userMiddleName' );
		$getUserMailFrom    = Kernel\Parser::normalizeData( Kernel\Request::setParam( 'userMailFrom' ) );
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
			'getUserMailFrom',
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
	 * @throws \Exception
	 */
	public static function checkToken() {
		System::checkToken();
	}

	/**
	 * @throws \Exception
	 */
	public static function checkMailAddress() {
		$form = self::getFormData();

		if ( Config\Ticket::getMailFrom( 'allow' )
		     && ! in_array( $form['getUserMailFrom'], Config\Ticket::getMailFrom( 'allow' ) ) ) {
			throw new \Exception( 'Mail Deny' );
		}

		if ( Config\Ticket::getMailFrom( 'deny' )
		     && in_array( $form['getUserMailFrom'], Config\Ticket::getMailFrom( 'deny' ) ) ) {
			throw new \Exception( 'Mail Deny' );
		}
	}

	/**
	 * @throws \Exception
	 */
	public static function checkFormField() {
		System::checkFormField( [ 'userMailFrom', 'fileLocation', 'fileDestination', 'fileDescription' ] );
	}

	/**
	 * @throws \Exception
	 */
	public static function checkCaptcha() {
		System::checkCaptcha();
	}

	/**
	 * @return string
	 * @throws \Exception
	 */
	public static function mailSubject() {
		$form = self::getFormData();
		$out  = '[CLOUD-' . mb_strtoupper( $_SESSION['_ticketID'] ) . '-OPEN] Загрузка в облако от: ' . $form['getUserFirstName'] . ' ' . $form['getUserLastName'];

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
		$out .= '<tr><td>E-mail:</td><td>' . $form['getUserMailFrom'] . '</td></tr>';
		$out .= '<tr><td>Телефон:</td><td>' . $form['getUserPhone'] . '</td></tr>';
		$out .= '<tr><td>Файл:</td><td><code>' . $form['getFileLocation'] . '</code></td></tr>';
		$out .= '<tr><td>Место назначения:</td><td>' . $form['getFileDestination'] . '</td></tr>';
		$out .= '<tr><td>Описание:</td><td>' . $form['getFileDescription'] . '</td></tr>';
		$out .= '<tr><td>Время:</td><td><strong>' . $form['getFileSaveTime'] . '</strong></td></tr>';

		if ( ! empty( $form['getUserComment'] ) ) {
			$out .= '<tr><td>Комментарий:</td><td>' . $form['getUserComment'] . '</td></tr>';
		}

		$out .= '<tr><td>Ticket ID:</td><td>' . mb_strtoupper( $_SESSION['_ticketID'] ) . '</td></tr>';
		$out .= '</table>';

		return $out;
	}

	/**
	 * Form: save fields.
	 */
	public static function saveForm() {
		Kernel\Cookie::set( 'form', 'ticketUserFirstName', 'userFirstName' );
		Kernel\Cookie::set( 'form', 'ticketUserLastName', 'userLastName' );
		Kernel\Cookie::set( 'form', 'ticketUserMiddleName', 'userMiddleName' );
		Kernel\Cookie::set( 'form', 'ticketUserMailFrom', 'userMailFrom' );
		Kernel\Cookie::set( 'form', 'ticketUserPhone', 'userPhone' );

		return true;
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
		self::checkMailAddress();

		try {
			$form = self::getFormData();
			$mail = new PHPMailer ( true );
			$mail->setFrom( 'cloud-' . $_SESSION['_ticketID'] . '@web.aoesp.ru' );
			$addresses = Config\Ticket::getMailTo();

			foreach ( $addresses as $address ) {
				$mail->addBCC( $address );
			}

			$mail->addAddress( $form['getUserMailFrom'] );
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