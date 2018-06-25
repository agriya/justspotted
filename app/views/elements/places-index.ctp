<?php
	$passed = array();
	if(!empty($type)) {
		$passed['type'] = $type;
	}
	if(!empty($business_slug)) {
		$passed['business'] = $business_slug;
	}
	if(!empty($view)) {
		$passed['view'] = $view;
	}
	if(!empty($from)) {
		$passed['from'] = $from;
	}
	echo $this->requestAction(array_merge(array('controller' => 'places', 'action' => 'index'), $passed), array('return'));
?>