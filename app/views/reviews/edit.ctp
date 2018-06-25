<?php /* SVN: $Id: $ */ ?>
  <?php if($this->Auth->user('user_type_id') != ConstUserTypes::Admin) { ?>
        <h2><?php echo $this->pageTitle; ?></h2>
    <?php } ?>
<div class="clearfix upload-share-block">
    <div class=" <?php echo (isset($this->request->params['prefix']) and $this->request->params['prefix'] == 'admin') ? '':'grid_16 alpha omega upload-block';?>">
        <div class="upload-tl">
            <div class="upload-tr">
                <div class="upload-tm"></div>
            </div>
        </div>
        <div class="upload-left">
            <div class="upload-right">
                <div class="upload-middle">
                    <div class="reviews form">
                    <?php echo $this->Form->create('Review', array('class' => 'normal upload-form clearfix', 'enctype' => 'multipart/form-data'));?>
                        <?php
                            echo $this->Form->input('id');
                            if($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
                                echo $this->Form->input('user_id');
                            } else {
                                echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => $this->Auth->user('id')));
                            }
                        ?>
                     <?php echo $this->Form->input('Attachment.filename', array('before' => '<div class="browse-field">','after' => '</div>','type' => 'file', 'label' => 'Share a food (or drink!) you recommend','class' =>'browse-field js-browse-fields')); ?>
                        <div class="js-review-add-details">
                            <div class="clearfix  place-info-block">
                                <div class="mapblock-info js-overlabel mapblock-info overlabel-wrapper <?php echo (isset($this->request->params['prefix']) and $this->request->params['prefix'] == 'admin') ? '':'grid_7';?>">
                                    <label for='ItemName'><?php echo __l('Item');?></label>
                                    <?php
                                        echo $this->Form->autocomplete('Item.name', array('disabled' => true,'label' => false, 'acFieldKey' => 'Item.id', 'acFields' => array('Item.name'), 'acSearchFieldNames' => array('Item.name'), 'maxlength' => '255'));
                                    ?>
                                    <div class="autocompleteblock"></div>
                                </div>
                                <div class="space-info <?php echo (isset($this->request->params['prefix']) and $this->request->params['prefix'] == 'admin') ? 'at-info':'grid_1';?>"><?php echo __l('@');?></div>
                                <div class="mapblock-info js-overlabel mapblock-info overlabel-wrapper <?php echo (isset($this->request->params['prefix']) and $this->request->params['prefix'] == 'admin') ? '':'grid_7';?>">
                                    <label for='PlaceName'><?php echo __l('Place');?></label>
                                    <?php
										echo $this->Form->autocomplete('Place.name', array('disabled' => true,'label' => false,   'acFieldKey' => array('Place' => 'Place.id'), 'acFields' => array('Place' => array('Place.id', 'Place.name', 'Place.address2')), 'acSearchFieldNames' => array('Place' => 'Place.name'), 'maxlength' => '255', 'acMultiple' => 'js-multi-autocomplete', 'accontrollers' => 'Place'));
                                    ?>
                                    <div class="autocompleteblock"></div>
                                </div>
                            </div>
                            <?php
                                echo $this->Form->input('sighting_id', array('type' => 'hidden'));
                            ?>
                           <div class="clearfix">
                           <div class="js-overlabel mapblock-info overlabel-description-block overlabel-wrapper <?php echo (isset($this->request->params['prefix']) and $this->request->params['prefix'] == 'admin') ? '':'grid_15' ;?>">
                                <label for='ReviewNotes'><?php echo __l('Description');?></label>
                                <?php
                                    echo $this->Form->input('notes',array('label'=>false));
                                ?>
                            </div>
                            </div>
                            <div class="clearfix">
                                <div class="js-overlabel mapblock-info overlabel-wrapper <?php echo (isset($this->request->params['prefix']) and $this->request->params['prefix'] == 'admin') ? '':'grid_8' ;?>">
                                    <label for='ReviewTag'><?php echo __l('Tags');?></label>
                                    <?php
                                        echo $this->Form->input('tag', array('label' => false, 'info' => __l('Comma separated tags. Optional')));
                                    ?>
                                </div>
                                <div class="mapblock-info js-overlabel overlabel-wrapper <?php echo (isset($this->request->params['prefix']) and $this->request->params['prefix'] == 'admin') ? '':'grid_7' ;?>">
                                    <label for='ReviewCategoryName'><?php echo __l('Category');?></label>
                                    <?php
                                        echo $this->Form->autocomplete('ReviewCategory.name', array('label' => false, 'acFieldKey' => 'ReviewCategory.id', 'acFields' => array('ReviewCategory.name'), 'acSearchFieldNames' => array('ReviewCategory.name'), 'maxlength' => '255'));
                                    ?>
                                    <div class="autocompleteblock"></div>
                                </div>
                            </div>
                          <div class="submit-block clearfix <?php echo (isset($this->request->params['prefix']) and $this->request->params['prefix'] == 'admin') ? '':'grid_right' ;?> ">
                                <?php
                                    echo $this->Form->Submit(__l('Update Review'));
                                ?>
                            </div>
                        </div>
                        <?php echo $this->Form->end();?>
                    </div>
                </div>
            </div>
        </div>
        <div class="upload-bl">
            <div class="upload-br">
                <div class="upload-bm"></div>
            </div>
        </div>
    </div>
        <div class=" <?php echo (isset($this->request->params['prefix']) and $this->request->params['prefix'] == 'admin') ? 'profile-image':'img-frame grid_5 grid_right';?>">
            <?php echo $this->Html->link($this->Html->showImage('Review', $reviews['Attachment'], array('dimension' => 'big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $reviews['Review']['id']), 'title' => __l($reviews['Sighting']['Item']['name'].' @ '.$reviews['Sighting']['Place']['name']), 'escape' => false)), array('controller' => 'reviews', 'action' => 'view',  $reviews['Review']['id'], 'admin' => false), array('escape' => false)); ?>
        </div>
</div>