<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminNewOrder extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $contact;

    public function __construct( $contact)
    {
        $this->contact = $contact;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        app()->setLocale($this->contact->lang ?? 'ru');
        return $this->from('no-reply@turkiyemart.com', 'Turkiyemart')
            ->to($this->contact->email, 'Turkiyemart')
            ->view('mail.AdminOrder')
            ->with([
                'contact' => $this->contact
            ]);
    }
}
