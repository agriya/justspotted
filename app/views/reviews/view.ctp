<?php /* SVN: $Id: $ */ ?>
<div class="reviews view clearfix">
<h2><?php echo $this->Html->link($this->Html->cText($review['Sighting']['Item']['name']), array('controller' => 'sightings', 'action' => 'index', 'item' => $review['Sighting']['Item']['slug']), array('escape' => false)) . ' @ ' .$this->Html->link($this->Html->cText($review['Sighting']['Place']['name']), array('controller' => 'places', 'action' => 'view', $review['Sighting']['Place']['slug']), array('escape' => false)); ?></h2>
<div class="clearfix">
 <div class="side1 grid_16 alpha omega">
      <div class="spot-tl">
        <div class="spot-tr">
          <div class="spot-tm"> </div>
        </div>
      </div>
      <div class="spot-lm">
        <div class="spot-rm">
          <div class="spot-middle center-spot-middle clearfix">
             <?php echo $this->Html->showImage('Review', $review['Attachment'], array('dimension' => 'very_big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $review['User']['username']), 'title' => __l('Review')));?>
                  <div class="clearfix view-block sighting-right-block">
                <div class="clearfix">
                    <?php echo $this->Html->link($this->Html->showImage('UserAvatar', $this->Html->getUserAvatar($review['User']['id']), array('dimension' => 'medium_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Auth->user('username')), 'title' => $this->Auth->user('username'), 'escape' => false)), array('controller' => 'users', 'action' => 'view',  $review['User']['username']), array('escape' => false)).' '.$this->Html->link($review['User']['username'], array('controller' => 'users', 'action' => 'view', $review['User']['username']),array('title' => $review['User']['username']));?>
	                 <?php echo __l('Created on'); ?><span class="date"><?php echo $this->Time->timeAgoInWords($review['Review']['created']);?></span>
                </div>
                <div class="content-block">
        			<p class="sighting-caption"><?php echo $this->Html->cText($review['Review']['notes']);?></p>
					<?php if($this->Auth->sessionValid() && ($this->Auth->user('user_type_id') == ConstUserTypes::Admin || $this->Auth->user('id') == $review['Review']['user_id'])) { ?>
					 <div class="delete-block">
							<?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $review['Review']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?>
                            <?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $review['Review']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
					</div>
					<?php } ?>

                </div>
                </div>
<div class="comment-box-block">
<div class="round-tl">
            <div class="round-tr">
              <div class="round-tm"> </div>
            </div>
          </div>
        <div class="comment-box-inner clearfix">
                <ul class="great-list clearfix">
                    <li class="view">
                       <span class="count-info">
                            <?php echo __l('Views'); ?>
                			<?php echo $this->Html->cInt($review['Review']['review_view_count']);?>
            			</span>
                    </li>
        			<li class="view">
            			<span class="count-info">
                            <?php echo __l('Comments'); ?>
                			<?php echo $this->Html->cInt($review['Review']['review_comment_count']);?>
            			</span>
                    </li>
                    <li>
                        <?php echo $this->element('review_rating_types-index', array('sighting_id' => $review['Review']['sighting_id'], 'review_id' => $review['Review']['id'],'type'=>'view'));?>
                    </li>
                </ul>
                <div class="comment-block">
                    <div class="js-response-link<?php echo $review['Review']['id']; ?>">
            		<?php
            		if(empty($this->request->params['named']['type'])){
                            if($review['Review']['review_comment_count'] >5){
                                echo $this->Html->link(__l('View all ') . $review['Review']['review_comment_count'] . __l(' comments'),array('controller' => 'review_comments', 'action' => 'index', $review['Review']['id'],'type'=>'all'),array('class'=>'js-link {"review_id" :"' . $review['Review']['id'] . '"}'));
                            }
                        }
            			echo $this->element('review_comments-index', array('config' => 'sec','type'=>'view'));
            		?>
            	   </div>
            	   <?php
            	   if($this->Auth->sessionValid()):
        				echo $this->element('../review_comments/add', array('config' => 'sec'));
        			endif; ?>
        	   </div>
        	</div>
        	<div class="round-bl">
                <div class="round-br">
                  <div class="round-tm"> </div>
                </div>
          </div>
          </div>
        	
            </div>
          </div>
        </div>
        <div class="spot-bl">
        <div class="spot-br">
          <div class="spot-bm"> </div>
        </div>
      </div>
	</div>
<div class="side2 grid_8 alpha omega">
    <div class="people-list-block clearfix">
            <div class="round-tl">
              <div class="round-tr">
                <div class="round-tm"> </div>
              </div>
            </div>
            <div class="people-list-inner about-guide-block clearfix">
            <div class="clearfix">
             <div class="image-block grid_3 alpha omega">
                 <?php echo $this->Html->link($this->Html->showImage('UserAvatar', $this->Html->getUserAvatar($review['User']['id']), array('dimension' => 'normal_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Auth->user('username')), 'title' => $this->Auth->user('username'), 'escape' => false)), array('controller' => 'users', 'action' => 'view',  $review['User']['username']), array('escape' => false));?>
             </div>
             <div class="grid_4 alpha omega">
              <h3>
                <?php echo $this->Html->link($review['User']['username'], array('controller' => 'users', 'action' => 'view', $review['User']['username']),array('title' => $review['User']['username']));?>
              </h3>

			 <?php
					echo $this->element('users-sighting-rating', array('username' => $review['User']['id'], 'config' => 'site_element_cache_5_min'));
				?>
             </div>
             	</div>		  <address><?php $i = 0; $class = ' class="altrow"';?>
        		<?php
        			$address = array();
        			if(!empty($review['User']['UserProfile']['City']['name'])):
        				$address[] = $review['User']['UserProfile']['City']['name'];
        			endif;
        			if(!empty($review['User']['UserProfile']['State']['name'])):
        				$address[] = $review['User']['UserProfile']['State']['name'];
        			endif;
        			if(!empty($review['User']['UserProfile']['Country']['name'])):
        				$address[] = $review['User']['UserProfile']['Country']['name'];
        			endif;
        			if(!empty($address)):
        				echo $this->Html->cText($this->Html->cText(implode(', ', $address)));
        			endif;
        		?>
        	 </address>
             </div>
             <div class="round-bl">
              <div class="round-br">
                <div class="round-tm"> </div>
              </div>
            </div>
        </div>
        <div class="people-list-block clearfix">
            <div class="round-tl">
              <div class="round-tr">
                <div class="round-tm"> </div>
              </div>
            </div>
			<div class="people-list-inner about-guide-block">
                <?php
					(!empty($review['Sighting']['Place']['City']['latitude']) ? $location['latitude'] = $review['Sighting']['Place']['City']['latitude'] :'');
					(!empty($review['Sighting']['Place']['City']['longitude']) ? $location['longitude'] = $review['Sighting']['Place']['City']['longitude'] :'');
				?>	
      	     	<?php if(!empty($location['latitude']) && !empty($location['longitude'])):?>
                    <div class="side2-map-block">
                        <?php 
							 if(Configure::read('GoogleMap.embedd_map') == 'Embedded'){
                       			 echo $this->Html->formGooglemap($location,'265x102','1');
                    		}
                    		else{
								echo $this->Html->image($this->Html->formGooglemap($location,'265x102'));
							 }?>
                           
                    </div>
                <?php endif;?>
                <dl class="list clearfix">
                    <dt><?php echo __l('Address');?></dt>
					<dd><?php echo $this->Html->link($this->Html->cText($review['Sighting']['Place']['address2']), 'http://maps.google.com/maps?f=d&sll='.$review['Sighting']['Place']['City']['latitude'] .','.$review['Sighting']['Place']['City']['longitude'], array('target' => '_blank', 'escape' => false));?></dd>
            	</dl>
			</div>
             <div class="round-bl">
              <div class="round-br">
                <div class="round-tm"> </div>
              </div>
            </div>
        </div>
	</div>
	</div>
	<?php
        if($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
            ?>
        <div class="jobs-admin-tabs-block">
            <h5 class="admin-panel"><?php echo __l('Admin Panel'); ?></h5>
          <div class="js-tabs">
            <ul class="clearfix tab-menu">
			  <li><em></em><?php echo $this->Html->link(__l('Action'), '#admin-action'); ?></li>
              <li><em></em><?php echo $this->Html->link(__l('Views'), array('controller' => 'review_views', 'action' => 'index', 'review' => $review['Review']['id'], 'simple_view' => 1, 'admin' => true), array('title' => __l('Views'), 'escape' => false)); ?></li>
              <li><em></em><?php echo $this->Html->link(__l('Comments'), array('controller' => 'review_comments', 'action' => 'index', 'review' => $review['Review']['id'], 'simple_view' => 1, 'admin' => true), array('title' => __l('Comments'), 'escape' => false)); ?></li>
            </ul>
			<div id="admin-action">
	        <div class="people-list-block clearfix">
				<div class="round-tl">
				  <div class="round-tr">
					<div class="round-tm"> </div>
				  </div>
				</div>
			   <div class="people-list-inner clearfix">
                    <div class="grid_left">
						<?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $review['Review']['id'], 'admin' => true), array('class' => 'edit js-edit', 'title' => __l('Edit')));?>
						<?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $review['Review']['id'], 'admin' => true), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
                        <?php if(empty($review['Review']['is_system_flagged'])) { ?>
						<?php echo $this->Html->link(__l('Flag'), array('action' => 'update_status', $review['Review']['id'], 'status' => 'flag', 'page' => (!empty($this->request->params['named']['page']) ? $this->request->params['named']['page'] : ''), 'admin' => true), array('class' => 'flag', 'title' => __l('Flag')));?>
                        <?php } else { ?>
                        <span class="page-info"><?php echo __l('Review has been flagged');?></span>
                        <?php echo $this->Html->link(__l('Clear flag'), array('controller' => 'reviews', 'action' => 'update_status', $review['Review']['id'], 'status' => 'unflag', 'f' => $this->request->url , 'admin' => true), array('class' => 'flag grid_right', 'title' => __l('Clear flag')));
                        } ?>
                        <?php   if(empty($review['Review']['admin_suspend'])) { ?>
						<?php echo $this->Html->link(__l('Suspend'), array('action' => 'update_status', $review['Review']['id'], 'status' => 'suspend', 'page' => (!empty($this->request->params['named']['page']) ? $this->request->params['named']['page'] : ''), 'admin' => true), array('class' => 'suspend-icon', 'title' => __l('Suspend')));?>
                        <?php } else { ?>
                        <span class="page-info"><?php echo __l('Review has been suspended');?></span>
                        <?php echo $this->Html->link(__l('Unsuspend'), array('controller' => 'reviews', 'action' => 'update_status', $review['Review']['id'], 'status' => 'unsuspend', 'admin' => true), array('class' => 'suspend-icon', 'title' => __l('Unsuspend')));
                        } ?>
					</div>
    				</div>
    				<div class="round-bl">
					  <div class="round-br">
						<div class="round-tm"> </div>
					  </div>
					</div>
				</div>
		</div>
          </div>
        </div>
        <?php
        endif; ?>
</div>