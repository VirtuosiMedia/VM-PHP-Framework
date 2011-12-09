<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description The model for generating the API Docs for VM PHP Framework Suite
 * @requirements PHP 5.2 or higher
 * @namespace Suite\Model\Docs
 * @uses Vm\Folder;
 */
namespace Suite\Model\Docs;

use \Vm\Folder;

class Api extends \Vm\Model {

	protected $appClasses = array();
	protected $class;
	protected $params;
	protected $phpClasses = array();
	protected $settings;
	
	/**
	 * @param array $params - An associative array of the URL parameters, with the parameter name as the key, it's 
	 * 		value as the value
	 * @param array $settings - An associative settings array, with the setting name as the key, it's value as the value
	 */	
	function __construct($params, $settings){
		$this->params = $params;
		$this->settings = $settings;
		$this->getClasses();
		
		$this->compileData();		
	}

	protected function getClasses(){
		$this->phpClasses = array('stdClass', 'Exception', 'ErrorException', 'Closure', 'COMPersistHelper', 
			'com_exception', 'com_safearray_proxy', 'variant', 'com', 'dotnet', 'DateTime', 'DateTimeZone', 
			'DateInterval', 'DatePeriod', 'ReflectionException', 'Reflection', 'ReflectionFunctionAbstract', 
			'ReflectionFunction', 'ReflectionParameter', 'ReflectionMethod', 'ReflectionClass', 'ReflectionObject', 
			'ReflectionProperty', 'ReflectionExtension', 'LogicException', 'BadFunctionCallException', 
			'BadMethodCallException', 'DomainException', 'InvalidArgumentException', 'LengthException', 
			'OutOfRangeException', 'RuntimeException', 'OutOfBoundsException', 'OverflowException', 'RangeException', 
			'UnderflowException', 'UnexpectedValueException', 'RecursiveIteratorIterator', 'IteratorIterator', 
			'FilterIterator', 'RecursiveFilterIterator', 'ParentIterator', 'LimitIterator', 'CachingIterator',
			'RecursiveCachingIterator', 'NoRewindIterator', 'AppendIterator', 'InfiniteIterator', 'RegexIterator', 
			'RecursiveRegexIterator', 'EmptyIterator', 'RecursiveTreeIterator', 'ArrayObject', 'ArrayIterator', 
			'RecursiveArrayIterator', 'SplFileInfo', 'DirectoryIterator', 'FilesystemIterator', 
			'RecursiveDirectoryIterator', 'GlobIterator', 'SplFileObject', 'SplTempFileObject', 'SplDoublyLinkedList', 
			'SplQueue', 'SplStack', 'SplHeap', 'SplMinHeap', 'SplMaxHeap', 'SplPriorityQueue', 'SplFixedArray', 
			'SplObjectStorage', 'MultipleIterator', '__PHP_Incomplete_Class', 'php_user_filter', 'Directory', 
			'LibXMLError', 'DOMException', 'DOMStringList', 'DOMNameList', 'DOMImplementationList', 
			'DOMImplementationSource', 'DOMImplementation', 'DOMNode', 'DOMNameSpaceNode', 'DOMDocumentFragment', 
			'DOMDocument', 'DOMNodeList', 'DOMNamedNodeMap', 'DOMCharacterData', 'DOMAttr', 'DOMElement', 'DOMText', 
			'DOMComment', 'DOMTypeinfo', 'DOMUserDataHandler', 'DOMDomError', 'DOMErrorHandler', 'DOMLocator', 
			'DOMConfiguration', 'DOMCdataSection', 'DOMDocumentType', 'DOMNotation', 'DOMEntity', 'DOMEntityReference', 
			'DOMProcessingInstruction', 'DOMStringExtend', 'DOMXPath', 'SimpleXMLElement', 'SimpleXMLIterator', 
			'XMLReader', 'XMLWriter', 'XSLTProcessor', 'PharException', 'Phar', 'PharData', 'PharFileInfo', 
			'mysqli_sql_exception', 'mysqli_driver', 'mysqli', 'mysqli_warning', 'mysqli_result', 'mysqli_stmt', 
			'PDOException', 'PDO', 'PDOStatement', 'PDORow', 'SoapClient', 'SoapVar', 'SoapServer', 'SoapFault', 
			'SoapParam', 'SoapHeader', 'SQLiteDatabase', 'SQLiteResult', 'SQLiteUnbuffered', 'SQLiteException', 
			'SQLite3', 'SQLite3Stmt', 'SQLite3Result', 'ZipArchive', 'SWFShape', 'SWFFill', 'SWFGradient', 'SWFBitmap', 
			'SWFText', 'SWFTextField', 'SWFFont', 'SWFDisplayItem', 'SWFMovie', 'SWFButton', 'SWFAction', 'SWFMorph', 
			'SWFMovieClip', 'SWFSprite', 'SWFSound', 'SWFFontChar', 'SWFButtonRecord', 'SWFSoundInstance', 
			'SWFVideoStream', 'SWFBinaryData', 'SWFInitAction', 'SWFPrebuiltClip', 'SWFSoundStream', 'SWFFilter', 
			'SWFFilterMatrix', 'SWFShadow', 'SWFBlur', 'SWFCXform', 'SWFMatrix', 'SWFInput', 'SWFBrowserFont', 
			'SWFFontCollection', 'SWFCharacter', 'PDFlibException', 'PDFlib'
		);

		$folder = new Folder('../Includes');
		$files = $folder->getFiles(TRUE, 'php');
		
		foreach ($files as $path=>$file){
			$name = str_replace('/', '_', str_replace('../Includes/', '', str_replace('.php', '', $path)));
			$this->appClasses[] = $name;
		}
	}
	
