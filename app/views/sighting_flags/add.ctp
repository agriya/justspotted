<?php /* SVN: $Id: $ */ ?>
<div class="sightingFlags form">
    <?php echo $this->Form->create('SightingFlag', array('class' => 'normal'));?>
	<h3><?php echo __l('Add Sighting Flag');?></h3>
	<?php
		echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => $this->Auth->user('id')));
		echo $this->Form->input('sighting_id', array('type' => 'hidden'));
		echo $this->Form->input('sighting_flag_category_id', array('label' => 'Category'));
		echo $this->Form->input('message');
	?>
    <div class="submit-block clearfix">
        <?php echo $this->Form->Submit(__l('Add'));?>
    </div>
    <?php echo $this->Form->end();?>
</div>