<?php

namespace li3_twilio\extensions\helper;

class Twiml extends \lithium\template\Helper {

	protected $service;

	public function __construct(){
		$this->service = new \Services_Twilio_Twiml();
	}

	public function init(){
		return $this->service;
	}

	public function __call($method, $options){
		return $this->service->{$method}($options);
	}

}