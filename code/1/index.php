<?php
use bdocument\comment\CommentGeneratorFactory;
define('APPLICATION_PATH', dirname(__FILE__));
define('CLASS_PATH', APPLICATION_PATH.DIRECTORY_SEPARATOR.'classes');

require CLASS_PATH.DIRECTORY_SEPARATOR.'sframe'.DIRECTORY_SEPARATOR.'SFrameBase.php';
require CLASS_PATH.'\\tmp\\CApplication.php';

/* use bdocument\reflection\ReflectionClass;
use bdocument\reflection\ReflectionDocComment;

$reflectionClass = new ReflectionClass('tmp\\CApplication');
$reflectionDocComment = new ReflectionDocComment();

echo $reflectionDocComment->analyze($reflectionClass->getDocComment()); */
$commentGenerator = CommentGeneratorFactory::create(CommentGeneratorFactory::PHP, array(
	'src'=>'G:/kuaipan_web/workspaces/document',
	'target'=>'',
));

$commentGenerator->generate();