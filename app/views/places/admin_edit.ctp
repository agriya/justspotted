<?php /* SVN: $Id: $ */ ?>
<div class="places form">
<?php echo $this->Form->create('Place', array('class' => 'normal'));?>
	<fieldset>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('place_type_id');
		echo $this->Form->input('name');
		echo $this->Form->input('address');
		echo $this->Form->input('city_id');
		echo $this->Form->input('state_id');
		echo $this->Form->input('country_id');
		echo $this->Form->input('zip_code');
		echo $this->Form->input('latitude');
		echo $this->Form->input('longitude');
		echo $this->Form->input('zoom_level');		
	?>
	</fieldset>
	<div class="submit-block clearfix">
<?php echo $this->Form->Submit(__l('Update'));?>
</div>
<?php echo $this->Form->end();?>
</div>