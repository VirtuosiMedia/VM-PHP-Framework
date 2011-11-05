<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A very basic image thumbnail creation class
* Requirements: PHP 5.2 or higher, GD Library enabled
*/
class Vm_Image_Thumbnail {
	
	protected $original;
	protected $type;
	
	/**
	 * @param string $imageFile - The relative URL to the image file
	 */
	function __construct($imageFile){
		$info = pathinfo($imageFile);
		$this->original = $imageFile;
		$this->type = $info['extension'];
	}
	
	/**
	 * Calculates the size ratio for the image
	 * @param int $width - The new max width of the image, in pixels
	 * @param int $height - optional - The new max height of the image, in pixels, automatically calculates 
	 * 	if not given
	 * @return array - An associative array of the x, y lengths of the image
	 */
	protected function calculateRatio($width, $height = NULL){
		$size = getimagesize($this->original);
		$height = ($height) ? $height : round($size[1]/($size[0]/$width));
		
		$xScale = $size[0]/$width;
		$yScale = $size[1]/$height;
		
		if ($yScale > $xScale){
			$x = round($size[0] * (1/$yScale));
			$y = round($size[1] * (1/$yScale));
		} else {
			$x = round($size[0] * (1/$xScale));
			$y = round($size[1] * (1/$xScale));
		}
		return array('x'=>$x, 'y'=>$y);
	}

	/**
	 * Saves the image to the specified filepath
	 * @param int $width - The new max width of the image, in pixels
	 * @param int $height - The new max height of the image, in pixels, automatically calculates 
	 * 	if not given
	 * @param string $filepath - The filepath to save to
	 * @return mixed - The filepath if the image was created successfully, FALSE otherwise
	 */
	protected function save($width, $height, $filepath){
		$new = $this->calculateRatio($width, $height);
		$old = getimagesize($this->original);
		
		$thumb = imagecreatetruecolor($new['x'], $new['y']);
		
		if (in_array($this->type, array('jpg', 'jpeg', 'jpe'))){
			$source = imagecreatefromjpeg($this->original);
		} else if ($this->type == 'png'){
			imagealphablending($thumb, false);
			$source = imagecreatefrompng($this->original);
		} else if ($this->type == 'gif'){
			imagealphablending($thumb, false);
			$source = imagecreatefromgif($this->original);
		} else { 
			return FALSE;
		}
		
		imagecopyresized($thumb, $source, 0, 0, 0, 0, $new['x'], $new['y'], $old[0], $old[1]);
				
		if (in_array($this->type, array('jpg', 'jpeg', 'jpe'))){
			imagejpeg($thumb, $filepath);
		} else if ($this->type == 'png'){
			imagesavealpha($thumb, true);
			imagepng($thumb, $filepath);
		} else if ($this->type == 'gif'){
			imagesavealpha($thumb, true);
			imagegif($thumb, $filepath);
		} else { 
			return FALSE;
		}
		return (file_exists($filepath)) ? $filepath : FALSE;		
	}
	
	/**
	 * Resizes the image to scale given
	 * @param int $width - The new max width of the image, in pixels
	 * @param int $height - optional - The new max height of the image, in pixels, automatically calculates 
	 * 	if not given
	 * @return mixed - The filepath if the image was created successfully, FALSE otherwise
	 */
	public function resize($width, $height = NULL){
		return $this->save($width, $height, $this->original);
	}

	/**
	 * Creates a thumbnail of the current image to the scale given
	 * @param int $width - The new max width of the image, in pixels
	 * @param int $height - optional - The new max height of the image, in pixels, automatically calculates 
	 * 	if not given
	 * @param string $filepath - optional - The filepath to save to if given, else the current directory
	 * @param boolean $overwrite - optional - Whether or not to overwrite an existing file, defaults FALSE
	 * @return mixed - The filepath if the image was created successfully, FALSE otherwise
	 */
	public function thumbnail($width, $height = NULL, $filepath = NULL, $overwrite = FALSE){
		$path = ($filepath) ? $filepath.'/' : './';
		$filename = basename($this->original);
		if ((!file_exists($path.$filename)) || ($overwrite)){
			return $this->save($width, $height, $path.$filename);
		} else {
			return FALSE;
		}
	}
}
?>