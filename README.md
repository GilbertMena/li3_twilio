li3_twilio
==========

Lithium data source to allow for interation with twilio rest api.

# Configuration

Add to your `app\config\bootstrap\libraries.php` file:

  Libraries::add('li3_twilio');

Add to your `app\config\bootstrap\connections.php`

```
  Connections::add('twilio',  array(
            'sid'   => 'TwilioSID', //replace with your keys
            'number'=> 'FromNumber',
            'token' => 'TwilioToken',
            'adapter' => 'Twilio',
            'type'     => 'http',
        )
  );
```

# Usage

Add wherever you wish to use the library:

  use li3_twilio\models\Sms;



##### Simple Usage for SMS

```
 $data = array('to'=>'3855555555','message'=>'Testing');
 $twilio = Sms::init();
 $twilio->send($data);
```
