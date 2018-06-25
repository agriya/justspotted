<div class="clearfix">
<?php if(empty($this->request->params['isAjax'])){ ?>
        <div class="side1  grid_16 alpha omega js-response">
<?php } ?>
<?php
    if(empty($this->request->params['isAjax'])){?>
         <?php echo $this->element('users-top_links'); ?>
<?php }
if(!empty($this->request->params['isAjax'])){?>
<h3 class="connected-info"><?php echo __l('You can connect with').' '.Configure::read('site.name').' '.__l('using multiple connect.'); ?></h3>
<?php echo $this->Form->create('User', array('action' => 'profile_image', 'class' => 'normal',  'enctype' => 'multipart/form-data'));
      
	  echo $this->Form->input('User.id', array('type' => 'hidden'));
	  unset($profileimages[ConstProfileImage::Upload]);
?>
<div class="dashboard-inner-block round-10">
		<div class="connect-link-block twitter-block clearfix">
		<div class="grid_10 omega alpha clearfix">
            <?php
			if(!empty($this->request->data['User']['twitter_avatar_url'])): ?>
                <h4><?php echo __l("You've Connected With Twitter"); ?></h4>
                <p>
				<?php echo __l('Your sightings are now automatically pushed to your followers.'); ?>
                </p>
               <?php
			else:
			?>
            	<h4><?php echo __l('Connect to Twitter'); ?></h4>
            	<p>
				<?php echo __l('Connect your Twitter account to automatically push sightings to your followers'); ?>
                </p>
            <?php endif;?>
            </div>

            <div class="grid_3 grid_right connection-right-block omega follow-block follow-block2 alpha">
			<?php
			if(!empty($this->request->data['User']['twitter_avatar_url'])):
				if(!$this->request->data['User']['is_twitter_register']) :
					echo $this->Html->link(__l('Disconnect'), array('controller' => 'users', 'action' => 'connect', $this->request->data['User']['id'], 'type' => 'twitter', 'c_action' => 'disconnect'), array('class'=>'button','escape' => false));
				endif;
			else:
        	   echo $this->Html->link(__l('Connect'), array('controller' => 'users', 'action' => 'connect', $this->request->data['User']['id'], 'type' => 'twitter'), array('class'=>'button','escape' => false));
        	endif;
			?>
			</div>

		</div>
</div>
<div class="dashboard-inner-block round-10">
		<div class="connect-link-block facebook-block clearfix">
		<div class="grid_10 omega alpha">

            <?php if(!empty($this->request->data['User']['fb_user_id'])): ?>
            	<h4><?php echo __l("You've Connected With Facebook"); ?></h4>
                <p>
				<?php echo __l('Your sightings are now automatically pushed to your followers.'); ?>
                </p>
            <?php else: ?>
                <h4><?php echo __l('Connect to Facebook'); ?></h4>
                <p><?php echo __l('Connect your Facebook account to automatically push sightings to your followers'); ?></p>
            <?php endif;?>
              </div>
            <div class="grid_3 grid_right connection-right-block omega follow-block follow-block2 alpha">
			<?php
			if(!empty($this->request->data['User']['fb_user_id'])):
				if(!$this->request->data['User']['is_facebook_register']) :
					echo $this->Html->link(__l('Disconnect'), array('controller' => 'users', 'action' => 'connect', $this->request->data['User']['id'], 'type' => 'facebook', 'c_action' => 'disconnect'), array('class'=>'button','escape' => false));
				endif;
			else:
			   echo $this->Html->link(__l('Connect'), $fb_login_url ,array('class'=>'button'));
			endif;?>
			</div>
		</div>

</div>
<div class="dashboard-inner-block round-10">
		<div class="connect-link-block foursquare-block clearfix">
		<div class="grid_10 omega alpha">

            <?php if(!empty($this->request->data['User']['fb_user_id'])): ?>
            	<h4><?php echo __l("You've Connected With Foursquare"); ?></h4>
                <p>
				<?php echo __l('Your sightings are now automatically pushed to your followers.'); ?>
                </p>
            <?php else: ?>
                <h4><?php echo __l('Connect to Foursquare'); ?></h4>
                <p><?php echo __l('Connect your Fourquare account to automatically push sightings to your followers'); ?></p>
            <?php endif;?>
              </div>

            <div class="grid_3 grid_right connection-right-block omega follow-block follow-block2 alpha">
			<?php
			if(!empty($this->request->data['User']['foursquare_user_id'])):
				if(!$this->request->data['User']['is_foursquare_register']) :
					echo $this->Html->link(__l('Disconnect'), array('controller' => 'users', 'action' => 'connect', $this->request->data['User']['id'], 'type' => 'foursquare', 'c_action' => 'disconnect'), array('class'=>'button','escape' => false));
				endif;
			else:
			   echo $this->Html->link(__l('Connect'), $fs_login_url ,array('class'=>'button'));
			endif;?>
			</div>
		</div>

</div>

<?php echo $this->Form->end(); ?>
<?php } ?>
<?php if(empty($this->request->params['isAjax'])) { ?>
</div>
<?php } 
    if(empty($this->request->params['isAjax'])) { ?>
<div class="side2 grid_8 alpha omega">
	<?php echo $this->element('users-sidebar', array('username' => $this->Auth->user('username'), 'config' => 'site_element_cache_5_min')); ?>
</div>
<?php } ?>
</div>
