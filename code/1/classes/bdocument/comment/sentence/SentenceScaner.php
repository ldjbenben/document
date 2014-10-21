<?php

namespace bdocument\comment\sentence;

use sframe\SFrame;
use sframe\base\Object;

class SentenceScaner extends Object
{
	private $_data = array();
	private $_use = array();
	public $inClass = false;
	private $_currentClass;
	private $_docComment;
	
	/**
	 * @var string 当前正在分析中的文件,文件绝对路径名.
	 */
	private $_file;
	
	private static $_sentences = array(
				'bdocument\\comment\\sentence\\NamespaceSentence',
				'bdocument\\comment\\sentence\\ClassSentence',
				'bdocument\\comment\\sentence\\UseSentence',
				'bdocument\\comment\\sentence\\PropertySentence',
				'bdocument\\comment\\sentence\\CommentSentence',
				/*'bdocument\\comment\\sentence\\InterfaceSentence',
				'bdocument\\comment\\sentence\\PropertySentence',
				'bdocument\\comment\\sentence\\MethodSentence', */
	);
	
	public function getData()
	{
		return $this->_data;
	}
	
	public function setNamespace($namespace)
	{
		$this->_data['namespace'] = $namespace;
	}
	
	public function addClass($classname, $parent = '', $implements = array())
	{
		if(!empty($parent))
		{
			$parent = $this->getFullClassname($parent);
		}
		
		if(is_array($implements) && !empty($implements))
		{
			foreach ($implements as $key=>$item)
			{
				$implements[$key] = $this->getFullClassname($item);
			}
		}
		
		if(isset($this->_data['namespace']))
		{
			$classname = $this->_data['namespace'].'\\'.$classname;
		}
		
		$this->_currentClass = $classname;
		
		$this->_data[$classname] = array(
				'parent'=>$parent,
				'implements'=>$implements
		);
	}
	
	public function addPropery($name, $access, $static, $value)
	{
		$this->_data[$this->_currentClass]['properties'][$name] = array(
			'name'=>$name,
			'access'=>$access,
			'static'=>(boolean)$static,
			'value'=>$value
		);
	}
	
	public function addUse($fullClassname)
	{
		$this->_use[] = $fullClassname;
	}
	
	public function addDocComment($comment)
	{
		$this->_docComment = $comment;
	}
	
	public function scan($file)
	{
		$this->reset();
		
		$handle = fopen($file, "r");
		$over = true;
		$scaningList = array();
		$list = array();
		$this->_file = $file;
		
		while(($line = fgets($handle)) !== false)
		{
			if(empty($list))
			{
				$list = self::$_sentences;
			}
			
			foreach ($list as $i=>$component)
			{
				$obj = SFrame::app()->loadObject($component, $this);
				if($obj->scan($line) == Sentence::DONE)
				{
					break;
				}
			}
		}
		
		fclose($handle);
		return $this->_data;
	}
	
	private function getFullClassname($classname)
	{
		if(strpos($classname, '\\') === false)
		{
			foreach ($this->_use as $item)
			{
				if( strpos($item, $classname) == (strlen($item)-strlen($classname)) )
				{
					$classname = $item;
				}
			}
		}
	
		return $classname;
	}
	
	private function reset()
	{
		$this->_data = array();
		$this->inClass = false;
	}
}