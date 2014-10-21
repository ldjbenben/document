<?php

namespace bdocument\comment\sentence;

use bdocument\comment\entry\PropertyEntry;
class PropertySentence extends Sentence
{
	/**
	 * @var bdocument\comment\entry\PropertyEntry
	 */	
	protected $_property;
	
	public function __construct()
	{
		$this->_property = new PropertyEntry();
	}
	
	public function scan($line)
	{
		/*
		 public $name;
		 public static $name;
		 static public $name;
		 const HELLO;
		 */
		$this->format1($line);
	}
	
	public function reset()
	{
		$this->_step = 0;
	}
	
	/**
	 * 语法格式1
	 * private/protect/public $name;
	 */
	private function format1($line)
	{
		$firstWord = $this->getFirstWord($line);
		
		if($this->_step == 0 && in_array($firstWord, array('private', 'protect', 'public')))
		{
			
			$this->_property->access = $firstWord;
			$this->_step ++;
			$line = ltrim(substr($line, strlen($firstWord)));
			$firstWord = $this->getFirstWord($line);
		}
		else
		{
			return self::OVER;
		}
		
		if($this->_step == 1)
		{
			if (empty($line))
			{
				return self::SCANING;
			}
			
			if($line[0] == '$')
			{
				$semicolonPos = strpos($line, ';');
				$equalPos = strpos($line, '=');
				
				if($equalPos === false && $line[strlen($line)-1] == ';')
				{
					$this->_property->name = substr($line, 0, strlen($line)-1);
					return self::OVER;
				}
				
				if($equalPos !== false)
				{
					$value = trim(substr($line, $equalPos+1));
					$len = strlen($value);
					if('"'==$value[0] || "'"==$value[0])
					{
						if($value[$len-2] == $value[0] && $value[$len-2])
						for($i=1; $i<strlen($value); $i++)
						{
							if($value[$i] == $value[0] && $value[$i-1] != '\\')
							{
								// private $a = "helooo";
							}
						}
					}
				}
				
				if($semicolonPos > $equalPos)
				{
					$this->_property->name = rtrim(substr($line, 0, $equalPos));
				}
				elseif ($equalPos !== false)
				{
					$arr = explode('=', $line);
					$this->_property->name = rtrim($arr[0]);
					$this->_property->defaultValue = rtrim($arr[1]);
				}
				
				if($semicolonPos !== false)
				{
					return self::OVER;
				}
			}
		}
	}
	
	private function format2()
	{
	
	}
	
	private function format3()
	{
	
	}
	
	private function format4()
	{
	
	}
}