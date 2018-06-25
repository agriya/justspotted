<?php /* SVN: $Id: $ */ ?>
<div class="placeTypes form">
<?php echo $this->Form->create('PlaceType', array('class' => 'normal'));?>
	<fieldset>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('is_active');
	?>
	</fieldset>
	<div class="submit-block clearfix">
<?php echo $this->Form->Submit(__l('Update'));?>
</div>
<?php echo $this->Form->end();?>
</div>