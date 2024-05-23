<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Email_JobAdmin extends Mailable
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
            ->subject('Hay una Nueva Solicitud de Empleo')
            ->view('mails.empleo_admin')            
            ->with(
            [
                'testVarOne' => '1',
                'testVarTwo' => '2',
            ]) ->attach($this->data->file->getRealPath(),[
                'as'=>$this->data->file->getClientOriginalName()
            ]);
    }
}
