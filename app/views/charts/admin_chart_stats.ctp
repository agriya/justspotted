<?php echo $this->element('chart-admin_chart_review', array('cache' => array('config' => 'site_element_cache_2_days'))); ?>
<?php echo $this->element('chart-admin_chart_sighting', array('cache' => array('config' => 'site_element_cache_2_days'))); ?>
<?php echo $this->element('chart-admin_chart_business', array('cache' => array('config' => 'site_element_cache_2_days'))); ?>
<?php echo $this->element('chart-admin_chart_item', array('cache' => array('config' => 'site_element_cache_2_days'))); ?>
<?php echo $this->element('chart-admin_chart_place', array('cache' => array('config' => 'site_element_cache_2_days'))); ?>
<?php echo $this->element('chart-admin_chart_guide', array('cache' => array('config' => 'site_element_cache_2_days'))); ?>
<?php echo $this->element('chart-admin_chart_users', array('user_type_id'=> ConstUserTypes::User, 'cache' => array('key' => 'user'.ConstUserTypes::User, 'config' => 'site_element_cache_2_days'))); ?>
<?php echo $this->element('chart-admin_chart_user_logins', array('user_type_id'=> ConstUserTypes::User, 'cache' => array('key' => 'user'.ConstUserTypes::User, 'config' => 'site_element_cache_2_days'))); ?>
