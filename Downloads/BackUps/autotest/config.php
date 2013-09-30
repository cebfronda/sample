<?php

unset($CONFIG);
global $CONFIG;
$str = file_get_contents('config.js');
$CONFIG = json_decode($str, true);
?>

