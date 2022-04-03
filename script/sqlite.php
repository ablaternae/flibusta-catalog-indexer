<?php
require (__DIR__.'/func.php);

$db = new SQLite3('catalog.sqlite');

$db->createFunction('lower', 'mb_strtolower');
$db->createFunction('upper', 'mb_strtoupper');
$db->createFunction('metaphone', 'metaphone');
$db->createFunction('translit', 'translit');

