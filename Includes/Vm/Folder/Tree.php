<?php
/**
 * @author Virtuosi Media
 * @license: MIT License
 * @description Extends VM\Folder by creating HTML trees to represent file structure
 * @requires Vm\Xml
 * @extends Vm\Folder
 * @namespace Vm\Folder
 */
namespace Vm\Folder;

class Tree extends \Vm\Folder {
	
	protected $xml;

	/**
	 * @description Recursively builds a series of HTML nested lists representing the file structure 
	 * @param array $resource - The contents of the base directory
	 * @return string - The nested lists
	 */
	protected function buildTree($resource){
		if (is_array($resource)){
			$listItems = array();
			foreach ($resource as $folder=>$file){
				if (is_array($file)){
					$ul = $this->xml->ul($this->buildTree($file));
					$listItems[] = $this->xml->li($folder.$ul, array('class'=>'folder'));					
				} else {
					if (preg_match('#\.#', $file)){
						$listItems[] = $this->xml->li($file, array('class'=>'file'));
					} else {
						$listItems[] = $this->xml->li($file, array('class'=>'folder'));
					} 
				}
			}
		} else {
			$listItems[] = $this->xml->li($resource, array('class'=>'file'));
		}
		return implode("\n", $listItems);
	}
	
	/**
	 * @description Creates an HTML nested list representing the file structure
	 * @param boolean $recursive - optional - Whether or not a recursive scan should be used
	 * @return string - The nested list of folders and files
	 */
	public function getHtmlTree($recursive = FALSE){
		$this->xml = new \Vm\Xml();
		$resources = $this->getContents($recursive);
		return $this->xml->ul($this->buildTree($resources), array('id'=>'fileTree'));
	}
}