<?php /* SVN: $Id: $ */ ?>
<div class="businesses view">
 <h2><?php echo $this->Html->cText($business['Business']['name']);?></h2>
<div class="side1 grid_16 alpha omega">
<div class="clearfix">
    <div class="spot-tl">
        <div class="spot-tr">
          <div class="spot-tm"> </div>
        </div>
      </div>
    <div class="spot-lm">
            <div class="spot-rm">
                  <div class="spot-middle center-spot-middle clearfix">
                		<div class="profile-image profile-image-left-block grid_left">
                              <?php echo $this->Html->showImage('Business', $business['Attachment'], array('dimension' => 'big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($business['Business']['slug'], false)), 'title' => $this->Html->cText($business['Business']['slug'], false), 'escape' => false)); ?>
                		</div>
                		<div class="profile-image-right-block">
                        <?php echo nl2br($this->Html->cText($business['Business']['about_your_business']));?>
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
<div class="updates-block clearfix">
    <div class="spot-tl">
        <div class="spot-tr">
          <div class="spot-tm"> </div>
        </div>
      </div>
    <div class="spot-lm">
        <div class="spot-rm">
              <div class="spot-middle center-spot-middle clearfix">
                      <?php echo $this->element('business_updates-business', array('business' => $business['Business']['slug'] , 'config' => 'site_element_cache_2_min')); ?>
               </div>
          </div>
        </div>
		<div class="spot-bl">
            <div class="spot-br">
              <div class="spot-bm"> </div>
            </div>
          </div>
</div>
<h3><?php echo __l('Sightings'); ?></h3>
<?php	echo $this->element('sightings-index', array('business' => $business['Business']['slug'], 'config' => 'site_element_cache_2_min')); ?>
</div>
 <div class="side2 grid_8 alpha omega">
<?php 
	if($this->Auth->sessionValid()):
		if(!empty($business['BusinessFollower'])) { ?>
			  <div class="follow-block unfollow-block1 follow-block1">
<?php				echo $this->Html->link(__l('Unfollow'), array('controller' => 'business_followers', 'action'=>'delete', $business['BusinessFollower'][0]['id']), array( 'title' => __l('Unfollow'))); ?>
					</div>
<?php	}
		endif;
			if(empty($business['BusinessFollower'])) { ?>
			  <div class="follow-block follow-block1">
<?php			echo $this->Html->link(__l('Follow'), array('controller' => 'business_followers', 'action'=>'add', 'business' => $business['Business']['slug']), array( 'title' => __l('Follow'))); ?>
				</div>
<?php	} ?>

<div class="people-list-block clearfix">
  <div class="round-tl">
      <div class="round-tr">
        <div class="round-tm"> </div>
      </div>
    </div>
    <div class="people-list-inner">
	
    <?php echo $this->element('places-index', array('business_slug' => $business['Business']['slug'], 'config' => 'site_element_cache_2_min')); ?>
    </div>
    <div class="round-bl">
          <div class="round-br">
            <div class="round-tm"> </div>
          </div>
        </div>
        </div>
	
    <?php echo $this->element('business_follows-index', array('business' => $business['Business']['slug'], 'config' => 'site_element_cache_5_min'));?>
</div>
	
</div>