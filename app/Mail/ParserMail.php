<?php

namespace App\Mail;

use App\Exports\ImportDataCatalogResultExport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as BaseExcel;

class ParserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $contact;
    public $data;
    public $locale;
    public $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($contact,$data,$locale,$url)
    {
        $this->contact = $contact;
        $this->data = $data;
        $this->locale = $locale;
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        app()->setLocale($this->contact->lang);
        $filename = "Import-product.xlsx";

        $attachment = Excel::raw(
            new ImportDataCatalogResultExport($this->data, $this->locale),
            \Maatwebsite\Excel\Excel::XLSX
        );

        return $this->from('no-reply@turkiyemart.com', 'Turkiyemart')
            ->to($this->contact->email, 'Turkiyemart')
            ->subject("Result Parser import file-".$this->url)
            ->view('mail.parser')
            ->attachData($attachment, $filename);

    }

}
