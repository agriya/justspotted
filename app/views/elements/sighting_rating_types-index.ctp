<?php
	$passed = array();
	if(isset($view)) {
		$passed['view'] = $view;
	}
	if(isset($sighting_id)) {
		$passed['sighting_id'] = $sighting_id;
	}
	echo $this->requestAction(array_merge(array('controller' => 'sighting_rating_types', 'action' => 'index'), $passed), array('return'));
?>