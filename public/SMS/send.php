<?php

$text = 'Hi, This is a test message.';
$number = '09267629048';

echo 'echo '.$text.' | gnokii --sendsms '.$number;
$a  = shell_exec('echo '.$text.' | gnokii --sendsms '.$number);
var_dump($a);

 ?>