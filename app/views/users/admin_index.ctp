<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>

<div class="users index js-response">
  <ul class="filter-list clearfix">
    	<li <?php if (!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::Active) { echo 'class="active"';} ?>><span class="active" title="<?php echo __l('Active Users'); ?>"><?php echo $this->Html->link( $this->Html->cInt($active, false) . '<span>' . __l('Active') . '</span>', array('controller' => 'users', 'action' => 'index', 'filter_id' => ConstMoreAction::Active), array('escape' => false)); ?></span></li>
    	<li <?php if (!empty($this->request->params['named']['main_filter_id']) && $this->request->params['named']['main_filter_id'] == ConstMoreAction::Inactive) { echo 'class="active"';} ?>><span class="inactive" title="<?php echo __l('Inactive Users'); ?>"><?php echo $this->Html->link( $this->Html->cInt($inactive, false) . '<span>' . __l('Inactive') . '</span>', array('controller' => 'users', 'action' => 'index', 'main_filter_id' => ConstMoreAction::Inactive), array('escape' => false)); ?></span></li>
    	<li <?php if (!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::Site) { echo 'class="active"';} ?>><span class="site" title="<?php echo __l('Site Users'); ?>"><?php echo $this->Html->link( $this->Html->cInt($site, false) . '<span>' . __l('Site') . '</span>', array('controller' => 'users', 'action' => 'index', 'filter_id' => ConstMoreAction::Site), array('escape' => false)); ?></span></li>
    	<li <?php if (!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::OpenID) { echo 'class="active"';} ?>><span class="openid" title="<?php echo __l('OpenID Users'); ?>"><?php echo $this->Html->link( $this->Html->cInt($openid, false) . '<span>' . __l('OpenID') . '</span>', array('controller' => 'users', 'action' => 'index', 'filter_id' => ConstMoreAction::OpenID), array('escape' => false)); ?></span></li>
    	<li <?php if (!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::Facebook) { echo 'class="active"';} ?>><span class="facebook" title="<?php echo __l('Facebook Users'); ?>"><?php echo $this->Html->link( $this->Html->cInt($facebook, false) . '<span>' . __l('Facebook') . '</span>', array('controller' => 'users', 'action' => 'index', 'filter_id' => ConstMoreAction::Facebook), array('escape' => false)); ?></span></li>
    	<li <?php if (!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::Twitter) { echo 'class="active"';} ?>><span class="twitter" title="<?php echo __l('Twitter Users'); ?>"><?php echo $this->Html->link( $this->Html->cInt($twitter, false) . '<span>' . __l('Twitter') . '</span>', array('controller' => 'users', 'action' => 'index', 'filter_id' => ConstMoreAction::Twitter), array('escape' => false)); ?></span></li>
    	<li <?php if (!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::Gmail) { echo 'class="active"';} ?>><span class="gmail" title="<?php echo __l('Gmail Users'); ?>"><?php echo $this->Html->link( $this->Html->cInt($gmail, false) . '<span>' . __l('Gmail') . '</span>', array('controller' => 'users', 'action' => 'index', 'filter_id' => ConstMoreAction::Gmail), array('escape' => false)); ?></span></li>
    	<li <?php if (!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::Yahoo) { echo 'class="active"';} ?>><span class="yahoo" title="<?php echo __l('Yahoo Users'); ?>"><?php echo $this->Html->link( $this->Html->cInt($yahoo, false) . '<span>' . __l('Yahoo') . '</span>', array('controller' => 'users', 'action' => 'index', 'filter_id' => ConstMoreAction::Yahoo), array('escape' => false)); ?></span></li>
		<li <?php if (!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::Foursquare) { echo 'class="active"';} ?>><span class="foursquare" title="<?php echo __l('Foursquare Users'); ?>"><?php echo $this->Html->link( $this->Html->cInt($foursquare, false) . '<span>' . __l('Foursquare') . '</span>', array('controller' => 'users', 'action' => 'index', 'filter_id' => ConstMoreAction::Foursquare), array('escape' => false)); ?></span></li>
    	<li <?php if (!empty($this->request->params['named']['main_filter_id']) && $this->request->params['named']['main_filter_id'] == ConstMoreAction::Business) { echo 'class="active"';} ?>><span class="business" title="<?php echo __l('Business Users'); ?>"><?php echo $this->Html->link( $this->Html->cInt($business, false) . '<span>' . __l('Business') . '</span>', array('controller' => 'users', 'action' => 'index', 'main_filter_id' => ConstMoreAction::Business), array('escape' => false)); ?></span></li>
    	<li <?php if (!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstUserTypes::Admin) { echo 'class="active"';} ?>><span class="yahoo" title="<?php echo __l('Admin Users'); ?>"><?php echo $this->Html->link( $this->Html->cInt($admin_count, false) . '<span>' . __l('Admin') . '</span>', array('controller' => 'users', 'action' => 'index', 'filter_id' => ConstUserTypes::Admin), array('escape' => false)); ?></span></li>
    	<li <?php if (!isset($this->request->params['named']['main_filter_id']) && !isset($this->request->params['named']['filter_id'])) { echo 'class="active"';} ?>><span class="total" title="<?php echo __l('Total Users'); ?>"><?php echo $this->Html->link( $this->Html->cInt($active + $inactive, false) . '<span>' . __l('Total') . '</span>', array('controller' => 'users', 'action' => 'index'), array('escape' => false)); ?></span></li>
  </ul>
  <div class="page-count-block clearfix">
        <?php echo $this->element('paging_counter'); ?>
        <div class="grid_left"> <?php echo $this->Form->create('User', array('class' => 'normal search-form clearfix', 'action'=>'index')); ?> <?php echo $this->Form->input('q', array('label' => 'Keyword')); ?> <?php echo $this->Form->submit(__l('Search'));?> <?php echo $this->Form->end(); ?> </div>
        <div class="grid_right"> <?php echo $this->Html->link(__l('Add'), array('controller' => 'users', 'action' => 'add'), array('class' => 'add','title'=>__l('Add'))); ?>
              <?php 	echo $this->Html->link(__l('Export'), array('controller' => 'users', 'action' => 'export', 'ext' => 'csv', 'admin' => true), array('title' => __l('Export'), 'class' => 'csv')); ?>
        </div>
  </div>
  <?php
	echo $this->Form->create('User' , array('class' => 'normal','action' => 'update'));
