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
	echo $this->requestAction(array_merge(array('controller' => 'businesses', 'action' => 'index'), $passed), array('return'));
?>