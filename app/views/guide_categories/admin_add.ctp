<?php /* SVN: $Id: $ */ ?>
<div class="guideCategories form">
<?php echo $this->Form->create('GuideCategory', array('class' => 'normal'));?>
	<fieldset>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('description', array('type' => 'textarea'));
	?>
	</fieldset>
<div class="submit-block clearfix">
    <?php echo $this->Form->Submit(__l('Add'));?>
</div>
	<?php echo $this->Form->end();?>
</div>