<?php
require (__DIR__.'/func.php');

$db = new SQLite3('catalog.sqlite');

$db->createFunction('lower', 'mb_strtolower', 1, SQLITE3_DETERMINISTIC);
$db->createFunction('upper', 'mb_strtoupper', 1, SQLITE3_DETERMINISTIC);

$db->createFunction('explode', 'explode');
$db->createFunction('implode', 'implode');

$db->createFunction('metaphone', 'metaphone', -1, SQLITE3_DETERMINISTIC);
$db->createFunction('translit', 'translit', 1, SQLITE3_DETERMINISTIC);

//  CREATE TABLE catalog (surname varchar(255), name varchar(255), patronymic varchar(255), title text, subtitle text, language char(2), year int, series text, id integer);

//$db->exec('CREATE INDEX idx_name ON catalog ( lower(name) ) WHERE name!="" OR name IS NOT NULL;');
//$db->exec('CREATE INDEX idx_surname ON catalog ( lower(surname) ) WHERE surname!="" OR surname IS NOT NULL;');
//$db->exec('CREATE INDEX idx_title ON catalog ( lower(title) ) WHERE title!="" OR title IS NOT NULL;');
//$db->exec('CREATE INDEX idx_lang ON catalog (language) WHERE language!="" OR language IS NOT NULL;');
//$db->exec('CREATE INDEX idx_year ON catalog (year) WHERE year IS NOT NULL;');
//$db->exec('CREATE INDEX idx_series ON catalog ( lower(series) ) WHERE series!="" OR series IS NOT NULL;');
$db->exec('CREATE INDEX idx_id ON catalog (id) WHERE id IS NOT NULL;');

$db->exec('ALTER TABLE catalog ADD COLUMN wholename VARCHAR(128);');
//$db->exec('ALTER TABLE catalog ADD COLUMN mtfn_wholename VARCHAR(64);');
//$db->exec('ALTER TABLE catalog ADD COLUMN mtfn_title VARCHAR(64);');

$db->exec('UPDATE catalog SET wholename = trim(surname||" "||name||" "||patronymic) ;');
//$db->exec('CREATE INDEX idx_wholename ON catalog ( lower(wholename) ) ;');

//$db->exec('UPDATE catalog SET mtfn_wholename = metaphone(translit(wholename)) ;');
//$db->exec('CREATE INDEX idx_mtfn_wholename ON catalog ( mtfn_wholename ) ;');
//
//$db->exec('UPDATE catalog SET mtfn_title = metaphone(translit(title)) ;');
//$db->exec('CREATE INDEX idx_mtfn_title ON catalog ( mtfn_title ) ;');


$db->exec('CREATE TABLE mtfn_index (id integer, x text);');
$db->exec('insert into mtfn_index ( id ) select catalog.id from catalog order by catalog.id;');

function mtfn_index($id) {
	global $db;
	$a = [];
	$r = $db->querySingle('select * from catalog where id='.$id, (bool)'entireRow');
	if (!empty($r)) {
		$a[] = metaphone(translit($r['wholename'])); 
		$a[] = metaphone(translit($r['title'])); 
		$a[] = metaphone(translit($r['surname'])); 
		$a[] = metaphone(translit($r['name']));
		//$tok = array_filter(
		//	array_unique(
		//		explode(' ', $r['title'])+explode(' ', $r['subtitle'])
		//		, SORT_STRING
		//	)
		//	, function($e){return mb_strlen($e)>2;}
		//);	
		$tok = array_unique(explode(' ', $r['title'])+explode(' ', $r['subtitle']), SORT_STRING);
		//	меньше функций быстрее считает
		if (count($tok)>1) {
			foreach ($tok as $t) {
				if (mb_strlen($t)>2 && $e=metaphone(translit($t)) && strlen($e)>1) {
					$a[] = $e;
				}
			} 
		}
	}
	return implode(',', $a);
}

$db->createFunction('mtfn_index', 'mtfn_index');

$db->exec('UPDATE mtfn_index SET x = mtfn_index1(id);');
