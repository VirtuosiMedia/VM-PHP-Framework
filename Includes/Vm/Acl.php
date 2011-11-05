<?php
/**
 * @author Virtuosi Media Inc.
 * @license: MIT License
 * @description: An access control resource/consumer class
 * @requirements: PHP 5.2 or higher
 * @extends Vm_Klass
 */
class Vm_Acl extends Vm_Klass {
 
    protected $id = NULL;
	protected $permissionsList = array();
    protected $resourceList = array();
	
    /**
     * @param mixed $id - The object id
     */
    function __construct($id){
		$this->id = $id;
    }

    /**
     * @return mixed - The object id
     */ 
    public function getId(){
        return $this->id;
    }

    /**
	 * Sets the list of resources ids that a potential consumer object can consume and the id passed in to 
	 * 	the constructor should be on the list in order for the consumer object to access it
     * @param array $resourceList - An array of resources accessible to the consumer
     */
    public function setResourceList(array $resourceList){
        $this->resourceList = $resourceList;
    }
 
    /**
	 * Gets the list of resources a potential consumer object can consume
     * @return array $resourceList - An array of resource ids accessible to the consumer
     */
    public function getResourceList(){
        return $this->resourceList;
    }

    /**
	 * Sets a list of resource ids the current object has permission to consume (or access)
     * @param array $permissionsList - An array of resource ids to which a consumer Acl object has access
     */
    public function setPermissions(array $permissionsList){
        $this->permissionsList = $permissionsList;
    }
 
    /**
	 * Gets a list of resource ids the object can consume
     * @return array $permissionsList - An array of resource ids to which a consumer Acl object has access
     */
    public function getPermissions(){
        return $this->permissionsList;
    }
	
    /**
     * @return boolean - TRUE if the consumer object has access to consume the current object's resources, FALSE otherwise
     */ 
    public function accessPermitted(){
        return (in_array($this->id, $this->resourceList)) ? TRUE : FALSE;
    }

    /**
     * Determines if the consumer object has access to the passed in resource
     * @param mixed $resource - The resource to check
  	 * @return boolean - TRUE if the consumer object has access to consume the given object's resources, FALSE otherwise
     */
    public function canAccess($resource){
         return (in_array($resource, $this->permissionsList)) ? TRUE : FALSE;   	
    }
}