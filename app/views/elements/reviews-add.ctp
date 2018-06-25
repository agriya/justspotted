<?php
	if($this->Auth->sessionValid()) {
		if(isset($sighting)) {
			echo $this->requestAction(array('controller' => 'reviews', 'action' => 'add', 'sighting_id' => $sighting), array('return'));
		} elseif(isset($guide)) {
			echo $this->requestAction(array('controller' => 'reviews', 'action' => 'add', 'guide' => $guide), array('return'));
		} elseif(isset($place)) {
			echo $this->requestAction(array('controller' => 'reviews', 'action' => 'add', 'place' => $place), array('return'));
		} else {
			echo $this->requestAction(array('controller' => 'reviews', 'action' => 'add'), array('return'));
		}
	}
?>