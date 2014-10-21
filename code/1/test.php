<?php
$str = 'public $_classname = "dd";';
$pattern = '/(private|protected|public)[ ]*(static)?[ ]*\$([\w]+)[ ]*=?[ ]*(.*);/';

$matches = array();

preg_match($pattern, $str, $matches);
echo '<pre>';print_r($matches);