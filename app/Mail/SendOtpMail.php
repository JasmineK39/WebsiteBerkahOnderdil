<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable
{
    use Queueable, SerializesModels;


    public $otp; // <-- agar bisa digunakan di email blade

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function build()
    {

        return $this->subject('Kode Verifikasi Akun Anda')
                    ->view('emails.send-otp'); // <-- arahkan ke file blade email
    }
}
