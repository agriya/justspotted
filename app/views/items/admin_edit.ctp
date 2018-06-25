<?php /* SVN: $Id: $ */ ?>
<div class="items form">
<?php echo $this->Form->create('Item', array('class' => 'normal'));?>
	<fieldset>
		<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
	?>
	</fieldset>
	<div class="submit-block clearfix">
<?php echo $this->Form->Submit(__l('Update'));?>
</div>
<?php echo $this->Form->end();?>
</div>