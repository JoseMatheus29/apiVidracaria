<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PasswordResetEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $recoveryCode;

    /**
     * Cria uma nova instância da classe.
     *
     * @param  string  $recoveryCode
     * @return void
     */
    public function __construct($recoveryCode)
    {
        $this->recoveryCode = $recoveryCode;
    }

    /**
     * Constrói a mensagem de email.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Recuperação de Senha')->view('emails.password-reset');
    }
}
