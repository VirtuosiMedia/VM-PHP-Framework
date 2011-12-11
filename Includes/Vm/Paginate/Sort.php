<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A data set sorting class that extends Vm_Paginate
 * @extends Vm\Paginate
 * @namespace Vm\Paginate
 */
namespace Vm\Paginate;

class Sort extends \Vm\Paginate {

	protected $currentSortColumn = NULL;
	protected $currentSortStyle = NULL;		
	protected $sortColumn = NULL;
	
	/**
	 * @param string $sortColumn - The column by which the data should initially be sorted
	 * @param array optional $options - The options array, which also inherits the options from Vm_Paginate
	 */
	function __construct($sortColumn, $options = array()){
		$this->sortColumn = $sortColumn;
		
		$defaultOptions = array(
			'sortStyle' => 'ASC',		//string - How the column should be sorted DESC or ASC, defaults to ASC
			'sortColumnParam' => 'c', 	//string - the URL parameter or field name that indicates the sort column value
			'sortStyleParam' => 's' 	//string - the URL parameter or field name that indicates the sort style value					
		);

		parent::__construct();
		$this->setOptions($options, $defaultOptions);
		
		$this->update();
	}

	protected function update(){
		$this->setOffset();
		$this->setSortColumn();
		$this->setSortStyle();
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
		if ($excludeParam != 'page') {
			$queryString .= '&'.$this->options['pageParam'].'='.$this->getCurrentPageNum();
		}
		if ($excludeParam != 'toggleSort'){
			if ($this->getSortColumn() != $this->sortColumn){
				$queryString .= '&'.$this->options['sortColumnParam'].'='.$this->getSortColumn();
			}
			if ($this->getSortStyle() != $this->options['sortStyle']){
				$queryString .= '&'.$this->options['sortStyleParam'].'='.$this->getSortStyle();
			}		
		}
		return $queryString;
	}
	
	/**
	 * @param string $sortColumnValue optional - The column by which the data should be sorted, else it automatically searches for it
	 * @return - The object, for chaining	
	 */
	public function setSortColumn($sortColumnValue = NULL){
		if ($sortColumnValue){
			$this->currentSortColumn = $sortColumnValue;
		} else {
			$sortColumnParam = $this->options['sortColumnParam'];
			$sortColumnValue = ($this->options['method'] == 'GET') ? $_GET[$sortColumnParam] : $_POST[$sortColumnParam];
			$this->currentSortColumn = (!empty($sortColumnValue)) ? $sortColumnValue : $this->sortColumn;
		}
		return $this;
	}

	/**
	 * @param string $sortStyleValue optional - The sort style, ASC or DESC, else it automatically searches for it
	 * @return - The object, for chaining	
	 */	
	public function setSortStyle($sortStyleValue = NULL){
		$this->currentSortStyle = ($sortStyleValue) ? $sortStyleValue : $this->getSortStyle();
		return $this;
	}
	
	/**
	 * @return string - The sort column name	
	 */	
	public function getSortColumn(){
		return $this->currentSortColumn;
	}

	/**
	 * @return string - The sort style: ASC or DESC	
	 */		
	public function getSortStyle(){
		$param = $this->options['sortStyleParam'];
		$paramValue = ($this->options['method'] == 'GET') ? $_GET[$param] : $_POST[$param];
		return (!empty($paramValue)) ? $paramValue : $this->options['sortStyle'];
	}

	/**
	 * @return string - The sort column parameter
	 */		
	public function getSortColumnParam(){
		return $this->options['sortColumnParam'];
	}

	/**
	 * @return string - The sort style parameter
	 */			
	public function getSortStyleParam(){
		return $this->options['sortStyleParam'];
	}

	/**
	 * @return string - ASC or DESC depending on what the current setting is
	 */	
	public function toggleSortStyle(){
		return ($this->currentSortStyle == 'ASC') ? 'DESC' : 'ASC';
	}

	/**
	 * @param string $sortColumn - The column name by which the result set should be sorted
	 * @return string - The sorted URL that is sorted the opposite of the current sort style
	 */	
	public function getToggleSortUrl($sortColumn){
		$queryString = $this->baseUrl;
		$queryString .= $this->options['sortColumnParam'].'='.$sortColumn;
		$queryString .= '&'.$this->options['sortStyleParam'].'='.$this->toggleSortStyle();
		$queryString .= $this->getUrlParams('toggleSort');
		return htmlentities($queryString, NULL, NULL, FALSE);	
	}
	
	/**
	 * @param string $sortColumn - The column name by which the result set should be sorted
	 * @return string - A class to indicate if the column is currently being used to sort the result set and how clicking would sort it
	 *	Example:
	 *		thisUp - This column is the current sort column and is sorted in ASC order
	 *		thisDown - This column is the current sort column and is sorted in DESC order
	 *		nullUp - This column is NOT the current sort column and clicking on it will sort in ASC order
	 *		nullDown - This column is NOT the current sort column and clicking on it will sort in DESC order
	 */	
	public function getToggleSortClass($sortColumn){
		$sortClass = ($sortColumn == $this->getSortColumn()) ? 'this' : 'null';
		$sortDir = ($sortClass == 'this') ? $this->getSortStyle() : $this->toggleSortStyle();
		$sortDir = ($sortDir == 'ASC') ? 'Up' : 'Down';
		return $sortClass.$sortDir;	
	}	
}