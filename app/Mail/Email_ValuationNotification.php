<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Email_ValuationNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $data;

    public function __construct( $data )
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('contacto@abcars.com')
            //cambiar vista
            ->subject('Nueva cita de valuación')
            ->view('mails.valuador')            
            ->with(
                [
                    'testVarOne' => '1',
                    'testVarTwo' => '2',
                ]);
    }
}
