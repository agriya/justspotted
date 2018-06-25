<?php /* SVN: $Id: $ */ ?>
<div class="reviewRatingTypes form">
<?php echo $this->Form->create('ReviewRatingType', array('class' => 'normal'));?>
	<fieldset>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('is_active', array('label' => __l('Active?'), 'type' => 'checkbox'));
	?>
	</fieldset>
	<div class="submit-block clearfix">
<?php echo $this->Form->Submit(__l('Add'));?>
</div>
<?php echo $this->Form->end();?>
</div>