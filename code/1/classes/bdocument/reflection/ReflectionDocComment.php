<?php

namespace bdocument\reflection;

use bdocument\Object;
use bdocument\Base;
class ReflectionDocComment extends Object
{
	const COMMENT_START = '/**';
	const COMMENT_END = '*/';
	const COMMENT_LINE_START = ' * ';
	
	/**
	 * @var array 必须在开头出现的标签
	 */
	private $_atStartTags = array(
		'property'=>'\\benben\\document\\parser\\PropertyParser',
		'param'=>'\\benben\\document\\parser\\ParamParser',
		'author'=>'\\benben\\document\\parser\\BaseParser',
		'since'=>'\\benben\\document\\parser\\BaseParser',
		'version'=>'\\benben\\document\\parser\\BaseParser',
		'license'=>'\\benben\\document\\parser\\BaseParser',
		'access'=>'\\benben\\document\\parser\\BaseParser',
		'deprecated'=>'\\benben\\document\\parser\\BaseParser',
		'since'=>'\\benben\\document\\parser\\BaseParser',
	);
	
	/**
	 * @var array 可以出现在任意地方的标签
	 */
	private $_freeTags = array(
		'link'
	);
	
	private $_comment;
	
	
	public function analyze($comment = '')
	{
		$lines = explode("\n", $comment);

		// 去除注释块的开头和结尾行
		unset($lines[count($lines)-1]);
		unset($lines[0]);
		
		$tags = array();
		$index = 0;
		foreach ($lines as $line)
		{
			$line = trim($line);
			if(strlen($line) <= strlen(self::COMMENT_LINE_START))
			{
				continue;
			}
			
			$line = substr($line, strlen(self::COMMENT_LINE_START)-1);
			$tag = substr($line, 0, strpos($line, ' '));
			
			if(!empty($tag) && $tag[0] == '@')
			{
				++$index;
				$tags[$index] = array(
						'name'=>ucfirst(substr($tag, 1)), 
						'text'=>substr($line, strlen($tag))
				);
			}
			else if(array_key_exists($index, $tags))
			{
				$tags[$index]['text'] .= $line;
			}
		}
		
		foreach ($tags as $item)
		{
			if(array_key_exists($item['name'], $this->_atStartTags))
			{
				$parser = Base::app()->loadObject($this->_atStartTags[$item['name']]);
				$parser->parse($item['text']);
			}
		}
	}
	
}