<?php
require_once (__DIR__.'/func.php');
require_once (__DIR__.'/sqlite.php');

$path = __DIR__.'/../docs/i/';

$json_save_w = function($filename, $ids) use ($path) {
	$dir = $path.substr($filename, 0, 2);
	is_dir($dir) || mkdir($dir, 0755, true);
	file_put_contents("{$dir}/{$filename}.json", 
		json_encode(['w'=>$filename, 'i'=> array_map('intval',explode(',', $ids)) ])
	);
};

$db->createFunction('w_json_save', $json_save_w);
$db->exec("drop index if exists idx_wordy_w; create index idx_wordy_w on wordy_index (w);");
//$db->exec('select w_json_save(w, group_concat(id)) from wordy_index group by w order by random() limit 1000;');
$db->exec('select w_json_save(w, group_concat(id)) from wordy_index group by w order by w;');


$json_save_t = function($id,$t,$n) use ($path) {
	$dir = $path.substr((string)$id, 0, 3);
	is_dir($dir) || mkdir($dir, 0755, true);
	file_put_contents("{$dir}/{$id}.json", 
		json_encode(['i'=>(int)$id, 't'=>$t, 'n'=>$n ],
			JSON_INVALID_UTF8_IGNORE | JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK  
		)
	);
};

$db->createFunction('t_json_save', $json_save_t);
$db->exec("drop index if exists idx_title_id; create index idx_title_id on title_index (id);");
//$db->exec('select w_json_save(w, group_concat(id)) from wordy_index group by w order by random() limit 1000;');
$db->exec('select t_json_save(id, t, n) from title_index order by id;');

