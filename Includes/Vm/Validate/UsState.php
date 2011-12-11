<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description: A validator for US state abbreviations - Evaluates TRUE if an empty string is passed. 
 * @note The state abbreviation must be capitalized
 * @extends Vm\Validate\Regex
 * @namespace Vm\Validate
 */
namespace Vm\Validate;

class UsState extends \Vm\Validate\Regex {

	/** 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$error = ($error) ? $error : 'Please enter a valid 2-letter US state abbreviation';
		parent::__construct($input, $error, '/^(?:A[KLRZ]|C[AOT]|D[CE]|FL|GA|HI|I[ADLN]|K[SY]|LA|M[ADEINOST]|N[CDEHJMVY]|O[HKR]|PA|RI|S[CD]|T[NX]|UT|V[AT]|W[AIVY])*$/');
	}
}