<?php

namespace bdocument\comment;

use sframe\base\Object;
use bdocument\comment\sentence\SentenceScaner;
use sframe\SFrame;
abstract class CommentGenerator extends Object
{
	/**
	 * @var string source files directory
	 */
	protected $_src;
	/**
	 * @var string target files diectory
	 */
	protected $_target;
	
	protected $_classMap = array();
	protected $_scaner = null;
	
	public function __construct()
	{
		$this->_scaner = SFrame::app()->loadObject('bdocument\\comment\\sentence\\SentenceScaner');
	}
	
	public function setSrc($value)
	{
		$this->_src = $value;
	}
	
	public function setTarget($value)
	{
		$this->_target = $value;
	}
	
	public function generate()
	{
		$this->listDirs($this->_src);
	}
	
	private function listDirs($where)
	{
		$itemHandler=opendir($where);
		$i=0;
		while($itemHandler && ($item=readdir($itemHandler)) !== false)
		{
			if($item != "." && $item != "..")
			{
				$filepath = $where.DIRECTORY_SEPARATOR.$item;
				if(is_dir($filepath))
				{
					$this->listDirs($filepath);
				}
				else
				{
					$this->findFile($where.DIRECTORY_SEPARATOR.$item);
				}
			}
		}
	}
	
	private function findFile($filepath)
	{
		if(substr($filepath, strrpos($filepath, '.')) == '.php')
		{
			$scaner = new SentenceScaner();
			$data = $this->_scaner->scan($filepath);
			echo '<pre>';print_r($data);
		}
		return;
	}
	
}