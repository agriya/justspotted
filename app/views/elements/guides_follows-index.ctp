<?php
	if(!empty($guide)):
		echo $this->requestAction(array('controller' => 'guide_followers', 'action' => 'index', 'follower' => $guide), array('key' => $guide, 'return'));	
	endif;
?>