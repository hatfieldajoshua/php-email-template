<?php
class template{
	
	# where the file is with trailing slash
	var $dir = '/home/user/classes/email/';
	
	# default from email address
	var $from = 'some@where.com';
	
	# did it fail or succeed?
	var $fail = false;
	
	# also configure the $mail->Username and $mail->Password vars
	# in the function "send"
	
	function parse($file, $data){
	
		# if $file it is located in $dir/templates you don't need to use a full path
		
		$check = $dir . $file;
		
		if(file_exists($check)) $file = $check;
		$html = @file_get_contents($file);
		if($html){
			if(is_array($data) && sizeof($data) > 0){
				foreach($data as $k => $v){
					$html = str_replace('{'. $k . '}',$v,$html);
				}
			}
			return $html;
		}
		else $this->fail = 'Error in file_get_contents('. $file .');';
	}
	
	# $to and $from should both be arrays!
	function send($to, $from=false, $subject, $body, $blind=false){
		
		/*
		# One receiver
		
		$to = array(
			'stevejobs@apple.com' => 'Steve Jobs'
		);
		
		# Multiple receivers
		
		$to   = array(
			'stevejobs@apple.com' => 'Steve Jobs',
			'billygates@msft.com' => 'Bill Gates'
		);
		
		# Example of proper $from array
		
		$from = array(
			'some@where.com' => 'Someone'
		);
		
		*/
		
		if(!class_exists('PHPMailer')) require_once($dir . 'phpmailer/class.phpmailer.php');
		
		$mail = new PHPMailer();
		
		# send via SMTP
		$mail->IsSMTP();
		
		# turn on SMTP authentication
		$mail->SMTPAuth = true;
		
		# SMTP username
		$mail->Username = "some@where.com";
		
		# SMTP password
		$mail->Password = "xyourpasswordx";
		
		# Check to see if required values are set!
		if(is_array($to) && sizeof($to) == 0)
		
			$this->fail = '$to not set!';
			
		if($to=='')
		
			$this->fail = '$to not set!';
		
		/*
		
		Checks to see if $from was passed through as a array
		If it is a string then the value will be used for BOTH $mail->From and $mail->FromName
		If it wasn't set at all then we will send using $this->from;
		
		*/
		
		if($from){
			if(is_array($from)){
				foreach($from as $email => $name){
					$mail->From		= $email;
					$mail->FromName = $name;
				}
			}
			else{
				$mail->From		= $from;
				$mail->FromName	= $from;
			}
		}
		else{
			$mail->From		= $this->from;
			$mail->FromName	= $this->from;
		}
		
		/*
		
		Checks to see if $to is an array or not
		Use an array to establish a "to:name" as well as to send to mulitple receivers
		If $to is a string it will use it's value for BOTH the "to:name" and "to:email"
		
		*/
		if(is_array($to)) {
			foreach($to as $email => $name){
				switch($blind):
					case 'bcc':
						$mail->AddBCC($email, $name) ;
					break;
					case 'cc':
						$mail->AddCC($email, $name) ;
					break;
					default:
						$mail->AddAddress($email, $name);
					break;
				endswitch;
			}
		}
		else $mail->AddAddress($to, $to);
		
		if(!$this->fail){
			$mail->IsHTML(true);
			$mail->Subject	= $subject;
			$mail->Body 	= $body;
			
			 # This will send a text body as well as the HTML body
			 # It's a fantastic failsafe, especially for webmail users
			$mail->AltBody	= strip_tags($body);
			
			if(!$mail->Send()) $this->fail = 'Error sending: " . $mail->ErrorInfo';
			
		}
		
		return $this->fail ? false : true;
	}
	
	function destroy(){
		settype(&$this,'null');
	}
}
?>