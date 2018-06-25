<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="clearfix">
<?php 
	if(empty($this->request->params['requested']) && empty($this->request->params['isAjax'])) { ?>
<div class="places index side1 grid_16 alpha omega">
<div class="spot-tl">
    <div class="spot-tr">
        <div class="spot-tm"> </div>
    </div>
</div>
 <div class="spot-lm">
    <div class="spot-rm">
        <div class="spot-middle center-spot-middle clearfix">
<?php } ?>
<h2>
<?php 
	if(!empty($this->pageTitle) && empty($this->request->params['isAjax'])):
		echo $this->pageTitle;
	endif;
?>
</h2>

<?php echo $this->element('paging_counter');?>
<ol class="guide-list  clearfix">
<?php
if (!empty($places)):

$i = 0;
foreach ($places as $place):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' altrow';
	}
?>
<li class="<?php echo $class;?> clearfix">
	<div class="content-block grid_12 omega">
		<h3><?php echo $this->Html->link($this->Html->cText($place['Place']['name']), array('controller'=> 'places', 'action' => 'view', $place['Place']['slug']), array('escape' => false));?></h3>
		<address class="address-info">
        <span><?php echo $this->Html->cText($place['Place']['address2']);?></span>
        </address>
      <ul class="sighting-list">
        <li class="follower"><?php echo __l('Followers:'); ?> <?php echo $this->Html->cInt($place['Place']['place_follower_count']);?>
        </li>
            <li class="view">
			<?php echo __l('Views:'); ?>
                      <?php echo $this->Html->cInt($place['Place']['place_view_count']);?>
            </li>
             <li class="sightings">
			<?php echo __l('Sightings:'); ?>
		            <?php echo $this->Html->cInt($place['Place']['item_count']);?>
              </li>
        </ul>
     </div>
		<?php
		if($this->Auth->sessionValid()):
			if(!empty($place['PlaceFollower'])) { ?>
		      	<div class="follow-block unfollow-block alpha grid_right grid_2">
					<?php echo $this->Html->link(__l('Unfollow'), array('controller' => 'place_followers', 'action'=>'delete', $place['PlaceFollower'][0]['id']), array( 'title' => __l('Unfollow'))); ?>
				</div>	<?php }
				endif;
				if(empty($place['PlaceFollower'])) { ?>
				<div class="follow-block alpha grid_right grid_2">
			     	<?php	echo $this->Html->link(__l('Follow'), array('controller' => 'place_followers', 'action'=>'add', 'place' => $place['Place']['slug']), array( 'title' => __l('Follow'))); ?>
				</div>
			<?php	}
			?>

   
	</li>
<?php
    endforeach;
else:
?>
	<li class="notice">
		<?php echo __l('No Places available');?>
	</li>
<?php
endif;
?>
</ol>

<?php
if (!empty($places)) {
    echo $this->element('paging_links');
}
?>
     </div>
<?php if(empty($this->request->params['requested']) && empty($this->request->params['isAjax'])) { ?>
    </div>
</div>
<div class="spot-bl">
    <div class="spot-br">
        <div class="spot-bm"> </div>
    </div>
</div>
</div>
<div class="side2 grid_8 alpha omega">
<div class="people-list-block ">
    <div class="round-tl">
      <div class="round-tr">
            <div class="round-tm"> </div>
      </div>
     </div>
      <div class="people-list-inner clearfix">
	       <?php echo $this->Form->create('Place', array('class' => 'normal search-form1 clearfix', 'action'=>'index')); ?>
            <h3><?php echo __l('Search');?></h3>
            <?php if(empty($this->request->params['isAjax'])){ ?>
			<?php echo $this->Form->input('q', array('label' => 'Keyword')); ?>
    		<?php echo $this->Form->submit(__l('Search'));?>
			<?php } ?>
	       <?php echo $this->Form->end(); ?>
	</div>
    <div class="round-bl">
          <div class="round-br">
                <div class="round-tm"> </div>
          </div>
    </div>
    </div>
    <div class="people-list-block ">
        <div class="round-tl">
          <div class="round-tr">
                <div class="round-tm"> </div>
          </div>
         </div>
          <div class="people-list-inner clearfix">
            <h3 class="popular"><?php echo __l('Popular Places');?></h3>
            <?php
            	echo $this->element('places-index', array('type' => 'popular', 'view' => 'simple', 'config' => 'site_element_cache_10_min'));
            ?>
    	</div>
        <div class="round-bl">
              <div class="round-br">
                    <div class="round-tm"> </div>
              </div>
        </div>
    </div>
</div>
<?php }?>
</div>