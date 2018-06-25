<?php /* SVN: $Id: $ */ ?>
<div class="reviewCategories form">
<?php echo $this->Form->create('ReviewCategory', array('class' => 'normal'));?>
	<fieldset>
		<legend><?php echo $this->Html->link(__l('Review Categories'), array('action' => 'index'));?> &raquo; <?php echo __l('Admin Add Review Category');?></legend>
	<?php
		echo $this->Form->input('title');
		echo $this->Form->input('review_count');
		echo $this->Form->input('is_active');
	?>
	</fieldset>
	<div class="submit-block clearfix">
<?php echo $this->Form->Submit(__l('Add'));?>
</div>
<?php echo $this->Form->end();?>
</div>