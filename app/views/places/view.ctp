<?php /* SVN: $Id: $ */ ?>
<div class="places view clearfix">
<div class="clearfix">
     <div class="side1 grid_16 alpha omega">
         <h2><?php echo $place['Place']['name'];?></h2>
            <address class="address-block upload-share-block">
                <?php	echo $this->Html->cText($place['Place']['address2']); ?>
            </address>
			<?php echo $this->element('reviews-add', array('place' => $place['Place']['slug'], 'config' => 'site_element_cache_5_min')); ?>
	        <?php 
			if($place['Place']['item_count']) {
				echo $this->element('sightings-index', array('place' => $place['Place']['slug'], 'config' => 'site_element_cache_5_min'));
			}
			?>
            <?php if($place['Place']['business_update_count']){ ?>
                <div class="updates-block clearfix">
                <div class="spot-tl">
                    <div class="spot-tr">
                      <div class="spot-tm"> </div>
                    </div>
              </div>
                <div class="spot-lm">
                    <div class="spot-rm">
                        <div class="spot-middle center-spot-middle clearfix">
                               <?php echo $this->element('business_updates-business', array('place' => $place['Place']['slug'] , 'config' => 'site_element_cache_2_min')); ?>
                        </div>
                    </div>
                  </div>
                <div class="spot-bl">
                    <div class="spot-br">
                        <div class="spot-bm"> </div>
                    </div>
                  </div>
            </div>
		<?php } ?>
    </div>
    <div class="side2 grid_8 alpha omega">
    <?php if($this->Auth->user('is_business_user')){ ?>
        <div class="people-list-block business-banel-block clearfix">
            <div class="business-tl">
                <div class="business-tr">
                    <div class="business-tm"> </div>
                </div>
            </div>
            <div class="business-center js-responses">
                  <h3 class="panel-title"><?php echo __l('Business Panel');?></h3>
                    <?php if(!empty($place['Place']['business_id'])){ ?>
                        <span class="panel-info"><?php echo __l('Currently it belong to ') . $this->Html->link($place['Business']['name'], array('controller' => 'businesses', 'action' => 'view', $place['Business']['slug'],), array('title' => $place['Business']['name'], 'escape' => false));?></span>
                    <?php } ?>
                    <div class="clearfix panel-inner-block">
                        <div class="grid_left panel-left-block omega alpha">
                            <?php if($this->Auth->user('id') != $place['Business']['user_id'] || empty($place['Business']['user_id'])){?>
                                <div class="follow-block follow-block2"><?php echo $this->Html->link(__l('This place is mine'), array('controller' => 'place_claim_requests', 'action' => 'add', 'place'=>$place['Place']['slug']), array( 'title' => __l('This place is mine')));?></div>
                            
                            <?php } else{ ?>
                                <div class="unfollow-block follow-block"><?php echo $this->Html->link(__l('Remove'), array('controller' => 'place_claim_requests', 'action' => 'update', $place['Place']['id']), array( 'title' => __l('Remove')));?></div>
                            <?php } ?>
                        </div>
						<p class="grid_right panel-right-block alpha">
						<?php if($this->Auth->user('id') != $place['Business']['user_id'] || empty($place['Business']['user_id'])){ ?>
							<?php echo __l('By clicking this option, request will be send to Admin. Once Admin approves, the places will be associated with your business');
								} else {
									echo __l('This place is already associated with your business through Admin approval process. If you remove, this place will be disassociated with your business.');
								}
							?>
						</p>
                    </div>
         
            </div>
            <div class="business-bl">
                <div class="business-br">
                    <div class="business-bm"> </div>
                 </div>
            </div>
        </div>
        <?php } ?>

 
		<?php
        if($this->Auth->user('id')) :
            if(!empty($place['PlaceFollower'])): ?>
            <div class="follow-block1 unfollow-block1 follow-block">
            <?php echo $this->Html->link(__l('Unfollow'), array('controller' => 'place_followers', 'action' => 'delete', $place['PlaceFollower'][0]['id']), array('escape' => false, 'title' => __l('Unfollow')));?>
            </div>
            <?php else: ?>
            <div class="follow-block1 follow-block">
            <?php echo $this->Html->link(__l('Follow'), array('controller' => 'place_followers', 'action' => 'add', 'place' => $place['Place']['slug']), array('escape' => false, 'title' => __l('Follow')));?>
            </div>
            <?php endif;
        endif;
        ?>

    <div class="people-list-block clearfix">
            <div class="round-tl">
              <div class="round-tr">
                 <div class="round-tm"> </div>
              </div>
            </div>
            <div class="people-list-inner">
                <div class="side2-map-block">
                <?php
                if(!empty($place['Place'])):
                    $placedetails = $place['Place'];
                    if(Configure::read('GoogleMap.embedd_map') == 'Embedded'){
                        echo $this->Html->formGooglemap($placedetails,'265x102','1');
                    }
                    else{
                        echo $this->Html->image($this->Html->formGooglemap($placedetails,'265x102','static'));
                    }
                endif;
                ?>
                </div>
                <dl class="list map-list clearfix">
                    <dt><?php echo __l('Address');?></dt>
                    <dd><?php echo $this->Html->link($this->Html->cText($place['Place']['address2']), 'http://maps.google.com/maps?f=d&sll='.$place['Place']['latitude'] .','.$place['Place']['longitude'], array('target' => '_blank', 'escape' => false));?></dd>
            	</dl>
			</div>
			<div class="round-bl">
              <div class="round-br">
                <div class="round-tm"> </div>
              </div>
            </div>
            </div>
 	<?php if(!empty($place['Place']['business_id'])) : ?>
    <h3><?php echo __l('About Business'); ?></h3>
    <div class="people-list-block sighting-rating-block1 clearfix">
      <div class="round-tl">
          <div class="round-tr">
            <div class="round-tm"> </div>
          </div>
        </div>
        <div class="people-list-inner about-guide-block clearfix">
          <h3>
		   <?php echo $this->Html->link($place['Business']['name'], array('controller' => 'businesses', 'action' => 'view', $place['Business']['slug']), array('escape' => false, 'title' => $place['Business']['name']));?>
		  </h3>
			<div class="clearfix">
                <div class="image-block grid_3 alpha omega">
                	<?php echo $this->Html->link($this->Html->showImage('Business', $place['Business']['Attachment'], array('dimension' => 'normal_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($place['Business']['name'], false)), 'title' => $this->Html->cText($place['Business']['name'], false))), array('controller' => 'businesses', 'action' => 'view', $place['Business']['slug']), array('escape' => false, 'title' => $place['Business']['name']));?>
                </div>
                <div class="grid_4 alpha omega">
                    <div class="userSightingRatingStats index">
                        <ul class="business-list clearfix">
                            <li>
                                <span class="sighting">
                                    <?php echo __l('Items').': '. $this->Html->cInt($item_count); ?>
                                </span>
                            </li>
                            <li>
                                <span class="sighting">
                                    <?php echo __l('Places').': '. $this->Html->cInt($place['Business']['place_count']); ?>
                                </span>
                            </li>
                		</ul>
                        </div>
            		</div>
		</div>		
        <div class="clearfix">
                    <h4><?php echo __l('Other Places'); ?></h4>
				    <?php echo $this->element('places-index', array('view' => 'simple', 'business_slug' => $place['Business']['slug'], 'from' => $place['Place']['id'], 'config' => 'site_element_cache_2_min')); ?>
                    </div>
        </div>
        <div class="round-bl">
            <div class="round-br">
                <div class="round-tm"> </div>
            </div>
        </div>
    </div>
	<?php endif; ?>
<?php  
			if($this->Auth->user('id')){
				echo  $this->element('reviews-top_spotter', array('place_id' => $place['Place']['id']));
			}
			else{
				echo  $this->element('reviews-top_spotter', array('place_id' => $place['Place']['id'], 'config' => 'site_element_cache_10_min'));
			}
?>
        <?php  echo  $this->element('place_follows-index', array('follower' => $place['Place']['slug'], 'config' => 'sec'));?>	
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
          <li><em></em><?php echo $this->Html->link(__l('Views'), array('controller' => 'place_views', 'action' => 'index', 'place' => $place['Place']['slug'], 'simple_view' => 1, 'admin' => true), array('title' => __l('Views'), 'escape' => false)); ?></li>
          <li><em></em><?php echo $this->Html->link(__l('Follows'), array('controller' => 'place_followers', 'action' => 'index', 'place' => $place['Place']['slug'], 'simple_view' => 1, 'admin' => true), array('title' => __l('Follows'), 'escape' => false)); ?></li>
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
						<?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $place['Place']['id'], 'admin' => true), array('class' => 'edit js-edit', 'title' => __l('Edit')));?>
						<?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $place['Place']['id'], 'admin' => true), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
                        <?php if(empty($place['Place']['is_system_flagged'])) { ?>
						<?php echo $this->Html->link(__l('Flag'), array('action' => 'update_status', $place['Place']['id'], 'status' => 'flag', 'page' => (!empty($this->request->params['named']['page']) ? $this->request->params['named']['page'] : ''), 'admin' => true), array('class' => 'flag', 'title' => __l('Flag')));?>
                        <?php } else {?>
                        <span class="page-info"><?php echo __l('Place has been flagged');?></span>
                        <?php echo $this->Html->link(__l('Clear flag'), array('controller' => 'places', 'action' => 'update_status', $place['Place']['id'], 'status' => 'unflag', 'f' => $this->request->url , 'admin' => true), array('class' => 'flag grid_right', 'title' => __l('Clear flag')));
                         } ?>
                        <?php if(empty($place['Place']['admin_suspend'])) { ?>
						<?php echo $this->Html->link(__l('Suspend'), array('action' => 'update_status', $place['Place']['id'], 'status' => 'suspend', 'page' => (!empty($this->request->params['named']['page']) ? $this->request->params['named']['page'] : ''), 'admin' => true), array('class' => 'suspend-icon', 'title' => __l('Suspend')));?>
                        <?php } else {  ?>
                        <span class="page-info"><?php echo __l('Place has been suspended');?></span>
                        <?php echo $this->Html->link(__l('Unsuspend'), array('controller' => 'places', 'action' => 'update_status', $place['Place']['id'], 'status' => 'unsuspend', 'admin' => true), array('class' => 'suspend-icon', 'title' => __l('Unsuspend')));
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
