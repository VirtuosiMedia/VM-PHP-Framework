<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A generic abstract base class for rewriting URLs, meant to be extended by rewrite rule classes
 * @namespace Vm
 */
namespace Vm;

abstract class Rewrite {
	
	protected $url;
	
	/**
	 * @param string $url - The URL to be stored
	 */
	function __construct($url){
		$this->url = $url;
	}
	
	/**
	 * @description Gets the altered URL
	 * @return string - The URL
	 */
	public function getUrl(){
		return $this->url;
	}
}