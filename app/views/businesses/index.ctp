<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="businesses index js-lazyload js-response">  
		<div class="side1 grid_16 alpha omega">
		 <div class="spot-tl">
			<div class="spot-tr">
			  <div class="spot-tm"> </div>
			</div>
		  </div>
		  <div class="spot-lm">
			<div class="spot-rm">
			  <div class="spot-middle center-spot-middle clearfix">
<?php
if(!empty($this->pageTitle) && empty($this->request->params['isAjax'])): ?>
	<h2><?php echo $this->pageTitle; ?></h2>
<?php endif; ?>
<?php echo $this->element('paging_counter');?>
	<?php
		$grid_class = "";

	        if(empty($this->request->params['isAjax'])) {
		         $grid_class = "grid_10";
		      } else {
			   $grid_class = "grid_9";
		}
	?>
<ol class="guide-list clearfix" start="<?php echo $this->Paginator->counter(array(
    'format' => '%start%'
));?>">
<?php
if (!empty($businesses)):

$i = 0;
foreach ($businesses as $business):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' altrow';
	}
	?>
	<li class="clearfix">
		<div class="image-block grid_3 omega">
            <?php 
				echo $this->Html->link($this->Html->showImage('Business', $business['Attachment'], array('dimension' => 'normal_thumb', 'alt' => sprintf(__l('[Image: %s]'), $business['Business']['name']), 'title' => $business['Business']['name'])), array('controller'=> 'businesses', 'action' => 'view', $business['Business']['slug']), array('escape' => false)); 
			?>
            </div>
			<div class="content-block <?php echo $grid_class; ?> omega">
			<h3>
			<?php echo $this->Html->link($this->Html->cText($business['Business']['name']), array('controller'=> 'business', 'action' => 'view', $business['Business']['slug']), array('escape' => false));?>	
            </h3>
            <p class="sighting-caption"><?php echo $this->Html->cText($this->Html->truncate($business['Business']['about_your_business'],138));?></p>
              <dl class="sighting-list clearfix">
                <dt class="follower"><?php echo __l('Followers:'); ?></dt><dd><?php echo $this->Html->cInt($business['Business']['business_follower_count']);?></dd>
                <dt class="view"><?php echo __l('Views:'); ?></dt><dd><?php echo $this->Html->cInt($business['Business']['business_view_count']);?></dd>
                <dt class="places"><?php echo __l('Places:'); ?></dt><dd><?php echo $this->Html->cInt($business['Business']['place_count']);?></dd>
            </dl>
	</div>
	<div class="grid_2 alpha grid_right">
	<?php 
                if($this->Auth->user('id') && $this->Auth->user('id') == $business['Business']['user_id']){ ?>

                <?php
                    echo $this->Html->link(__l('Edit'), array('controller' => 'businesses', 'action'=>'edit', $business['Business']['id']), array('class' => 'edit', 'title' => __l('Edit'))); ?>
          
              <?php  }
                else{
				if($this->Auth->sessionValid()):
					if(!empty($business['BusinessFollower'])) { ?>

								  <div class="follow-block unfollow-block grid_right grid_2">
					<?php	echo $this->Html->link(__l('Unfollow'), array('controller' => 'business_followers', 'action'=>'delete', $business['BusinessFollower'][0]['id']), array( 'title' => __l('Unfollow'))); ?>
					</div>
				<?php	}
				endif;
				if(empty($business['BusinessFollower'])) { ?>
							  <div class="follow-block grid_right grid_2">
				<?php	echo $this->Html->link(__l('Follow'), array('controller' => 'business_followers', 'action'=>'add', 'business' => $business['Business']['slug']), array( 'title' => __l('Follow'))); ?>
				</div>
			<?php	}
				}
			?>
 </div>
	</li>
<?php
    endforeach;
else:
?>
	<li class="notice">
		<?php echo __l('No Businesses available');?>
	</li>
<?php
endif;
?>
</ol>
<div class="js-pagination">
<?php
if (!empty($businesses)) {
    echo $this->element('paging_links');
}
?>
</div>
<?php if(empty($this->request->params['requested']) && empty($this->request->params['isAjax'])) { ?>
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


   	<?php if(empty($this->request->params['requested'])){?>
   <?php if(empty($this->request->params['isAjax'])){ ?>
      <div class="people-list-block">
      <div class="round-tl">
      <div class="round-tr">
        <div class="round-tm"> </div>
      </div>
     </div>
      <div class="people-list-inner clearfix">
        	<?php echo $this->Form->create('Business', array('class' => 'normal search-form1 clearfix', 'action'=>'index')); ?>
             <h3><?php echo __l('Search');?></h3>
    		<?php echo $this->Form->input('q', array('label' => 'Search')); ?>
    		<?php echo $this->Form->submit(__l('Go'));?>
         	<?php echo $this->Form->end(); ?>
         </div>
        <div class="round-bl">
          <div class="round-br">
            <div class="round-tm"> </div>
          </div>
        </div>
    </div>
     <div class="people-list-block">
          <div class="round-tl">
              <div class="round-tr">
                <div class="round-tm"> </div>
              </div>
         </div>
          <div class="people-list-inner clearfix">
            	<h3 class="popular"><?php echo __l('Popular Businesses');?></h3>
                <?php
                	echo $this->element('businesses-index', array('type' => 'popular', 'view' => 'simple', 'config' => 'site_element_cache_10_min'));
                ?>

            </div>
            <div class="round-bl">
              <div class="round-br">
                <div class="round-tm"> </div>
              </div>
            </div>
		</div>
  <?php } } ?>


</div>
<?php }?>
</div>