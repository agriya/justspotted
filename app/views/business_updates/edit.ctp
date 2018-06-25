<?php /* SVN: $Id: $ */ ?>
<div class="businessUpdates form">
<div class="side1 grid_16 alpha omega">
<?php echo $this->Form->create('BusinessUpdate', array('class' => 'normal'));?>
	<fieldset>
<?php if($this->Auth->user('user_type_id') != ConstUserTypes::Admin) { ?>
		<legend> <?php echo __l('Edit Business Update');?></legend>
<?php
	}
		echo $this->Form->input('id');
?>
<?php		
		echo $this->Form->input('updates');
?>
<div class="mapblock-info">	
<?php        
		echo $this->Form->autocomplete('Item.name', array('label' => __l('Item'), 'acFieldKey' => 'Item.id', 'acFields' => array('Item.name'), 'acSearchFieldNames' => array('Item.name'), 'maxlength' => '255'));		
?>
		<div class="autocompleteblock">            
        </div>
</div> 
</fieldset>
    <div class="submit-block clearfix">
        <?php echo $this->Form->submit(__l('Update'));?>
    </div>
    <?php echo $this->Form->end();?>
</div>
<div class="side2 grid_8 alpha omega">
<?php if($this->Auth->user('user_type_id') != ConstUserTypes::Admin) { ?>
<h3> <?php echo __l('Other Businesses'); ?></h3>
	<?php echo $this->element('business_updates-business', array('type' => 'own','from' => $this->request->data['BusinessUpdate']['id'], 'config' => 'site_element_cache_2_min'));
?>
<?php } ?>
</div>
</div>