# CakePHP plugin for Clickatell API

It's useful if you want to build multi protocol system which can send either mail and sms messages from one place.

### Usage

This plugin uses [CakeEmail class](http://book.cakephp.org/2.0/en/core-utility-libraries/email.html), and works almost the same. There are some specifics.

Basic example:

```php
App::uses('CakeEmail', 'Network/Email');
$Email = new CakeEmail('clickatell');
$Email->to('447711223344');
$Email->send('SMS Message');
```

More advanced example:

```php
App::uses('CakeEmail', 'Network/Email');
$Email = new CakeEmail('clickatell');

$Email->template('default', 'default');
$Email->emailFormat('text');
$Email->viewVars(array('name' => 'Your Name'));
$Email->to(array('447758899441', '3598877775566'));

$email->send();
```

The syntax of all parameters is the same as the default [CakeEmail class](http://book.cakephp.org/2.0/en/core-utility-libraries/email.html):

### Installation

You can clone the plugin into your project (or download the archive and place it in the app/Plugin folder:

```
cd path/to/app/Plugin
git clone git@github.com:nchankov/CakePHP-Clickatell-Transport-Plugin.git Clickatell
```

Bootstrap the plugin in app/Config/bootstrap.php:

```php
CakePlugin::load('Clickatell');
//Or use
CakePlugin::loadAll();
```

Create the file app/Config/email.php with the class EmailConfig.

```php
<?php
class EmailConfig {
	public $clickatell = array(
    	'debug'=>true, //if set to true will print the api call instead of sending it
    	'from'=>'foo@email.com', //It's not used since this should be done from Clickatell site, but without this CakeEmail fail to send.
    	'emailPattern'=>'/[0-9]{10,}/', //Used because the CakeEmail validates email addresses. This way it can accept numeric value which usually the phones are. Customize on request
    	'transport'=>'Clickatell.Clickatell',
    	'username'=>'{your-username-in-clickatell}',
    	'password'=>'{your-password-in-clickatell}',
    	'api_id'=>'{your-api-id-in-clickatell}',
    	'baseurl'=>'http://api.clickatell.com',
    	//'concatenate'=>10 //how many messages to concatenate if messages are longer than 160 symbols. By default it's 4 this way you can increase them (careful, because it's charged by 160 char message)
    );
}
```

### Requirements

CakePHP 2.4+ (or at least CakeEmail class from 2.4 version). If you are working on CakePHP <2.4 copy the CakeEmail class in your project.

### License

Licensed under The MIT License

Developed by [Nik Chankov](http://nik.chankov.net)
