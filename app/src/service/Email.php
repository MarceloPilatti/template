<?php
namespace App\service;
class Email {
	public static function sendEmail($emailTo, $name, $subject, $body){
		try{
			$mail = new \PHPMailer();
			$mail->IsSMTP();
			$mail->Host = HOST;
			$mail->Port = PORT;
			$mail->SMTPAutoTLS = TLS;
			$mail->Username = EMAIL;
			$mail->Password = PASSWORD;
			$mail->From = EMAIL;
			$mail->FromName = NAME;
			$mail->SMTPAuth = true;
			$mail->AddAddress($emailTo, $name);
			$mail->IsHTML(true);
			$mail->CharSet = 'utf-8';
			$mail->Subject = $subject;
			$mail->Body = $body;
			#$mail->AddAttachment("c:/temp/documento.pdf", "documento.pdf");
			$sended = $mail->Send();
			$mail->ClearAllRecipients();
			$mail->ClearAttachments();
			if ($sended) {
				return true;
			} else {
				return false;
			}
		}catch (\Exception $e){
			return false;
		}
	}
}