<?php

namespace li3_twilio\models;

use lithium\data\Connections;

class Base extends \lithium\data\Model {

	protected $_meta = array('connection' => 'twilio');

	protected static $_connection;
	protected static $_service;

	protected static $_self;

	public static function init($type){
		$self = __CLASS__;
		$self = new $self();
		static::$_self = $self;
		if($type !== 'twiml'){
			static::$_connection = $connection = Connections::get('twilio');
			static::$_service = $service = $connection->create(array('type' => $type));
		} else {
			static::$_service = $service = new \Services_Twilio_Twiml();
		}
		return $service;
	}

}