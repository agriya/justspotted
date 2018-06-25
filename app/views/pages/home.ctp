<h2>Sweet, "App" got Baked by CakePHP!</h2>

<?php
if(Configure::read() > 0):
	Debugger::checkSessionKey();
endif;
?>
<p>
<?php
	if (is_writable(TMP)):
		echo '<span class="success">';
		echo __l('Your tmp directory is writable.');
		echo '</span>';
	else:
		echo '<span class="error">';
		echo __l('Your tmp directory is NOT writable.');
		echo '</span>';
	endif;
?>
</p>
<p>
<?php
	$settings = Cache::settings();
	if (!empty($settings)):
		echo '<span class="success">';
			echo sprintf(__l('The %s is being used for caching. To change the config edit APP/config/core.php ', true), '<em>'. $settings['engine'] . 'Engine</em>');
		echo '</span>';
	else:
		echo '<span class="error">';
			echo __l('Your cache is NOT working. Please check the settings in APP/config/core.php');
		echo '</span>';
	endif;
?>
</p>
<p>
<?php
	$filePresent = null;
	if (file_exists(CONFIGS . 'database.php')):
		echo '<span class="success">';
			echo __l('Your database configuration file is present.');
			$filePresent = true;
		echo '</span>';
	else:
		echo '<span class="error">';
			echo __l('Your database configuration file is NOT present.');
			echo '<br/>';
			echo __l('Rename config/database.php.default to config/database.php');
		echo '</span>';
	endif;
?>
</p>
<?php
if (!empty($filePresent)):
 	uses('model' . DS . 'connection_manager');
	$db = ConnectionManager::getInstance();
 	$connected = $db->getDataSource('default');
?>
<p>
<?php
	if ($connected->isConnected()):
		echo '<span class="success">';
 			echo __l('Cake is able to connect to the database.');
		echo '</span>';
	else:
		echo '<span class="error">';
			echo __l('Cake is NOT able to connect to the database.');
		echo '</span>';
	endif;
?>
</p>
<?php endif;?>
<h3><?php echo __l('Editing this Page') ?></h3>
<p>
<?php
	echo sprintf(__l('To change the content of this page, edit: %s
		To change its layout, edit: %s
		You can also add some CSS styles for your pages at: %s', true),
		APP . 'views' . DS . 'pages' . DS . 'home.ctp.<br />',  APP . 'views' . DS . 'layouts' . DS . 'default.ctp.<br />', APP . 'webroot' . DS . 'css');
?>
</p>
