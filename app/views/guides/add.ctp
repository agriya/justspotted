<?php /* SVN: $Id: $ */ ?>
<div class="guides form">
<?php echo $this->Form->create('Guide', array('class' => 'normal', 'enctype' => 'multipart/form-data'));?>
<h2><?php echo $this->pageTitle; ?></h2>
	<fieldset>
		<?php if(!$this->Auth->user('user_type_id') == ConstUserTypes::Admin): ?>
			<legend><?php echo $this->Html->link(__l('Guides'), array('action' => 'index'));?> &raquo; <?php echo __l('Create a Guide');?></legend>
		<?php endif; ?>
<?php		echo $this->Form->input('guide_category_id',array('empty' => __l('Please Select'), 'label' => __l('Category')));		
		if($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
			echo $this->Form->input('user_id');
		} else {
			echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => $this->Auth->user('id')));
		}	
		echo $this->Form->input('name');
		echo $this->Form->input('tagline');
	?>
	<div class="mapblock-info">
		<?php echo $this->Form->autocomplete('City.name', array('label' => __l('City'), 'acFieldKey' => 'City.id', 'acFields' => array('City.name'), 'acSearchFieldNames' => array('City.name'), 'maxlength' => '255')); ?>
		<div class="autocompleteblock">            
        </div>
	</div>  
	<?php
		echo $this->Form->input('description');
		echo $this->Form->input('no_of_max_sightings',array('label' => __l('No of Max Sightings'), 'info' => __l('Maximum number of sightings can be added.')));
		echo $this->Form->input('is_anyone_add_additional_sightings_to_this_guide', array('label' => __l('Anyone Add Additional Sightings to This Guide?')));
		echo $this->Form->input('is_published', array('label' => __l('Is Published?')));
		echo $this->Form->input('Attachment.filename', array('type' => 'file','size' => '33', 'label' => 'Share a food (or drink!) you recommend','class' =>'browse-field'));
		if($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
			echo $this->Form->input('is_featured');
		}
	?>
	</fieldset>
	<div class="submit-block clearfix">
<?php echo $this->Form->Submit(__l('Add'));?>
</div>
<?php echo $this->Form->end();?>
</div>