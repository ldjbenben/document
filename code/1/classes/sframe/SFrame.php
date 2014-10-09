<?php

namespace sframe;

require 'base/Object.php';
use sframe\base\Object;
use sframe\base\Exception;
use sframe\base\Application;

defined('CLASS_PATH') ? CLASS_PATH : define('CLASS_PATH', APPLICATION_PATH.DIRECTORY_SEPARATOR.'classes');

class SFrame extends Object
{
	public static $_app = null;
	
	public static function app()
	{
		return self::$_app;
	}
	
	public static function createApplication()
	{
		self::$_app = new Application();
	}
	
	public static function autoload($className)
	{
		$class_file = CLASS_PATH.DIRECTORY_SEPARATOR.str_replace('\\', '/', $className).'.php';
		
		if(file_exists($class_file))
		{
			require_once $class_file;
		}
		
		return class_exists($className) || interface_exists($className); 
	}
	
	public static function createObject($config)
	{
		if(is_string($config))
		{
			$type=$config;
			$config=array();
		}
		elseif(isset($config['class']))
		{
			$type=$config['class'];
			unset($config['class']);
		}
		else
		{
			throw new Exception('Object configuration must be an array containing a "class" element.');
		}
		
		$object = null;
		
		if(($n=func_num_args())>1)
		{
			$args=func_get_args();
			if($n===2)
			{
				$object=new $type($args[1]);
			}
			elseif($n===3)
			{
				$object=new $type($args[1],$args[2]);
			}
			elseif($n===4)
			{
				$object=new $type($args[1],$args[2],$args[3]);
			}
			else
			{
				unset($args[0]);
				$class=new \ReflectionClass($type);
				// Note: ReflectionClass::newInstanceArgs() is available for PHP 5.1.3+
				$object=$class->newInstanceArgs($args);
			}
		}
		else
		{
			$object = new $type();
		}
		
		return $object;
	}
	
}

spl_autoload_register(array('sframe\\SFrame','autoload'));