<?php /* SVN: $Id: $ */ ?>
<div class="sightingRatingTypes form">
<?php echo $this->Form->create('SightingRatingType', array('class' => 'normal'));?>
	<fieldset>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('filter_name');		
		echo $this->Form->input('is_active', array('label' => __l('Active?'),'type' => 'checkbox'));
		echo $this->Form->input('is_filtering_enabled', array('label' => __l('Filter Enabled?'), 'type' => 'checkbox'));
	?>
	</fieldset>
	<div class="submit-block clearfix">
<?php echo $this->Form->Submit(__l('Add'));?>
</div>
<?php echo $this->Form->end();?>
</div>