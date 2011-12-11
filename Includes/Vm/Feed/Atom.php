<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Creates an Atom 1.0 feed
 * @requires Vm\Xml
 * @namespace Vm\Feed
 */
namespace Vm\Feed;

class Atom {
	
	protected $contributors;
	protected $entries;
	protected $feedAuthors;
	protected $info;
	protected $xml;
		
	function __construct(){
		$this->contributors = array();
		$this->feedAuthors = array();
		$this->entries = array();
	}
	
	/**
	 * @param array $channel - An associative array of feed's meta elements with the element as the key and its value as the value
	 * 		Notes: Both author and contributor can be multi-dimensional arrays: To enter more than one author or contributor, use the following format:
	 * 				$item[author] = array(
	 * 					array('Jim', 'jim@example.com', 'http://www.example.com/jim'),
	 * 					array('Bob', 'bob@example.com', 'http://www.example.com/bob'),
	 * 					array('Sue', 'sue@example.com', 'http://www.example.com/sue')
	 * 				);
	 * 			The category value can be a single value or an array of categories
	 */
	public function setFeedInfo(array $info){
		if ((!isset($info['id']))&&(!isset($info['title']))&&(!isset($info['updated']))){
			throw new Vm_Feed_Exception("A feed must contain 'id', 'title', and 'updated' elements");			
		}
		
		$accepted = array('id', 'title', 'updated', 'link', 'category', 'author', 'contributor', 'generator', 'icon', 'logo', 'rights', 'subtitle');
		
		foreach ($info as $element=>$value){
			if (($element == 'author')&&(!is_array($value))){
				throw new Vm_Feed_Exception("The value of the author key must be an array, containing the author name as the first array item and (optionally) the uri and email as the second and third items.");			
			} else if (($element == 'contributor')&&(!is_array($value))){
				throw new Vm_Feed_Exception("The value of the contributor key must be an array, containing the author name as the first array item and (optionally) the uri and email as the second and third items.");
			} else if (($element == 'link')&&(!is_array($value))){
				throw new Vm_Feed_Exception("The value of the link key must be an array, containing the href (url) as the first array item and (optionally) the rel attribute value as the second item.");				
			} else if (!in_array($element, $accepted)){
				throw new Vm_Feed_Exception("$element is an invalid element");
			} else if ($element == 'author'){
				$this->validateAuthors($value);
			} else if ($element == 'contributor'){
				$this->validateContributors($value);
			}
		}
		$this->info = $info;
	}

	/**
	 * @param array $item - An associative array of item elements with the element as the key and its value as the value
	 * 		Notes: The value of the following elements, 'author', 'contributor', 'content', 'link', and 'source' key must be an array
	 * 			Both author and contributor can be multi-dimensional arrays: To enter more than one author or contributor, use the following format:
	 * 				$item[author] = array(
	 * 					array('Jim', 'jim@example.com', 'http://www.example.com/jim'),
	 * 					array('Bob', 'bob@example.com', 'http://www.example.com/bob'),
	 * 					array('Sue', 'sue@example.com', 'http://www.example.com/sue')
	 * 				);
	 * 			The category value can be a single value or an array of categories
	 */		
	public function addEntry(array $entry){
		if ((!isset($entry['id']))&&(!isset($entry['title']))&&(!isset($entry['updated']))){
			throw new Vm_Feed_Exception("An item entry must contain the following elements: id, title, and updated.");
		}		
		if (($entry['author'])&&(!is_array($entry['author']))){
			throw new Vm_Feed_Exception("The value of the author key must be an array, containing the author name as the first array item and (optionally) the uri and email as the second and third items.");			
		} else if ($entry['author']){
			$this->validateAuthors($entry['author']);
		}
		if (($entry['contributor'])&&(!is_array($entry['contributor']))){
			throw new Vm_Feed_Exception("The value of the contributor key must be an array, containing the author name as the first array item and (optionally) the uri and email as the second and third items.");
		} else if ($entry['contributor']){
			$this->validateContributors($entry['contributor']);
		}
		if (($entry['content'])&&(!is_array($entry['content']))){
			throw new Vm_Feed_Exception("The value of the content key must be an array, containing the content as the first array item, the content type as the second item, and (optionally) the src as the third item.");
		}
		if (($entry['link'])&&(!is_array($entry['link']))){
			throw new Vm_Feed_Exception("The value of the contributor key must be an array, containing the href as the first array item and (optionally) the rel as the second item.");
		}				
		if (($entry['source'])&&(!is_array($entry['source']))){
			throw new Vm_Feed_Exception("The value of the source key must be an array, containing the following elements in order: id, title, updated, and rights.");
		}		

		$accepted = array('id', 'title', 'updated', 'link', 'author', 'content', 'link', 'summary', 'category', 'contributor', 'published', 'source', 'rights');
		
		foreach ($entry as $element=>$value){
			if (!in_array($element, $accepted)){
				throw new Vm_Feed_Exception("$element is an invalid element");
			}
		}		
		$this->entries[] = $entry;
	}

	/**
	 * @param array $authors - The multidimensional authors array
	 */
	protected function validateAuthors(array $authors){
		if (is_array($authors[0])){
			foreach ($authors as $author){
				if (!is_array($author)){
					throw new Vm_Feed_Exception("The value of the author key must be an array, containing the author name as the first array item and (optionally) the uri and email as the second and third items.");
				}
			}
		}
	}

