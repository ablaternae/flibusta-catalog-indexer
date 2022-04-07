<?php
require_once (__DIR__.'/func.php');
require_once (__DIR__.'/sqlite.php');

$db->exec('drop table if exists mf_index; create table mf_index (id integer, x text);');
//$db->exec('insert into mf_index ( id, x ) select c.id, cast( (
//	select GROUP_CONCAT( gr.wholename ) from catalog as gr where gr.id = c.id
//) as text ) from catalog as c group by c.id order by c.id;');

$db->exec("insert into mf_index (id, x) select c.id, title||','||(select GROUP_CONCAT(surname) where id = c.id)||','||(select GROUP_CONCAT(wholename) where id = c.id) from catalog as c group by c.id order by c.id ;");

$mf_index = function (int $id) use ($db) :string {
	print_r($id);
	$a = [];
	$res = $db->query("select * from catalog where id={$id}");
	//var_dump("select * from catalog where id={$id}");
	while ($r = $res->fetchArray(SQLITE3_ASSOC)) {
	//var_dump($r);
		if (empty($a)) {
			//$tok = array_filter(
			//	array_unique(
			//		explode(' ', $r['title'])+explode(' ', $r['subtitle'])
			//		, SORT_STRING
			//	)
			//	, function($e){return mb_strlen($e)>2;}
			//);	
			$a[] = metaphone(translit($r['title'])); 
			$tok = array_unique(explode(' ', $r['title'])+explode(' ', $r['subtitle']), SORT_STRING);
			//	меньше функций быстрее считает
			if (count($tok)>1) {
				foreach ($tok as $t) {
					if (mb_strlen($t)>2 && $e=metaphone(translit($t)) ) {
						if ( strlen($e)>1 ) $a[] = $e;
					}
				} 
			}
		}	
		
		$a[] = metaphone(translit($r['surname'])); 
		$a[] = metaphone(translit($r['wholename'])); 
		$a[] = metaphone(translit($r['name']));
		
	}
	//var_dump(implode(',', $a));
	return implode(',', $a);
};

$db->createFunction('mf_index', $mf_index);


//$db->exec('drop table if exists mf_index; create table mf_index (id integer, x text);');
$db->exec('insert into mf_index ( id, x ) select c.id, cast( mf_index(c.id) as text ) from catalog as c group by c.id order by c.id;');

//$db->exec('insert into mf_index ( id ) select c.id from catalog as c group by c.id order by c.id;');
//$db->exec('insert into mf_index ( id ) select distinct c.id from catalog as c order by c.id;');
//group by sooo faster


//$db->exec("UPDATE mf_index SET x = cast( mf_index(id) as text );");
