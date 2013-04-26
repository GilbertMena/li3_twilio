<?php

use lithium\core\Libraries;

defined('LI3_TWILIO_PATH') OR define('LI3_TWILIO_PATH', dirname(__DIR__));
defined('LI3_TWILIO_LIB') OR define('LI3_TWILIO_LIB', dirname(__DIR__) . "/libraries/twilio-php/");

require_once(LI3_TWILIO_LIB . "Services/Twilio.php");

?>