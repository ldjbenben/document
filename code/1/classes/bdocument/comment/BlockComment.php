<?php

namespace bdocument\comment;

use bdocument\ClassInfo;
abstract class BlockComment implements IComment
{
	/**
	 * @var ClassInfo
	 */
	protected $_classInfo;
	protected $_lines;
	
	public function __construct()
	{
		$this->_classInfo = new ClassInfo();
	}
	
	public function setLines($lines)
	{
		$this->_lines = $lines;
	}
}