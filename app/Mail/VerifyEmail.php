<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
	use Queueable, SerializesModels;
	private $email, $code;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct($code)
	{
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
			->view('mails.verification')
			->with([
				'code' => $this->code,
				'site' =>  config('app.name')
			]);
	}
}
