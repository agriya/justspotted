<?php /* SVN: $Id: $ */ ?>
<div class="sightings view sightings-view-block">
	<div class="clearfix">
    <div class="side1 grid_16 alpha omega">
    	<?php echo $this->element('reviews-add', array('sighting' => $sighting['Sighting']['id'], 'config' => 'site_element_cache_5_min')); ?>
	<h2><?php echo $this->Html->link($this->Html->cText($sighting['Item']['name']), array('controller' => 'sightings', 'action' => 'index', 'item' => $sighting['Item']['slug']), array('escape' => false)). ' @ ' .$this->Html->link($this->Html->cText($sighting['Place']['name']), array('controller' => 'places', 'action' => 'view', $sighting['Place']['slug']), array('escape' => false)); ?></h2>
    <div class="spot-tl">
        <div class="spot-tr">
          <div class="spot-tm"> </div>
        </div>
      </div>
      <div class="spot-lm">
        <div class="spot-rm">
          <div class="spot-middle sightings-inner clearfix">
    	       <?php echo $this->Html->link($this->Html->showImage('Review', $sighting['Review'][0]['Attachment'], array('dimension' => 'very_big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $sighting['Item']['name']), 'title' => $sighting['Item']['name'])), array('controller' => 'sightings', 'action' => 'view',  $sighting['Sighting']['id']), array('escape' => false)); ?>
            <div class="clearfix view-block">
            <div class="grid_2 alpha omega">
            <?php echo $this->Html->link($this->Html->showImage('UserAvatar', $this->Html->getUserAvatar($sighting['Review'][0]['User']['id']), array('dimension' => 'medium_thumb', 'alt' => sprintf(__l('[Image: %s]'), $sighting['Review'][0]['User']['username']), 'title' => $sighting['Review'][0]['User']['username'], 'escape' => false)), array('controller' => 'users', 'action' => 'view',  $sighting['Review'][0]['User']['username']), array('escape' => false));?>
            </div>
            <div class="content-block grid_10 alpha">
                <p>
            		<?php echo __l('Spotted by'); ?>
            		<?php echo $this->Html->link($sighting['Review'][0]['User']['username'], array('controller' => 'users', 'action' => 'view',  $sighting['Review'][0]['User']['username'])); ?>
            	</p>

            	   	<p><?php echo __l('Spotted on'); ?> <span class="date"><?php echo $this->Time->timeAgoInWords($sighting['Sighting']['created']);?></span></p>
            	   	</div>
            </div>
                <div class="spot-lm">
                <div class="spot-rm">
                <div class="sighting-middle2 clearfix">
                	<ul class="sighting-list grid_left clearfix">
                		<li class="reviews-count">
                		<?php echo __l('Reviews') . ' (';?>
                        <?php echo $this->Html->link($this->Html->cInt($sighting['Sighting']['review_count'], false), array('controller' => 'reviews', 'action' => 'index', 'sighting_id' => $sighting['Sighting']['id']), array('title' => $this->Html->cInt($sighting['Sighting']['review_count'], false))) . ')';?>
                    	</li>
                		<li>
                		<?php
                			echo $this->element('sighting_rating_types-index', array('sighting_id' => $sighting['Sighting']['id'], 'config' => 'site_element_cache_1_min'));
                		?>
                		</li>
                        <li class="view"><?php echo $this->Html->cInt($sighting['Sighting']['sighting_view_count']);?><?php echo __l(' Views'); ?></li>
                        <?php if($sighting['Sighting']['user_id'] != $this->Auth->user('id')) {?>
                        <li class="flag"><?php 
							if($this->Auth->sessionValid()):
								echo $this->Html->link(__l('Flag'), array('controller' => 'sighting_flags', 'action' => 'add', 'sighting_id' => $sighting['Sighting']['id']), array('class'=>'tool-tip js-ajax-colorbox-flag', 'title' => __l('Something wrong with this sighting?')));
							else:
								echo $this->Html->link(__l('Flag'), array('controller' => 'sighting_flags', 'action' => 'add', 'sighting_id' => $sighting['Sighting']['id']), array('class' => 'tool-tip', 'title' => __l('Something wrong with this sighting?')));
							endif; 
							?>
						</li>
                        <?php } ?>
                	</ul>
              </div>
             </div>
             </div>
		<?php
			echo $this->element('reviews-index', array('sighting_id' => $sighting['Sighting']['id'], 'config' => 'site_element_cache_5_min'));
		?>
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
	<div class="addthis_toolbox addthis_default_style addthis_32x32_style addthis-block">
		<a class="addthis_button_preferred_1" addthis:url="<?php echo Router::Url(array('controller' => 'sightings', 'action' => 'view', $sighting['Sighting']['id']),true); ?>" addthis:title="<?php echo $this->Html->cText($sighting['Item']['name'], false). ' @ '. $this->Html->cText($sighting['Place']['name'], false); ?>"></a>
		<a class="addthis_button_preferred_2"  addthis:url="<?php echo Router::Url(array('controller' => 'sightings', 'action' => 'view', $sighting['Sighting']['id']),true); ?>" addthis:title="<?php echo $this->Html->cText($sighting['Item']['name'], false). ' @ '. $this->Html->cText($sighting['Place']['name'], false); ?>"></a>
		<a class="addthis_button_preferred_3"  addthis:url="<?php echo Router::Url(array('controller' => 'sightings', 'action' => 'view', $sighting['Sighting']['id']),true); ?>" addthis:title="<?php echo $this->Html->cText($sighting['Item']['name'], false). ' @ '. $this->Html->cText($sighting['Place']['name'], false); ?>"></a>
		<a class="addthis_button_preferred_4"  addthis:url="<?php echo Router::Url(array('controller' => 'sightings', 'action' => 'view', $sighting['Sighting']['id']),true); ?>" addthis:title="<?php echo $this->Html->cText($sighting['Item']['name'], false). ' @ '. $this->Html->cText($sighting['Place']['name'], false); ?>"></a>
		<a class="addthis_button_compact"  addthis:url="<?php echo Router::Url(array('controller' => 'sightings', 'action' => 'view', $sighting['Sighting']['id']),true); ?>" addthis:title="<?php echo $this->Html->cText($sighting['Item']['name'], false). ' @ '. $this->Html->cText($sighting['Place']['name'], false); ?>"></a>
	</div>
</div>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4f0c203468d3e409"></script>
<script type="text/javascript">
  var addthis_config = {"data_track_clickback":true};
  var addthis_share = {templates: {twitter: "{{title}} {{url}} via @<?php echo Configure::read('site.name'); ?>"}};
</script>
<div class="side2 grid_8 alpha omega">
  <?php
 		 $i=0;
	 	foreach($sighting['Item']['ItemFollower'] as $sighting_follower) {
			if(in_array($this->Auth->user('id'), $sighting_follower)) {
				$i++;
			}
		}
   	if(empty($sighting['Item']['ItemFollower']) || isset($sighting['Item']['ItemFollower']) && $i==0) {?>
   	 <div class="follow-block1 follow-block">
        <?php  echo $this->Html->link(sprintf(__l('Follow this Item'), $sighting['Item']['name']), array('controller' => 'item_followers', 'action' => 'add', 'item' => $sighting['Item']['slug']), array('escape' => false,'title' => sprintf(__l('Follow this Item "%s"'), $sighting['Item']['name']))); ?>
      </div>
     <?php }else
     {?>
       	<div class="follow-block1 unfollow-block1 follow-block">
       <?php echo $this->Html->link(sprintf(__l('Unfollow this Item'), $sighting['Item']['name']), array('controller' => 'item_followers', 'action' => 'delete', $sighting['Item']['ItemFollower'][0]['id']), array('escape' => false,'title' => sprintf(__l('Unfollow this Item "%s"'), $sighting['Item']['name']))); ?>
          </div>
        <?php }?>
     
        
        <div class="people-list-block clearfix">
            <div class="round-tl">
              <div class="round-tr">
                <div class="round-tm"> </div>
              </div>
            </div>
          <div class="people-list-inner">
            <div class="side2-map-block">
                	<?php
                	if(!empty($sighting['Place'])):
                		$placedetails = $sighting['Place'];
                        if(Configure::read('GoogleMap.embedd_map') == 'Embedded'){
                            echo $this->Html->formGooglemap($placedetails,'265x102','1');
                        }
                        else{
                            echo $this->Html->image($this->Html->formGooglemap($placedetails,'265x102','static'));
                        }

                	endif;
                	?>
        	</div>
    		<dl class="list clearfix">
        		<dt><?php echo __l('Address');?></dt>
        		<dd><?php echo $this->Html->link($this->Html->cText($sighting['Place']['address2']), 'http://maps.google.com/maps?f=d&sll='.$sighting['Place']['latitude'] .','.$sighting['Place']['longitude'], array('target' => '_blank', 'escape' => false));?></dd>
        	</dl>
        </div>
		<div class="round-bl">
              <div class="round-br">
                <div class="round-tm"> </div>
              </div>
            </div>
            </div>
            <?php if(!empty($sighting['SightingRating'])): ?>
			<div class="sighting-rating-block people-list-block cleafix">
    			<div class="round-tl">
                  <div class="round-tr">
                    <div class="round-tm"> </div>
                  </div>
                </div>
                 <div class="people-list-inner">
                 
                    <?php echo $this->element('sighting_rating_types-index', array('sighting_id' => $sighting['Sighting']['id'], 'view' => 'raters', 'config' => 'site_element_cache_10_min')); ?>
                  </div>
                  <div class="round-bl">
                      <div class="round-br">
                             <div class="round-tm"> </div>
                        </div>
                 </div>
             </div>
             <?php endif; ?>
             <?php echo $this->element('sightings-simple_index', array('place_id' => $sighting['Place']['id'],'sighting_id'=>$sighting['Sighting']['id'], 'config' => 'site_element_cache_10_min'));?>
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
          <li><em></em><?php echo $this->Html->link(__l('Views'), array('controller' => 'sighting_views', 'action' => 'index', 'sighting' => $sighting['Sighting']['id'], 'simple_view' => 1, 'admin' => true), array('title' => __l('Views'), 'escape' => false)); ?></li>
          <li><em></em><?php echo $this->Html->link(__l('Flags'), array('controller' => 'sighting_flags', 'action' => 'index', 'sighting' => $sighting['Sighting']['id'], 'simple_view' => 1, 'admin' => true), array('title' => __l('Flags'), 'escape' => false)); ?></li>
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
                			<?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $sighting['Sighting']['id'], 'admin' => true), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
                            <?php   if(empty($sighting['Sighting']['is_system_flagged'])) { ?>
    				                <?php echo $this->Html->link(__l('Flag'), array('action' => 'update_status', $sighting['Sighting']['id'], 'status' => 'flag', 'page' => (!empty($this->request->params['named']['page']) ? $this->request->params['named']['page'] : ''), 'admin' => true), array('class' => 'flag', 'title' => __l('Flag')));?>
                            <?php } else { ?>
                                <span class="page-info"><?php echo __l('Sighting has been flagged');?></span>
                                <?php echo $this->Html->link(__l('Clear flag'), array('controller' => 'sightings', 'action' => 'update_status', $sighting['Sighting']['id'], 'status' => 'unflag', 'f' => $this->request->url , 'admin' => true), array('class' => 'flag', 'title' => __l('Clear flag')));
                            } ?>
                            <?php   if(empty($sighting['Sighting']['admin_suspend'])) { ?>
    				        <?php echo $this->Html->link(__l('Suspend'), array('action' => 'update_status', $sighting['Sighting']['id'], 'status' => 'suspend', 'page' => (!empty($this->request->params['named']['page']) ? $this->request->params['named']['page'] : ''), 'admin' => true), array('class' => 'suspend-icon', 'title' => __l('Suspend')));?>
                            <?php } else { ?>
                            <span class="page-info"><?php echo __l('Sighting has been suspended');?></span>
                             <?php echo $this->Html->link(__l('Unsuspend'), array('controller' => 'sightings', 'action' => 'update_status', $sighting['Sighting']['id'], 'status' => 'unsuspend', 'admin' => true), array('class' => 'suspend-icon', 'title' => __l('Unsuspend')));
                            }?>
                        </div>
                    </div>
    				<div class="round-bl">
					  <div class="round-br">
						<div class="round-tm"> </div>
					  </div>
					</div>
				</div>
				<?php
                if(!empty($sighting['Item']['is_system_flagged']) || !empty($sighting['Item']['admin_suspend']))  { ?>
                <div class="people-list-block clearfix">
				<div class="round-tl">
				  <div class="round-tr">
					<div class="round-tm"> </div>
				  </div>
				</div>
			     <div class="people-list-inner clearfix">
    				<?php if(!empty($sighting['Item']['is_system_flagged'])) { ?>
					<span class="page-info"><?php echo __l('Item has been flagged');?></span>
					<?php echo $this->Html->link(__l('Clear flag'), array('controller' => 'items', 'action' => 'update_status', $sighting['Item']['id'], 'status' => 'unflag', 'f' => $this->request->url , 'admin' => true), array('class' => 'flag grid_right', 'title' => __l('Clear flag')));?>
	    			<?php } ?>
					<?php if(!empty($sighting['Item']['admin_suspend'])) { ?>
    				<span class="page-info"><?php echo __l('Item has been suspended');?></span>
					<?php echo $this->Html->link(__l('Unsuspend'), array('controller' => 'items', 'action' => 'update_status', $sighting['Item']['id'], 'status' => 'unsuspend', 'admin' => true), array('class' => 'suspend-icon grid_right', 'title' => __l('Unsuspend')));?>
		    		<?php } ?>
				  </div>
    				<div class="round-bl">
					  <div class="round-br">
						<div class="round-tm"> </div>
					  </div>
					</div>
				</div>
    			<?php } ?>
				<?php
                if(!empty($sighting['Place']['is_system_flagged']) || !empty($sighting['Place']['admin_suspend']))  { ?>
			    <div class="people-list-block clearfix">
    				<div class="round-tl">
    				  <div class="round-tr">
    					<div class="round-tm"> </div>
    				  </div>
    				</div>
    			    <div class="people-list-inner clearfix">
        				<h4 class="grid_left"><?php echo __l('Place');?></h4>
        				<div class="grid_left">
        					<?php if(!empty($sighting['Place']['is_system_flagged'])) { ?>
            				<span class="page-info"><?php echo __l('Place has been flagged');?></span>
        					<?php echo $this->Html->link(__l('Clear flag'), array('controller' => 'places', 'action' => 'update_status', $sighting['Place']['id'], 'status' => 'unflag', 'f' => $this->request->url , 'admin' => true), array('class' => 'flag grid_right', 'title' => __l('Clear flag')));?>
        					<?php } ?>
        					<?php if(!empty($sighting['Place']['admin_suspend'])) { ?>
            				<span class="page-info"><?php echo __l('Place has been suspended');?></span>
        					<?php echo $this->Html->link(__l('Unsuspend'), array('controller' => 'places', 'action' => 'update_status', $sighting['Place']['id'], 'status' => 'unsuspend', 'admin' => true), array('class' => 'suspend-icon grid_right', 'title' => __l('Unsuspend')));?>
        					<?php } ?>
    					</div>
                  </div>
    				<div class="round-bl">
					  <div class="round-br">
						<div class="round-tm"> </div>
					  </div>
					</div>
				</div>
                <?php } ?>
		</div>
      </div>
    </div>
    <?php
    endif; ?>
</div>