<?php
require_once (__DIR__.'/func.php');

$db = new SQLite3('catalog.sqlite');

$db->createFunction('lower', 'mb_strtolower', 1, SQLITE3_DETERMINISTIC);
$db->createFunction('upper', 'mb_strtoupper', 1, SQLITE3_DETERMINISTIC);
$db->createFunction('strlen', 'mb_strlen', 1, SQLITE3_DETERMINISTIC);
//  $db->createFunction('ltrim', 'ltrim');
//  $db->createFunction('rtrim', 'rtrim');
//  $db->createFunction('trim', 'trim');
$db->createFunction('explode', 'explode');
$db->createFunction('implode', 'implode');

$db->createFunction('metaphone', 'metaphone', -1, SQLITE3_DETERMINISTIC);
$db->createFunction('translit', 'translit', 1, SQLITE3_DETERMINISTIC);
return;


//  CREATE TABLE catalog (surname varchar(255), name varchar(255), patronymic varchar(255), title text, subtitle text, language char(2), year int, series text, id integer);

$db->exec('CREATE INDEX idx_name ON catalog ( lower(name) ) WHERE name!="" OR name IS NOT NULL;');
$db->exec('CREATE INDEX idx_surname ON catalog ( lower(surname) ) WHERE surname!="" OR surname IS NOT NULL;');

$db->exec('ALTER TABLE catalog ADD COLUMN wholename VARCHAR(128);');
$db->exec('UPDATE catalog SET wholename = TRIM(surname||" "||name||" "||patronymic) ;');
$db->exec('CREATE INDEX idx_wholename ON catalog ( lower(wholename) ) ;');

$db->exec('CREATE INDEX idx_lang ON catalog (language) WHERE language!="" OR language IS NOT NULL;');
$db->exec('CREATE INDEX idx_title ON catalog ( lower(trim(title)) ) WHERE title!="" OR title IS NOT NULL;');
$db->exec('CREATE INDEX idx_year ON catalog ( year ) WHERE year IS NOT NULL;');
$db->exec('CREATE INDEX idx_series ON catalog ( lower(trim(series)) ) WHERE series!="" OR series IS NOT NULL;');

