<div class="js-response setting-edit-block">
<?php if (!empty($setting_category['SettingCategory']['description'])):?>
	<div class=" info-details"><?php echo $setting_category['SettingCategory']['description'];?> </div>
	<?php if($setting_category['SettingCategory']['id'] == 44) :?>
	<div>
		<h4>Common Regular expressions</h4>
		<dl class="list claerfix">
			<dt>Email</dt>
				<dd>\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*([,;]\s*\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*)*</dd>
			<dt>Phone Number</dt>
				<dd>^0[234679]{1}[\s]{0,1}[\-]{0,1}[\s]{0,1}[1-9]{1}[0-9]{6}$</dd>
			<dt>URL</dt>
				<dd>((https?|ftp|gopher|telnet|file|notes|ms-help):((//)|(\\\\))+[\w\d:#@%/;$()~_?\+-=\\\.&]*)</dd>
		</dl>
	</div>
	<?php endif;?>
<?php endif;?>
</div>
<?php
	if (!empty($settings)):
		echo $this->Form->create('Setting', array('action' => 'edit', 'class' => 'normal'));
			echo $this->Form->input('setting_category_id', array('label' => __l('Setting Category'),'type' => 'hidden'));
		// hack to delete the thumb folder in img directory
        if($settings[0]['SettingCategory']['name'] == 'Images'):
        	echo $this->Form->input('delete_thumb_images', array('type' => 'hidden', 'value' => '1'));
        endif;
		$inputDisplay = 0;
		$is_changed = $prev_cat_id = 0;
    	foreach ($settings as $setting):
					if(empty($prev_cat_id)){
						$prev_cat_id = $setting['SettingCategory']['id'];
						$is_changed = 1;
					} else {
						$is_changed = 0;
						if($setting['SettingCategory']['id'] != $prev_cat_id ){ 
						$is_changed = 1;
							$prev_cat_id  = $setting['SettingCategory']['id'];
						}
					}	
			if($setting['Setting']['name'] == 'twitter.site_user_access_key' || $setting['Setting']['name'] == 'twitter.site_user_access_token' || $setting['Setting']['name'] == 'facebook.fb_access_token' || $setting['Setting']['name'] == 'facebook.fb_user_id' || $setting['Setting']['name'] == 'foursquare.site_user_fs_id' || $setting['Setting']['name'] == 'foursquare.site_user_access_token'):
					$options['readonly'] = TRUE;
					$options['class'] = 'disabled';		
			endif;	
			if(!empty($is_changed)):	
				if($setting['Setting']['setting_category_parent_id'] != '7' && !empty($setting['SettingCategory']['name'])):	
			?>
			<fieldset  class="form-block">
					<h3 id="<?php echo str_replace(' ','',$setting['SettingCategory']['name']); ?>"> <?php echo $setting['SettingCategory']['name']; ?></h3>
						<?php if(!empty($setting['SettingCategory']['description'])) { ?><div class=" info-details"><?php
						$findReplace = array(
							'##TRANSLATIONADD##' => $this->Html->link(Router::url('/', true).'admin/translations/add', Router::url('/', true).'admin/translations/add', array('title' => __l('Translations add')))
						);
						$setting['SettingCategory']['description'] = strtr($setting['SettingCategory']['description'], $findReplace);
						echo $setting['SettingCategory']['description'];
						?> </div><?php } ?>
			</fieldset>			
		<?php	
			endif;		
			endif;		
		?>
<?php				if(in_array( $setting['Setting']['id'], array(164, 166, 113,115,169,171) ) ) : ?>
                     
                        <h4>
                           <?php echo (in_array($setting['Setting']['id'], array('164',115,113) ) )? __l('Application Info') : ''; ?>
                           <?php echo (in_array($setting['Setting']['id'], array('166',169,171) ) )? __l('Credentials') : ''; ?>
                           <?php echo (in_array($setting['Setting']['id'], array() ) )? __l('Other Info') : ''; ?>
                        </h4>
						<?php if(in_array( $setting['Setting']['id'], array(166,171,169))):?>
                            <div class=" info-details">
                                <?php 
                                    if($setting['Setting']['id'] == 171) :
                                        echo __l('Here you can update Facebook credentials . Click \'Update Facebook Credentials\' link below and Follow the steps. Please make sure that you have updated the API Key and Secret before you click this link.');
                                    elseif($setting['Setting']['id'] == 169) :
                                        echo __l('Here you can update Twitter credentials like Access key and Accss Token. Click \'Update Twitter Credentials\' link below and Follow the steps. Please make sure that you have updated the Consumer Key and  Consumer secret before you click this link.');
                                    elseif($setting['Setting']['id'] == 166) : 
                                        echo __l('Here you can update Foursquare credentials . Click  \'Update Foursquare Credentials\' link below and Follow the steps. Please make sure that you have updated the API Key and Secret before you click this link.');
                                    endif;
                                ?>
                            </div>
                        <?php endif;?>             
						<?php 
							if($setting['Setting']['id'] == 171) : ?>
							
							<div class="clearfix credentials-info-block">
							<div class="credentials-left">
						      	<div class="credentials-right round-5">
        							<?php	echo $this->Html->link(__l('<span>Update Facebook Credentials</span>'), $fb_login_url, array('escape'=>false,'class' => 'facebook-link', 'title' => __l('Here you can update Facebook credentials . Click this link and Follow the steps. Please make sure that you have updated the API Key and Secret before you click this link.')));
                                    ?>
                                </div>
                            </div>
                            <div class="credentials-right-block">
                            <?php
                            elseif($setting['Setting']['id'] == 169) :
                            ?>
                            <div class="clearfix credentials-info-block">
                            <div class="credentials-left">
						      	<div class="credentials-right round-5">
                                    <?php
                                    	echo $this->Html->link(__l('<span>Update Twitter Credentials</span>'), $tw_login_url, array('escape'=>false,'class' => 'twitter-link', 'title' => __l('Here you can update Twitter credentials like Access key and Accss Token. Click this link and Follow the steps. Please make sure that you have updated the Consumer Key and  Consumer secret before you click this link.')));
                                    ?>
                                </div>
                             </div>
                             <div class="credentials-right-block">
                            <?php
                        	elseif($setting['Setting']['id'] == 166) : 
                            ?>
                            <div class="clearfix credentials-info-block">
                             <div class="credentials-left">
						      	<div class="credentials-right round-5">
                                    <?php
                                        echo $this->Html->link(__l('Update Foursquare Credentials'), $fs_login_url, array('escape'=>false,'class' => 'foursquare-link', 'title' => __l('Here you can update Foursquare credentials . Click this link and Follow the steps. Please make sure that you have updated the API Key and Secret before you click this link.')));
                                    ?>
                                 </div>
                             </div>
                             <div class="credentials-right-block">
                            <?php
                        	endif;
						?>
<?php 				endif; ?>  
<?php        
			
            if($setting['Setting']['name'] == 'site.language'):
				$empty_language = 0;
				$get_language_options = $this->Html->getLanguage();
				if(!empty($get_language_options)):
					$options['options'] = $get_language_options;
				else:
					$empty_language = 1;
				endif;
            endif;
            $field_name = explode('.', $setting['Setting']['name']);
            if(isset($field_name[2]) && ($field_name[2] == 'is_not_allow_resize_beyond_original_size' || $field_name[2] == 'is_handle_aspect')){
                continue;
            }
            $options['type'] = $setting['Setting']['type'];
            $options['value'] = $setting['Setting']['value'];
            $options['div'] = array('id' => "setting-{$setting['Setting']['name']}");
            if($options['type'] == 'checkbox' && $options['value']):
                $options['checked'] = 'checked';
            endif;
            if($options['type'] == 'select'):
                $selectOptions = explode(',', $setting['Setting']['options']);
                $setting['Setting']['options'] = array();
                if(!empty($selectOptions)):
                    foreach($selectOptions as $key => $value):
                        if(!empty($value)):
                            $setting['Setting']['options'][trim($value)] = trim($value);
                        endif;
                    endforeach;
                endif;
                $options['options'] = $setting['Setting']['options'];
            endif;
            if($setting['Setting']['name'] == 'site.language'):
                $options['options'] = $this->Html->getLanguage();
            endif;
			if($setting['Setting']['name'] == 'site.timezone_offset'):
				$options['options'] = $timezoneOptions;				
			endif;
			$options['label'] = $setting['Setting']['label'];
			if ($setting['Setting']['setting_category_parent_id'] == '7' && $inputDisplay == 0):
				$options['class'] = 'image-settings';
				echo '<div class="outer-image-settings clearfix">';
			elseif($setting['Setting']['setting_category_parent_id'] == '7'):
				$options['class'] = 'image-settings image-settings-height';
			endif;
			if (!empty($setting['Setting']['description']) && empty($options['after'])):
				$findReplace = array(
					'##SITE_NAME##' => Configure::read('site.name'),
				);
				$setting['Setting']['description'] = strtr($setting['Setting']['description'], $findReplace);
				$options['help'] = "{$setting['Setting']['description']}";
			endif;
            //default account
            if($setting['Setting']['id'] == '72'):
				if(empty($empty_language)):
					echo $this->Form->input("Setting.{$setting['Setting']['id']}.name", $options);
				endif;
			else:
				if($setting['Setting']['id'] == '151' || $setting['Setting']['id'] == '152' || $setting['Setting']['id'] == '153'):
					$options['after'] = __l('points');
					?>
					<div class="point">
					<?php
				endif;
				echo $this->Form->input("Setting.{$setting['Setting']['id']}.name", $options);
				if($setting['Setting']['id'] == '151' || $setting['Setting']['id'] == '152' || $setting['Setting']['id'] == '153'):
					?>				
					</div>
				<?php
				endif;				
			endif;
			if($setting['Setting']['setting_category_parent_id'] == '7' && !$inputDisplay++):
                echo '<div class="input image-separator">X</div>';
			endif;
			if($setting['Setting']['setting_category_parent_id'] == '7' && $inputDisplay == 2):
				echo '</div>';
			endif;
			$inputDisplay = ($inputDisplay == 2) ? 0 : $inputDisplay;
            unset($options);
			if(in_array($setting['Setting']['id'], array(172, 170, 167) ) ) {
			?>
				</div>
				</div>
			<?php
			}		endforeach;
		if(!empty($beyondOriginals)){
            echo $this->Form->input('not_allow_beyond_original', array('label' => __l('Not Allow Beyond Original'),'type' => 'select', 'multiple' => 'multiple', 'options' => $beyondOriginals));
        }
        if(!empty($aspects)){
            echo $this->Form->input('allow_handle_aspect', array('label' => __l('Allow Handle Aspect'),'type' => 'select', 'multiple' => 'multiple', 'options' => $aspects));
        } ?>
		
		
		<?php
			if(!empty($sightingRatingTypes)):
		?>	
		<div class="point point-setting-block">
			<p class="setting-info"><?php echo __l('For every ratings received on sightings, user gets');?></p>
			<?php				
				foreach($sightingRatingTypes as $sightingRatingType) :
				echo $this->Form->input('Sighting.'.$sightingRatingType['SightingRatingType']['id'].'.point', array('after' => __l('points'), 'label' => $sightingRatingType['SightingRatingType']['name'], 'value' => $sightingRatingType['SightingRatingType']['tip_points']));
				endforeach;				
			?>
		</div>
		<?php
			endif;
		?>
	<?php
		if(!empty($reviewRatingTypes)):
	?>
		<div class="point point-setting-block">
			<p class="setting-info"><?php echo __l('For every ratings received on reviews, user gets');?></p>
			<?php				
				foreach($reviewRatingTypes as $reviewRatingType) :
				echo $this->Form->input('Review.'.$reviewRatingType['ReviewRatingType']['id'].'.point', array('after' => __l('points'), 'label' => $reviewRatingType['ReviewRatingType']['name'], 'value' => $reviewRatingType['ReviewRatingType']['tip_points']));
				endforeach;
			?>
		</div>
		<?php
			endif;
		?>	
    <div class="submit-block clearfix">
    <?php	echo $this->Form->end('Update'); ?>
    </div>
    <?php
	else:
?>
		<div class="notice"><?php echo __l('No settings available'); ?></div>
<?php
	endif;
?>
</div>
