<?php
	$passed = array();
	if(!empty($type)) {
		$passed['type'] = $type;
	}
	if(!empty($guide_id)) {
		$passed['guide_id'] = $guide_id;
	}
	echo $this->requestAction(array_merge(array('controller' => 'users', 'action' => 'index', 'view' => $view), $passed), array('return'));
?>