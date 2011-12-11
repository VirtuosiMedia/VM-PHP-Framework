<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A class for rendering a view.
 * @namespace Vm
 * @uses Vm\Filter
 * @uses Vm\Filter\StripTags
 */
namespace Vm;

use Vm\Filter;

class View {

	protected $filters = array();
	protected $overridePath;
	protected $path;
	protected $valueStorage = array();
	protected $view = array();
	protected $viewName = 'default';
	
	/**
	 * @description The constructor method for Vm_View.
	 * @param string $path - optional -  The relative path to the view files including the trailing slash, defaults to 
	 * 		the current directory
	 * @param string $overridePath - optional -  The relative override path to the view files including the trailing 
	 * 		slash. If the view file is not present in the override directory, it will default to the default path. This 
	 * 		is meant to easily enable template overrides.
	 * @example
	 * <pre class="php">
	 * //The template files are in the current directory
	 * $view = new Vm_View();
	 * 
	 * //The template files are in the directory named 'default'
	 * $view = new Vm_View('default/');
	 * 
	 * //Use the template files in the 'alternate' directory if they exist, otherwise use the 'default' directory
	 * $view = new Vm_View('default/', 'alternate/');
	 * </pre>
	 */
	function __construct($path = './', $overridePath = NULL){
		$this->path = $path;
		$this->overridePath = $overridePath;
		$this->valueStorage['default'] = array();
		$this->filters['StripTags'] = array();	
	}

	/**
	 * @description Dynamically retrieves variables from the current viewspace - meant to be used in the template file. 
	 * @param string $name - The name of the data in the current key
	 * @return mixed - The name of the data in the current key if it exists, FALSE otherwise.
	 * @example
	 * <p>An example header.php template (see the example for the <a href="#__set">__set()</a> method to see how the 
	 * 		variables are assigned):</p>
	 * <pre class="php">
	 * &lt;!DOCTYPE html&gt;
	 * &lt;head&gt;
	 * &lt;meta charset="UTF-8" /&gt;
	 * &lt;title&gt;&lt;?php echo $this-&gt;title; ?&gt;&lt;/title&gt;
	 * &lt;meta name="description" content="&lt;?php echo $this-&gt;metaDescription; ?&gt;" /&gt;
	 * &lt;/head&gt;
	 * &lt;body&gt; 
	 * </pre>
	 */
	public function __get($name){
		return (array_key_exists($name, $this->valueStorage[$this->viewName])) 
			? $this->filter($this->valueStorage[$this->viewName][$name]) 
			: FALSE;	
	}	

	/**
	 * @description Dynamically assigns variables to the current viewspace.
	 * @param string $name - The name of the data in the current key
	 * @param mixed $value - The value for the name of the data in the current key
	 * @example 
	 * <pre class="php">
	 * $view = new Vm_View();
	 * $view-&gt;setViewspace('header');
	 * 
	 * //Both the title and the metaDescription are set automagically to the header viewspace
	 * $view-&gt;title = 'This is my title';
	 * $view-&gt;metaDescription = 'This is my meta description';
	 * $view-&gt;loadTemplate('header.php');
	 * </pre>
	 */
	public function __set($name, $value){
		$this->valueStorage[$this->viewName][$name] = $value;
	}

	/**
	 * @description Maps and automatically filters each of the passed in values as variables into the current viewspace.
	 * @note Both the keys and the values of the array will automatically be filtered with the current filters.
	 * @param array $values - An associative array of key/value pairs, with each key corresponding to the variable names.
	 * @example
	 * <pre class="php">
	 * $view->map(array('firstName'=&gt;'John', 'lastName'=&gt;'Doe'));
	 * //The data will be available in the template file as $this-&gt;firstName and $this-&gt;lastName
	 * </pre>
	 * <p>When working with a Model, use the Vm_Model::getViewData() method in conjunction with Vm_View::map() to 
	 * transfer data from the Model to the View.</p>
	 * <pre class="php">
	 * $model = new My_Model();
	 * $view = new Vm_View();
	 * $view-&gt;map($model-&gt;getViewData());
	 * $view-&gt;loadTemplate('template.php');
	 * echo $view-&gt;render();
	 * </pre>
	 */
	public function map(array $values){
		foreach ($values as $name=>$value){
			$this->valueStorage[$this->viewName][$name] = $value;
		}
	}
	
