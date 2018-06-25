<?php /* SVN: $Id: $ */ ?>
<div class="guideCategories form">
<?php echo $this->Form->create('GuideCategory', array('class' => 'normal'));?>
	<fieldset>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('description');
	?>
	</fieldset>
<div class="submit-block clearfix">
    <?php echo $this->Form->Submit(__l('Update'));?>
</div>
	<?php echo $this->Form->end();?>
</div>