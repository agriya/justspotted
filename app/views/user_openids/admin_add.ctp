<?php /* SVN: $Id: $ */ ?>
<div class="userOpenids form">
<?php echo $this->Form->create('UserOpenid', array('class' => 'normal'));?>
	<fieldset>
 		<legend><?php echo $this->Html->link(__l('User Openids'), array('action' => 'index'),array('title' => __l('User openids')));?> &raquo; <?php echo __l('Add User Openid');?></legend>
	<?php
		echo $this->Form->input('user_id');
		echo $this->Form->input('openid');
		echo $this->Form->input('verify',array('type' => 'checkbox'));
	?>
	</fieldset>
	<div class="submit-block clearfix">
<?php echo $this->Form->Submit(__l('Add'));?>
</div>
<?php echo $this->Form->end();?>
</div>
