<?php

namespace bdocument\comment;

use sframe\base\Object;
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
		echo '<pre>';print_r($this->_classMap);
	}
	
	private function listDirs($where)
	{
		$itemHandler=opendir($where);
		$i=0;
		while(($item=readdir($itemHandler)) !== false)
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
		$handle = fopen($filepath, "r");
		$namespace = '';
		$classname = '';
		$context = '';
		$inBlockComment = false;
		$comments = array();
		$unknownComments = array();
		$unknownCommentsLineNo = 0;
		$lineNo = 0;
		
		while(($line = fgets($handle)) !== false)
		{
			$line = preg_replace("/( )+/", " ", trim($line));
			$isClass = false;
			$words = explode(' ', $line, 3);
			
			if('namespace' == $words[0])
			{
				$namespace = rtrim($words[1], ';');
			}
			elseif('class' == $words[0])
			{
				$isClass = true;
				$classname = $words[1];
			}
			elseif('abstract' == $words[0] && isset($words[1]) && 'class' == trim($words[1]))
			{
				$isClass = true;
				$classname = $words[2];
			}
			elseif('interface' == $words[0])
			{
				$isClass = true;
				$classname = $words[1];
			}
			elseif('/**' == $words[0])
			{
				$inBlockComment = true;
				$unknownComments = array();
				if(empty($context))
				{
					$context = 'file_comment';
					$comments[$context][] = $line;
				}
				else
				{
					$unknownComments[] = $line;
				}
			}
			elseif ($inBlockComment && '*' == $words[0])
			{
				if($context == 'file_comment')
				{
					$comments[$context][] = $line;
				}
				else
				{
					$unknownComments[] = $line;
				}
			}
			elseif('*/' == $words[0])
			{
				$inBlockComment = false;
				$comments[$context][] = $line;
				if($context == 'file_comment')
				{
					$comments[$context][] = $line;
				}
				else
				{
					$unknownComments[] = $line;
					$unknownCommentsLineNo = $lineNo;
				}
			}
			elseif($context == 'in_class' && ('public'==$words[0] || 'protect'==$words[0] || 'private'==$words[0]))
			{
				echo '<h1>line:'.$lineNo.':'.$line.'</h1>';
				if($words[1] == 'function') // is method of class
				{
					
				}
				elseif($words[1] == 'static')
				{
					
				}
				elseif($words[1][0] == '$')
				{
					$arr = explode('=', $words[1]);
					$propertyName = trim($arr[0],' ;');
					$comments[$classname]['properties'][$propertyName]['comments'] = $unknownComments;
					$comments[$classname]['properties'][$propertyName]['access'] = $words[0];
				}
				$unknownComments = array();
			}
			
			if($isClass)
			{
				$context = 'in_class';
				if(!empty($namespace))
					$classname = $namespace.'\\'.$classname;
				if(!isset($this->_classMap[$classname]))
				{
					$this->_classMap[$classname] = $filepath;
				}
				
				$comments[$classname]['class_comments'] = $unknownComments;
			}
			
			$lineNo++;
		}
		
		fclose($handle);
		echo '<pre>comments:';print_r($comments).'<br />';
	}
	
}