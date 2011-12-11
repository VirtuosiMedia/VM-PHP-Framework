<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A generic class for performing operations with a URL
 * @namespace Vm
 */
namespace Vm;

class Url {
	
	/**
	 * @description Rewrites a URL according the passed in rule class if URL rewriting is enabled
	 * @param string $ruleClass - The name of the Rewrite rule class to use
	 * @param string $url - The complete URL
	 * @param boolean $enabled - optional - TRUE if URL rewriting is enabled, FALSE otherwise. Defaults FALSE.
	 * @return string - The url
	 */
	public function rewrite($ruleClass, $url, $enabled = FALSE){
		if ($enabled) {
			$newUrl = new $ruleClass($url);
			return $newUrl->getUrl();
		} else {
			return $url;
		}
	}
	
	/**
	* @description Redirects to the specified URL, regardless if headers have been sent or Javascript has been enabled
	* @attribution stevenwebster http://ca3.php.net/manual/en/function.header.php#83448
	* @param string $url - The url to redirect the user to
	*/
	public function redirect($url){
		if (!headers_sent()){	//If headers not sent yet... then do php redirect
			header('Location: '.$url); 
			exit();
		} else {	//If headers are sent... do javascript redirect... if javascript disabled, do html redirect.
			echo '<script type="text/javascript">';
			echo 'window.location.href="'.$url.'";';
			echo '</script>';
			echo '<noscript>';
			echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
			echo '</noscript>'; 
			exit();
		}
	}
	
	/**
	 * @description Gets the value of a parameter from a URL string
	 * @param string $url - The entire URL string
	 * @param string $paramName - The name of the parameter for which a value should be returned
	 * @return mixed - The value of the parameter if it exists, FALSE otherwise
	 */
	public function getParamValue($url, $paramName){
		$url = preg_split('#(\?|&amp;|&)#', $url);
		foreach ($url as $urlSegment){
			$value = explode('=', $urlSegment);
			if (strtolower($value[0]) == strtolower($paramName)){
				return $value[1];
			}
		}
		return FALSE;
	}
}