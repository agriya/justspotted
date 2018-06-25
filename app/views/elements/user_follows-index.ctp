<?php
if(!empty($following)) {
		echo $this->requestAction(array('controller' => 'user_followers', 'action' => 'index', 'following' => $following), array('return'));
    } else if(!empty($follower)) {
		echo $this->requestAction(array('controller' => 'user_followers', 'action' => 'index', 'follower' => $follower), array('return'));
    }
?>