<?php
define ('INPHP', '1');
require_once ("load.php");

$comment = trim($_POST["comment"]);
$email = trim($_POST["email"]);
$name = trim($_POST["name"]);

$output = new StdClass;
$output->status = "200 OK";
$output->error = false;
$output->message = "Message sent";

if (!empty($comment) AND !empty($email) AND !empty($name)) {
    $mailbody = 'IP: '.$_SERVER["REMOTE_ADDR"]."\n"
    .'name: '.name."\n"
    .'lien: '.$lien."\n"
    .'message: '."\n"
    .$comment;

    $headers = "From: \"".DOC_TITLE."\" <{$config["admin_email"]}>\r\nX-originating-IP: {$_SERVER["REMOTE_ADDR"]}\r\nContent-Type: text/plain;";
    if (!smtpmail( $config["admin_email"] ,  DOC_TITLE  , $mailbody, $headers )) {
        $output->message = "Sorry! There was an error sending your contribution.";
        $output->error = true;
    }
}
else {
    $output->error = true;
    $output->message = "Some fields are missing.";
}

echo (json_encode($output));
?>