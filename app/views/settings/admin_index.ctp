<ul class="setting-links   clearfix">
<?php
	foreach ($setting_categories as $setting_category):
?>	<li class="grid_12 omega alpha">
        <?php if($setting_category['SettingCategory']['parent_id'] == 0) {?>
		<div class="setting-details-info setting-category-<?php echo str_replace(',','',$setting_category['SettingCategory']['name']); ?>">
    	<h3><?php echo $this->Html->link($this->Html->cText($setting_category['SettingCategory']['name'], false), array('controller' => 'settings', 'action' => 'edit', $setting_category['SettingCategory']['id']), array('title' => $setting_category['SettingCategory']['name'], 'escape' => false)); ?></h3>
       <div class="js-truncate">
           <?php echo $setting_category['SettingCategory']['description'];
		?>
        </div>
		<?php } ?>
	</li>
<?php
	endforeach;
?>
</ul>