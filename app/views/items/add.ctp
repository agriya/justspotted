<?php /* SVN: $Id: $ */ ?>
<div class="items form">
<?php echo $this->Form->create('Item', array('class' => 'normal'));?>
	<fieldset>
	<?php if(!$this->Auth->user('user_type_id') == ConstUserTypes::Admin): ?>
		<legend><?php echo $this->Html->link(__l('Items'), array('action' => 'index'));?> &raquo; <?php echo __l('Add Item');?></legend>
	<?php endif;
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