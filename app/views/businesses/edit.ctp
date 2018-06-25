<?php /* SVN: $Id: $ */ ?>
<div class="businesses form">
<?php echo $this->Form->create('Business', array('action' => 'edit', 'class' => 'normal', 'enctype' => 'multipart/form-data'));?>
    <?php if($this->Auth->user('user_type_id') != ConstUserTypes::Admin) { ?>
        <h2>
             <?php	echo __l('Edit Business');?>
        </h2>
   <?php }?>
       <?php
    		echo $this->Form->input('id');
    		echo $this->Form->input('name', array('label' => __l('Business Name')));
    		if($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
    			echo $this->Form->input('why_do_you_want_a_business_access', array('div' =>'input textarea big-textarea','label' => __l('Why Do You Want a Business Access')));
    		}
    		echo $this->Form->input('about_your_business',array('div' =>'input textarea big-textarea'));
    		if($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
			echo $this->Form->input('is_my_own_business', array('label' => __l('My Own Business?')));
        ?>
	
          <?php
		     }
                ?>
        <div class="clearfix">
          
    	 
            <?php
        		echo $this->Form->input('Attachment.filename', array('type' => 'file','size' => '33', 'label' => 'Upload Photo', 'class' =>'browse-field'));
            ?>
              <div class="grid_left admin-profile-image profile-image">
                <?php
                    echo $this->Html->link($this->Html->showImage('Business', $this->request->data['Attachment'], array('dimension' => 'small_big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($this->request->data['Business']['slug'], false)), 'title' => $this->Html->cText($this->request->data['Business']['slug'], false), 'escape' => false)), array('controller' => 'businesses', 'action' => 'view',  $this->request->data['Business']['slug'], 'admin' => false), array('escape' => false));
                ?>
    		</div>
        </div>
		<?php if($this->Auth->user('user_type_id') == ConstUserTypes::Admin) { ?>
          <fieldset class="edit-form-block round-5">
          <legend class="round-5"><?php echo __l('Action block');?></legend>
          <div class="approved-block">
            	<span class="label-content label-content-radio"> <?php echo __l(' Approved?');?> </span>
                <?php
    			echo $this->Form->input('is_approved', array('legend' => false, 'type' => 'radio', 'options' => array(0 => 'Waiting for Approval', 1 => 'Approved', '2' => 'Rejected')));
                 ?>
          </div>
          </fieldset>
		<?php } ?>
        <div class="submit-block clearfix">
            <?php echo $this->Form->submit(__l('Update'));?>
        </div>
        <?php echo $this->Form->end();?>
</div>