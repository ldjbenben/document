<?php

namespace sframe\base;

class Application extends Object
{
	protected $_objects;
	
	public function loadObject($className)
	{
		if(!isset($this->_objects[$className]))
		{
			$this->_objects[$className] = call_user_func_array(array('sframe\SFrame', 'createObject')
					, func_get_args());
		}
		
		return $this->_objects[$className];
	}
	
}