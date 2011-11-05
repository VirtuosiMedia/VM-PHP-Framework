<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A pagination class
* Requirements: PHP 5.2 or higher
*/
class Vm_Paginate extends Vm_Klass {

	protected $baseUrl = NULL;
	protected $baseParams;
	protected $offset = 0;	
	protected $numRecords = NULL;
	protected $numPages = NULL;
	protected $currentPageNum = NULL;
	protected $currentLimit = NULL;

	/**
	 * @param array optional $options - The options array
	 */
	function __construct($options = array()){
		$defaultOptions = array(
			'method' => 'GET',			//string - How the parameters should be read, GET or POST - defaults to GET
			'limit' => 10,				//int - The number of rows that should be displayed at once, defaults to 10
			'limitParam' => 'l', 		//string - the URL parameter or field name that indicates the limit value				
			'pageParam' => 'pn' 		//string - the URL parameter or field name that indicates the page number							
		);

		parent::__construct();
		$this->setOptions($options, $defaultOptions);
		
		$this->setBaseUrl();
		$this->update();
	}

	protected function setBaseUrl(){
		if (isset($_SERVER['QUERY_STRING'])){
			$params = explode('&', $_SERVER['QUERY_STRING']);
			$pageParams = array(
				$this->options['limitParam'], 
				$this->options['pageParam'], 
				$this->options['sortColumnParam'], 
				$this->options['sortStyleParam']
			);
			$newParams = array();
			foreach ($params as $param){
				$param = explode('=', $param);
				$name = $param[0];
				$value = $param[1];
				$newParams[$name] = $value;
				if (in_array($name, $pageParams)){
					unset($newParams[$name]);
				} 
			}
			$params = '';
			$this->baseParams = $newParams;
			foreach ($newParams as $param=>$value){
				$params .= $param.'='.$value.'&amp;';
			}
			$this->baseUrl = $_SERVER['PHP_SELF'].'?'.$params;
		} else {		
			$this->baseUrl = $_SERVER['PHP_SELF'].'?';
		}
	}
	
	protected function update(){
		$this->setOffset();
		$this->setLimit();
	}

	/** 
	 * @param string $excludeParam - The parameter to exclude in the string build
	 * @return string - The compiled query string
	 */
	protected function getUrlParams($excludeParam){
		$queryString = '';
		if (($excludeParam != 'limit') && ($this->getLimit() != $this->options['limit'])){
			$queryString .= '&'.$this->options['limitParam'].'='.$this->currentLimit;
		}
		return $queryString;
	}
	
	/**
	 * Description: This function MUST be used for most of the get functions to work
	 * @param int $numRecords - The total number of records 
	 * @return - The object, for chaining	
	 */
	public function setNumRecords($numRecords){
		$this->update();
		$this->numRecords = $numRecords;
		$this->getNumPages();
		$this->getCurrentPageNum();
		return $this;
	}

	/**
	 * @param int $limitValue optional - The number of rows to be returned, else it automatically searches for it
	 * @return - The object, for chaining	
	 */	
	public function setLimit($limitValue = NULL){
		$this->currentLimit = ($limitValue) ? $limitValue : $this->getLimit();
		return $this;
	}
	
	/**
	 * @param int $offsetValue optional - The row at which the result set should begin, else it automatically searches for it
	 * @return - The object, for chaining
	 */	
	public function setOffset($offsetValue = NULL){
		$this->offset = ($offsetValue) ? $offsetValue : $this->getOffset();
		return $this;
	}

	/**
	 * @return int - The limit (the number of rows to be displayed)
	 */	
	public function getLimit(){
		$param = $this->options['limitParam'];
		$paramValue = ($this->options['method'] == 'GET') ? (int) $_GET[$param] : (int) $_POST[$param];
		if ((!empty($paramValue)) && is_int($paramValue)) {
			$this->currentLimit = $paramValue;
		} else {
			$this->currentLimit = ($this->currentLimit) ? $this->currentLimit : $this->options['limit'];
		}
		return $this->currentLimit;
	}

	/**
	 * Description - Returns the offset for either a page number if it exists else the offset parameter if it exists else 
	 *	the default offset 
	 * @return int - The offset (the row number at which the result set should begin)
	 */		
	public function getOffset(){
		$pageParam = ($this->options['method'] == 'GET') ? (int) $_GET[$this->getPageParam()] : (int) $_POST[$this->getPageParam()];
		if (($pageParam > 0) && ($pageParam <= $this->getNumPages())) { 
			$this->offset = ($pageParam * $this->getLimit()) - $this->getLimit();
		} 
		return $this->offset;
	}

