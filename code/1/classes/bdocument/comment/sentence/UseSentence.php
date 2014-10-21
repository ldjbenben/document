<?php

namespace bdocument\comment\sentence;

class UseSentence extends Sentence
{
	private $_keyword = 'use ';
	private $_endKeyword = ';';
	private $_fullClassname;
	
	public function scan($line)
	{
		if($this->_status == self::FIRST && ($pos = strpos($line, $this->_keyword)) === 0)
		{
			$this->_status = self::SCANING;
		}
		
		if ($this->_status == self::SCANING)
		{
			$this->_text .= $line;
			if(($pos = strpos($line, $this->_endKeyword)) === false)
			{
				$this->_status = self::SCANING;
			}
			else
			{
				$this->doScan();
				$this->_status = self::DONE;
			}
		}
		
		if($this->_status == self::DONE)
		{
			$this->reset();
		}
		
		return $this->_status;
	}
	
	protected function reset()
	{
		parent::reset();
		$this->_status = self::FIRST;
		$this->_text = '';
	}
	
	public function doScan()
	{
		$this->_text = str_replace("\r\n", ' ', $this->_text);
		$this->_text = str_replace("\n", ' ', $this->_text);
		$this->_text = preg_replace('/( )+/', ' ', $this->_text);
		
		$lessLine = substr($this->_text, strlen($this->_keyword));
		$this->_fullClassname = rtrim($this->getFirstWord($lessLine),';');
		
		$this->_owner->addUse($this->_fullClassname);
	}
}