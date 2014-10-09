<?php

namespace bdocument\comment\sentence;

use sframe\SFrame;
class SentenceScaner 
{
	public $inClass = false;
	public $namespace;
	
	private static $_sentences = array(
				'bdocument\\comment\\sentence\\NamespaceSentence',
				/* 'bdocument\\comment\\sentence\\ClassSentence',
				'bdocument\\comment\\sentence\\InterfaceSentence',
				'bdocument\\comment\\sentence\\PropertySentence',
				'bdocument\\comment\\sentence\\MethodSentence', */
	);
	
	
	
	public static function scan($file)
	{
		$handle = fopen($file, "r");
		$over = true;
		$scaningList = array();
		$list = array();
		
		while(($line = fgets($handle)) !== false)
		{
			foreach ($list as $i=>$component)
			{
				$obj = SFrame::app()->loadObject($component, $this);
				if($obj->scan($line) == Sentence::DONE)
				{
					break;
				}
			}
			/*
			if(empty($scaningList))
			{
				$list = self::$_sentences;
			}
			else
			{
				$list = $scaningList;
			}
			
			$line = preg_replace("/( )+/", " ", trim($line));

			foreach ($list as $i=>$classname)
			{
				$obj = SFrame::app()->loadObject($classname);
				if(($status = $obj->scan($line)) == Sentence::SCANING)
				{
					$scaningList[$i] = $obj;
				}
				elseif ($status == Sentence::OVER)
				{
					$obj->reset();
					unset($scaningList[$i]);
				}
			}
			*/
		}
		fclose($handle);
	}
}