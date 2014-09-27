<?php

namespace bdocument\parser;

class PropertyParser extends BaseParser 
{
	/**
	 * @var string ������������
	 */
	private $_type;
	
	public function parse($text) 
	{
		$this->_type = substr($text, 0, strpos($text, ' '));
		$this->_desc = substr($text, ltrim(strlen($this->_type)+1));
	}
	
	public function getType()
	{
		return $this->_type;
	}
}