	/**
	 * @description Sets the filters for the view, overriding all existing filters.
	 * @note This method will overwrite any existing filters, including StripTags.
	 * @note Filters are set on a global level for the Vm_View object and will apply to all viewspaces until the 
	 * 		filters are modified.
	 * @security Overwrite filters with care. If the StripTags filter is not included, your application may be 
	 * 		vulnerable to XSS attacks.
	 * @param array $filters - optional - If the filter has required parameters, the filter name should be the key and 
	 * 		the parameters should be contained in an array, excluding the input parameter, which is automatically 
	 * 		included. If there are no parameters other than input exist, the filter name should be the array value, not 
	 * 		the key.
	 * @example
	 * 		<p>Filters with params:</p>
	 *		<pre class="php">
	 * $view-&gt;setFilters(array(
	 * 		'StripTags'=&gt;array('&lt;i&gt;&lt;b&gt;')
	 * ));
	 * 		</pre>
	 * 		<p>Filters without params:</p>
	 * <pre class="php">
	 * $view-&gt;setFilters(array('StripTags', 'Lower'));
	 * </pre>
	 * 		<p>Mixed:</p>
	 * <pre class="php">
	 * $view-&gt;setFilters(array(
	 * 		'StripTags'=>array('&lt;i&gt;&lt;b&gt;'),
	 * 		'Lower',
	 * 		'Hyphenate'
	 * ));
	 * </pre>
	 */
	public function setFilters(array $filters){
		$this->filters = $filters;
	}

	/**
	 * @description Gets the current filters that are applied the the Vm_View object.
	 * @return array - The current filter names as the array values
	 */
	public function getFilters(){
		return $this->filters;
	}

	/**
	 * @description Adds filters to the existing set of filters for the view.
	 * @note This method will not overwrite any existing filters. The passed in filters will be appended to the existing 
	 * 		filters.
	 * @note Filters are set on a global level for the Vm_View object and will apply to all viewspaces until the filters 
	 * 		are modified.
	 * @param array $filters - optional - If the filter has required parameters, the filter name should be the key and 
	 * 		the parameters should be contained in an array, excluding the input parameter, which is automatically 
	 * 		included. If there are no parameters other than input exist, the filter name should be the array value, not 
	 * 		the key.
	 * @example
	 * 		<p>Filters with params:</p>
	 *		<pre class="php">
	 * $view-&gt;addFilters(array(
	 * 		'StripTags'=&gt;array('&lt;i&gt;&lt;b&gt;')
	 * ));
	 * 		</pre>
	 * 		<p>Filters without params:</p>
	 * <pre class="php">
	 * $view-&gt;addFilters(array('StripTags', 'Lower'));
	 * </pre>
	 * 		<p>Mixed:</p>
	 * <pre class="php">
	 * $view-&gt;addFilters(array(
	 * 		'StripTags'=>array('&lt;i&gt;&lt;b&gt;'),
	 * 		'Lower',
	 * 		'Hyphenate'
	 * ));
	 * </pre>
	 */
	public function addFilters(array $filters){
		$this->filters = array_merge($this->filters, $filters);
	}	

