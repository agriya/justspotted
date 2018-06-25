<div class="clearfix js-responses">
<?php if(empty($this->request->params['isAjax'])){ ?>
        <div class="side1  grid_16 alpha omega js-response">
<?php } 
		echo $this->Form->create('User', array('action' => 'change_password' ,'class' => 'normal js-ajax-form'));
    	echo $this->Form->input('user_id', array('empty' => 'Select'));
		echo $this->Form->input('passwd', array('type' => 'password','label' => __l('Enter a new password') , 'id' => 'new-password'));
		echo $this->Form->input('confirm_password', array('type' => 'password', 'label' => __l('Confirm Password'))); ?>
<div class="submit-block clearfix">
<?php echo $this->Form->end(__l('Change Password'));?>
</div>
<?php echo $this->Form->end();?>
<?php if(empty($this->request->params['isAjax'])){?>
</div>

<?php } ?>
</div>