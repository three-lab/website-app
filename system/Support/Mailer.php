<?php

namespace System\Support;

use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
    private PHPMailer $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();

        $this->mail->Host = config('smtp.host');
        $this->mail->SMTPAuth = true;
        $this->mail->Username = config('smtp.username');
        $this->mail->Password = config('smtp.password');
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mail->Port = config('smtp.port');

        $this->mail->setFrom(config('smtp.from_mail'), config('app.name'));
    }

    public function send(string|array $address, string $subject, string $content): bool
    {
        if(is_string($address)) $this->mail->addAddress($address);
        if(is_array($address))
            foreach($address as $addr) $this->mail->addAddress($addr);

        $this->mail->isHTML(true);
        $this->mail->Subject = $subject;
        $this->mail->Body = $content;

        return $this->mail->send();
    }
}
