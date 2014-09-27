<?php

namespace sframe\base;

class Object
{
	public function __get($key)
	{
		$method_name = 'get'.ucfirst($key);
		if(method_exists($this, $method_name))
		{
			return call_user_func(array($this, $method_name), $key);
		}
	}
	
	public function __set($key, $value)
	{
		$method_name = 'set'.ucfirst($key);
		if(method_exists($this, $method_name))
		{
			return call_user_func(array($this, $method_name), $value);
		}
	}
}