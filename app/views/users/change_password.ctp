<div class="clearfix js-responses">
<?php if(empty($this->request->params['isAjax'])){ ?>
        <div class="side1  grid_16 alpha omega js-response">
<?php } ?>
<?php
if(empty($this->request->params['isAjax'])){ ?>
         <?php echo $this->element('users-top_links'); ?>
<?php } if(!empty($this->request->params['isAjax'])){?>
<?php
	echo $this->Form->create('User', array('action' => 'change_password' ,'class' => 'normal js-ajax-form'));
	if($this->Auth->user('user_type_id') == ConstUserTypes::Admin) :
    	echo $this->Form->input('user_id', array('empty' => 'Select'));
    endif;
    if($this->Auth->user('user_type_id') != ConstUserTypes::Admin) :
        echo $this->Form->input('user_id', array('type' => 'hidden'));
    	echo $this->Form->input('old_password', array('type' => 'password','label' => __l('Old password') ,'id' => 'old-password'));
    endif;
    echo $this->Form->input('passwd', array('type' => 'password','label' => __l('Enter a new password') , 'id' => 'new-password'));
	echo $this->Form->input('confirm_password', array('type' => 'password', 'label' => __l('Confirm Password'))); ?>
<div class="submit-block clearfix">
<?php echo $this->Form->end(__l('Change Password'));?>
</div>
<?php echo $this->Form->end();}?>
<?php if(empty($this->request->params['isAjax'])){?>
</div>
	<div class="side2 grid_8 alpha omega">
		<?php echo $this->element('users-sidebar', array('username' => $this->Auth->user('username'), 'config' => 'site_element_cache_5_min')); ?>
	</div>
<?php } ?>
</div>