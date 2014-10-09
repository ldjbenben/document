<?php

namespace bdocument\comment\sentence;

class NamespaceSentence extends Sentence
{
	public function scan($line)
	{
		if(($pos = strpos($line, 'namespace ')) !== false)
		{
			$this->_owner->namespace = substr($line, $pos);
			return self::OK;
		}
		
	}
}