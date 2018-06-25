<?php /* SVN: $Id: $ */ ?>
<div class="items form">
<?php echo $this->Form->create('Item', array('class' => 'normal'));?>
	<fieldset>
		<legend><?php echo $this->Html->link(__l('Items'), array('action' => 'index'));?> &raquo; <?php echo __l('Admin Add Item');?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('item_follower_count');
		echo $this->Form->input('sighting_count');
	?>
	</fieldset>
	<div class="submit-block clearfix">
<?php echo $this->Form->Submit(__l('Add'));?>
</div>
<?php echo $this->Form->end();?>
</div>