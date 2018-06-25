<div class="userProfiles form">
  	<?php if(empty($this->request->params['admin'])) :?>
	<h2><?php echo $pageTitle; ?></h2>
    <?php endif; ?>
              <div class="form-blocks round-5">
            <?php echo $this->Form->create('UserProfile', array('action' => 'edit', 'class' => 'normal', 'enctype' => 'multipart/form-data'));?>
				<fieldset  class="form-block">
							<h3 class=""><?php echo __l('Personal'); ?></h3>
						
        		<?php
                if($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
                    echo $this->Form->input('User.id');
                endif;
                if($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
                    echo $this->Form->input('User.username');
                endif;
                echo $this->Form->input('first_name');
        		echo $this->Form->input('last_name');
        		echo $this->Form->input('middle_name');
        		echo $this->Form->input('gender_id', array('empty' => __l('Please Select')));
				if($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
						echo $this->Form->input('User.email',array('label' => __l('Email')));
				endif;
          		echo $this->Form->input('dob', array('empty' => __l('Please Select'), 'maxYear' => date('Y'), 'minYear' => date('Y') - 100, 'orderYear' => 'asc', 'label' => "DOB"));
            	echo $this->Form->input('about_me'); ?>
				</fieldset>	
				<fieldset  class="form-block">
							<h3 class=""><?php echo __l('Address'); ?></h3>
				
				<?php
        		echo $this->Form->input('address');
        		echo $this->Form->input('country_id', array('empty' => __l('Please Select')));
?>		
                <div class="mapblock-info">
                    <?php
                        echo $this->Form->autocomplete('State.name', array('id' => 'profile_state', 'label' => __l('State'), 'acFieldKey' => 'State.id', 'acFields' => array('State.name'), 'acSearchFieldNames' => array('State.name'), 'maxlength' => '255'));
                    ?>
                    <div class="autocompleteblock">
                    </div>
                </div>                		
                <div class="mapblock-info">
                    <?php
                        echo $this->Form->autocomplete('City.name', array('id' => 'profile_city', 'label' => __l('City'), 'acFieldKey' => 'City.id', 'acFields' => array('City.name'), 'acSearchFieldNames' => array('City.name'), 'maxlength' => '255'));
                    ?>
                    <div class="autocompleteblock">
                    </div>
                </div>                		
<?php
        		
        		
        		echo $this->Form->input('zip_code'); ?>
				</fieldset>	
				<fieldset  class="form-block">
				<h3 class=""><?php echo __l('Language'); ?></h3>
				<?php
                echo $this->Form->input('language_id', array('empty' => __l('Please Select')));
        		if($this->Auth->user('user_type_id') == ConstUserTypes::Admin):                    
                    echo $this->Form->input('User.is_active', array('label' => __l('Active')));
                    echo $this->Form->input('User.is_email_confirmed', array('label' => __l('Email confirmed')));
                endif; ?>
				</fieldset>	
				<fieldset  class="form-block">
				<h3 class=""><?php echo __l('Profile Image'); ?></h3>
				<div class="grid_right profile-image1 profile-image">
                    <?php echo $this->Html->link($this->Html->showImage('UserAvatar', $this->request->data['UserAvatar'], array('dimension' => 'big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($this->request->data['User']['username'], false)), 'title' => $this->Html->cText($this->request->data['User']['username'], false), 'escape' => false)), array('controller' => 'users', 'action' => 'view',  $this->request->data['User']['username'], 'admin' => false), array('escape' => false)); ?>
                </div>
				<?php
        		  echo $this->Form->input('UserAvatar.filename', array('type' => 'file','size' => '33', 'label' => 'Upload Photo', 'class' =>'browse-field'));
            	?>
        	</fieldset>
        	<div class="submit-block clearfix">
                <?php echo $this->Form->Submit(__l('Update')); ?>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
  

    


</div>
