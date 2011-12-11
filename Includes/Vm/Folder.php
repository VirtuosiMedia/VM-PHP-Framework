<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A class for working with folders
 * @namespace Vm
 */
namespace Vm;

class Folder {
	
	protected $baseDir;
	protected $files = array();
	protected $folders = array();
	protected $xml;
	
	/**
	 * @param string $baseDir - The base directory relative to the current working directory, without the trailing slash
	 */
	function __construct($baseDir){
		$this->baseDir = $baseDir;
	}

	/**
	 * @description Recursively scans a directory and returns all contents
	 * @param boolean $recursive - optional - Whether or not the scan should be recursive
	 * @param boolean $includePath - optional - Whether or not the file path should be prepended to results
	 * @param string $dir - optional - The current folder being scanned
	 * @return array - An array of the directory contents
	 */
	protected function scan($recursive = FALSE, $includePath = FALSE, $dir = NULL){
		$folder = ($dir) ? $dir : $this->baseDir;
		$exclude = array('.', '..', '.svn'); //There may be others to include here as well
		$files = array_diff(scandir($folder), $exclude);
		if ($recursive){
			$resources = array();
			foreach ($files as $file){
				$dirPath = $folder.'/'.$file;
				$filePath = ($includePath) ? $dirPath : $file;
				if (is_dir($dirPath)){
					$resources[$filePath] = $this->scan($recursive, $includePath, $dirPath);
					$this->folders[$dirPath] = $filePath;
				} else {
					$resources[$filePath] = $filePath;
					$this->files[$dirPath] = $filePath;
				}
			}
		} else {
			if ($includePath){
				$fullFiles = array();
				
				foreach ($files as $file){
					$fullFiles[] = $folder.'/'.$file;
				}
				$resources = $fullFiles;
			} else {
				$resources = $files;
			}
		}
		return $resources;
	}

	/**
	 * @description Recursively empties a directory of its contents
	 * @param array $resource - The directory contents to be emptied
	 */
	protected function recursiveEmpty($resource){
		if (is_array($resource)){
			foreach ($resource as $folder=>$file){
				if (is_array($file)){
					$this->recursiveEmpty($file);
					rmdir($folder);
				} else {
					unlink($file);
				}
			}
		} else {
			unlink($resource);
		}
	}
	
	/**
	 * @description Gets the contents of the base directory
	 * @param boolean $recursive - optional - Whether or not a recursive scan should be used
	 * @param boolean $includePath - optional - Whether or not the file path should be prepended to results
	 * @return array - A multi-dimensional array of the directory contents
	 */
	public function getContents($recursive = FALSE, $includePath = FALSE){
		return $this->scan($recursive, $includePath);
	}
	
	/**
	 * @description Gets the contents of the base directory
	 * @param boolean $recursive - optional - Whether or not a recursive scan should be used
	 * @param boolean $includePath - optional - Whether or not the file path should be prepended to result values
	 * @return array - A single dimensional array of the directory folders
	 */	
	public function getFolders($recursive = FALSE, $includePath = FALSE){
		$folders = $this->scan($recursive, $includePath);
		if (!$recursive){
			foreach ($folders as $name=>$folder){
				if (!preg_match('#\.#', $folder)){
					$this->folders[] = $folder;
				}
			}
		}
		return $this->folders;
	}

	/**
	 * @description Gets the files from the base base directory
	 * @param boolean $recursive - optional - Whether or not a recursive scan should be used
	 * @param string $fileExtension - optional - The file extension (minus the dot). 
	 * @param boolean $includePath - optional - Whether or not the file path should be prepended to result values
	 * @return array - A single dimensional array of the files in the base directory. If the file extension is set,
	 * 	only files with that extension will be returned 
	 */
	public function getFiles($recursive = FALSE, $fileExtension = NULL, $includePath = FALSE){
		$files = $this->scan($recursive, $includePath);
		if (!$recursive){
			foreach ($files as $path=>$file){
				if (preg_match('#\.#', $file)){
					$this->files[] = $file;
				}
			}
		}
		if ($fileExtension){
			$files = array();
			foreach($this->files as $path=>$file){
				if (preg_match('#\.'.$fileExtension.'$#', $file)){
					$files[$path] = $file;
				}
			}
		} else {
			$files = $this->files;
		}
		return $files; 
	}

