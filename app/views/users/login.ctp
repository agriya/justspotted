<div class="users form">
    <h2><?php echo __l('Login'); ?></h2>
<?php if (empty($this->request->params['prefix'])) { ?>
<div class="clearfix">
<div class="open-id-block grid_right clearfix">
  <h5 class="grid_left"><?php echo __l('Sign In using: '); ?></h5>
	<ul class="open-id-list grid_left clearfix">
			<li class="facebook">
				 <?php if(Configure::read('facebook.is_enabled_facebook_connect')):  ?>
					<?php echo $this->Html->link(__l('Sign in with Facebook'), array('controller' => 'users', 'action' => 'login','type'=>'facebook'), array('title' => __l('Sign in with Facebook'), 'escape' => false)); ?>
				 <?php endif; ?>
			</li>
			<?php if(Configure::read('twitter.is_enabled_twitter_connect')):?>
				<li class="twitter"><?php echo $this->Html->link(__l('Sign in with Twitter'), array('controller' => 'users', 'action' => 'login',  'type'=> 'twitter', 'admin'=>false), array('class' => 'Twitter', 'title' => __l('Sign in with Twitter')));?></li>
			<?php endif;?>
				<?php if(Configure::read('foursquare.is_enabled_foursquare_connect')):?>
					<li class="foursquare"><?php echo $this->Html->link(__l('Sign in with Foursquare'), array('controller' => 'users', 'action' => 'login',  'type'=> 'foursquare', 'admin'=>false), array('class' => 'Foursquare', 'title' => __l('Sign in with Foursquare')));?></li>
				<?php endif;?>
			<?php if(Configure::read('user.is_enable_yahoo_openid')):?>
				<li class="yahoo"><?php echo $this->Html->link(__l('Sign in with Yahoo'), array('controller' => 'users', 'action' => 'login', 'type'=>'yahoo'), array('title' => __l('Sign in with Yahoo')));?></li>
			<?php endif;?>
			<?php if(Configure::read('user.is_enable_gmail_openid')):?>
				<li class="gmail"><?php echo $this->Html->link(__l('Sign in with Gmail'), array('controller' => 'users', 'action' => 'login', 'type'=>'gmail'), array('title' => __l('Sign in with Gmail')));?></li>
			<?php endif;?>
			<?php if(Configure::read('user.is_enable_openid')):?>
				<li class="openid"><?php 	echo $this->Html->link(__l('Sign in with Open ID'), array('controller' => 'users', 'action' => 'login','type'=>'openid'), array('class'=>'','title' => __l('Sign in with Open ID')));?></li>
			<?php endif;?>
	</ul>
	</div>
</div>
<?php } ?>
    <?php
	    echo $this->Form->create('User', array('action' => 'login', 'class' => 'normal'));
		echo $this->Form->input(Configure::read('user.using_to_login'));
	    echo $this->Form->input('passwd', array('label' => __l('Password')));
	?>
	<?php echo $this->Form->input('User.is_remember', array('type' => 'checkbox', 'label' => __l('Remember me on this computer.'))); ?>
	<?php if(!(!empty($this->request->params['prefix']) && $this->request->params['prefix'] == 'admin')):	?>
	<div class="from-left">
	 <?php  echo $this->Html->link(__l('Forgot your password?') , array('controller' => 'users', 'action' => 'forgot_password'),array('title' => __l('Forgot your password?')));?> |
	<?php echo $this->Html->link(__l('Signup') , array('controller' => 'users',	'action' => 'register'),array('title' => __l('Signup'))); ?>
    </div>
	<?php endif; ?>
    <?php
        $f = (!empty($_GET['f'])) ? $_GET['f'] : (!empty($this->request->data['User']['f']) ? $this->request->data['User']['f'] : (($this->request->url != 'admin/users/login' && $this->request->url != 'users/login') ? $this->request->url : ''));
		if(!empty($f)) :
            echo $this->Form->input('f', array('type' => 'hidden', 'value' => $f));
        endif; ?>
    <div class="submit-block clearfix">
    <?php echo $this->Form->submit(__l('Login'));?>
    </div>
    <?php echo $this->Form->end();?>
</div>