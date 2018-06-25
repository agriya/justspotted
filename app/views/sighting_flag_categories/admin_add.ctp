<?php /* SVN: $Id: $ */ ?>
<div class="sightingFlagCategories form">
<?php echo $this->Form->create('SightingFlagCategory', array('class' => 'normal'));?>
	<fieldset>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('is_active', array('type' => 'checkbox', 'label' => __l('Active?')));
	?>
	</fieldset>
	<div class="submit-block clearfix">
<?php echo $this->Form->Submit(__l('Add'));?>
</div>
<?php echo $this->Form->end();?>
</div>