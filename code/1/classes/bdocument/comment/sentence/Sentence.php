<?php

namespace bdocument\comment\sentence;

abstract class Sentence 
{
	const FIRST = 1;	
	const SCANING = 2;
	const DONE = 3;
	const FAIL = 4;
	
	/**
	 * @var SentenceScaner
	 */
	protected $_owner = null;
	protected $_text;
	protected $_status = self::FIRST;
	
	public function __construct($owner)
	{
		$this->_owner = $owner;
	}
	
	public abstract function scan($line);
	
	protected function getFirstWord($line)
	{
		$word = '';
		
		if(($pos = strpos($line, ' ')) === false)
		{
			$word = $line;
		}
		else
		{
			$word = substr($line, 0, $pos);
		}
		
		return $word;
	}
	
	protected function reset()
	{
		$this->_text = null;
		$this->_status = self::FIRST;
	}
}