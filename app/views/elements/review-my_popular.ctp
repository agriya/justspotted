<?php
	echo $this->requestAction(array('controller' => 'reviews', 'action' => 'index', 'view' => 'my_popular', 'user' => $user), array('return'));
?>