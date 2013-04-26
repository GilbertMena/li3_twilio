<?php

namespace li3_twilio\models;

use lithium\data\Connections;

class Sms extends Base {

	public static function init($type = 'sms'){
		return parent::init($type);
	}

}