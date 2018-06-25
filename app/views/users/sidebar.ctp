        <?php
    	if($user['User']['id']!=$this->Auth->user('id')){
    	?>
    
        	<?php if(empty($user_follow)){ ?>
        	<div class="follow-block1 follow-block">
        	<?php echo $this->Html->link(__l('Follow'), array('controller' => 'user_followers', 'action' => 'add', 'user' => $user['User']['username']), array('escape' => false, 'title' => __l('Follow'))); ?>
            </div>
            <?php } 
              else { ?>
         	<div class="follow-block1 unfollow-block1 follow-block">
              <?php echo $this->Html->link(__l('Unfollow'), array('controller' => 'user_followers', 'action' => 'delete', $user_follow[0]['UserFollower']['id']), array('escape' => false, 'title' => __l('Unfollow')));?>
             </div>
            <?php }?>
            
       <?php } ?>
 
	<div class="people-list-block clearfix">
	   <div class="grid_3 alpha">
          <div class="round-tl">
              <div class="round-tr">
                <div class="round-tm"> </div>
              </div>
            </div>
            <div class="people-list-inner about-guide-block clearfix">
    			<dl class="point-display  point-display-sep">
    				<dt><?php echo __l('spotted');?></dt><dd class="point-display"><?php echo $this->Html->cInt($user['User']['guide_count']);?></dd>
    			</dl>
    	   </div>
            <div class="round-bl">
                <div class="round-br">
                    <div class="round-tm"> </div>
                </div>
            </div>
        </div>
        <div class="grid_5 grid_right omega">
            <div class="round-tl">
              <div class="round-tr">
                 <div class="round-tm"> </div>
              </div>
            </div>
            <div class="people-list-inner about-guide-block clearfix">
    			<dl class="point-display point-display1 grid_left">
    				<dt><?php echo __l('points earned');?></dt>
    				<dd class="point-display">
                        <?php
    					if($user['User']['tip_points']>0)
    					{
    						echo "+ " .  $this->Html->cInt($user['User']['tip_points']);
    					}
    					else
    					{
    						echo $this->Html->cInt($user['User']['tip_points']);
    					}
                        ?>
    				</dd>
    			</dl>
    	    </div>
            <div class="round-bl">
                <div class="round-br">
                    <div class="round-tm"> </div>
                </div>
            </div>
        </div>
    </div>
    <div class="people-list-block clearfix">
      <div class="round-tl">
          <div class="round-tr">
            <div class="round-tm"> </div>
          </div>
        </div>
        <div class="people-list-inner about-guide-block clearfix">
          <h3><?php echo $this->Html->cText($user['User']['username']);?></h3>
			<div class="clearfix">
                <div class="image-block grid_3 alpha omega">
                	<?php echo $this->Html->showImage('UserAvatar', $user['UserAvatar'], array('dimension' => 'normal_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($user['User']['username'], false)), 'title' => $this->Html->cText($user['User']['username'], false)));?>

                </div>
                <div class="grid_4 alpha omega">
                  
                   <div class="clearfix">
                        <?php echo $this->element('users-sighting-rating', array('username' => $user['User']['id'], 'config' => 'site_element_cache_5_min')); ?>
        		</div>
<?php
                			$address = array();
                			if(!empty($user['UserProfile']['City']['name'])):
                				$address[] = $user['UserProfile']['City']['name'];
                			endif;
                			if(!empty($user['UserProfile']['State']['name'])):
                				$address[] = $user['UserProfile']['State']['name'];
                			endif;
                			if(!empty($user['UserProfile']['Country']['name'])):
                				$address[] = $user['UserProfile']['Country']['name'];
                			endif;
					if(!empty($address)):
?>
        				<h4 class="location-title"><?php echo __l('Location:');?></h4>
					<address>
						<?php $i = 0; $class = ' class="altrow"';?>
<?php          									
						echo $this->Html->cText(implode(', ', $address));
?>
            				</address>
<?php					endif; ?>

            		</div>
		</div>		
        </div>
        <div class="round-bl">
            <div class="round-br">
                <div class="round-tm"> </div>
            </div>
        </div>
    </div>
        <div class="people-list-block sighting-rating-block1 clearfix">
            <h3><?php echo __l('Followings'); ?></h3>			
            <div class="round-tl">
                <div class="round-tr">
                    <div class="round-tm"> </div>
                </div>
            </div>
            <div class="people-list-inner clearfix">
				<div class="followings-inner">
				<h4><?php echo __l('People:'); ?></h4>
            	<?php
                    echo $this->element('user_follows-index', array('following' => $user['User']['username'], 'config' => 'site_element_cache_5_min'));
                ?>
				</div>
				<?php if($user['User']['place_follower_count']) {?>
				<div class="followings-inner">
					<h4><?php echo __l('Place:'); ?></h4>
					<?php echo $this->element('place_follows-index', array('user' => $user['User']['username'], 'config' => 'site_element_cache_5_min'));	?>
					</div>
				<?php } ?>
				<?php if($user['User']['item_follower_count']>0) {?>
					<div class="followings-inner item-inner">
					<h4><?php echo __l('Items:'); ?></h4>
					<?php echo $this->element('item_follows-index', array('user' => $user['User']['username'], 'config' => 'site_element_cache_5_min')); ?>
					</div>
				<?php } ?>
            </div>
            <div class="round-bl">
                <div class="round-br">
                    <div class="round-tm"> </div>
                </div>
            </div>
        </div>
        <div class="people-list-block following-block clearfix">
            <h3><?php echo __l('Followers') . ' ('.$this->Html->cInt($user['User']['user_follower_count']).')'; ?></h3>
            <div class="round-tl">
                <div class="round-tr">
                    <div class="round-tm"> </div>
                </div>
            </div>
            <div class="people-list-inner">
            	<?php
                    echo $this->element('user_follows-index', array('follower' => $user['User']['username'], 'config' => 'site_element_cache_5_min'));
                ?>
            </div>
            <div class="round-bl">
                <div class="round-br">
                    <div class="round-tm"> </div>
                </div>
            </div>
     </div>
	 	<div class="people-list-block following-block clearfix">
			<?php if ($this->Auth->sessionValid() &&  $user['User']['id'] == $this->Auth->user('id')) {
                $user_label='My';
            }
            else{
                $user_label= $this->Html->cText($user['User']['username'])."'s";
            } ?>
            <h3><?php echo $user_label." ".__l('Popular Sightings'); ?></h3>
            <div class="round-tl">
                <div class="round-tr">
                    <div class="round-tm"> </div>
                </div>
            </div>
            <div class="people-list-inner">
            	<?php
                    echo $this->element('review-my_popular', array('user' => $user['User']['username'], 'config' => 'site_element_cache_5_min'));
                ?>
            </div>
            <div class="round-bl">
                <div class="round-br">
                    <div class="round-tm"> </div>
                </div>
            </div>
     </div>
		 <?php if(!empty($user['User']['guide_count']) || !empty($user['User']['guide_follower_count'])) { ?>
	 <div class="people-list-block sighting-rating-block1 clearfix">
            <div class="clearfix">
                <h3 class="grid_left"><?php echo __l('Guides'); ?></h3>
                <?php echo $this->Html->link(__l('Browse all guides'), array('controller' => 'guides', 'action' => 'index'), array('class'=>'browse browse1 grid_right','title' => __l('Browse all guides'))); ?>
            </div>
                        
		     <div class="round-tl">
                <div class="round-tr">
                    <div class="round-tm"> </div>
                </div>
            </div>
            <div class="people-list-inner">
       			<?php if(!empty($user['User']['guide_count'])) { ?>
    			<div class="followings-inner followings-inner-block">
                   <h4 class="created"><?php echo __l('Created'); ?></h4>
               	    <?php
        				echo $this->element('guides-index', array('username' => $user['User']['username'], 'view' => 'simple', 'type' => 'popular', 'config' => 'site_element_cache_10_min'));
        			?>
    			</div>
    			<?php } ?>
		      	<?php if(!empty($user['User']['guide_follower_count'])) { ?>
				<div class="followings-inner item-inner">
                <div class="clearfix guides-inner-block">
                    <h4 class="grid_left"><?php echo __l('Followings'); ?></h4>
    				<ul class="following-list grid_right clearfix">
                        <li>
                        	<?php if ($this->Auth->sessionValid() &&  $user['User']['id'] == $this->Auth->user('id')) {
									$user_label="you're following";
							}
							else{
								$user_label= $this->Html->cText($user['User']['username'], false) . "'s following";
							} ?>
                            <?php echo $this->Html->link($user_label, array('controller' => 'guides', 'action' => 'index','following'=>$user['User']['username']), array('class'=>'following','title' => $user_label)); ?>
                        </li>
                    </ul>
                  </div>
			 	<?php echo $this->element('guides-index', array('following' => $user['User']['username'], 'view' => 'simple', 'type' => 'popular', 'config' => 'site_element_cache_10_min')); ?>
				</div>
				<?php } ?>
			</div>
            <div class="round-bl">
                <div class="round-br">
                    <div class="round-tm"> </div>
                </div>
            </div>

     </div>
	<?php } ?>

	 