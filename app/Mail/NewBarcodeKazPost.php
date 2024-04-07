<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewBarcodeKazPost extends Mailable
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
        app()->setLocale($this->contact->lang);

        return $this->from('admin@turkiyemart.com', 'Turkiyemart')
            ->to($this->contact->email, 'Turkiyemart')
            ->subject('Kazpost - postcode')
            ->view('mail.newBarcodeMail')
            ->with([
                'contact' => $this->contact,
            ]);
    }
}
