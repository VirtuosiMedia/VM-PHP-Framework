<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description An Active Record class for users
 * @requires A database connection script that uses a PDO extension
 * @namespace Ar
 * @uses PDO
 */
namespace Ar;

class Users extends \Vm\Db\ActiveRecord {
	
	protected $relationships = array(
		'hasOne'=>array('usersettings', 'usersessions'),
		'hasMany'=>array(),
		'belongsTo'=>array()
	);
	protected $validations = array();
	
	function __construct(\PDO $db){
		parent::__construct($db, 'users', $this->validations, $this->relationships);		
	}	
}