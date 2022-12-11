<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RecoveryPassword extends Mailable
{
	use Queueable, SerializesModels;
	private $email, $code;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct($email, $code)
	{
		$this->email = $email;
		$this->code = $code;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{
		return $this->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
			->view('mails.recovery_password')
			->with([
				'link' => env('FRONT_URL') . '/auth/recovery/reset-password?email=' . $this->email . '&code=' . $this->code,
				'site' =>  config('app.name')
			]);
	}
}