?>
  <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
  <div class="overflow-block">
    <table class="list">
      <tr class="js-even">
        <th rowspan="3" class="select"></th>
        <th rowspan="3" class="actions"><?php echo __l('Actions');?></th>
        <th rowspan="3" class="dl user-status-block"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('User'), 'User.username'); ?></div></th>
        <th rowspan="3" class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Points'), 'User.tip_points'); ?></div></th>
        <th <?php if (!empty($this->request->params['named']['main_filter_id']) && $this->request->params['named']['main_filter_id'] == ConstMoreAction::Business) {?>colspan="12" <?php } else {?>colspan="11"<?php } ?> class="dc"><div class="js-pagination"><?php echo  __l('Activity'); ?></div></th>
        <th rowspan="3" class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Followers'), 'User.user_following_count'); ?></div></th>
        <th colspan="3"><div class="js-pagination"><?php echo __l('Logins'); ?></div></th>
        <th rowspan="3" class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Registered On'), 'User.created'); ?></div></th>
      </tr>
      <tr class="js-even">
        <th <?php if (!empty($this->request->params['named']['main_filter_id']) && $this->request->params['named']['main_filter_id'] == ConstMoreAction::Business) {?>colspan="7" <?php } else {?>colspan="6"<?php } ?>><div class="js-pagination"><?php echo __l('Posted'); ?></div></th>
        <th colspan="5"><div class="js-pagination"><?php echo __l('Following'); ?></div></th>
        <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Count'), 'User.user_login_count'); ?></div></th>
        <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Time'), 'User.last_logged_in_time'); ?></div></th>
        <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('IP'), 'LastLoginIp.ip'); ?></div></th>
      </tr>
      <tr class="js-even">
        <th><div class="js-pagination"><span title="<?php echo __l('Sightings'); ?>" class ="tool-tip"><?php echo $this->Paginator->sort(__l('S'), 'User.sighting_count'); ?></span></div></th>
        <th><div class="js-pagination"><span title="<?php echo __l('Reviews'); ?>" class ="tool-tip"><?php echo $this->Paginator->sort(__l('R'), 'User.review_count'); ?></span></div></th>
        <th><div class="js-pagination"><span title="<?php echo __l('Comments'); ?>" class ="tool-tip"><?php echo $this->Paginator->sort(__l('C'), 'User.review_comment_count'); ?></span></div></th>
        <th><div class="js-pagination"><span title="<?php echo __l('Places'); ?>" class ="tool-tip"><?php echo $this->Paginator->sort(__l('P'), 'User.place_count'); ?></span></div></th>
        <th><div class="js-pagination"><span title="<?php echo __l('Items'); ?>" class ="tool-tip"><?php echo $this->Paginator->sort(__l('I'), 'User.item_count'); ?></span></div></th>
        <th><div class="js-pagination"><span title="<?php echo __l('Guides'); ?>" class ="tool-tip"><?php echo $this->Paginator->sort(__l('G'), 'User.guide_count'); ?></span></div></th>
