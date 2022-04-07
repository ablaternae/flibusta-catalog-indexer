<?php
require_once (__DIR__.'/func.php');
require_once (__DIR__.'/sqlite.php');

const SEP = ';';
$s=SEP;
$db->exec('drop table if exists mf_index; create table mf_index (id integer, n text, t text, x text);');
$db->exec("insert into mf_index (id, t, n, x) select c.id, title, (select GROUP_CONCAT(surname||' '||name,',') where id = c.id), title||'{$s}'||(select GROUP_CONCAT(surname,'{$s}') where id = c.id) from catalog as c group by c.id order by c.id ;");
$db->exec("create index idx_mf_id on mf_index (id);");
$db->exec("create index idx_id on catalog (id);");

$mf_index = function (string $string, string $sep=SEP) use ($db) : string {
	$a = [];
	foreach ($r=explode($sep, $string) as $s) {
		if (empty($a) && count($r)>1) {	//	first step 'title'
			$a[] = metaphone( translit($s) );
			$a = array_merge($a,
				array_unique(
					array_filter( 
						array_map( function($e) { return strlen($e)>2 ? metaphone($e) : null;}
						, preg_split("/[^\w\d]+/im", translit( $s )) )
					)
				, SORT_STRING)
 
			);
			
				//, array_map( function($e) { return strlen($e)>2 ? metaphone($e) : null;}
				//	, preg_split("/[^\w\d]+/im", translit( $s )) )
					
				//array_unique(
				//	array_filter( 
				//		array_map( function($e) { return strlen($e)>2 ? metaphone($e) : null;}
				//		, preg_split("/[^\w\d]+/im", translit( $s )) )
				//	)
				//, SORT_STRING);
			continue;
		}
		$a[] = metaphone(translit($s)); 
	}
	return implode(',', $a);
};

$db->createFunction('mf_index', $mf_index);

$db->exec("UPDATE mf_index SET x = cast( mf_index(x) as text );");
