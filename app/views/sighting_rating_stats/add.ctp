<?php /* SVN: $Id: $ */ ?>
<div class="sightingRatingStats form">
<?php echo $this->Form->create('SightingRatingStat', array('class' => 'normal'));?>
	<fieldset>
		<legend><?php echo $this->Html->link(__l('Sighting Rating Stats'), array('action' => 'index'));?> &raquo; <?php echo __l('Add Sighting Rating Stat');?></legend>
	<?php
		echo $this->Form->input('sighting_id');
		echo $this->Form->input('sighting_rating_type_id');
		echo $this->Form->input('count');
		echo $this->Form->input('User');
	?>
	</fieldset>
<?php echo $this->Form->end(__l('Add'));?>
</div>