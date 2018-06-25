<?php /* SVN: $Id: admin_edit.ctp 1251 2011-04-28 05:34:11Z boopathi_026ac09 $ */ ?>
<div class="translations form">
<?php echo $this->Form->create('Translation', array('class' => 'normal'));?>
	<fieldset>
 		<legend><?php echo $this->Html->link(__l('Translations'), array('action' => 'index'),array('title' => __l('Translations')));?> &raquo; <?php echo __l('Edit Translation');?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('language_id');
		echo $this->Form->input('key');
		echo $this->Form->input('lang_text');
	?>
	</fieldset>
<?php echo $this->Form->end(__l('Update')); ?>
</div>