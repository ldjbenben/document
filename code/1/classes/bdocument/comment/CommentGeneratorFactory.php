<?php

namespace bdocument\comment;

use bdocument\comment\php\PhpCommentGenerator;
class CommentGeneratorFactory
{
	const PHP = 'php';
	
	/**
	 * @param string $language
	 * @param array $config
	 * @return bdocument\CommentGenerator
	 */
	public static function create($language, $config = array())
	{
		$generator = null;
	
		switch ($language)
		{
			case 'php':
				$generator = new PhpCommentGenerator();
				break;
		}
	
		if($generator != null)
		{
			foreach ($config as $key=>$value)
			{
				$generator->$key = $value;
			}
		}
	
		return $generator;
	}
}