<?php
require_once (__DIR__.'/func.php');
require_once (__DIR__.'/sqlite.php');

/**
 * schema 
 *  ID 
 *  TITLE 
 *  NAMES surname+' '+name {, author_N surname name}
 * 
 */

$db->exec('drop table if exists title_index; create table title_index (id integer UNIQUE, t varchar, n varchar);');
$db->exec("insert into title_index (id, t, n) select c.id, c.title, (select GROUP_CONCAT(surname||' '||name,', ') where id = c.id) from catalog as c group by c.id order by c.id ;");
//$db->exec("drop index if exists idx_mf_id; create index idx_mf_id on mf_index (id);");
//$db->exec("drop index if exists idx_ctlg_id; create index idx_ctlg_id on catalog (id);");

