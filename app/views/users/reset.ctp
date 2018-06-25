<h2><?php echo __l('Reset your password'); ?></h2>
<?php
	echo $this->Form->create('User', array('action' => 'reset' ,'class' => 'normal'));
	echo $this->Form->input('user_id', array('type' => 'hidden'));
	echo $this->Form->input('hash', array('type' => 'hidden'));
	echo $this->Form->input('passwd', array('type' => 'password','label' => __l('Enter a new password') ,'id' => 'password'));
	echo $this->Form->input('confirm_password', array('type' => 'password','label' => __l('Confirm Password'))); ?>
   <div class="submit-block clearfix"><?php echo $this->Form->submit('Change password'); ?></div>
   <?php echo $this->Form->end(); ?>