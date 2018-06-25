<?php /* SVN: $Id: $ */ ?>
<div class="reviewComments form">
<?php echo $this->Form->create('ReviewComment', array('class' => 'normal'));?>
	<fieldset>
		<legend><?php echo $this->Html->link(__l('Review Comments'), array('action' => 'index'));?> &raquo; <?php echo __l('Admin Edit Review Comment');?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('review_id');
		echo $this->Form->input('user_id');
		echo $this->Form->input('comment');
		echo $this->Form->input('ip_id');
	?>
	</fieldset>
	<div class="submit-block clearfix">
<?php echo $this->Form->Submit(__l('Update'));?>
</div>
<?php echo $this->Form->end();?>
</div>