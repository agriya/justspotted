<?php
	$passed = array();
	if(!empty($type)) {
		$passed['type'] = $type;
	}
	if(!empty($username)) {
		$passed['user'] = $username;
	}
	if(!empty($view)) {
		$passed['view'] = $view;
	}
	if(!empty($following)) {
		$passed['following'] = $following;
	}
	if(!empty($filter)) {
		$passed['filter'] = $filter;
	}
	echo $this->requestAction(array_merge(array('controller' => 'guides', 'action' => 'index'), $passed), array('return'));
?>