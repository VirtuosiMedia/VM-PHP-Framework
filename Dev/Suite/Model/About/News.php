<?php
/**
 * @author Virtuosi Media Inc.
 * @license: MIT License
 * @description: The model for generating the latest news for VM PHP Framework Suite
 * @requirements: PHP 5.2 or higher
 */
class Suite_Model_About_News extends Vm_Model {
	
	protected $params;
	protected $settings;
	
	/**
	 * 
	 * @param array $params - An associative array of the URL parameters, with the parameter name as the key, it's value as the value
	 * @param array $settings - An associative settings array, with the setting name as the key, it's value as the value
	 */	
	function __construct($params, $settings){
		$this->params = $params;
		$this->settings = $settings;
		$this->compileData();		
	}
	
	protected function compileData(){
		//TODO: Change the URL to the actual feed once the site is up. Until then, enjoy football
		$url = 'http://profootballtalk.nbcsports.com/feed/rss/';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		$feed = curl_exec($ch);
		curl_close($ch);

		$xml = simplexml_load_string($feed);
		$news = array();
		$i = 1;
		
		foreach ($xml->channel->item as $item){
			if ($i <= 5){
				$post = array();
				$post['title'] = $item->title;
				$post['link'] = $item->link;
				$news[] = $post;
				$i++;
			}
		}
		$this->setData('news', $news);
	}
}