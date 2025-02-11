<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactUsMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public $contact)
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('no-reply@turkiyemart.com', 'Turkiyemart')
            ->to('no-reply@turkiyemart.com', 'Turkiyemart')
            ->view('mail.sendAdminMessage')
            ->with([
                'contact' => $this->contact
            ]);
    }
}
