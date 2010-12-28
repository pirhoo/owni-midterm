<?php
if (!defined("INPHP")) die ("Not here");

define ("INC_DIR", "includes/");
define ("APP_NAME", "Dépenses de campagne");

//// SMTP MAIL
require_once ("config.php");
require_once (INC_DIR . "smtp.php");

define("BASE_HREF", $config["basehref"]);
define("DOC_URL"   , "http://www.rfi.fr/ameriques/20101028-depenses-campagne-candidats-americains");
define("DOC_TITLE" , "[application] ". APP_NAME);
define("DOC_TWUSER", "rfi");
define('THEME_DIR', '');
?>
