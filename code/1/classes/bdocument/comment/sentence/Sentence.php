<?php

namespace bdocument\comment\sentence;

abstract class Sentence 
{
	const SCANING = 1;
	const OVER = 2;
	const DONE = 3;
	protected $_line;
	protected $_step = 0;
	protected $_owner = null;
	
	public function __construct($owner)
	{
		$this->_owner = $owner;
	}
	
	public abstract function scan($line);
	
	protected function getFirstWord($line)
	{
		if(($pos = strpos($line, ' ')) === false)
		{
			return $line;
		}
		else
		{
			return substr($line, 0, $pos);
		}
	}
}