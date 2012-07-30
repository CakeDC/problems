# CakePHP Problems Plugin #

Version 1.1 for cake 2.x

This plugin allows to attach problem reports to any model record on your app. Problem reports
consist of a configurable type and a problem description or cause and can be submitted by logged in users
only once per model record.

Problem reports can be listed and managed in the admin section. The admin_index action will list a summary of
all problem reports by model type and record. Such reports can be accepted and unaccepted by the administrator.
The acceptance of reports could trigger actions configurable in this plugin.

## Installation ##

### 1. Install plugin directory ###

Place the problems folder into any of your plugin directories for your app (for example app/plugins or cake/plugins)

### 2. Create database tables ###

Create database tables using either the schema shell or the migrations plugin:

	cake schema create --plugin Problems --name problems
	cake migration run all --plugin Problems

### 3. Attach `Reportable` behavior to models ###

Attach the Reportable behavior to your models via the `$actsAs` variable or dynamically using the BehaviorsCollection object methods:

	public $actsAs = array('Problems.Reportable' => array('userClass' => 'User'))
	// or
	$this->Behaviors->attach('Problems.Reportable', array('userClass' => 'User'))

### 4. Register the model in Configure settings ###

Register your model in the Configure storage. This can be done in bootstrap.php or loading custom config files using Configure::load()

	Configure::write('Problems.Models', array(
		'AModel' => 'AModel',
		'APluginModel' => 'MyPlugin.ApluginModel'
	));

## Usage ##

### 1. Include the helper ###

Add the Problems helper to you controller:

	public $helpers = array('Problems.Problems');

### 2. Use the helper ###

Use the helper in your views to generate links to the problem report form

	<?php echo $this->Problem->link('ModelName', 'recordID', 'Link Title'); ?>

This link will redirect the user to a form where he can report the problem

## Configuration Options ##
The Reportable behavior has some configuration option to adapt to your app needs. The configuration array accepts the following keys

1. modelClass: Set this to the real Model class you are attaching the behavior to. For example if you are attaching the reportable behavior to the class Comment in plugin Comments, set this config option to "Comments.Comment". By default the property $name of the model is used
2. userClass: The cake class name to use as the User model associated to the Problem report. By default it is "Users.User"
3. problemClass: If you need to extend the Problem model or override it with your own implementation set this key to the model you want to use
4. foreignKey: the field in your table that serves as reference for the primary key of the models it is attached to. (Used for own implementations of Problem model)
5. problemTypes: Set this variable to an array of problems types you want to use for your model.

For example:

	array(
		'stolen' => 'Stolen Material',
		'explicit_lyrics' => 'Explicit Lyrics'
	);

## Customization ##

### Behavior callbacks ###

Additionally the behavior provides three callbacks to implement in your model:
1. beforeReport: Implement this function in your model to make decission to allow report about the problem before a data about reported recorded
2. afterReport: Implement this function in your model to take specific actions after a model record is reported
3. afterAcceptReport: Implement this function in your model to take specific actions after a problem report is marked as accepted for a model record, for example delete the entry in the database

The admin interface links to reported model records to ease the task of accepting the problems. This link is generated using the method `reportedObjectUrl`.
You can customize this links implementing this method in your model:

	public function reportedObjectUrl($id) {
		return array('controller' => 'my_controller', 'action' => 'view', $id);
	}

### Flash message improvement ###

You can add options to Session::setFlash message in case of success or error of action.
To do this, add 'Problems.flashTypes' to your config file.
For exemple, to use an element 'error.ctp' for wraping error flash messages  and use 'Problems' as flash key add to your bootstrap:

	Configure::write('Problems.flashTypes', array(
		'error' => array('error', array(), 'Problems'),
	));

Allowed key are 'success' and 'error'.
For parameters, see  the documentation of Session::setFlash.

## Requirements ##

* PHP version: PHP 5.2+
* CakePHP version: Cakephp 1.3 Stable
* [CakeDC Utils plugin](http://github.com/CakeDC/utils)

## Support ##

For support and feature request, please visit the [Problems Plugin Support Site](http://cakedc.lighthouseapp.com/projects/59614-problems-plugin/).

For more information about our Professional CakePHP Services please visit the [Cake Development Corporation website](http://cakedc.com).

## Branch strategy ##

The master branch holds the STABLE latest version of the plugin. 
Develop branch is UNSTABLE and used to test new features before releasing them. 

Previous maintenance versions are named after the CakePHP compatible version, for example, branch 1.3 is the maintenance version compatible with CakePHP 1.3.
All versions are updated with security patches.

## Contributing to this Plugin ##

Please feel free to contribute to the plugin with new issues, requests, unit tests and code fixes or new features. If you want to contribute some code, create a feature branch from develop, and send us your pull request. Unit tests for new features and issues detected are mandatory to keep quality high. 

## License ##

Copyright 2009-2011, [Cake Development Corporation](http://cakedc.com)

Licensed under [The MIT License](http://www.opensource.org/licenses/mit-license.php)<br/>
Redistributions of files must retain the above copyright notice.

## Copyright ###

Copyright 2009-2011<br/>
[Cake Development Corporation](http://cakedc.com)<br/>
1785 E. Sahara Avenue, Suite 490-423<br/>
Las Vegas, Nevada 89104<br/>
http://cakedc.com<br/>