	/**
	 * @param array $contributors - The multidimensional contributors array
	 */
	protected function validateContributors(array $contributors){
		if (is_array($contributors[0])){
			foreach ($contributors as $contributor){
				if (!is_array($contributor)){
					throw new Vm_Feed_Exception("The value of the contributor key must be an array, containing the author name as the first array item and (optionally) the uri and email as the second and third items.");
				}
			}
		}
	}	

	/**
	 * @description Renders the XML for an author or contributor
	 * @param string $element - The type of element to return: 'author' or 'contributor'
	 * @param array $content - An array containing the following information: (name, email, uri)
	 * @return strubg - The rendered person XML
	 */
	protected function renderPerson($element, $content){
		$name = $this->xml->name($content[0]);
		$email = ($content[1]) ? $this->xml->email($content[1]) : NULL;
		$uri = ($content[2]) ? $this->xml->uri($content[2]) : NULL; 
		return $this->xml->$element($name.$email.$uri);			
	}

	/**
	 * @return string - The rendered meta information for the feed
	 */	
	protected function renderInfo(){
		$info = '';
		foreach ($this->info as $element=>$content){
			switch($element){
				case 'updated':
					$dateTime = new \DateTime($content);			
					$info .= $this->xml->updated($dateTime->format('Y-m-d\TH:i:sP'));
					break;
				case 'author':
					if (is_array($content[0])){
						foreach($content as $author=>$data){
							$info .= $this->renderPerson('author', $data);					
						}					
					} else {
						$info .= $this->renderPerson('author', $content);
					}
					break;
				case 'contributor':
					if (is_array($content[0])){
						foreach($content as $contributor=>$data){
							$info .= $this->renderPerson('contributor', $data);					
						}					
					} else {
						$info .= $this->renderPerson('contributor', $content);
					}
					break;
				case 'link':
					$attributes = ($content[1]) ? array('href'=>$content[0], 'rel'=>$content[1]) : array('href'=>$content[0]);
					$info .= $this->xml->link('', $attributes);
					break;
				default:				
					$info .= $this->xml->$element($content);
			}
		}
		return $info;		
	}

	/**
	 * @return string - The rendered entries for the feed
	 */		
	protected function renderEntries(){
		$entries = '';
		foreach ($this->entries as $entry){
			$entryContainer = '';
			foreach ($entry as $element=>$content){
				switch($element){
					case 'updated':
						$dateTime = new \DateTime($content);			
						$entryContainer .= $this->xml->updated($dateTime->format('Y-m-d\TH:i:sP'));						
						break;
					case 'author':
						if (is_array($content[0])){
							foreach($content as $author=>$data){
								$entryContainer .= $this->renderPerson('author', $data);					
							}					
						} else {
							$entryContainer .= $this->renderPerson('author', $content);
						}
						break;
					case 'link':
						$attributes = ($content[1]) ? array('href'=>$content[0], 'rel'=>$content[1]) : array('href'=>$content[0]);
						$entryContainer .= $this->xml->link('', $attributes);
						break;
					case 'category':
						if(is_array($element)){
							foreach ($content as $category){
								$entryContainer .= $this->xml->category(NULL, array('term'=>$category));
							}
						} else {
							$entryContainer .= $this->xml->category(NULL, array('term'=>$content));
						}
						break;
					case 'contributor':
						if (is_array($content[0])){
							foreach($content as $contributor=>$data){
								$entryContainer .= $this->renderPerson('contributor', $data);					
							}					
						} else {
							$entryContainer .= $this->renderPerson('contributor', $content);
						}
						break;
					case 'published':
						$dateTime = new \DateTime($content);			
						$entryContainer .= $this->xml->updated($dateTime->format('Y-m-d\TH:i:sP'));
						break;
					case 'source':
						$id = (!empty($content[0])) ? $this->xml->id($content[0]) : NULL;
						$title = (!empty($content[1])) ? $this->xml->title($content[1]) : NULL;
						$updated = (!empty($content[2])) ? $this->xml->updated(date(DATE_ATOM, $content[2])) : NULL;
						$rights = (!empty($content[3])) ? $this->xml->rights($content[3]) : NULL;
						$entryContainer .= $this->xml->source($id.$title.$updated.$rights);
						break;
					case 'rights':
						$entryContainer .= $this->xml->rights($content, array('type'=>'html'));
						break;
					case 'content':
						$attributes = array();
						if ($content[1]){
							$attributes['type'] = $content[1];
						}
						if ($content[2]){
							$attributes['src'] = $content[2];
						}
						if (strtolower($content[1]) == 'xhtml'){
							$entryContainer .= $this->xml->content($this->xml->div($content[0]), $attributes);
						} else {						
							$entryContainer .= $this->xml->content($content[0], $attributes);
						}
						break;					
					default:				
						$entryContainer .= $this->xml->$element($content);																									
				}
			}
			$entries .= $this->xml->entry($entryContainer);
		}
		return $entries;		
	}
	
	/**
	 * @return string - The rendered Atom feed
	 */
	public function render(){
		$this->xml = new \Vm\Xml();
		$info = $this->renderInfo();
		$entries = $this->renderEntries();
		return '<?xml version="1.0" encoding="utf-8"?>'.$this->xml->feed($info.$entries, array('xmlns'=>'http://www.w3.org/2005/Atom'));
	}
}
?>