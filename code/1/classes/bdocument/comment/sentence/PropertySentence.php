<?php

namespace bdocument\comment\sentence;

class PropertySentence extends Sentence
{
	private $_endKeyword = ';';
	private $_rules = array(
		'/(private|protected|public)[ ]*(static)?[ ]*\$([\w]+)[ ]*=?[ ]*(.*);/',
		'static[ ]*(private|protected|public)[ ]*\$([\w]+)[ ]*=?[ ]*(.*);/',
	);
	private $_access = array('private', 'protected', 'public');
	private $_formatId;
	
	public function scan($line)
	{
		$this->_text .= trim($line);
		$this->_text = str_replace("\r\n", ' ', $this->_text);
		$this->_text = str_replace("\n", ' ', $this->_text);
		$this->_text = preg_replace('/( )+/', ' ', $this->_text);
		
		if(($this->_status = $this->format1($this->_text)) != self::FAIL)
		{
// 			$this->_status = $this->format2($this->_text);
		}
		else
		{
			$this->reset();
		}
		
		if ($this->_status == self::SCANING)
		{
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
		
		if($this->_status != self::SCANING)
		{
			$this->reset();
		}
		
		return $this->_status;
	}
	
	private function format1($line)
	{
		$word = $this->getFirstWord($line);
		if(in_array($word, $this->_access))
		{
			$lessLine = trim(substr($line, strlen($word)));
			if (empty($lessLine)) 
			{
				return self::SCANING;
			}
			elseif($lessLine[0] == '$')
			{
				return self::SCANING;
			}
			elseif(strpos($lessLine, 'static ') == 0) 
			{
				$lessLine = substr($lessLine, strlen('static '));
				if(empty($lessLine) || $lessLine[0] == '$')
				{
					return self::SCANING;
				}
			}
			$this->_formatId = 1;
		}
		
		return self::FAIL;
	}
		
	public function doScan()
	{
		$this->_text = str_replace("\r\n", ' ', $this->_text);
		$this->_text = str_replace("\n", ' ', $this->_text);
		$this->_text = preg_replace('/( )+/', ' ', $this->_text);
		
		$matches = array();
		$index = -1;
		
		foreach ($this->_rules as $i=>$pattern)
		{
			if(preg_match($pattern, $this->_text, $matches) == 1)
			{
				echo '<pre>matches:';print_r($matches);
				$index = $i;
				break;
			}
		}
		echo '<h1>'.$this->_text.'</h1>';
		if($index == 0)
		{
			$this->_owner->addPropery($matches[3], $matches[1], $matches[2], $matches[4]);
		}
	}
}