<?php if (!empty($this->request->params['named']['main_filter_id']) && $this->request->params['named']['main_filter_id'] == ConstMoreAction::Business) {?>
        <th><div class="js-pagination"><span title="<?php echo __l('Business Updates Count'); ?>" class ="tool-tip"><?php echo $this->Paginator->sort(__l('BU'), 'User.business_update_count'); ?></span></div></th>
<?php }?>        
        <th><div class="js-pagination"><span title="<?php echo __l('Businesses'); ?>" class ="tool-tip"><?php echo $this->Paginator->sort(__l('B'), 'User.business_follower_count'); ?></span></div></th>
        <th><div class="js-pagination"><span title="<?php echo __l('Places'); ?>" class ="tool-tip"><?php echo $this->Paginator->sort(__l('P'), 'User.place_follower_count'); ?></span></div></th>
        <th><div class="js-pagination"><span title="<?php echo __l('Items'); ?>" class ="tool-tip"><?php echo $this->Paginator->sort(__l('I'), 'User.item_follower_count'); ?></span></div></th>
        <th><div class="js-pagination"><span title="<?php echo __l('Users'); ?>" class ="tool-tip"><?php echo $this->Paginator->sort(__l('U'), 'User.user_follower_count'); ?></span></div></th>
        <th><div class="js-pagination"><span title="<?php echo __l('Guides'); ?>" class ="tool-tip"><?php echo $this->Paginator->sort(__l('G'), 'User.guide_follower_count'); ?></span></div></th>
        <th></th>
        <th></th>
        <th></th>
      </tr>
      <?php
if (!empty($users)):
$i = 0;
foreach ($users as $user):
	$class = null;
		$active_class = '';
	if ($i++ % 2 == 0):
		$class = ' altrow';
	endif;
	 if($user['User']['is_active']):
      $status_class = 'js-checkbox-active';
      else:
	   $active_class = ' inactive-record';
       $status_class = 'js-checkbox-inactive';
       endif;
		$email_active_class = ' email-not-comfirmed';
		if($user['User']['is_email_confirmed']):
		$email_active_class = ' email-comfirmed';
		endif;
		if($user['User']['is_facebook_register']):
		$email_active_class = ' ';
		endif;
		if($user['User']['is_twitter_register']):
		$email_active_class = ' ';
		endif;				
		if($user['User']['is_openid_register'] || $user['User']['is_gmail_register'] || $user['User']['is_yahoo_register'] ):
		$email_active_class = ' ';
		endif;				
  	$online_class = 'offline';
	if (!empty($user['CkSession']['user_id'])) {
		$online_class = 'online';
	}
