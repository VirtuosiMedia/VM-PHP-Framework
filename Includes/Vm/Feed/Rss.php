<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Creates an RSS 2.0 feed
* Requirements: PHP 5.2 or higher, Vm_Xml
*/
class Vm_Feed_Rss {
	
	protected $channel;
	protected $image;
	protected $items;
	
	function __construct(){
		$this->items = array();
	}
	
	/**
	 * @param array $channel - An associative array of channel meta elements (excluding the channel image) with the element as the key and its value as the value
	 */
	public function setFeedInfo(array $channel){
		if ((!isset($channel['title']))&&(!isset($channel['link']))&&(!isset($channel['description']))){
			throw new Vm_Feed_Exception("A feed must contain a title, link, and a description");			
		}
		
		$accepted = array(
			'title', 'link', 'description', 'language', 'copyright', 'managingEditor', 'webMaster', 'pubDate', 'lastBuildDate', 'category', 'generator', 
			'docs', 'cloud', 'ttl', 'rating', 'skipHours', 'skipDays'
		);
		
		foreach ($channel as $element=>$value){
			if ($element == 'image'){
				throw new Vm_Feed_Exception("Image properties must be specified by using the setChannelImage method");
			} else if (($element == 'skipHours')&&(!in_array($element, range(0, 23)))){
				throw new Vm_Feed_Exception("The skipHours value must be an integer between 0 and 23");
			} else if (($element == 'skipDays')&&(!in_array(explode(',', $element), array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday')))){
				throw new Vm_Feed_Exception("The skipDays value must be a comma separated string of the full days of the week: Sunday,Monday,etc.");				
			} else if (!in_array($element, $accepted)){
				throw new Vm_Feed_Exception("$element is an invalid element");
			}
		}
		$this->channel = $channel;
	}

	/**
	 * @param array $image - An associative array of image elements with the element as the key and its value as the value
	 */	
	public function setChannelImage(array $image){
		if ((!isset($image['url']))&&(!isset($image['title']))&&(!isset($image['link']))){
			throw new Vm_Feed_Exception("An image element must contain a url, title, and a link");			
		}
		
		$accepted = array('url', 'title', 'link', 'height', 'width', 'description');
		
		foreach ($image as $element=>$value){
			if ($element == 'height'){
				if (!is_numeric($value)){
					throw new Vm_Feed_Exception("Image height must be numeric");
				} else if ($value > 400){
					throw new Vm_Feed_Exception("Image height cannot exceed 400");
				}
			} else if ($element == 'width'){
				if (!is_numeric($value)){
					throw new Vm_Feed_Exception("Image width must be numeric");
				} else if ($value > 400){
					throw new Vm_Feed_Exception("Image width cannot exceed 144");
				}				
			} else if (!in_array($element, $accepted)){
				throw new Vm_Feed_Exception("$element is an invalid element");
			}
		}
		
		$this->image = $image;
	}

	/**
	 * @param array $item - An associative array of item elements with the element as the key and its value as the value
	 * 		Note: The value of the 'source' key must be an array, with a URL as the first value of the array and the link text as the second value.
	 */		
	public function addItem(array $item){
		if ((!isset($item['title']))&&(!isset($item['description']))){
			throw new Vm_Feed_Exception("An item must contain either a title or a description");
		}		
		if (($item['source'])&&(!is_array($item['source']))){
			throw new Vm_Feed_Exception("The value of the 'source' key must be an array, with a URL as the first value of the array and the link text as the second value.");
		}
		if (($item['enclosure'])&&(!is_array($item['enclosure']))){
			throw new Vm_Feed_Exception("The value of the 'enclosure' key must be an array, with a URL as the first value of the array, the size of the media object in bytes as the second value, and the MIME type of the media object as the third value.");
		}		
		$this->items[] = $item;
	}

	/**
	 * @return string - The rendered RSS feed
	 */
	public function render(){
		$xml = new Vm_Xml(FALSE);
		
		$channel = '';
		foreach ($this->channel as $element=>$content){
			if (in_array($element, array('pubDate', 'lastBuildDate'))){
				$dateTime = new DateTime($content);			
				$channel .= $xml->$element($dateTime->format('D, d M Y H:i:s O'));
			} else if ($element == 'link'){
				$channel .= "<link>$content</link>";
			} else {
				$channel .= $xml->$element($content);
			}
		}
		
		if ($this->image){
			$image = '';
			foreach ($this->image as $element=>$content){
				$image .= $xml->$element($content);
			}
			$channel .= $xml->image($image);
		}
		
		$items = '';
		foreach ($this->items as $item){
			$itemContainer = '';
			foreach ($item as $element=>$content){
				if ($element == 'source'){
					$itemContainer .= $xml->source($content[1], array('url'=>$content[0]));
				} else if ($element == 'description'){
					$itemContainer .= $xml->description("<![CDATA[$content]]>");	
				} else if ($element == 'pubDate'){
					$dateTime = new DateTime($content);
					$itemContainer .= $xml->pubDate($dateTime->format('D, d M Y H:i:s O'));
				} else if ($element == 'link'){
					$content = htmlentities($content);
					$itemContainer .= "<link>$content</link>";					
				} else {
					$itemContainer .= $xml->$element($content);
				}
			}
			$items .= $xml->item($itemContainer);
		}
		
		return '<?xml version="1.0" encoding="utf-8"?>'.$xml->rss($xml->channel($channel.$items), array('version'=>'2.0'));
	}
}
?>