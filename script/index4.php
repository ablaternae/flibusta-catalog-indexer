<?php
require_once (__DIR__.'/func.php');
require_once (__DIR__.'/sqlite.php');

const SEP = '|';
const STRLEN_MIN = 2;
//const FH_SQL = fopen('wordy_index.sql', 'a+');

//$fh=fopen('wordy_index.sql', 'a+');

fputs(fopen('wordy_index.csv', 'w'), 'w,id'.PHP_EOL);
$fh=fopen('wordy_index.csv', 'a+');

$word_upset = function (int $id, string $string) use ($db, $fh) {
	$a = [];
	foreach ($r=explode(SEP, $string) as $s) {
		//$a[] = metaphone( translit($s) );
		
		$a = array_merge($a,
			array_unique(
				array_filter( 
					array_map( function($e) { return mb_strlen($e)>STRLEN_MIN ? metaphone($e) : null;}
					//	оставить только слова и цифры
					, preg_split("/[^\w\d]+/im", translit( $s )) )
				)
			, SORT_STRING)

		);
	}
	
	$sql='';
	foreach ($a as $w) {
		fputs($fh, "\"{$w}\",{$id}".PHP_EOL);
		//$sql .= "insert into wordy_index (w, id) values ('{$w}', {$id});".PHP_EOL;
	}
	//fputs($fh, $sql);
	//$db->exec($sql);
	
	//return $sql;
};


//	тестовый блок на готовой таблице
//$db->exec('drop table if exists wordy_index; create table wordy_index (w varchar, id integer);');
//$db->createFunction('word_upset', $word_upset);
//$db->exec('select word_upset(id, w) from wordy_single;');
//exit();
// sqlite.exe catalog.sqlite -csv -separator "," ".import wordy_index.csv wordy_index"

/**
 * все слова
 * wordy
 *  w  
 *  sum
 * 
 * wordy_index 
 *  w строка поиска
 *  i id 
 */

//$db->exec('drop table if exists wordy; create table wordy (word text UNIQUE, sum integer DEFAULT 1);');
//$db->exec("insert into wordy (word) select upper(c.surname) from catalog as c where c.surname>'' order by c.id ON CONFLICT(w) DO UPDATE SET sum=sum+1;");
//$db->exec("insert into wordy (word) select upper(c.name) from catalog as c where c.name>'' order by c.id ON CONFLICT(w) DO UPDATE SET sum=sum+1;");
//$db->exec("insert into wordy (word) select upper(c.title) from catalog as c where c.title>'' order by c.id ON CONFLICT(w) DO UPDATE SET sum=sum+1;");
//$db->exec("insert into wordy (word) select upper(c.series) from catalog as c where c.series>'' order by c.id ON CONFLICT(w) DO UPDATE SET sum=sum+1;");

$db->exec('drop table if exists wordy_single; create table wordy_single (w text, id integer);');
$db->exec("insert into wordy_single (id, w) select c.id, upper(c.surname) from catalog as c where c.surname>'' order by c.id;");
$db->exec("insert into wordy_single (id, w) select c.id, upper(c.name) from catalog as c where c.name>'' order by c.id;");
$db->exec("insert into wordy_single (id, w) select c.id, upper(c.title) from catalog as c where c.title>'' order by c.id;");
$db->exec("insert into wordy_single (id, w) select c.id, upper(c.subtitle) from catalog as c where c.subtitle>'' order by c.id;");
$db->exec("insert into wordy_single (id, w) select c.id, upper(c.series) from catalog as c where c.series>'' order by c.id;");

//$db->exec("create index idx_wrd_sngl on wordy_single (w);");
//$db->exec("create index idx_wrd_sngl on wordy_single (strlen(w));");

$db->exec('drop table if exists wordy_index; create table wordy_index (w varchar, id integer);');
$db->createFunction('word_upset', $word_upset);
$db->exec('select word_upset(id, w) from wordy_single;');
// sqlite.exe catalog.sqlite -csv -separator "," ".import wordy_index.csv wordy_index"


