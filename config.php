<?php
if (!defined('INPHP')) {die("This file cannot be accessed directly.");}

// SERVER configs

$config = array();

/*
* PROD
*/

$config["prod"]["status"] = "prod";
$config["prod"]["server_name"] = "stm-own.france24.com";
$config["prod"]["basehref"] = "http://".$config["prod"]["server_name"]."/midterm";
$config["prod"]["email"] = "vincent.roux@rfi.fr";
$config["prod"]["email_sender"] = $config["prod"]["server_name"];
$config["prod"]["admin_email"] = $config["prod"]["email"];

$config["prod"]['smtp_host'] = 'localhost';
$config["prod"]['smtp_port'] = 25;
$config["prod"]['smtp_username'] = '';
$config["prod"]['smtp_password'] = '';

/*
* ADMIN
*/

$config["admin"]["status"] = "admin";
$config["admin"]["server_name"] = "data.owni.fr";
$config["admin"]["basehref"] = "http://data.owni.fr/pourfrance24";
$config["admin"]["email"] = "vincent.roux@rfi.fr";
$config["admin"]["email_sender"] = $config["admin"]["server_name"];
$config["admin"]["admin_email"] = $config["admin"]["email"];

$config["admin"]['smtp_host'] = 'localhost';
$config["admin"]['smtp_port'] = 25;
$config["admin"]['smtp_username'] = '';
$config["admin"]['smtp_password'] = '';

/*
* DEV
*/

$config["dev"]["status"] = "dev";
$config["dev"]["server_name"] = "owniapps.dev";
$config["dev"]["basehref"] = "http://owniapps.dev/midterm";
$config["dev"]["email"] = "ja@jeromealexandre.com";
$config["dev"]["email_sender"] = $config["dev"]["server_name"];
$config["dev"]["admin_email"] = $config["dev"]["email"];

// SMTP
$config["dev"]['smtp_host'] = 'smtp.free.fr';
$config["dev"]['smtp_port'] = 587;
$config["dev"]['smtp_username'] = '';
$config["dev"]['smtp_password'] = '';

// Where are we?
if (!defined('CONFIG_STATUS')) {
	foreach ($config as $status => $conf) {
		$http_host = ($_SERVER["HTTP_HOST"])?$_SERVER["HTTP_HOST"]:$_SERVER['SERVER_NAME'];
		if (stristr($http_host,$conf["server_name"])) {
			define('CONFIG_STATUS', $status);
			break;
		}
	}
}
$config = $config[CONFIG_STATUS];

?>