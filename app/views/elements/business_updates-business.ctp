<?php
	$passed = array();
	if(!empty($type)) {
		$passed['type'] = $type;
	}
	else if(!empty($business)) {
		$passed['business'] = $business;
	}
	else if(!empty($place)) {
		$passed['place'] = $place;
	}
	else if(!empty($user)) {
		$passed['user'] = $user;
	}
	if(!empty($from)) {
		$passed['from'] = $from;
	}
	echo $this->requestAction(array_merge(array('controller' => 'business_updates', 'action' => 'index', 'admin' => false), $passed), array('return'));
?>