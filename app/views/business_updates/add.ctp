<?php /* SVN: $Id: $ */ ?>
<div class="businessUpdates form js-responses">
<?php echo $this->Form->create('BusinessUpdate', array('class' => 'normal  update-form js-ajax-form'));?>
    <div class="update-form-block">
    <?php
		if($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
			echo $this->Form->input('user_id');
			echo $this->Form->input('business_id');
		} else {
			echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => $this->Auth->user('id')));
			echo $this->Form->input('business_id', array('type' => 'hidden'));
		}
        echo $this->Form->input('updates',array('class'=>'js-content-show', 'onfocusin' => 'OnFocusInForm (event)'));
        ?>
        <div class="<?php echo (empty($this->request->data['Item']) ? 'hide' : '') ?> js-content">
        <div class="mapblock-info js-overlabel">
        <?php
        		//echo $this->Form->autocomplete('Item.name', array('label' => __l('Item'), 'acFieldKey' => 'Item.id', 'acFields' => array('Item.name'), 'acSearchFieldNames' => array('Item.name'), 'maxlength' => '255'));
        ?>
        		<div class="autocompleteblock">
                </div>
        </div>

<?php
        if(!empty($places)) {
?>
        <div class="checkbox-info-block">
        <div class="page-info"><?php echo __l(' Choose the places where above update is applicable...'); ?></div>
<?php        		
			foreach($places as  $place) {
				echo $this->Form->input('Place.'.$place['Place']['id'].'.place', array('type' => 'checkbox', 'checked' => true, 'value' => $place['Place']['id'], 'label' => $place['Place']['name'])); ?>
				<span><?php echo $this->Html->cText($place['Place']['address2']);?></span>
				<?php
			}
?>
        </div>
<?php			
        }
?>

       </div>
       </div>
        <div class="clearfix">
    		<div class="submit-block clearfix grid_right">
                <?php echo $this->Form->Submit(__l('Post'));?>
            </div>
        </div>
        <?php echo $this->Form->end();?>
</div>

<script type="text/javascript">
        function OnFocusInForm (event) {
            $(".js-content").removeClass('hide');
        }

    </script>
