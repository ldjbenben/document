<?php

namespace bdocument\comment\sentence;

class CommentSentence extends Sentence
{
	private $_keyword = '/**';
	private $_endKeyword = '*/';
	
	public function scan($line)
	{
		$this->_text .= $line;
		
		if($this->_status == self::FIRST && ($pos = strpos($line, $this->_keyword)) === 0)
		{
			$this->_status = self::SCANING;
		}
		
		if ($this->_status == self::SCANING)
		{
			if($line[0] != '*')
			{
				$this->_status = self::FAIL;
			}
			else
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
		}
		
		if($this->_status != self::SCANING)
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
		/* $this->_text = str_replace("\r\n", ' ', $this->_text);
		$this->_text = str_replace("\n", ' ', $this->_text);
		$this->_text = preg_replace('/( )+/', ' ', $this->_text); */
		
		$lessLine = substr($this->_text, strlen($this->_keyword));
		echo '<h3>'.$this->_text.'</h3>';
		$this->_owner->addDocComment($this->_text);
	}
}