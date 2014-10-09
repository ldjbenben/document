<?php

namespace sframe\base;

use sframe\SFrame;
class Application extends Object
{
	protected $_objects;
	
	public function loadObject($className)
	{
		if(!isset($this->_objects[$className]))
		{
			$this->_objects[$className] = SFrame::createObject($className);
		}
		
		return $this->_objects[$className];
	}
	
}