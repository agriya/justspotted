<?php
	echo $this->requestAction(array('controller' => 'sighting_rating_types', 'action' => 'menu', 'user' => $user, 'type' => $type, 'current' => $current), array('return'));
?>