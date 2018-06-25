<?php /* SVN: $Id: $ */ ?>
<div class="clearfix upload-share-block js-responses js-responses-review_add">
 <?php
		$grid_class = "";
	        if(!empty($this->request->params['named']['guide'])) {
		         $grid_class = "grid_16";
		      } else {
			   $grid_class = "grid_16";
		}
	?>
<div class="upload-block <?php echo  $grid_class;?>  alpha omega">
    <div class="upload-tl">
        <div class="upload-tr">
            <div class="upload-tm">
            </div>
        </div>
    </div>
  <div class="upload-left">
    <div class="upload-right">
    <div class="upload-middle">
    <div class="reviews form">
    <?php echo $this->Form->create('Review', array('class' => 'normal upload-form clearfix js-ajax-form-submit', 'enctype' => 'multipart/form-data'));?>
         <?php echo $this->Form->input('Attachment.filename', array('before' => '<div class="browse-field">','after' => '</div>','type' => 'file', 'label' => 'Share a food (or drink!) you recommend','class' =>'browse-field js-browse-fields', 'onpropertychange'=>"if(window.event.propertyName=='value'){triggerattached(this.value)}")); ?>
        <div class="hide js-review-add-details">
		<?php
			if($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
				echo $this->Form->input('user_id');
			endif;
			if(!empty($place['Place']['id'])){  ?>
			<div class="clearfix place-info-block">
			     <div class="mapblock-info js-overlabel mapblock-info overlabel-wrapper <?php echo (isset($this->request->params['prefix']) and $this->request->params['prefix'] == 'admin') ? '':'grid_7';?>">
                     <?php
                        echo $this->Form->autocomplete('Item.name', array('label' => __l('Item') .  ' (<span class="required">' . __l('Required') . '</span>)' , 'acFieldKey' => 'Item.id', 'acFields' => array('Item.name'), 'acSearchFieldNames' => array('Item.name'), 'maxlength' => '255'));
                        ?>
                    <div class="autocompleteblock"> </div>
                 </div>
                 <div class="space-info <?php echo (isset($this->request->params['prefix']) and $this->request->params['prefix'] == 'admin') ? 'at-info':'grid_1';?>"><?php echo __l('@');?></div>
                 <div class="mapblock-info disabled-field <?php echo (isset($this->request->params['prefix']) and $this->request->params['prefix'] == 'admin') ? '':'grid_4';?>">
                     <?php
    				 		echo $this->Form->autocomplete('Place.name', array('disabled' => true, 'label' => __l('Place') .  ' ' . __l('Required') . '</span>)' , 'acFieldKey' => 'Place.id', 'acFields' => array('Place.name'), 'acSearchFieldNames' => array('Place.name'), 'maxlength' => '255'));
    				 		echo $this->Form->input('Place.id', array('type' => 'hidden'));
    				 ?>
                    <div class="autocompleteblock">   </div>
                </div>
            </div>
             <?php
			} elseif(!empty($this->request->data['Review']['sighting_id'])){?>
		<div class="clearfix  place-info-block">
    		<div class="mapblock-info js-overlabel overlabel-wrapper <?php echo (isset($this->request->params['prefix']) and $this->request->params['prefix'] == 'admin') ? '':'grid_7';?>">
    			<?php
    				echo $this->Form->autocomplete('Item.name', array('disabled' => true, 'label' => __l('Item') .  ' (<span class="required">' . __l('Required') . '</span>)' , 'acFieldKey' => 'Item.id', 'acFields' => array('Item.name'), 'acSearchFieldNames' => array('Item.name'), 'maxlength' => '255'));
    			?>
    			<div class="autocompleteblock">
    			</div>
    		</div>
		    <div class="space-info <?php echo (isset($this->request->params['prefix']) and $this->request->params['prefix'] == 'admin') ? 'at-info':'grid_1';?>"><?php echo __l('@');?></div>
	        <div class="mapblock-info js-overlabel overlabel-wrapper  <?php echo (isset($this->request->params['prefix']) and $this->request->params['prefix'] == 'admin') ? '':'grid_7';?>">
		      <?php  echo $this->Form->autocomplete('Place.name', array('disabled' => true, 'label' => __l('Place') .  ' (<span class="required">' . __l('Required') . '</span>)' , 'acFieldKey' => 'Place.id', 'acFields' => array('Place.name'), 'acSearchFieldNames' => array('Place.name'), 'maxlength' => '255')); ?>
			  <div class="autocompleteblock">
			  </div>
			</div>		
		</div>

		<?php
				} else { 
		?>
		<div class="clearfix  place-info-block">
    		<div class="mapblock-info js-overlabel overlabel-wrapper <?php echo (isset($this->request->params['prefix']) and $this->request->params['prefix'] == 'admin') ? '':'grid_7';?>">
        		<label for='ItemName'><?php echo __l('Item') .  ' (<span class="required">' . __l('Required') . '</span>)';?></label>
        		<?php echo $this->Form->autocomplete('Item.name', array('label' => false, 'acFieldKey' => 'Item.id', 'acFields' => array('Item.name'), 'acSearchFieldNames' => array('Item.name'), 'maxlength' => '255'));?>
    			<div class="autocompleteblock"> </div>
    		</div>
    		<div class="space-info <?php echo (isset($this->request->params['prefix']) and $this->request->params['prefix'] == 'admin') ? 'at-info':'grid_1';?>"><?php echo __l('@');?></div>
    		<div class="mapblock-info js-overlabel overlabel-wrapper <?php echo (isset($this->request->params['prefix']) and $this->request->params['prefix'] == 'admin') ? '':'grid_7';?>">
        		<label for='PlaceName'><?php echo __l('Place') .  ' (<span class="required">' . __l('Required') . '</span>)'; ?></label>
        		<?php echo $this->Form->autocomplete('Place.name', array('label' => false,   'acFieldKey' => array('Place' => 'Place.id'), 'acFields' => array('Place' => array('Place.id', 'Place.name', 'Place.address2')), 'acSearchFieldNames' => array('Place' => 'Place.name'), 'maxlength' => '255', 'acMultiple' => 'js-multi-autocomplete', 'accontrollers' => 'Place')); ?>
        		<div class="autocompleteblock"> </div>
    		</div>
		</div>
		<?php
		}
		echo $this->Form->input('sighting_id', array('type' => 'hidden')); ?>
            <div class="clearfix">
            <div class="js-overlabel overlabel-wrapper overlabel-description-block clearfix <?php echo (isset($this->request->params['prefix']) and $this->request->params['prefix'] == 'admin') ? '':'grid_15';?>">
                   <label for='ReviewNotes'><?php echo __l('Description');?></label>
                	<?php echo $this->Form->input('notes',array('label'=>false));?>
			</div>
			</div>
			<div class="clearfix">
			<div class="js-overlabel overlabel-wrapper <?php echo (isset($this->request->params['prefix']) and $this->request->params['prefix'] == 'admin') ? '':'grid_8';?>">
                <label for='ReviewTag'><?php echo __l('Tags');?></label>
                <?php  echo $this->Form->input('tag', array('label' => false, 'info' => __l('Comma separated tags. Optional')));?>
            	<?php if(!empty($this->request->params['named']['guide'])) {
                        echo $this->Form->input('guide', array('type' => 'hidden', 'value' => $this->request->params['named']['guide']));
				}
		      	?>
			</div>
 			<div class="mapblock-info js-overlabel overlabel-wrapper <?php echo (isset($this->request->params['prefix']) and $this->request->params['prefix'] == 'admin') ? '':'grid_7';?>">
    		  <label for='ReviewCategoryName'><?php echo __l('Category');?></label>
 	           <?php
				echo $this->Form->autocomplete('ReviewCategory.name', array('label' => false, 'acFieldKey' => 'ReviewCategory.id', 'acFields' => array('ReviewCategory.name'), 'acSearchFieldNames' => array('ReviewCategory.name'), 'maxlength' => '255'));
		        ?>
				<div class="autocompleteblock">
				</div>
		  </div>
		</div>
        <?php 
		$fb_access_token=$this->Auth->user('fb_access_token');
        $fb_user_id=$this->Auth->user('fb_user_id');
		$twitter_access_token=$this->Auth->user('twitter_access_token');
        $twitter_access_key=$this->Auth->user('twitter_access_key');
		$foursquare_access_token=$this->Auth->user('foursquare_access_token');
        $foursquare_user_id=$this->Auth->user('foursquare_user_id');
		if((!empty($fb_access_token) && !empty($fb_user_id)) || (!empty($twitter_access_token) && !empty($twitter_access_key)) || (!empty($foursquare_access_token) && !empty($foursquare_user_id))){?>
            <ul class="access-block grid_left clearfix">
            <?php
            if(!empty($fb_access_token) && !empty($fb_user_id)){ ?>
                 <li class="fb-block">
                    <?php echo $this->Form->input('is_facebook', array('type' => 'checkbox')); ?>
                    <span class="facebook-block" title="Share on facebook"><?php echo __l('Share on facebook');?></span>
                </li>
            <?php } ?>
           
            <?php
            if(!empty($twitter_access_token) && !empty($twitter_access_key)){ ?>
                <li class="tw-block">
                    <?php echo $this->Form->input('is_tweet', array('type' => 'checkbox')); ?>
                    <span class="twitter-block" title="Share on twitter"><?php echo __l('Share on twitter');?></span>
                </li>
            <?php } ?>
            
            <?php
            if(!empty($foursquare_access_token) && !empty($foursquare_user_id)){ ?>
                <li class="fs-block">
                    <?php echo $this->Form->input('is_foursquare', array('type' => 'checkbox')); ?>
                    <span class="foursquare-block" title="Share on foursquare"><?php echo __l('Share on foursquare');?></span>
                </li>
            <?php } ?>
            
            </ul>
        <?php } ?>
			<div class="submit-block clearfix <?php echo (isset($this->request->params['prefix']) and $this->request->params['prefix'] == 'admin') ? '':'grid_right';?>">
                <?php echo $this->Form->Submit(__l('Add Sighting'));?>
            </div>
            </div>
            <?php if(!empty($this->request->params['named']['guide'])) { ?>
            <div class="browser-existing-block">
                <?php echo __l('(or) ');
				echo $this->Html->link(__l('Browse the Existing sighting'),  array('controller' => 'sightings', 'action' => 'user_sighting', 'user' => $this->Auth->user('username'), 'view' => 'simple', 'guide' => $this->request->params['named']['guide']), array('title' => __l('Browse the Existing sighting'), 'class' => 'js-thickbox'));
                ?>
        	</div>
        	<?php } ?>

        <?php echo $this->Form->end();?>

        </div>
    </div>
  </div>
  </div>
    <div class="upload-bl">
        <div class="upload-br">
            <div class="upload-bm">
            </div>
        </div>
    </div>
</div>

</div>
<script type="text/jscript">
	function triggerattached($value){
		$('.js-review-add-details').show();
		filename = $value.split("\\");
		no = parseInt(filename.length) - 1;
        $('.js-browse-fields').prev('label').text(filename[no]);
        $('.js-browse-fields').prev('label').addClass('upload-img');
	}

</script>