	/**
	 * @description Removes filters from the view.
	 * @param array $filters - The filter names to remove, with the filter names as values
	 * @note Filters are set on a global level for the Vm_View object and will apply to all viewspaces until the filters 
	 * 		are modified.
	 * @security Remove filters with care. If the StripTags filter is not included, your application may be vulnerable 
	 * 		to XSS attacks.
	 * @example
	 * <pre class="php">
	 * $view-&gt;removeFilters(array('Hyphenate', 'StripTags'));
	 * </pre>
	 */	
	public function removeFilters(array $filters){
		foreach ($filters as $filter){
			unset($this->filters[$filter]);
			foreach ($this->filters as $key=>$filterName){
				if ($filter == $filterName){
					unset($this->filters[$key]);
				}
			}
		}
	}
	
	/**
	 * @description The viewspace acts as a namespace for the Vm_View object. Any variables or template files assigned 
	 * 		to the view object will	be assigned to the current viewspace. The current viewspace will remain current 
	 * 		until another viewspace is explicitly named.
	 * @note Viewspaces make it easy to build multiple parts of a rendered page separately using the same Vm_View 
	 * 		object. Named viewspaces can be	rendered in any order.
	 * @note The default viewspace is named 'default' and does not need to be explicitly named unless it is being 
	 * 		rendered with multiple other viewspaces.
	 * @param string $name - The name of the viewspace
	 * @example
	 * <pre class="php">
	 * $view = new Vm_View();
	 * 
	 * //The header viewspace
	 * $view-&gt;setViewspace('header');
	 * $view-&gt;title = 'This is my title';
	 * $view-&gt;loadTemplate('header.php');
	 * 
	 * //The nav viewspace will only be assigned $this-&gt;menu, but not $this-&gt;title (assigned to the header)
	 * $view-&gt;setViewspace('nav');
	 * $view-&gt;menu = array(
	 * 		'Home'=&gt;'http://www.example.com/', 
	 * 		'About'=&gt;'http://www.example.com/about', 
	 * 		'Contact'=&gt;'http://www.example.com/contact'
	 * );
	 * $view-&gt;loadTemplate('nav.php');
	 *
	 * //The body viewspace
	 * $view-&gt;setViewspace('body');
	 * $view-&gt;content = 'This is my content';
	 * $view-&gt;loadTemplate('body.php');
	 * 
	 * //Normally this will be passed back to the controller rather than echoed directly.
	 * echo $view-&gt;render(array('header', 'nav', 'body'));
	 * </pre>
	 */
	public function setViewspace($name){
		$this->viewName = $name;
		if (!array_key_exists($name, $this->valueStorage)){
			$this->valueStorage[$name] = array();
		}
	}

	/**
	 * @description A protected method for filtering data.
	 * @note Both array keys and values will be filtered.
	 * @note Because this method is called automatically when you set your view data, it is a protected method, not 
	 * 		public.
	 * @security If the passed in value is not a string, array, or numeric, it will be returned unfiltered.
	 * @uses Vm\Filter 
	 * @param mixed $value - The value to be filtered. The value can be either a string, a number, or an array.
	 * @param array $filters - optional - If the filter has required parameters, the filter name should be the key and 
	 * 		the parameters should be contained in an array, excluding the input parameter, which is automatically 
	 * 		included. If there are no parameters other than input exist, the filter name should be the array value, not 
	 * 		the key. If no filters are passed in, any filters that are currently set will be used.
	 * @return The filtered value, if the value was a string, an array, or numeric.
	 */
	protected function filter($value, array $filters = array()){
		$filters = (sizeof($filters) > 0) ? $filters : $this->filters;
		if (is_array($value)){
			$filteredValue = array();
			foreach ($value as $key=>$val){
				$key = $this->filter($key, $filters);
				$val = $this->filter($val, $filters);
				$filteredValue[$key] = $val;
			}
		} else if ((is_string($value))||(is_numeric($value))){
			$filteredValue = $value;
			foreach ($filters as $filterName=>$params){
				$filterName = (is_array($params)) ? '\Vm\Filter\\'.$filterName : 'Vm\Filter\\'.$params;
				$params = (is_array($params)) ? $params : array();
				$numParams = sizeof($params);
				$filter = new $filterName();				
				
				switch ($numParams){
					case 0:
						$filteredValue = $filter->filter($filteredValue);
						break;
					case 1:
						$filteredValue = $filter->filter($filteredValue, $params[0]);
						break;
					case 2:
						$filteredValue = $filter->filter($filteredValue, $params[0], $params[1]);
						break;
					default:
						throw new Vm_View_Exception("Too many parameters ($numParams) have been passed to $filterName");
						break;										
				}				
			}
		} else { //It's an object, boolean, or something else that isn't able to be filtered
			$filteredValue = $value;
		}
		return $filteredValue;	
	}

