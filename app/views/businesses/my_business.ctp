<?php /* SVN: $Id: $ */ ?>
<div class="businesses my_business">
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
                     <?php echo $this->Html->link($this->Html->showImage('Business', $business['Attachment'], array('dimension' => 'big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $business['Business']['slug']), 'title' => $business['Business']['slug'])), array('controller'=> 'businesses', 'action' => 'view', $business['Business']['slug']), array('escape' => false)); ?>
                 </div>
                <div class="profile-image-right-block">
                <div class="add-block">
                  <?php echo $this->Html->link(__l('Edit'), array('controller' => 'businesses', 'action'=>'edit', $business['Business']['id']), array('class' => 'edit', 'title' => __l('Edit'))); ?>
                  </div>
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
            <h3><?php echo __l('Updates'); ?></h3>
             <div class="page-info"><?php echo __l('Updates are announcement from business to followers'); ?> </div>
              <?php echo $this->element('business_updates-add', array('config' => 'site_element_cache_2_min')); ?>
           
            <?php echo $this->element('business_updates-business', array('type' => 'own', 'config' => 'site_element_cache_2_min')); ?>
           </div>
          </div>
        </div>
		<div class="spot-bl">
            <div class="spot-br">
              <div class="spot-bm"> </div>
            </div>
          </div>
</div>
</div>
<!-- left end-->

<!-- side starts -->
<div class="side2 grid_8 alpha omega">
  
    <div class="people-list-block clearfix">
  <div class="round-tl">
      <div class="round-tr">
        <div class="round-tm"> </div>
      </div>
    </div>
    <div class="people-list-inner">
    <div class="clearfix">
      <h3 class="grid_left"><?php echo __l('Places'); ?></h3>
      <p class="grid_right places-add">
         <?php echo $this->Html->link(__l('Add'), array('controller' => 'places', 'action'=>'add'), array('class' => 'add', 'title' => __l('Add'))); ?>
     </p>
    </div>
    <?php echo $this->element('places-index', array('type' => 'own', 'config' => 'site_element_cache_2_min')); ?>
    </div>
    <div class="round-bl">
          <div class="round-br">
            <div class="round-tm"> </div>
         </div>
     </div>
     </div>
  

     <?php echo $this->element('business_follows-index', array('business' => $business['Business']['slug'], 'config' => 'site_element_cache_5_min'));?>
    
</div>
<!-- side end -->

</div>
