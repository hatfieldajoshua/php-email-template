<?php
include("email-template.class.php");
$template = new template;
?>

<!doctype html>
<html>
<head>
    <title>Example of a simple email template</title>
</head>
<body>
<?php
# set $to
$to = array(
	'some@where.com' => 'somewhere',
	'some@who.com' 	 => 'somewho'
);

# set $from
$from = array(
	'some@where.com' => 'some'
);

# set $subject
$subject = 'Hello to you sir';

# set data to be parsed
$data = array(
	'to_whom' 	=> 'abc xyz',
	'when'		=> 'June 14, 2009',
	'time'		=> '6:00PM EST',
	'the_code'  => '8675309'
);
			  
# parse the template
$body = $template->parse('example-template.htm',$data);

# send the email
$sent = $template->send($to, $from, $subject, $body);

if($sent) echo 'it sent!';

else echo $template->fail;

/*

So you're only coding 8+ lines depending on how much data needs parsed...

$to = array(
	'some@where.com' => 'somewhere',
	'some@who.com' 	 => 'somewho'
);

$from = array(
	'some@where.com' => 'some'
);

$subject = 'Hello to you sir';

$data = array(
	'to_whom' 	=> 'abc xyz',
	'when'		=> 'June 14, 2009',
	'time'		=> '6:00PM EST',
	'the_code'  => '8675309'
);

$body = $template->parse('example-template.htm',$data);

$template->send($to, $from, $subject, $body);

*/
?>
</body>
</html>
