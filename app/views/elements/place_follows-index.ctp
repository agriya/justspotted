<?php
	if(!empty($follower)) {
		echo $this->requestAction(array('controller' => 'place_followers', 'action' => 'index', 'follower' => $follower), array('return'));
	} else if (!empty($user)) {
		echo $this->requestAction(array('controller' => 'place_followers', 'action' => 'index', 'user' => $user), array('return'));
	}
?>