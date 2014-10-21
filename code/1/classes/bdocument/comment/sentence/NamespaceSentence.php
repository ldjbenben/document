<?php

namespace bdocument\comment\sentence;

class NamespaceSentence extends Sentence
{
	public function scan($line)
	{
		$keywords = 'namespace ';
		if(($pos = strpos($line, $keywords)) === 0)
		{
			$this->_owner->namespace = rtrim(trim(substr($line, strlen($keywords))), ';');
			return self::DONE;
		}
	}
}