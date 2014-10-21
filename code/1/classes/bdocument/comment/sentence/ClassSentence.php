<?php

namespace bdocument\comment\sentence;

class ClassSentence extends Sentence
{
	private $_classname;
	private $_extends;
	private $_implements = array();
	private $_keyword = 'class ';
	private $_endKeyword = '}';
	
	public function scan($line)
	{
		if($this->_status == self::FIRST && ($pos = strpos($line, $this->_keyword)) === 0)
		{
			$this->_status = self::SCANING;
		}
		
		if ($this->_status == self::SCANING)
		{
			$this->_text .= $line;
			if(($pos = strpos($line, '{')) === false)
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
		$this->_classname = '';
		$this->_extends = '';
		$this->_implements = '';
	}
	
	public function doScan()
	{
		$keyword2 = 'extends ';
		$keyword3 = 'implements';
		
		$this->_text = str_replace("\r\n", ' ', $this->_text);
		$this->_text = str_replace("\n", ' ', $this->_text);
		$this->_text = preg_replace('/( )+/', ' ', $this->_text);
		
		$lessLine = substr($this->_text, strlen($this->_keyword));
		$this->_classname = $this->getFirstWord($lessLine);
		$lessLine = trim(substr($lessLine, strlen($this->_classname)));
		
		if(($pos = strpos($lessLine, $keyword2)) === 0)
		{
			$lessLine = substr($lessLine, strlen($keyword2));
			$this->_extends = rtrim($this->getFirstWord($lessLine),' {');
			$lessLine = trim(substr($lessLine, strlen($this->_extends)));
		}
		
		if(($pos = strpos($lessLine, $keyword3)) === 0)
		{
			$lessLine = str_replace(' ', '', rtrim(substr($lessLine, strlen($keyword3)), '{'));
			$this->_implements = explode(',', $lessLine);
		}
			
		$this->_owner->addClass($this->_classname, $this->_extends, $this->_implements);
	}
}