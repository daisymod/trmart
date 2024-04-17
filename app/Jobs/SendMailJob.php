<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PHPMailer\PHPMailer\PHPMailer;

class SendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email, $title, $body;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $title, $body)
    {
        $this->email = $email;
        $this->title = $title;
        $this->body = $body;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //$body = view('cart_order', compact("cart", 'address', 'delivery', 'paymethod', 'delivery_price', 'order_price'))->render();

        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        $mail->setFrom("info@" . $_SERVER["HTTP_HOST"]);
        $mail->addAddress($this->email);

        $mail->isHTML(true);
        $mail->Subject = $this->title;
        $mail->Body = $this->body;
        $mail->AltBody = self::stripHtmlTags($this->body);
        $mail->send();
    }

    protected static function stripHtmlTags($str)
    {
        $str = preg_replace('/(<|>)\1{2}/is', '', $str);
        $str = preg_replace(
            array( // Remove invisible content
                '@<head[^>]*?>.*?</head>@siu',
                '@<style[^>]*?>.*?</style>@siu',
                '@<script[^>]*?.*?</script>@siu',
                '@<noscript[^>]*?.*?</noscript>@siu',
            ), "", //replace above with nothing
            $str);
        $str = self::replaceWhitespace($str);
        $str = strip_tags($str);
        return html_entity_decode($str, ENT_NOQUOTES, 'utf-8');
    }

    //function strip_html_tags ENDS
    //To replace all types of whitespace with a single space
    protected static function replaceWhitespace($str)
    {
        $result = $str;
        foreach (array(
                     "  ", " \t", " \r", " \n",
                     "\t\t", "\t ", "\t\r", "\t\n",
                     "\r\r", "\r ", "\r\t", "\r\n",
                     "\n\n", "\n ", "\n\t", "\n\r",
                 ) as $replacement) {
            $result = str_replace($replacement, $replacement[0], $result);
        }
        return $str !== $result ? self::replaceWhitespace($result) : $result;
    }
}
