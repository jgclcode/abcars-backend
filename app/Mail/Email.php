<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Email extends Mailable
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
                    ->subject('Cuenta de ABCars.mx')
                    ->view('mails.registro')
                    //->text('mails.registro_plano')
                    ->with(
                      [
                            'testVarOne' => '1',
                            'testVarTwo' => '2',
                      ]);
                      /*
                      ->attach(public_path('/img').'/demo.jpeg', [
                              'as' => 'demo.jpg',
                              'mime' => 'image/jpeg',
                      ]);
                      */
    }
}
