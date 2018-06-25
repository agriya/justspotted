<?php
	$passed = array();
	if(!empty($place)):
		echo $this->requestAction(array('controller' => 'sightings', 'action' => 'index', 'place' => $place), array('return'));
	elseif(!empty($guide)):
		echo $this->requestAction(array('controller' => 'sightings', 'action' => 'index', 'guide' => $guide), array('return'));
	elseif(!empty($sighting_rating_type)):
		echo $this->requestAction(array('controller' => 'sightings', 'action' => 'index', 'sighting_rating_type' => $sighting_rating_type, 'user' => $user), array('return'));	
	elseif(!empty($business)):
		$passed['business'] = $business;
		echo $this->requestAction(array_merge(array('controller' => 'sightings', 'action' => 'index'), $passed), array('return'));	
	endif;
?>