<?php
require (__DIR__.'/func.php);

$db = new SQLite3('catalog.sqlite');

$db->createFunction('lower', 'mb_strtolower');
$db->createFunction('upper', 'mb_strtoupper');
//  $db->createFunction('ltrim', 'ltrim');
//  $db->createFunction('rtrim', 'rtrim');
//  $db->createFunction('trim', 'trim');
$db->createFunction('explode', 'explode');
$db->createFunction('implode', 'implode');

$db->createFunction('metaphone', 'metaphone');
$db->createFunction('translit', 'translit');

//  CREATE TABLE catalog (surname varchar(255), name varchar(255), patronymic varchar(255), title text, subtitle text, language char(2), year int, series text, id integer);

$db->exec('CREATE INDEX idx_name ON catalog ( lower(name) ) WHERE name!="" OR name IS NOT NULL;');
$db->exec('CREATE INDEX idx_surname ON catalog ( lower(surname) ) WHERE surname!="" OR surname IS NOT NULL;');

$db->exec('ALTER TABLE catalog ADD COLUMN wholename VARCHAR(765);');
$db->exec('UPDATE wholename SET wholename = TRIM(surname||" "||name||" "||patronymic) FROM catalog;');
$db->exec('CREATE INDEX idx_wholename ON catalog ( lower(wholename) ) ;');

$db->exec('CREATE INDEX idx_lang ON catalog (language) WHERE language!="" OR language IS NOT NULL;');
$db->exec('CREATE INDEX idx_title ON catalog ( lower(trim(title)) ) WHERE title!="" OR title IS NOT NULL;');
$db->exec('CREATE INDEX idx_year ON catalog ( year ) WHERE year IS NOT NULL;');
$db->exec('CREATE INDEX idx_series ON catalog ( lower(trim(series)) ) WHERE series!="" OR series IS NOT NULL;');