	/**
	 * @return string - The limit parameter
	 */	
	public function getLimitParam(){
		return $this->options['limitParam'];
	}

	/**
	 * @return string - The page parameter
	 */		
	public function getPageParam(){
		return $this->options['pageParam'];
	}

	/**
	 * @return int - The number of records in the result set
	 */	
	public function getNumRecords(){
		return $this->numRecords;
	}
	
	/**
	 * @return int - The row number that begins the current page's result set
	 */	
	public function getCurrentStartRow(){
		return $this->getOffset() + 1;
	}

	/**
	 * @return int - The record row number that ends the current page's result set
	 */	
	public function getCurrentEndRow(){
		$endRow = $this->getOffset() + $this->getLimit();
		if ($endRow > $this->getNumRecords()){
			$endRow = $this->getNumRecords();
		}
		return $endRow;
	}

	/**
	* @return int - The number of pages in the result set
	*/	
	public function getNumPages(){
		$limit = ($this->currentLimit) ? $this->currentLimit : $this->options['limit'];
		$this->numPages = ($this->numRecords != 0) ? ceil($this->numRecords / $limit) : 1;
		return $this->numPages;
	}

	/**
	 * @return int - The current page number
	 */	
	public function getCurrentPageNum(){
		$limit = ($this->currentLimit) ? $this->currentLimit : $this->options['limit'];	
		$this->currentPageNum = ($this->offset != 0) ? floor($this->offset / $limit) + 1 : 1;
		return $this->currentPageNum;
	}

	/**
	 * @param int $addNumber (optional) - The number to add to the current page number, defaults to 1
	 * @return int - The next page number if it exists, FALSE otherwise
	 */	
	public function getNextPageNum($addNumber = 1){
		$next = $this->getCurrentPageNum() + $addNumber;
		return ($next <= $this->numPages) ? $next : FALSE;
	}

	/**
	 * @param int $subtractNumber (optional) - The number to subtract from the current page number, defaults to 1
	 * @return int - The previous page number if it exists, FALSE otherwise
	 */	
	public function getPreviousPageNum($subtractNumber = 1){
		$previous = $this->getCurrentPageNum() - $subtractNumber;
		return ($previous >= 1) ? $previous : FALSE;
	}

	/**
	 * @param int $addNumber (optional) - The number to add to the current page number, defaults to 1
	 * @return string - The next page URL if it exists, FALSE otherwise
	 */		
	public function getNextPageUrl($addNumber = 1){
		$nextPage = $this->getCurrentPageNum() + $addNumber;
		if ($nextPage <= $this->numPages){
			$queryString = $this->baseUrl.$this->options['pageParam'].'='.$nextPage.$this->getUrlParams('page');
			return htmlentities($queryString, NULL, NULL, FALSE);
		} else {
			return FALSE;
		}
	}

	/**
	 * @param int $subtractNumber (optional) - The number to subtract from the current page number, defaults to 1
	 * @return string - The previous page URL if it exists, FALSE otherwise
	 */		
	public function getPreviousPageUrl($subtractNumber = 1){
		$previousPage = $this->getCurrentPageNum() - $subtractNumber;
		if ($previousPage >= 1){
			$queryString = $this->baseUrl.$this->options['pageParam'].'='.$previousPage.$this->getUrlParams('page');	
			return htmlentities($queryString, NULL, NULL, FALSE);
		} else {
			return FALSE;
		}
	}

	/**
	 * @return string - The first page URL
	 */		
	public function getFirstPageUrl(){
		$queryString = $this->baseUrl.$this->options['pageParam'].'=1'.$this->getUrlParams('page');
		return htmlentities($queryString, NULL, NULL, FALSE);
	}

	/**
	 * @return string - The last page URL
	 */		
	public function getLastPageUrl(){
		$queryString = $this->baseUrl.$this->options['pageParam'].'='.$this->getNumPages().$this->getUrlParams('page');
		return htmlentities($queryString, NULL, NULL, FALSE);
	}

	/**
	 * @return string - The base URL
	 */
	public function getBaseUrl(){
		return htmlentities($this->baseUrl, NULL, NULL, FALSE);
	}
	
	/**
	 * @return array - Each of the base params as $param=>$value
	 */
	public function getBaseParams(){
		return $this->baseParams;
	}
}
?>