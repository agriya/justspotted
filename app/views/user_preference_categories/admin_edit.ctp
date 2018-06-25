<?php /* SVN: $Id: admin_edit.ctp 1251 2011-04-28 05:34:11Z boopathi_026ac09 $ */ ?>
<div class="userPreferenceCategories form">
<?php echo $this->Form->create('UserPreferenceCategory', array('class' => 'normal'));?>
	<fieldset>
 		<legend><?php echo $this->Html->link(__l('User Preference Categories'), array('action' => 'index'));?> &raquo; <?php echo __l('Edit User Preference Category');?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('description');
	?>
	</fieldset>
	<div class="submit-block clearfix">
<?php echo $this->Form->Submit(__l('Update'));?>
</div>
<?php echo $this->Form->end();?>
</div>
