<?php

namespace sframe\base;

use sframe\SFrameBase;
class Application extends Object
{
	protected $_objects;
	
	public function loadObject($className)
	{
		if(!isset($this->_objects[$className]))
		{
			$this->_objects[$className] = SFrameBase::createObject($className);
		}
		
		return $this->_objects[$className];
	}
	
}