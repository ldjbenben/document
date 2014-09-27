<?php

namespace bdocument\parser;

use bdocument\Object;

class BaseParser extends Object implements IParser
{
	/**
	 * @var string ��ǩ����
	 */
	protected $_desc;
	
	public function parse($text)
	{
		$this->_desc = $text;
	}
	
	public function getDesc()
	{
		return $this->_desc;
	}
}