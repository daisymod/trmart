<?php

namespace App\Mail;

use App\Exports\ImportDataCatalogResultExport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as BaseExcel;

class ResultImportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $contact;
    public $data;
    public $locale;
    public $success;
    public $error;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($contact,$data,$locale,$success,$error)
    {
        $this->contact = $contact;
        $this->data = $data;
        $this->locale = $locale;
        $this->success = $success;
        $this->error = $error;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        app()->setLocale($this->contact->lang);
        $filename = "Import-product.csv";

        $attachment = Excel::raw(
            new ImportDataCatalogResultExport($this->data, $this->locale),
            BaseExcel::CSV
        );

        return $this->from('no-reply@turkiyemart.com', 'Turkiyemart')
            ->to($this->contact->email, 'Turkiyemart')
            ->subject("Result import file")
            ->view('mail.import',['success' => $this->success,'error' => $this->error])
            ->attachData($attachment, $filename);

    }

}
