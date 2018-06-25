<?php /* SVN: $Id: $ */ ?>
<script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>
<div class="guides view clearfix">
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
                     <?php if(($this->request->params['controller'] == 'guides' && $this->request->params['action'] == 'view')) { ?>
                    	   <h2><?php echo $this->Html->link($this->Html->cText($guide['Guide']['name']), array('controller' => 'guides', 'action' => 'view', $guide['Guide']['slug']), array('escape' => false));?></h2>
                            <div class="description-block">
                            <p><?php echo nl2br($this->Html->cText($guide['Guide']['description']));?></p>
                            </div>
                      <?php } ?>
                </div>
              </div>
            </div>
            <div class="spot-bl">
            <div class="spot-br">
              <div class="spot-bm"> </div>
            </div>
          </div>
            <?php if(($this->request->params['controller'] == 'guides' && $this->request->params['action'] == 'view')) { ?>
            	  <?php
					if($this->Auth->sessionValid()) { ?>
					<div class="guide-upload-block">
					<?php
						if($guide['Guide']['is_anyone_add_additional_sightings_to_this_guide'] || $guide['Guide']['user_id'] == $this->Auth->user('id')) {
							if ($guide['Guide']['no_of_max_sightings'] > $guide['Guide']['sighting_count'] || $guide['Guide']['no_of_max_sightings'] == 0) {
								echo $this->element('reviews-add', array('guide' => $guide['Guide']['slug'], 'config' => 'site_element_cache_1_min'));
							
							}
						} ?>
					</div>
				<?php	}
					?>
            <?php } ?>
            <?php  
					if($guide['Guide']['sighting_count']) {
						echo  $this->element('sightings-index', array('guide' => $guide['Guide']['slug'], 'config' => 'site_element_cache_1_min'));
					}
			?>
 </div>
<div class="side2 grid_8 alpha omega">
	<div class="addthis_toolbox addthis_default_style addthis_32x32_style addthis-block">
		<a class="addthis_button_preferred_1" addthis:url="<?php echo Router::Url(array('controller' => 'guides', 'action' => 'view', $guide['Guide']['slug']),true); ?>" addthis:title="<?php echo $this->Html->cText($guide['Guide']['name'], false); ?>"></a>
		<a class="addthis_button_preferred_2" addthis:url="<?php echo Router::Url(array('controller' => 'guides', 'action' => 'view', $guide['Guide']['slug']),true); ?>" addthis:title="<?php echo $this->Html->cText($guide['Guide']['name'], false); ?>"></a>
		<a class="addthis_button_preferred_3" addthis:url="<?php echo Router::Url(array('controller' => 'guides', 'action' => 'view', $guide['Guide']['slug']),true); ?>" addthis:title="<?php echo $this->Html->cText($guide['Guide']['name'], false); ?>"></a>
		<a class="addthis_button_preferred_4" addthis:url="<?php echo Router::Url(array('controller' => 'guides', 'action' => 'view', $guide['Guide']['slug']),true); ?>" addthis:title="<?php echo $this->Html->cText($guide['Guide']['name'], false); ?>"></a>
		<a class="addthis_button_compact" addthis:url="<?php echo Router::Url(array('controller' => 'guides', 'action' => 'view', $guide['Guide']['slug']),true); ?>" addthis:title="<?php echo $this->Html->cText($guide['Guide']['name'], false); ?>"></a>
	</div>
</div>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4f0c203468d3e409"></script>
<script type="text/javascript">
  var addthis_config = {"data_track_clickback":true};
  var addthis_share = {templates: {twitter: "{{title}} {{url}} via @<?php echo Configure::read('site.name'); ?>"}};
