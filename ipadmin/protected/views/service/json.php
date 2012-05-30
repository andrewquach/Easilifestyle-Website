<?php 
$data = array();
foreach ($model as $idx => $m) {
	$item = array();
	$attributes = $m->getAttributes();
	
	// format asset links
	if (!empty($assets)) {
		foreach ($assets as $f => $t) {
			$attributes[$f] = sprintf(Yii::app()->params['siteurl']."/assets/".$t, $attributes[$f]);
		}
	}
	
	// format dates
	if (!empty($dates)) {
		foreach ($dates as $f) {
			$attributes[$f] = date('Y-m-d', $attributes[$f]);
		}
	}
	
	// field list
	if (!empty($select) && $select != '*') {
		foreach ($attributes as $k => $v) {
			if (in_array($k, $select)) {
				$item[str_replace(".", "_", $k)] = $v;
			}
		}
	}
	else {
		$item = $attributes;
	}
	
	// related
	if (!empty($related)) {
		foreach ($related as $f) {
			if (strpos($f, '.') !== false) {
				$f1 = substr($f, 0, strpos($f, '.'));
				$f2 = substr($f, strpos($f, '.') + 1);
				$r = $m->getRelated($f1);
				$item[str_replace(".", "_", $f)] = $r->$f2 == null ? '' : $r->$f2;
			}
			else {
				$r = $m->getRelated($f);
				$item[$f] = $r == null ? '' : $r;
			}
		}
	}
	$data[] = $item;
}
echo json_encode($data);
?>