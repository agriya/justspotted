<?php /* SVN: $Id: $ */ ?>
<div class="sightingFlags form">
<?php echo $this->Form->create('SightingFlag', array('class' => 'normal'));?>
	<fieldset>
		<legend><?php echo $this->Html->link(__l('Sighting Flags'), array('action' => 'index'));?> &raquo; <?php echo __l('Admin Add Sighting Flag');?></legend>
	<?php
		echo $this->Form->input('user_id');
		echo $this->Form->input('sighting_id');
		echo $this->Form->input('sighting_flag_category_id');
		echo $this->Form->input('message');
		echo $this->Form->input('ip_id');
	?>
	</fieldset>
<div class="submit-block clearfix">
<?php echo $this->Form->Submit(__l('Add'));?>
</div>
<?php echo $this->Form->end();?>
</div>