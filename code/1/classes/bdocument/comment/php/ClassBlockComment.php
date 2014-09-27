<?php

namespace bdocument\comment\php;

use bdocument\comment\BlockComment;

class ClassBlockComment extends BlockComment
{
	private $_desc;
	
	public function parse($text) 
	{
		$step = 0;
		$index = 0;
		
		foreach ($this->_lines as $i=>$line)
		{
			if(!empty(trim($line)))
			{
				$this->_classInfo->desc[$index] .= $line;
			}
			else
			{
				$index++;
			}
		}
		
		
	}
}