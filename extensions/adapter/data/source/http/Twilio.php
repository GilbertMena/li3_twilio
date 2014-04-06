<?php

namespace li3_twilio\extensions\adapter\data\source\http;

/**
 * Lithium Twilio Data Source.
 *
 * @package li3_twilio\extensions\adapter\data\source\http
 * @author Josey Morton
 */
class Twilio extends \lithium\data\source\Http {

	protected $_version = '2010-04-01';
	protected $_client = null;
	protected $_type = null;

	/**
	 * Class dependencies.
	 */
	protected $_classes = array(
		'service' => 'lithium\net\http\Service',
		'entity' => 'lithium\data\entity\Document',
		'set' => 'lithium\data\collection\DocumentSet',
        'schema' => 'lithium\data\DocumentSchema'
	);

	/**
	 * Constructor.
	 *
	 * @param array $config Configuration options.
	 */
	public function __construct(array $config = array()) {
        
		if (!in_array('curl', get_loaded_extensions())) {
			throw new ErrorException("It looks like you do not have curl installed.\n" .
					"Curl is required to make HTTP requests using the twilio-php\n" .
					"library. For install instructions, visit the following page:\n" .
					"http://php.net/manual/en/curl.installation.php"
			);
		}

		$defaults = array(
			'adapter'  => 'Twilio',
			'token'    => null,
			'number'   => null,
			'scheme'   => 'https',
			'auth'     => null,
			'version'  => '1.1',
			'host'     => 'api.twilio.com',
			'port'     => 443,
			'path'     => '/' . $this->_version,
		);

		$config += $defaults;

		parent::__construct($config);

	}

	/**
	 * Data source READ operation.
	 *
	 * @param string $query
	 * @param array $options
	 * @return mixed
	 */
	public function read($query, array $options = array()) {}

	/**
	 * Data Source CREATE operation.
	 *
	 * @param string $query
	 * @param array $options
	 * @return mixed
	 */
	public function create($query, array $options = array()) {

		$params = compact('query', 'options');
		$config = $this->_config;
		extract($query);

		$this->_client = new \Services_Twilio($config['sid'], $config['token']);

		switch ($type) {
			case 'sms':
				$this->_type = 'sms_messages';
				break;
			case 'call':
				$this->_type = 'calls';
				break;
		}
		
		$params['client'] = $this->_client;

		return $this->_filter(__METHOD__, $params, function($self, $params) use ($config) {
			$request = array('type' => 'json');
			$query = $params['query'];
			$options = $params['options'];
			if($params['client']->accounts->get($config['sid'])->status == 'active') return $self;
			return false;
		});

	}

	/**
	 * Data source READ operation.
	 *
	 * @param string $query
	 * @param array $options
	 * @return mixed
	 */
	public function send(array $options = array()) {

		$defaults = array(
			'from' => $this->_config['number']
		);

		$options += $defaults;

		return $this->{$this->_type}($options);

	}

	/**
	 * Builds a sms message to be sent to the provided number
	 * @param  array  $options to, from and sms message content
	 * @return object          Services_Twilio_Rest_SmsMessage Object 
	 */
	private function sms_messages(array $options = array()){
		if(!isset($options['from']) || !$options['from']) throw new \ErrorException("Please provide your twilio number");

		if(!isset($options['to'])) throw new \ErrorException("No receiving (to) number passed to method\n
			Please provide a recipient mobile number.");

		if(!isset($options['message'])) throw new \ErrorException("No message defined\n
			Please provide the message content.");

		
		return $this->_client->account->{$this->_type}->create($options['from'], $options['to'], $options['message']);

	}

	private function calls(array $options = array()){

		if(!isset($options['from']) || !$options['from']) throw new \ErrorException("Please provide your twilio number");

		if(!isset($options['to'])) throw new \ErrorException("No receiving (to) number passed to method\n
			Please provide a recipient phone number.");

		if(!isset($options['connection'])) throw new \ErrorException("No twiML path specified...");

		return $this->_client->account->{$this->_type}->create($options['from'], $options['to'], $options['connection']);

	}


}