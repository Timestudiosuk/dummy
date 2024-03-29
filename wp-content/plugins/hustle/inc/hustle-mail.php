<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Hustle_Mail
 *
 * @package Hustle
 */

/**
 * Base class for sending emails.
 *
 * @since 3.0.5
 */
class Hustle_Mail {

	/**
	 * Email recipient
	 * The email address that will receive the mail
	 *
	 * @var string
	 */
	protected $recipient = '';

	/**
	 * Email message
	 *
	 * @var string
	 */
	protected $message = '';

	/**
	 * Email subject
	 *
	 * @var string
	 */
	protected $subject = '';

	/**
	 * Email 'from' address
	 *
	 * @var string
	 */
	protected $sender_email = '';

	/**
	 * Email 'from' name
	 *
	 * @var string
	 */
	protected $sender_name = '';

	/**
	 * Email headers
	 *
	 * @var array
	 */
	protected $headers = array();


	/**
	 * Main constructor
	 *
	 * @since 3.0.5
	 * @param string $recipient The email recipient.
	 * @param string $message The email message.
	 * @param string $subject The email subject.
	 */
	public function __construct( $recipient = '', $message = '', $subject = '' ) {
		$this->set_recipient( $recipient );
		$this->set_message( $message );
		if ( ! empty( $subject ) ) {
			$this->subject = $subject;
		}

		$this->set_sender();
		$this->set_headers();
	}

	/**
	 * Set recipient
	 *
	 * @since 3.0.5
	 * @param string $recipient The email recipient.
	 */
	private function set_recipient( $recipient ) {
		if ( empty( $recipient ) ) {
			return;
		}
		$recipients = array_map( 'trim', explode( ',', $recipient ) );
		if ( empty( $recipients ) ) {
			return;
		}
		$emails = array();
		foreach ( $recipients as $email ) {
			$filtered_email = filter_var( $email, FILTER_VALIDATE_EMAIL );
			if ( $filtered_email ) {
				$emails[] = $filtered_email;
			}
		}
		if ( empty( $emails ) ) {
			return;
		}

		$this->recipient = $emails;
	}

	/**
	 * Set message
	 *
	 * @since 3.0.5
	 * @param string $message The email message.
	 */
	private function set_message( $message ) {
		if ( ! empty( $message ) ) {
			$this->message = $message;
		}
	}

	/**
	 * Set headers.
	 *
	 * @since 3.0.5
	 * @param array $headers The email headers.
	 */
	public function set_headers( $headers = array() ) {
		if ( ! empty( $headers ) ) {
			$this->headers = $headers;

		} else {

			$this->headers   = array(
				'From: ' . $this->sender_name . ' <' . $this->sender_email . '>',
			);
			$this->headers[] = 'Content-Type: text/html; charset=UTF-8';
		}
	}

	/**
	 * Set sender details.
	 *
	 * @since 3.0.5
	 */
	private function set_sender() {
		$general_settings   = Hustle_Settings_Admin::get_general_settings();
		$this->sender_email = $general_settings['sender_email_address'];
		$this->sender_name  = $general_settings['sender_email_name'];
	}

	/**
	 * Clean mail variables
	 *
	 * @since 3.0.5
	 */
	private function clean() {
		$subject       = stripslashes( $this->subject );
		$subject       = wp_strip_all_tags( $subject );
		$this->subject = $subject;

		$message       = stripslashes( $this->message );
		$message       = wpautop( $message );
		$message       = make_clickable( $message );
		$this->message = $message;
	}

	/**
	 * Send mail
	 *
	 * @since 3.0.5
	 * @return bool
	 */
	public function send() {
		$sent = false;
		if ( ! empty( $this->recipient ) && ! empty( $this->subject ) && ! empty( $this->message ) ) {
			$this->clean();
			foreach ( $this->recipient as $to ) {
				$sent = wp_mail( $to, $this->subject, $this->message, $this->headers );
			}
		}
		return $sent;
	}

	/**
	 * Send the actual email.
	 *
	 * @since 3.0.5
	 * @return bool
	 */
	public function process_mail() {
		return $this->send();
	}

	/**
	 * Does the process to submit the unsubscription email.
	 *
	 * @since 3.0.5
	 * @param string $email Email to be unsubscribed.
	 * @param array  $modules_id IDs of the modules to which it will be unsubscribed.
	 * @param string $referer URL referer.
	 * @return boolean
	 */
	public static function handle_unsubscription_user_email( $email, $modules_id, $referer ) {
		if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			Opt_In_Utils::maybe_log( __METHOD__, 'The provided email address is not valid.' );
			return false;
		}

		if ( ! filter_var( $referer, FILTER_VALIDATE_URL ) ) {
			Opt_In_Utils::maybe_log( __METHOD__, 'The provided referer is not valid.' );
			return false;
		}

		$email           = apply_filters( 'hustle_unsubscribe_email_recipient', $email, $modules_id, $referer );
		$unsubscribe_url = self::get_unsubscribe_link( $email, $modules_id, $referer );
		if ( ! $unsubscribe_url ) {
			Opt_In_Utils::maybe_log( __METHOD__, 'There was an error getting the nonce.' );
			return false;
		}
		$email_settings = Hustle_Settings_Admin::get_unsubscribe_email_settings();
		$message        = str_replace( '{hustle_unsubscribe_link}', $unsubscribe_url, $email_settings['email_body'] );
		$message        = apply_filters( 'hustle_unsubscribe_email_message', $message, $unsubscribe_url, $email, $modules_id, $referer );

		$email_handler = new self( $email, $message, $email_settings['email_subject'] );
		$sent          = $email_handler->process_mail();

		return $sent;
	}

	/**
	 * Get Unsubscribe link
	 *
	 * @param string $email Email.
	 * @param array  $modules_id Module IDs unsubscribe from.
	 * @param string $referer Unsubscription base URL.
	 * @return string
	 */
	public static function get_unsubscribe_link( $email, $modules_id, $referer = '' ) {
		if ( ! $referer ) {
			$referer = get_option( 'hustle_unsubscribe_page' );
		}
		if ( ! $referer ) {
			return '';
		}
		$module = new Hustle_Module_Model();
		$nonce  = $module->create_unsubscribe_nonce( $email, $modules_id );
		if ( ! $nonce ) {
			return '';
		}

		$parsed_url      = wp_parse_url( $referer, PHP_URL_QUERY );
		$concatenate     = empty( $parsed_url ) ? '?' : '&';
		$unsubscribe_url = apply_filters(
			'hustle_unsubscribe_email_url',
			$referer . $concatenate . 'token=' . $nonce . '&email=' . rawurlencode( $email ),
			$email,
			$modules_id,
			$referer
		);

		return $unsubscribe_url;
	}

	/**
	 * Static function to send an email.
	 *
	 * @since 3.0.5
	 * @param string $to Email recipient.
	 * @param string $subject Email subject.
	 * @param string $body Email body.
	 * @return bool
	 */
	public static function send_email( $to, $subject, $body ) {
		$email_handler = new self( $to, $body, $subject );
		$sent          = $email_handler->process_mail();

		return $sent;
	}

}