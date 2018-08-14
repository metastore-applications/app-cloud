<?php

namespace MetaStore\App\Cloud\Ticket;

use MetaStore\App\Kernel;
use MetaStore\App\Cloud\Config\Mail;
use PHPMailer\PHPMailer\{PHPMailer, Exception};

/**
 * Class Ticket_Send
 * @package MetaStore\App\Cloud\Ticket
 */
class Ticket_Send {

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
		Kernel\Cookie::set( 'form', 'userFirstName', 'userFirstName' );
		Kernel\Cookie::set( 'form', 'userLastName', 'userLastName' );
		Kernel\Cookie::set( 'form', 'userMiddleName', 'userMiddleName' );
		Kernel\Cookie::set( 'form', 'userMailFrom', 'userMailFrom' );
		Kernel\Cookie::set( 'form', 'userPhone', 'userPhone' );

		return true;
	}

	/**
	 * Mail: send.
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public static function sendMail() {
		if ( ! isset( $_POST['_metaToken'] )
		     || $_POST['_metaToken'] !== $_SESSION['_metaToken'] ) {
			unset( $_SESSION['_metaToken'], $_SESSION['_metaCaptcha'] );
			throw new \Exception( Kernel\View::get( 'error', 'status' ) );
		}

		if ( empty( $_POST['userMailFrom'] )
		     || empty( $_POST['fileLocation'] )
		     || empty( $_POST['fileDestination'] )
		     || empty( $_POST['fileDescription'] ) ) {
			unset( $_SESSION['_metaToken'], $_SESSION['_metaCaptcha'] );
			throw new \Exception( Kernel\View::get( 'warning.field', 'status' ) );
		}

		if ( ( ! isset( $_POST['_metaCaptcha'] ) || $_POST['_metaCaptcha'] != $_SESSION['_metaCaptcha'][1] ) ) {
			unset( $_SESSION['_metaToken'], $_SESSION['_metaCaptcha'] );
			throw new \Exception( Kernel\View::get( 'warning.captcha', 'status' ) );
		}

		$getUserMail = Kernel\Parser::normalizeData( Kernel\Parser::clearData( $_POST['userMailFrom'] ) );

		if ( ! in_array( $getUserMail, Mail::getAuth( 'allow' ) ) ) {
			unset( $_SESSION['_metaToken'], $_SESSION['_metaCaptcha'] );

			throw new \Exception( Kernel\View::get( 'warning.auth', 'status' ) );
		} else if ( in_array( $getUserMail, Mail::getAuth( 'deny' ) ) ) {
			unset( $_SESSION['_metaToken'], $_SESSION['_metaCaptcha'] );

			throw new \Exception( Kernel\View::get( 'warning.auth', 'status' ) );
		}

		$getUserFirstName  = Kernel\Parser::clearData( $_POST['userFirstName'] );
		$getUserLastName   = Kernel\Parser::clearData( $_POST['userLastName'] );
		$getUserMiddleName = Kernel\Parser::clearData( $_POST['userMiddleName'] );
		$getUserPhone      = Kernel\Parser::clearData( $_POST['userPhone'] );
		$getUserComment    = Kernel\Parser::clearData( $_POST['userComment'] );
		$getFileLocation   = Kernel\Parser::clearData( $_POST['fileLocation'] );
		$getFileSaveTime   = Kernel\Parser::clearData( $_POST['fileSaveTime'] );
		$getHash           = Kernel\Hash::generator();

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
			Kernel\View::get( 'success', 'status' );
		} catch ( \Exception $e ) {
			Kernel\View::get( 'error', 'status' );
		}

		unset( $_SESSION['_metaToken'], $_SESSION['_metaCaptcha'] );

		return true;
	}
}