<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Vm_Mixer is an abstract class that simulates multiple inheritance using mixins. It allows you to extend 
 * 		the functionality of multiple classes and manage any conflicts between them, should they arise.
 */
abstract class Vm_Mixer {
	
	protected $methods = array();
	protected $mixins = array();
	protected $priorities = array();
	
	/**
	 * @description By adding a mixin, the extending class will automatically adopt all of a mixin's public methods.
	 * @param object $mixin - The instantiated object that's methods should be adopted by the extending class.
	 * @security You are expected to know if there will be a conflict in which two mixins share the same method name. If 
	 * 		this conflict occurs, use the <a href="#setPriorities">setPriorities()</a> method to specify which method 
	 * 		should take precedence.	Vm_Mixer will not handle the conflict automatically on its own. 
	 */
	protected function addMixin($mixin){
		if (!is_object($mixin)){
			throw new Vm_Mixer_Exception("The mixin is not valid because it is not an object.");
		}
		
		$name = get_class($mixin);
		$this->mixins[$name] = $mixin;
		$methods = get_class_methods($name);
		$this->methods[$name] = $methods;
	}

	/**
	 * @description Allows multiple mixins to be added at once. By adding a mixin, the extending class will 
	 * 		automatically adopt all of a mixin's public methods.
	 * @param array $mixin - An array of instantiated objects whose methods should be adopted by the extending class.
	 * @security You are expected to know if there will be a conflict in which two mixins share the same method name. If 
	 * 		this conflict occurs, use the <a href="#setPriorities">setPriorities()</a> method to specify which method 
	 * 		should take precedence.	Vm_Mixer will not handle the conflict automatically on its own. 
	 */
	protected function addMixins(array $mixins){
		foreach ($mixins as $mixin){
			$this->addMixin($mixin);
		}
	}	

	/**
	 * @description Removes all references to a mixin class from the extending object.
	 * @param string $mixin - The name of the mixin class
	 * @note Use this method with care as it dynamically removing mixins could cause your program to function in 
	 * 		unexpected ways.
	 */
	protected function removeMixin($mixin){
		unset($this->mixins[$mixin]);
		unset($this->methods[$mixin]);
		foreach ($this->priorities as $method=>$className){
			if ($mixin == $className){
				unset($this->priorities[$method]);
			}
		}
	}	

	/**
	 * @description Removes all references to multiple mixin classes from the extending object.
	 * @param array $mixin - An array of mixin class names
	 * @note Use this method with care as it dynamically removing mixins could cause your program to function in 
	 * 		unexpected ways.
	 */
	protected function removeMixins(array $mixins){
		foreach ($mixins as $mixin){
			$this->removeMixin($mixin);
		}
	}	
	
	/**
	 * @description Gets the class's current mixins by name
	 * @return An array of mixin class names.
	 */
	public function getMixins(){
		return array_keys($this->methods);
	}

	/**
	 * @description Disables methods from a specific mixin class. 
	 * @param string $class - The name of the mixin class.
	 * @param array $methods - An array of method names within the class to remove.
	 * @note Use this method with care as it dynamically removing methods could cause your program to function in 
	 * 		unexpected ways.
	 */
	protected function disableMethods($class, array $methods){
		if (isset($this->methods[$class])){
			$this->methods[$class] = array_diff($this->methods[$class], $methods);
		} else {
			throw new Vm_Mixer_Exception("$class is not a valid mixin.");
		}
	}
	
	/**
	 * @description Enables methods from a specific mixin class that have been disabled. 
	 * @param string $class - The name of the mixin class.
	 * @param array $methods - An array of method names within the class to enable.
	 */
	protected function enableMethods($class, array $methods){
		if (isset($this->methods[$class])){
			$classMethods = get_class_methods($class);
			foreach ($methods as $method){
				if ((in_array($method, $classMethods))&&(!in_array($method, $this->methods[$class]))){
					$this->methods[$class][] = $method;
				} else if (!in_array($method, $classMethods)){
					throw new Vm_Mixer_Exception("$class does not have a method named '$method'.");
				}
			}
		} else {
			throw new Vm_Mixer_Exception("$class is not a valid mixin.");
		}
	}

	/**
	 * @description Gets the current mixin methods.
	 * @return An array with the mixin class names as keys, their methods in an array as a value.
	 * @note Only mixin class methods will be returned. Any methods belonging to Vm_Mixer or the extending class will
	 * 		not be shown. 
	 */
	public function getMethods(){
		return $this->methods;
	}
	
	/**
	 * @description Manages conflicts for the mixins.
	 * @param array $priorities - The method name as the key, the class name that has priority in a conflict as 
	 * 		the value.
	 * @note Once a method has been assigned to a class, it cannot be reassigned to a different class at a later point. 
	 * 		This is done to minimize potential bugs due to dynamic prioritization.
	 */
	protected function setPriorities(array $priorities){
		$classNames = array_keys($this->methods);
		$setPriorities = array_keys($this->priorities);
		foreach ($priorities as $method=>$class){
			if (!in_array($class, $classNames)){
				throw new Vm_Mixer_Exception("$class is not a valid mixin. To make $class a mixin, use the addMixin 
					method.");
			}
			if (!in_array($method, $this->methods[$class])){
				throw new Vm_Mixer_Exception("$class does not have a method named '$method'.");
			}
			if (in_array($method, $setPriorities)){
				$assigned = $this->priorities[$method];
				throw new Vm_Mixer_Exception("$method has already been assigned to $assigned and cannot be 
					reassigned.");
			}
		}
		$this->priorities = $priorities;
	}

	/**
	 * @description Gets the current method/class priorities.
	 * @return An array with the method names as keys, their classes as values.
	 */	
	public function getPriorities(){
		return $this->priorities;
	}
	
	/**
	 * @description A magic method that calls the mixin methods automatically. This method should not be 
	 * 		called directly.
	 * @param string $methodName - The name of the mixin method
	 * @param array $arguments - The arguments for the method
	 * @return The return value will vary depending on the function called.
	 */
	public function __call($methodName, array $arguments){
		foreach ($this->methods as $className=>$methods){
			if (in_array($methodName, $methods)){
				if (
					(in_array($methodName, array_keys($this->priorities))) &&
					($className == $this->priorities[$methodName])
				){
					return call_user_func_array(array($className, $methodName), $arguments);
				} else if (!in_array($methodName, array_keys($this->priorities))){
					return call_user_func_array(array($className, $methodName), $arguments);
				}
			} 
		}
		$mixins = (sizeof($this->methods) > 0) ? implode(', ', array_keys($this->methods)) : 'No mixins are listed.';
		throw new Vm_Mixer_Exception("$methodName is not a method. Your current mixins are: $mixins");
	}
}