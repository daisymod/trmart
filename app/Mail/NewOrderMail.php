<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $contact;

    public $role;
    public function __construct( $contact,$role)
    {
        $this->contact = $contact;
        $this->role = $role;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        app()->setLocale($this->contact->lang ?? 'ru');

        switch ($this->role){
            case 'user':
                return $this->from('admin@turkiyemart.com', 'Turkiyemart')
                    ->to($this->contact->email, 'Turkiyemart')
                    ->view('mail.UserOrder')
                    ->with([
                        'contact' => $this->contact
                    ]);
            case 'merchant':
                return $this->from('admin@turkiyemart.com', 'Turkiyemart')
                    ->to($this->contact->email, 'Turkiyemart')
                    ->view('mail.MerchantOrder')
                    ->with([
                        'contact' => $this->contact
                    ]);
            case 'admin':
                return $this->from('admin@turkiyemart.com', 'Turkiyemart')
                    ->to($this->contact->email, 'Turkiyemart')
                    ->view('mail.AdminOrder')
                    ->with([
                        'contact' => $this->contact
                    ]);
        }

    }
}