	/**
	 * @description Creates a directory
	 * @param string $dirName - The name of the directory to create
	 * @param int $mode - optional - The folder permissions in octal, defaults to 0755 for security purposes - Note: 
	 * 		Mode is ignored on Windows.
	 * @param boolean $recursive - optional - TRUE if the directory creation should be recursive, FALSE otherwise - 
	 * 		Defaults FALSE
	 * @return boolean - TRUE if the directory was created, FALSE otherwise
	 */
	public function createDir($dirName, $mode = 0755, $recursive = FALSE){
		return mkDir($this->baseDir.'/'.$dirName, $mode, $recursive);
	}

	/**
	 * @description Deletes a directory if a name is given, else it deletes the base directory - Note: Directory must 
	 * 		be empty to be deleted
	 * @param string $dirName - optional - The name of the directory to delete, defaults to base directory 
	 * @return boolean - TRUE if the directory was deleted, FALSE otherwise
	 */
	public function deleteDir($dirName = NULL){
		return ($dirName) ? rmdir($this->baseDir.'/'.$dirName) : rmdir($this->baseDir);
	}

	/**
	 * @description Empties a directory of its contents if a name is given, else it empties the base directory
	 * @param string $dirName - optional - The name of the directory to empty, defaults to the base directory
	 * @param boolean - TRUE if the directory should be emptied recursively, FALSE otherwise. Defaults FALSE
	 */
	public function emptyDir($dirName = NULL, $recursive = FALSE){
		$dir = ($dirName) ? $dirName : $this->baseDir;
		$resources = new \Vm\Folder($dir);
		$contents = $resources->getContents($recursive, TRUE);
		$this->recursiveEmpty($contents);			
	}

	/**
	 * @description Renames a directory if it is given, else it renames the base directory
	 * @param string $newName - The new directory name relative to the current working directory
	 * @param string $oldName - optional - The name of the directory to be renamed relative to the base directory
	 * 	If no old name is given, defaults to the base directory 
	 * @return boolean - TRUE if the directory was renamed, FALSE otherwise
	 */
	public function renameDir($newName, $oldName = NULL){
		$oldName = ($oldName) ? $this->baseDir.'/'.$oldName : $this->baseDir;
		return rename($oldName, $newName);
	}

	/**
	 * @param string $dirName - optional - The name of the directory to check, defaults to the base directory
	 * @return boolean - TRUE if the directory is a directory, FALSE otherwise
	 */
	public function isDir($dirName = NULL){
		$dir = ($dirName) ? $this->baseDir.'/'.$dirName : $this->baseDir;
		return is_dir($dir);
	}

	/**
	 * @param string $dirName - optional - The name of the directory to check, defaults to the base directory
	 * @return int - The folder permissions in octal
	 */	
	public function getPermissions($dirName = NULL){
		$dir = ($dirName) ? $this->baseDir.'/'.$dirName : $this->baseDir;
		return substr(sprintf('%o', fileperms($dir)), -4);
	}
	
	/**
	 * @description Sets the directory permissions if the user has permission to do so
	 * @param int $mode - The folder permissions in octal, ex: 0777
	 * @param string $dirName -optional- The name of the directory to set the permissions for, defaults to base 
	 * 		directory
	 * @return boolean - TRUE if the directory permissions were changed, FALSE otherwise
	 */
	public function setPermissions($mode, $dirName = NULL){
		$dir = ($dirName) ? $this->baseDir.'/'.$dirName : $this->baseDir;
		return chmod($dir, $mode);
	}
}