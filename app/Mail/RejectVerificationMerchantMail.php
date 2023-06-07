<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RejectVerificationMerchantMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $contact;
    public $request;

    public function __construct( $contact,$request)
    {
        $this->contact = $contact;
        $this->request = $request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        app()->setLocale($this->contact->lang);

        return $this->from('no-reply@turkiyemart.com', 'Turkiyemart')
            ->to($this->contact->email, 'Turkiyemart')
            ->subject('reject verification merchant')
            ->view('mail.RejectVerificationMerchantMail')
            ->with([
                'contact' => $this->contact,
                'request' => $this->request
            ]);
    }
}