	/**
	 * @param string $class - The name of the class
	 * @return string - A link to the class documentation, if it can be found
	 */
	protected function getClassLink($class){
		if (in_array($class, $this->phpClasses)){
			return '<a href="http://php.net/manual/en/class.'.strtolower(str_replace('_', '-', $class)).'.php" target="_blank">'.$class.'</a> (external link)';
		} else if (in_array($class, $this->appClasses)){
			return '<a href="index.php?p=docs&amp;f='.$class.'">'.$class.'</a>';
		} else {
			return 'See Parent Class';
		}
	}
	
	protected function compileData(){
		$fileParts = explode('_', str_replace('/', '', str_replace('.', '', $this->params['f'])));
		$filePath = '../Includes/'.implode('/', $fileParts).'.php';
			
		if (file_exists($filePath)){
			$this->class = new \ReflectionClass(implode('_', $fileParts));
			$publicMethods = $this->class->getMethods(\ReflectionMethod::IS_PUBLIC);
			$protectedMethods = $this->class->getMethods(\ReflectionMethod::IS_PROTECTED);
			
			$this->setData('classDocs', $this->parseComments($this->class->getDocComment()));
			$this->setData('publicMethods', $this->getMethodNames($publicMethods));
			$this->setData('protectedMethods', $this->getMethodNames($protectedMethods));

			$methods = array_merge($publicMethods, $protectedMethods);
			$methodDocs = array();
			
			foreach ($methods as $method){
				$methodDocs[$method->getName()] = $this->getMethodData($method);
			}
			
			$this->setData('methodDocs', $methodDocs);
			$this->setData('api', TRUE);
		} else {
			$this->setData('api', FALSE);
		}		
	}
	
	/**
	 * @param string $comments - The DocBloc comments
	 * @return array - The property as the key, an array of values as the value
	 */
	protected function parseComments($comments){
		$docs = explode('@', trim(str_replace('@', '@^%', str_replace('*', '', str_replace('/**', '', $comments)))));
		$docsData = array();
		$tags = array(
			'Author'=>'@author',
			'Copyright'=>'@copyright',
			'Deprecated'=>'@deprecated',
			'Example'=>'@example',
			'Group'=>'@group',
			'License'=>'@license',
			'Link'=>'@link',
			'Package'=>'@package',
			'Param'=>'@param',
			'Requires'=>'@requires',
			'Returns'=>'@return',
			'See'=>'@see',
			'Subgroup'=>'@subgroup',
			'Subpackage'=>'@subpackage',
			'To-Do'=>'@todo',
			'Var'=>'@var'						
		);
		
		$classTags = array(
			'Extends'=>'@extends',
			'Implements'=>'@implements',
			'Uses'=>'@uses'						
		);
		
		foreach ($docs as $data){
			$data = trim(str_replace('^%', '@', $data));
			if (strpos($data, '@description') !== FALSE){
				$data = rtrim(trim(str_replace('@description', '', $data)), '/');
				$docsData['Description'] = (!empty($data))? $data : '<i class="noValue">No description given.</i>';
			} else if (strpos($data, '@example') !== FALSE){
				$data = rtrim(trim(str_replace('@example', '', $data)), '/');
				$docsData['Example'] = (!empty($data))? $data : '<i class="noValue">No example given.</i>';
			} else if (strpos($data, '@note') !== FALSE){
				$data = rtrim(trim(str_replace('@note', '', $data)), '/');
				$docsData['Note'][] = (!empty($data))? $data : '<i class="noValue">No note given.</i>';
			} else if (strpos($data, '@security') !== FALSE){
				$data = rtrim(trim(str_replace('@security', '', $data)), '/');
				$docsData['Security'][] = (!empty($data))? $data : '<i class="noValue">No security notice given.</i>';				
			} else {
				foreach ($tags as $name=>$tag){
					if (strpos($data, $tag) !== FALSE){
						$data = rtrim(trim(str_replace($tag, '', $data)), '/');
						$docsData[$name][] = (!empty($data))? $data : '<i class="noValue">Unspecified</i>';
					} 
				}
				foreach ($classTags as $name=>$tag){
					if (strpos($data, $tag) !== FALSE){
						$data = rtrim(trim(str_replace('/', '', str_replace($tag, '', $data))), '/');
						$docsData[$name][] = (!empty($data)) 
							? $this->getClassLink($data) 
							: '<i class="noValue">Unspecified</i>';
					} 
				}
			}		
		}
		$modifiers = implode(', ', \Reflection::getModifierNames($this->class->getModifiers()));
		$docsData['Modifiers'][] = (!empty($modifiers)) ? $modifiers : '<i class="noValue">None</i>';
		ksort($docsData);
		return $docsData;
	}
	
