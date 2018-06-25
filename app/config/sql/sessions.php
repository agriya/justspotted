<?php
/**
 * Just Spotted
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    justspotted
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2018 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
/*
 *
 * Using the Schema command line utility
 * cake schema run create Sessions
 *
 */
class SessionsSchema extends CakeSchema {

	var $name = 'Sessions';

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	var $cake_sessions = array(
			'id' => array('type'=>'string', 'null' => false, 'key' => 'primary'),
			'data' => array('type'=>'text', 'null' => true, 'default' => NULL),
			'expires' => array('type'=>'integer', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);

}
?>