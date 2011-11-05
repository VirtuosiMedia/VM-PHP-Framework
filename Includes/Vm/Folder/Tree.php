<?php
/**
* @author Virtuosi Media
* @license: MIT License
* Description: Extends VM_Folder by creating HTML trees to represent file structure
* Dependencies: Vm_Folder and Vm_Xml
* Requirements: PHP 5.2 or higher, Vm_Xml
*/
class Vm_Folder_Tree extends Vm_Folder {
	
	protected $xml;

	/**
	 * Recursively builds a series of HTML nested lists representing the file structure 
	 * @param array $resource - The contents of the base directory
	 * @return string - The nested lists
	 */
	protected function buildTree($resource){
		if (is_array($resource)){
			$listItem = '';
			foreach ($resource as $folder=>$file){
				if (is_array($file)){
					$ul = $this->xml->ul($this->buildTree($file));
					$listItem .= $this->xml->li($folder.$ul, array('class'=>'folder'));					
				} else {
					if (preg_match('#\.#', $file)){
						$listItem .= $this->xml->li($file, array('class'=>'file'));
					} else {
						$listItem .= $this->xml->li($file, array('class'=>'folder'));
					} 
				}
			}
		} else {
			$listItem = $this->xml->li($resource, array('class'=>'file'));
		}
		return $listItem;
	}
	
	/**
	 * Creates an HTML nested list representing the file structure
	 * @param boolean $recursive - optional - Whether or not a recursive scan should be used
	 * @return string - The nested list of folders and files
	 */
	public function getHtmlTree($recursive = FALSE){
		$this->xml = new Vm_Xml();
		$resources = $this->getContents($recursive);
		return $this->xml->ul($this->buildTree($resources), array('id'=>'fileTree'));
	}
}
?>