	/**
	 * @param array $methods - An array of ReflectionMethod objects
	 * @return array - The method names as the keys, their parameters as values
	 */
	protected function getMethodNames($methods){
		$names = array();
		foreach ($methods as $method){
			$args = $method->getParameters();
			$params = array();
			foreach ($args as $arg){
				$paramName = '$'.$arg->getName(); 
				$params[] = ($arg->isOptional()) ? '<span class="tips" title="This parameter is optional">['.$paramName.']</span>' : $paramName;
			}
			$params = (sizeof($params) == 0) ? array('<i class="noValue">No Parameters</i>') : $params;
			$names[$method->getName()] = implode(', ', $params);
		}
		ksort($names);
		return $names;
	}
	
	/**
	 * @param ReflectionMethod $method - The ReflectionMethod object for which data should be retrieved
	 * @return array - An associative array of the method data
	 */
	protected function getMethodData(\ReflectionMethod $method){
		$methodData = array();
		$comments = $this->parseComments($method->getDocComment());
		
		$methodData['Params'] = (isset($comments['Param'])) 
			? $this->getMethodParams($method->getParameters(), $comments['Param']) 
			: array();
		$methodData['Returns'] = (isset($comments['Returns'])) 
			? implode(' ', $comments['Returns']) 
			: '<i class="noValue">No return value given.</i>';
		$methodData['Description'] = (isset($comments['Description'])) 
			? $comments['Description'] 
			: '<i class="noValue">No description available.</i>';
		
		$modifiers = implode(' ', \Reflection::getModifierNames($method->getModifiers()));
		$comments['Modifiers'][0] = (!empty($modifiers)) ? $modifiers : '<i class="noValue">None</i>';
		
		$class = $method->getDeclaringClass()->getName();
		if ($class != $this->class->getName()){
			if (in_array($class, $this->phpClasses)){
				$comments['Defined'][0] = '<a href="http://php.net/manual/en/class.'.strtolower(str_replace('_', '-', $class))
					.'.php" target="_blank">'.$class.'</a> (external link)';
			} else if (in_array($class, $this->appClasses)){
				$comments['Defined'][0] = '<a href="index.php?p=docs&amp;f='.$class.
					'#source">Line '.$method->getStartLine().' of '.$class.'</a>';
			} else {
				$comments['Defined'][0] = 'See Parent Class';
			}
		} else {
			$comments['Defined'][0] = ($method->getStartLine()) 
				? '<a href="#source">Line '.$method->getStartLine().'</a>' 
				: 'See Parent Class';	
		}
		
		unset($comments['Param']);
		unset($comments['Description']);
		unset($comments['Returns']);
		$methodData['Data'] = $comments;
		return $methodData;
	}

	/**
	 * @param array $params - An array of ReflectionParameter objects 
	 * @param array $comments - The comments array from Suite_Model_Docs_Api::parseComments()
	 * @return array - An associative array of the parameter data
	 */
	protected function getMethodParams(array $params, array $comments){
		$paramsList = array();
		foreach ($params as $index=>$param){
			$paramsList[$index] = array();
			$paramsList[$index]['name'] = ($param->isOptional()) 
				? '<span class="tips" title="This parameter is optional">[$'.$param->getName().']</span>' 
				: '$'.$param->getName();
			$paramsList[$index]['type'] = $this->getParameterType($param);
			if ($param->isDefaultValueAvailable()){
				$value = $param->getDefaultValue();
				$value = ($value === TRUE) ? 'true' : $value;
				$value = ($value === FALSE) ? 'false' : $value; 
				$paramsList[$index]['default'] = (is_null($value)) ? 'null' : (string) $value; 
			} else {
				$paramsList[$index]['default'] = '<i class="noValue">None</i>';
			} 
			$paramsList[$index]['description'] = (isset($comments[$index])) 
				? trim($comments[$index]) 
				: '<i class="noValue">No description available.</i>';
		}
		return $paramsList;
	}

	/**
	 * @param ReflectionParameter $param  - The parameter for which a type should be retrieved
	 * @return string - The parameter type
	 */
	protected function getParameterType(\ReflectionParameter $param){
		if ($param->isArray()){
			return 'array';
		} else if ($param->getClass()){
			$className = $param->getClass()->getName();
			return $this->getClassLink($className);
		} else if ($param->isDefaultValueAvailable()){
			$default = $param->getDefaultValue();
			if (is_bool($default)){
				return 'boolean';
			} else if (is_int($default)){
				return 'integer';
			} else if (is_numeric($default)){
				return 'numeric';
			} else if (is_object($default)){
				return 'object';
			} else if (is_string($default)){
				return 'string';
			}															
		} 
		return '<i class="noValue">unspecified</i>';		
	}
}