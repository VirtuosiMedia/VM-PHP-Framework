<?php 
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* @description: Tests for a timeout of unit test.
*/

declare(ticks=1);

class Tests_Test_Timer {

	private static $startTime;
	private static $timeLimit;
	
	/**
	 * @param float $timeLimit - The time limit 
	 */
	public static function start($timeLimit){
		self::$timeLimit = (float) $timeLimit;
		self::$startTime = microtime(TRUE);
		register_tick_function(array('Tests_Test_Timer', 'checkTime'));
	}
	
	/**
	 * Unregisters the timer function
	 */
	public static function end(){
		unregister_tick_function(array('Tests_Test_Timer', 'checkTime'));
	}

	
	public static function checkTime(){
		if ((microtime(TRUE) - self::$startTime) > self::$timeLimit){
			throw new Exception("This test exceeded the alloted time limit of ".self::$timeLimit." seconds and was terminated.");
		}	
	}	
}

?>