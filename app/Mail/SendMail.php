<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $html;
 
    
    public function __construct($html)
    {
        $this->html = $html;
    }

    
    public function build()
    {
        return $this->from('example@example.com')->view('mail');
    }
}