</script>
	<div class="side2 grid_8 alpha omega">
		<?php
			if(empty($guide['GuideFollower'])) { ?>
			<div class="follow-block follow-block1 clearfix">
				<?php echo $this->Html->link(__l('Follow'), array('controller' => 'guide_followers', 'action'=>'add', 'guide' => $guide['Guide']['slug']), array( 'title' => __l('Follow')));?>
			</div>
            <?php } else { ?>
            <div class="follow-block follow-block1 unfollow-block1 clearfix">
			<?php echo $this->Html->link(__l('Unfollow'), array('controller' => 'guide_followers', 'action'=>'delete', $guide['GuideFollower'][0]['id']), array( 'title' => __l('Follow'))); ?>
            </div>
			<?php }	?>

    <div class="people-list-block clearfix">
	   <div class="grid_3 alpha">
          <div class="round-tl">
              <div class="round-tr">
                <div class="round-tm"> </div>
              </div>
            </div>
            <div class="people-list-inner about-guide-block clearfix">
    			<dl class="point-display  point-display-sep">
    				<dt><?php echo __l('Items');?></dt>
                    <dd class="point-display"><?php echo $this->Html->cInt($guide['Guide']['sighting_count']);?></dd>
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
    				<dt><?php echo __l('Followers');?></dt>
    				<dd class="point-display"><?php echo $this->Html->cInt($guide['Guide']['guide_follower_count']);?></dd>
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
            <div class="people-list-inner about-guide-block">
			<h3 class="about-guide"><?php echo __l('About this Guide:'); ?></h3>
			<div class="clearfix">
               	<div class="image-block grid_3 alpha omega">
                     <?php
                        echo $this->Html->link($this->Html->showImage('Guide', $guide['Attachment'], array('dimension' => 'normal_thumb', 'alt' => sprintf(__l('[Image: %s]'), $guide['Guide']['name']), 'title' => $guide['Guide']['name'])), array('controller'=> 'guides', 'action' => 'view', $guide['Guide']['slug']), array('escape' => false));
                    ?>
                    </div>
                      <dl class="list grid_4 alpha omega">
                        <dt><?php echo __l('Created by: '); ?></dt>
                        <dd><?php echo $this->Html->showImage('UserAvatar', $guide['User']['UserAvatar'], array('dimension' => 'small_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($guide['User']['username'], false)), 'title' => $this->Html->cText($guide['User']['username'], false))).' '.$this->Html->link($this->Html->cText($guide['User']['username']), array('controller'=> 'users', 'action' => 'view', $guide['User']['username']), array('escape' => false));
                        ?></dd>
                        <?php if(!empty($guide['City']['name'])) { ?>
                            <dt> <?php echo __l('City: '); ?> </dt>
                            <dd> <?php echo $this->Html->cText($guide['City']['name']); ?> </dd>
                      	<?php } ?>
                    	<?php if(!empty($guide['GuideCategory']['name'])) { ?>
                            <dt><?php echo __l('Category: '); ?></dt>
                            <dd><?php echo $this->Html->cText($guide['GuideCategory']['name']); ?></dd>
                      	<?php } ?>
                	</dl>
      		</div>
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
        <div class="people-list-inner about-guide-block clearfix">
			<?php echo $this->element('users-index', array('view'=>"guide_top_contributor", 'guide_id'=>$guide['Guide']['id'])); ?>
		</div>
        <div class="round-bl">
            <div class="round-br">
                <div class="round-tm"> </div>
            </div>
        </div>
    </div>
	<?php  echo  $this->element('guides_follows-index', array('guide' => $guide['Guide']['slug']));?>
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
          <li><em></em><?php echo $this->Html->link(__l('Views'), array('controller' => 'guide_views', 'action' => 'index', 'guide' => $guide['Guide']['slug'], 'simple_view' => 1, 'admin' => true), array('title' => __l('Views'), 'escape' => false)); ?></li>
          <li><em></em><?php echo $this->Html->link(__l('Followers'), array('controller' => 'guide_followers', 'action' => 'index', 'guide' => $guide['Guide']['slug'], 'simple_view' => 1, 'admin' => true), array('title' => __l('Followers'), 'escape' => false)); ?></li>
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
        				<?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $guide['Guide']['id'], 'admin' => true), array('class' => 'edit js-edit', 'title' => __l('Edit')));?>
    					<?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $guide['Guide']['id'], 'admin' => true), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
                        <?php if(empty($guide['Guide']['is_system_flagged'])) { ?>
						<?php echo $this->Html->link(__l('Flag'), array('action' => 'update_status', $guide['Guide']['id'], 'status' => 'flag', 'page' => (!empty($this->request->params['named']['page']) ? $this->request->params['named']['page'] : ''), 'admin' => true), array('class' => 'flag', 'title' => __l('Flag')));?>
                        <?php } else { ?>
                        <span class="page-info"><?php echo __l('Guide has been flagged');?></span>
                        <?php echo $this->Html->link(__l('Clear flag'), array('controller' => 'guides', 'action' => 'update_status', $guide['Guide']['id'], 'status' => 'unflag', 'f' => $this->request->url , 'admin' => true), array('class' => 'flag grid_right', 'title' => __l('Clear flag')));
                        } ?>
                        <?php   if(empty($guide['Guide']['admin_suspend'])) { ?>
						<?php echo $this->Html->link(__l('Suspend'), array('action' => 'update_status', $guide['Guide']['id'], 'status' => 'suspend', 'page' => (!empty($this->request->params['named']['page']) ? $this->request->params['named']['page'] : ''), 'admin' => true), array('class' => 'suspend-icon', 'title' => __l('Suspend')));?>
                        <?php } else { ?>
                        <span class="page-info"><?php echo __l('Guide has been suspended');?></span>
                        <?php echo $this->Html->link(__l('Unsuspend'), array('controller' => 'guides', 'action' => 'update_status', $guide['Guide']['id'], 'status' => 'unsuspend', 'admin' => true), array('class' => 'suspend-icon', 'title' => __l('Unsuspend')));
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
				<div class="round-bl">
					  <div class="round-br">
						<div class="round-tm"> </div>
					  </div>
					</div>
				</div>
		</div>
<?php  endif; ?>
</div>