	/**
	 * @description Loads a view template file into the current viewspace and injects it with the data assigned to the 
	 * 		viewspace. 
	 * @param string $file - The name of the view template file, with the file extension
	 * @param string $path - optional -  The relative path to the view files including the trailing slash, defaults to 
	 * 		the set directory. Note that if an override	directory was specified in the object constructor, this $path 
	 * 		argument will be overriden unless you also pass the loadTemplate method an override path.
	 * @param string $overridePath - optional -  The relative override path to the view files including the trailing 
	 * 		slash. If the view file is not present in the override directory, it will default to the default path. This 
	 * 		is meant to easily enable template overrides.
	 * @example
	 * 
	 * <pre class="php">
	 * $view = new \Vm\View();
	 * 
	 * //Assign an array to the view, which will be available as $this-&gt;view in the template file
	 * $view-&gt;items = array('Item 1', 'Item 2', 'Item 3');
	 * 
	 * //In this example, template.php must reside in the current directory
	 * $view-&gt;loadTemplate('template.php');
	 * 
	 * //This will render the template, populated with the passed in values
	 * echo $view-&gt;render();
	 * </pre>
	 * <p>If you want to use a template override, you can specify the override path either in the constructor or as a 
	 * 	parameter passed to the	loadTemplate method. The template file in the default directory and the template file 
	 * 	in the override directory must share the same name. The loadTemplate will not overwrite the default directory 
	 * 	unless an alternate directory is also provided.</p>
	 * <pre class="php">
	 * $view-&gt;loadTemplate('template.php', 'default/', 'alternate/');
	 * </pre>
	 */
	public function loadTemplate($file, $path = NULL, $overridePath = NULL){
		$path = ($path) ? $path : $this->path;
		$overridePath = ($overridePath) ? $overridePath : $this->overridePath;
		if (($overridePath)&&(file_exists($overridePath.$file))){
			$path = $overridePath;
		}
		if (file_exists($path.$file)){
			ob_start();
			require($path.$file);
			$view = ob_get_clean();
			$this->view[$this->viewName] = (isset($this->view[$this->viewName])) 
				? $this->view[$this->viewName].$view 
				: $view;
		} else {
			throw new Vm_View_Exception('View file could not be loaded because '.$path.$file.' does not exist');
		} 
	}

	/**
	 * @description Renders the viewspace.
	 * @param mixed $name - optional - The name of a single viewspace as a string, or multiple viewspaces as an array 
	 * 		of strings in their desired order. Defaults to the 'default' view.
	 * @return mixed - The view as a string if it exists, otherwise FALSE.
	 * @example
	 * <pre class="php">
	 * //Render the default viewspace
	 * $view-&gt;render();
	 * 
	 * //Render a named viewspace
	 * $view-&gt;render('Alternate');
	 * 
	 * //Render multiple viewspaces, in order
	 * $view-&gt;render(array('default', 'Header', 'Body', 'Footer'));
	 * </pre>
	 */
	public function render($name = 'default'){
		if (is_array($name)){
			$compiledView = '';
			foreach ($name as $viewName){
				$compiledView .= (array_key_exists($viewName, $this->view)) ? $this->view[$viewName] : '';
			}
		} else {
			$compiledView = (array_key_exists($viewName, $this->view)) ? $this->view[$viewName] : FALSE;
		}
		return $compiledView;
	}
}