?>
      <tr class="<?php echo $class.$active_class;?> ">
        <td class="select"><div class="arrow"></div><?php echo $this->Form->input('User.'.$user['User']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$user['User']['id'], 'label' => false, 'class' => $status_class.' js-checkbox-list')); ?></td>
        <td class="actions">
               <div class="action-block">
                <span class="action-information-block">
                    <span class="action-left-block">&nbsp;&nbsp;</span>
                        <span class="action-center-block">
                            <span class="action-info">
                                <?php echo __l('Action');?>
                             </span>
                        </span>
                    </span>
                    <div class="action-inner-block">
                    <div class="action-inner-left-block">
                        <ul class="action-link clearfix">
                              <?php if(Configure::read('user.is_email_verification_for_register') and (!$user['User']['is_active'] or !$user['User']['is_email_confirmed'])): ?>
                              <li> <?php echo $this->Html->link(__l('Resend Activation'), array('controller' => 'users', 'action'=>'resend_activation', $user['User']['id'], 'admin' => false),array('class'=>'resend','title' => __l('Resend Activation'))); ?></li>
                              <?php endif;?>
                              <li><?php echo $this->Html->link(__l('Edit'), array('controller' => 'user_profiles', 'action'=>'edit', $user['User']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
                                <?php
								if($user['User']['is_business_user'] && !empty($user['Business']))
								{
                                ?>
                              <li><?php echo $this->Html->link(__l('Edit Business'), array('controller'=> 'businesses', 'action' => 'edit', $user['Business'][0]['id']), array('class' => 'edit js-edit','title'=>__l('Edit Business'), 'escape' => false));?> </li>
                                <?php
								}
                                ?>
                              <?php if($user['User']['user_type_id'] != ConstUserTypes::Admin){ ?>
                              <li><?php echo $this->Html->link(__l('Delete'), array('action'=>'delete', $user['User']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
                              <?php } ?>
                               <?php if(!$user['User']['is_facebook_register'] && !$user['User']['is_openid_register'] && !$user['User']['is_twitter_register'] && !$user['User']['is_foursquare_register'] && !$user['User']['is_gmail_register'] && !$user['User']['is_yahoo_register']){?>
                              <li><?php echo $this->Html->link(__l('Change password'), array('controller' => 'users', 'action'=>'admin_change_password', $user['User']['id']), array('class'=>'change-password','title' => __l('Change password')));?></li>
                               <?php  }?>
                              <li><?php echo $this->Html->link(__l('Ban Signup IP'), array('controller'=> 'banned_ips', 'action' => 'add', $user['SignupIp']['ip']), array('class' => 'network-ip','title'=>__l('Ban Sign up IP'), 'escape' => false));?> </li>

                        </ul>
						</div>
        	            <div class="action-bot-left">
	                        <div class="action-bot-right">
        					     <div class="action-bot-mid"></div>
        					</div>
                         </div>
					  </div>
					 </div>
        </td>
        <td class="dr user-status-block">
           <div class="clearfix user-info-block">
                        <div class="grid_left">
                        <div class="clearfix">
                              <p class="grid_left">
                                    <?php
            						$chnage_user_info = $user['User'];
                                   	$chnage_user_info['UserAvatar'] = $user['UserAvatar'];
                                  	$user['User']['full_name'] = (!empty($user['UserProfile']['first_name']) || !empty($user['UserProfile']['last_name'])) ? $user['UserProfile']['first_name'] . ' ' . $user['UserProfile']['last_name'] :  $user['User']['username'];
                                   	echo $this->Html->link($this->Html->getUserAvatarLink($chnage_user_info, 'micro_thumb',false), array('controller'=> 'users', 'action' => 'view', $user['User']['username'], 'admin' => false), array('escape' => false));
            						?>
                                     <?php  echo $this->Html->link($this->Html->cText($user['User']['username']), array('controller'=> 'users', 'action' => 'view', $user['User']['username'], 'admin' => false), array('escape' => false));
									 ?>
                              </p>
                          </div>
                          <?php
								if($user['User']['is_business_user'] && !empty($user['Business']))
								{
                                ?>
						
                                <p title="<?php  echo $this->Html->truncate($user['Business'][0]['about_your_business'], 90);?>" class="tool-tip">
                                    <?php
                                    echo $this->Html->link($this->Html->showImage('Business', $user['Business'][0]['Attachment'], array('dimension' => 'micro_thumb', 'alt' => sprintf(__l('[Image: %s]'), $user['Business'][0]['name']), 'title' => $user['Business'][0]['name'])), array('controller'=> 'businesses', 'action' => 'view', $user['Business'][0]['slug'], 'admin' => false), array('escape' => false));
                                    
									echo $this->Html->link($this->Html->cText($user['Business'][0]['name']), array('controller'=> 'businesses', 'action' => 'view', $user['Business'][0]['slug'], 'admin' => false), array('escape' => false));
									?>
                                 </p>
                                <?php
							     	}
                                ?>
                            <div class="clearfix user-status-block user-info-block">
                                    <?php
            							if(!empty($user['UserProfile']['Country'])):
            								?>
                                            <span class="flags flag-<?php echo strtolower($user['UserProfile']['Country']['iso2']); ?>" title ="<?php echo $user['UserProfile']['Country']['name']; ?>">
            									<?php echo $user['UserProfile']['Country']['name']; ?>
            								</span>
                                            <?php
            	                        endif;
            						?>


                                    <?php if($user['User']['is_openid_register']):?>
            								<span class="open_id" title="OpenID"> <?php echo __l('OpenID'); ?> </span>
            						<?php endif; ?>
                                    <?php if($user['User']['is_gmail_register']):?>
            								<span class="gmail" title="Gmail"> <?php echo __l('Gmail'); ?> </span>
            						<?php endif; ?>
                                    <?php if($user['User']['is_yahoo_register']):?>
            								<span class="yahoo" title="Yahoo"> <?php echo __l('Yahoo'); ?> </span>
            						<?php endif; ?>
                                    <?php if($user['User']['is_facebook_register']):?>
            								<span class="facebook" title="Facebook"> <?php echo __l('Facebook'); ?> </span>
            						<?php endif; ?>
                                    <?php if($user['User']['is_twitter_register']):?>
            								<span class="twitter" title="Twitter"> <?php echo __l('Twitter'); ?> </span>
            						<?php endif; ?>
                                            <?php if(!empty($user['User']['email'])):?>
            								<span class="email-comfirmed email <?php echo $email_active_class; ?>" title="<?php echo $user['User']['email']; ?>">
            								<?php
            								if(strlen($user['User']['email'])>20) :
            									echo '..' . substr($user['User']['email'], strlen($user['User']['email'])-15, strlen($user['User']['email']));
            								else:
            									echo $user['User']['email'];
            								endif;
            								?>
                                            </span>
            						<?php endif; ?>
    						</div>
                        </div>
                        
                       
                           	  <?php if($user['User']['user_type_id'] == ConstUserTypes::Admin):?>
                           	   <p class="user-img-right grid_right clearfix">
								    <span class="admin round-5"> <?php echo __l('Admin'); ?> </span>
								</p>
					       	   <?php endif; ?>
                          	  <?php if($user['User']['is_business_user']):?>
                           	  	<p class="user-img-right grid_right clearfix">
    								<span class="admin round-5"> <?php echo __l('Business'); ?> </span>
    							</p>
						      <?php endif; ?>
				
                        </div>
              
        </td>
        <td class="dr"><?php echo $this->Html->cInt($user['User']['tip_points']);?></td>
        <td class="dr"><?php echo $this->Html->link($this->Html->cInt($user['User']['sighting_count'], false), array('controller' => 'sightings', 'action' => 'index', 'username' => $user['User']['username']));?></td>
        <td class="dr"><?php echo $this->Html->link($this->Html->cInt($user['User']['review_count'], false), array('controller' => 'reviews', 'action' => 'index', 'username' => $user['User']['username']));?></td>
        <td class="dr"><?php echo $this->Html->link($this->Html->cInt($user['User']['review_comment_count'], false), array('controller' => 'review_comments', 'action' => 'index', 'username' => $user['User']['username']));?></td>
        <td class="dr"><?php echo $this->Html->link($this->Html->cInt($user['User']['place_count'], false), array('controller' => 'places', 'action' => 'index', 'username' => $user['User']['username']));?></td>
        <td class="dr"><?php echo $this->Html->link($this->Html->cInt($user['User']['item_count'], false), array('controller' => 'items', 'action' => 'index', 'username' => $user['User']['username']));?></td>
        <td class="dr"><?php echo $this->Html->link($this->Html->cInt($user['User']['guide_count'], false), array('controller' => 'guides', 'action' => 'index', 'username' => $user['User']['username']));?></td>
<?php if (!empty($this->request->params['named']['main_filter_id']) && $this->request->params['named']['main_filter_id'] == ConstMoreAction::Business) {?>
        <td class="dr"><?php echo $this->Html->link($this->Html->cInt($user['User']['business_update_count'], false), array('controller' => 'business_updates', 'action' => 'index', 'username' => $user['User']['username']));?></td>        
<?php } ?>        
        <td class="dr"><?php echo $this->Html->link($this->Html->cInt($user['User']['business_follower_count'], false), array('controller' => 'business_followers', 'action' => 'index', 'username' => $user['User']['username']));?></td>
        <td class="dr"><?php echo $this->Html->link($this->Html->cInt($user['User']['place_follower_count'], false), array('controller' => 'place_followers', 'action' => 'index', 'username' => $user['User']['username']));?></td>
        <td class="dr"><?php echo $this->Html->link($this->Html->cInt($user['User']['item_follower_count'], false), array('controller' => 'item_followers', 'action' => 'index', 'username' => $user['User']['username']));?></td>
        <td class="dr"><?php echo $this->Html->link($this->Html->cInt($user['User']['user_follower_count'], false), array('controller' => 'user_followers', 'action' => 'index', 'username' => $user['User']['username'], 'type' => 'following'));?></td>
        <td class="dr"><?php  echo $this->Html->link($this->Html->cInt($user['User']['guide_follower_count']), array('controller'=> 'guide_followers', 'action' => 'index', 'username' => $user['User']['username']), array('escape' => false));?></td>
        <td class="dr"><?php echo $this->Html->link($this->Html->cInt($user['User']['user_following_count'], false), array('controller' => 'user_followers', 'action' => 'index', 'username' => $user['User']['username'], 'type' => 'follower'));?></td>
        <td class="dr"><?php echo $this->Html->link($this->Html->cInt($user['User']['user_login_count'], false), array('controller' => 'user_logins', 'action' => 'index', 'username' => $user['User']['username']));?></td>
        <td class="dc"><?php if($user['User']['last_logged_in_time'] == '0000-00-00 00:00:00' || empty($user['User']['last_logged_in_time'])){
                                echo '-';
                            }else{
                                echo $this->Html->cDateTimeHighlight($user['User']['last_logged_in_time']);
                            }?></td>
        <td class="dl"><?php if(!empty($user['Ip']['ip'])): ?>
          <?php echo  $this->Html->cText($user['Ip']['ip']);
							?>
          <p>
            <?php
                            if(!empty($user['Ip']['Country'])):
                                ?>
            <span class="flags flag-<?php echo strtolower($user['Ip']['Country']['iso2']); ?>" title ="<?php echo $user['Ip']['Country']['name']; ?>"> <?php echo $user['Ip']['Country']['name']; ?> </span>
            <?php
                            endif;
							 if(!empty($user['Ip']['City'])):
                            ?>
            <span> <?php echo $user['Ip']['City']['name']; ?> </span>
            <?php endif; ?>
          </p>
          <?php else: ?>
          <?php echo __l('N/A'); ?>
          <?php endif; ?>
        </td>
        <td><?php echo $this->Html->cDateTimeHighlight($user['User']['created']);?></td>
      </tr>
      <tr class="hide">
        <td class="action-block" colspan="11">
		<div class="action-info-block clearfix">
		<div class="action-left-block">
            <h3> <?php echo __l('Action'); ?> </h3>
            <ul>
              <?php if(Configure::read('user.is_email_verification_for_register') and (!$user['User']['is_active'] or !$user['User']['is_email_confirmed'])): ?>
              <li> <?php echo $this->Html->link(__l('Resend Activation'), array('controller' => 'users', 'action'=>'resend_activation', $user['User']['id'], 'admin' => false),array('class'=>'resend','title' => __l('Resend Activation'))); ?></li>
              <?php endif;?>
              <li><?php echo $this->Html->link(__l('Edit'), array('controller' => 'user_profiles', 'action'=>'edit', $user['User']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
              <?php if($user['User']['user_type_id'] != ConstUserTypes::Admin){ ?>
              <li><?php echo $this->Html->link(__l('Delete'), array('action'=>'delete', $user['User']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
              <?php } ?>
              <li><?php echo $this->Html->link(__l('Change password'), array('controller' => 'users', 'action'=>'admin_change_password', $user['User']['id']), array('class'=>'change-password','title' => __l('Change password')));?></li>
              <li><?php echo $this->Html->link(__l('Ban Signup IP'), array('controller'=> 'banned_ips', 'action' => 'add', $user['SignupIp']['ip']), array('class' => 'network-ip','title'=>__l('Ban Sign up IP'), 'escape' => false));?> </li>
            </ul>
          </div>
          <div class="clearfix action-right-block">
            <div class="action-right action-right1">
              <h3><?php echo __l('Sightings'); ?></h3>
              <dl class="clearfix">
                <dt><?php echo __l('Sighings'); ?></dt>
                <dd>
                  <?php echo $this->Html->link($this->Html->cInt($user['User']['sighting_count'], false), array('controller' => 'sightings', 'action' => 'index', 'username' => $user['User']['username']));?>
                </dd>
                <dt><?php echo __l('Reviews'); ?></dt>
                <dd>
                  <?php echo $this->Html->link($this->Html->cInt($user['User']['review_count'], false), array('controller' => 'reviews', 'action' => 'index', 'username' => $user['User']['username']));?>
                </dd>
                <dt><?php echo __l('Review Comments'); ?></dt>
                <dd>
                  <?php echo $this->Html->link($this->Html->cInt($user['User']['review_comment_count'], false), array('controller' => 'review_comments', 'action' => 'index', 'username' => $user['User']['username']));?>
                </dd>
                <dt><?php echo __l('Flags'); ?></dt>
                <dd>
                  <?php echo $this->Html->link($this->Html->cInt($user['User']['sighting_flag_count'], false), array('controller' => 'sighting_flags', 'action' => 'index', 'username' => $user['User']['username']));?>
                </dd>
              </dl>
            </div>
            <div class="action-right">
              <h3><?php echo __l('Followings'); ?></h3>
              <dl class="clearfix">
                <dt><?php echo __l('Places'); ?></dt>
                <dd>
                  <?php echo $this->Html->link($this->Html->cInt($user['User']['place_follower_count'], false), array('controller' => 'place_followers', 'action' => 'index', 'username' => $user['User']['username']));?>
                </dd>
                <dt><?php echo __l('Items'); ?></dt>
                <dd>
                  <?php echo $this->Html->link($this->Html->cInt($user['User']['item_follower_count'], false), array('controller' => 'item_followers', 'action' => 'index', 'username' => $user['User']['username']));?>
                </dd>
                <dt><?php echo __l('Users'); ?></dt>
                <dd>
                  <?php echo $this->Html->link($this->Html->cInt($user['User']['user_follower_count'], false), array('controller' => 'user_followers', 'action' => 'index', 'username' => $user['User']['username'], 'type' => 'following'));?>
                </dd>
                <dt><?php echo __l('Guides'); ?></dt>
                <dd>
                  <?php  echo $this->Html->link($this->Html->cText($user['User']['guide_follower_count']), array('controller'=> 'users', 'action' => 'view', $user['User']['username'], 'admin' => false), array('escape' => false));?>
                </dd>
              </dl>
            </div>
            <div class="action-right">
              <h3><?php echo __l('Followers'); ?></h3>
              <dl class="clearfix">
                <dt><?php echo __l('User'); ?></dt>
                <dd>
					<?php echo $this->Html->link($this->Html->cInt($user['User']['user_following_count'], false), array('controller' => 'user_followers', 'action' => 'index', 'username' => $user['User']['username'], 'type' => 'follower'));?>
                </dd>
              </dl>
            </div>
			<div class="action-right">
              <h3><?php echo __l('User'); ?></h3>
              <dl class="clearfix">
                <dd>
						<?php  echo $this->Html->link($this->Html->getUserAvatarLink($chnage_user_info, 'normal_thumb',false), array('controller'=> 'users', 'action' => 'view', $user['User']['username'], 'admin' => false), array('escape' => false));?>
                </dd>
              </dl>
            </div>
          </div>
          </div>
          </td>
      </tr>
      <?php
    endforeach;
else:
?>
      <tr>
        <td colspan="14" class="notice"><?php echo __l('No users available');?></td>
      </tr>
      <?php
endif;
?>
    </table>
  </div>
  <?php
if (!empty($users)):
?>
  <div class="clearfix">
    <div class="admin-select-block grid_left">
      <div> <?php echo __l('Select:'); ?> <?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all', 'title' => __l('All'))); ?> <?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none', 'title' => __l('None'))); ?> <?php echo $this->Html->link(__l('Inactive'), '#', array('class' => 'js-admin-select-pending', 'title' => __l('Inactive'))); ?> <?php echo $this->Html->link(__l('Active'), '#', array('class' => 'js-admin-select-approved', 'title' => __l('Active'))); ?> </div>
      <div class="admin-checkbox-button"><?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?></div>
    </div>
    <div class="js-pagination grid_right"> <?php echo $this->element('paging_links'); ?> </div>
  </div>
  <div class="hide"> <?php echo $this->Form->submit('Submit'); ?> </div>
  <?php
endif;
echo $this->Form->end();
?>
</div>
