<?php
	$passed = array();
	if(!empty($sighting_id)) {
		$passed['sighting_id'] = $sighting_id;
	}
	if(!empty($user)) {
		$passed['user'] = $user;
	}
	echo $this->requestAction(array_merge(array('controller' => 'reviews', 'action' => 'index'), $passed), array('return'));
?>	