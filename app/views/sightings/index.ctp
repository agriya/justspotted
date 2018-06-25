<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="sightings index js-response js-pagination">
 <?php if($this->request->params['controller'] == 'sightings' && $this->request->params['action'] == 'index' && empty($this->request->params['isAjax']) && empty($this->request->params['requested'])) { ?>
  <div class="title-block clearfix">
    <h2 class="alpha omega"><?php echo Configure::read('site.slogan');?></h2>
  </div>
<?php
	if(!empty($this->pageTitle) && empty($this->request->params['isAjax'])):
	//	echo $this->pageTitle;
	else:
	//	echo __l('Sightings');
	endif;
   }
    ?>
<div class="clearfix">
<div class="side1 grid_16 alpha omega js-lazyload js-response js-responses js-ratings-filter-responses">
 <?php if($this->request->params['controller'] == 'sightings' && $this->request->params['action'] == 'index' && empty($this->request->params['isAjax']) && empty($this->request->params['named']['guide']) && empty($this->request->params['requested'])) { ?>
	<?php
		if(!empty($place)) {
			echo $this->element('reviews-add', array('place' => $place, 'config' => 'site_element_cache_5_min'));
		} else {
			echo $this->element('reviews-add', array('config' => 'site_element_cache_5_min'));
		}
	?>
<?php  } ?>

<div class="js-search-responses">
<?php echo $this->element('paging_counter'); ?>

<ol class="spot-list clearfix">
<?php
if (!empty($sightings)):

$i = 0;
foreach ($sightings as $sighting):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
   if(empty($this->request->params['isAjax'])) {
		         $grid_class = "grid_10";
		      } else {
			   $grid_class = "guide-side grid_left";
		} ?>
        <li class="clearfix js-slide-<?php echo $sighting['Sighting']['id']; ?>">
          <div class="spot-tl">
            <div class="spot-tr">
              <div class="spot-tm"> </div>
            </div>
          </div>
          <div class="spot-lm">
            <div class="spot-rm">
              <div class="spot-middle clearfix">
                <div class="spot-content <?php echo $grid_class; ?>">
                  <div class="clearfix">
                    <h5 class="spot-band"><span><?php echo __l('Spotted by...'); ?></span></h5>
                    <?php echo $this->element('reviews-index_spotters', array('sighting_id' => $sighting['Sighting']['id'], 'view' => 'spotters', 'config' => 'site_element_cache_1_min'));?>
                  </div>
                  <h3><?php echo $this->Html->link($this->Html->cText($sighting['Item']['name']), array('controller' => 'sightings', 'action' => 'view', $sighting['Sighting']['id']), array('escape' => false,'title' => $this->Html->cText($sighting['Item']['name'],false) . ' @ ' . $this->Html->cText($sighting['Place']['name'], false), 'class'=> "js-sighting_reviews {sighting_id:".$sighting['Sighting']['id'].", container: 'js-sighting-reviews-".$sighting['Sighting']['id']."', reopen_container: 'js-sighting-review-block-".$sighting['Sighting']['id']."', url:'".Router::url('/', true)."reviews/index/sighting_id:".$sighting['Sighting']['id']."/type:home'}")); ?></h3>
                  <address class="address-block">
                  <?php echo $this->Html->link('@ '. $this->Html->cText($sighting['Place']['name']) . ' - ' . $this->Html->cText($sighting['Place']['City']['name']). ', ' . $this->Html->cText($sighting['Place']['Country']['name']), array('controller' => 'places', 'action' => 'view', $sighting['Place']['slug']), array('escape' => false));
				  ?>
                  </address>
<?php
					$last_spotted_by = '';
					if (!empty($sighting['Review']))
					{
						$last_spotted_by_ary = array_shift($sighting['Review']);		
						$last_spotted_by = $this->Html->link($last_spotted_by_ary['User']['username'], array('controller' => 'users', 'action' => 'view',$last_spotted_by_ary['User']['username']), array('title' => $last_spotted_by_ary['User']['username']));
					}
?>
                  <p class="last-spotted"><?php echo __l('Last spotted by').' '.$last_spotted_by.' '. $this->Time->timeAgoInWords($sighting['Sighting']['created']);?></p>
				   <?php echo $this->element('guides_sightings-index', array('sighting_id' => $sighting['Sighting']['id'], 'config' => 'site_element_cache_5_min')); ?>
        		</div>
               <div class="img-frame grid_5 grid_right">
        			<?php
        				echo $this->Html->link($this->Html->showImage('Review', $sighting['BaseReview'][0]['Attachment'], array('dimension' => 'big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $sighting['Item']['name']), 'title' => $sighting['Item']['name'])), array('controller' => 'sightings', 'action' => 'view',  $sighting['Sighting']['id']), array('escape' => false));
        			?>
        		</div>
		</div>
		</div>
		</div>
            <div class="spot-lm">
                <div class="spot-rm">
                  <div class="sighting-middle2 clearfix">
                    <ul class="sighting-list clearfix">
                      <li class="sighting"><?php echo $this->Html->link($this->Html->cInt($sighting['Sighting']['review_count'], false).' Sightings', array('controller' => 'reviews', 'action' => 'index', 'sighting_id' => $sighting['Sighting']['id'],'type'=>'home'), array('title' => $this->Html->cInt($sighting['Sighting']['review_count'], false), 'class'=> "js-sighting_reviews {sighting_id:".$sighting['Sighting']['id'].", container: 'js-sighting-reviews-".$sighting['Sighting']['id']."', reopen_container: 'js-sighting-review-block-".$sighting['Sighting']['id']."', url:'".Router::url('/', true)."reviews/index/sighting_id:".$sighting['Sighting']['id']."/type:home'}"));?></li>
					  <?php echo $this->element('sighting_rating_types-index', array('sighting_id' => $sighting['Sighting']['id'], 'config' => 'site_element_cache_1_min'));?>
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
						 <li class="non-icon grid_right"><?php
								echo $this->Html->cText(__l('More')).': '.$this->Html->link(__l('like this'), array('controller' => 'sightings', 'action' => 'index', 'item'=> $sighting['Item']['slug']), array('title' => __l('like this'))).' / '.$this->Html->link(__l('at this place'), array('controller' => 'places', 'action' => 'view', $sighting['Place']['slug']), array('title' => __l('At this place')));
							?>
						</li>
                    </ul>

                  </div>
                </div>
              </div>
              <div class="spot-lm">
                  <div class="spot-rm">
                   <div class="spot-middle2">
                      <div class="js-response js-sighting-reviews-<?php echo $sighting['Sighting']['id']; ?>">
                      </div>
                      </div>
                  </div>
              </div>
		    <div class="spot-bl">
                <div class="spot-br">
                  <div class="spot-bm"> </div>
                </div>
              </div>
	</li>
<?php
    endforeach;
else:
?>
	<li class="notice">
		<?php echo __l('No Sightings available');?>
	</li>
<?php
endif;
?>
</ol>
<?php
if(!empty($this->request->params['requested']) || !empty($this->params['isAjax']) && empty($this->request->form['sighting_search'])) { ?>
	<div class="js-pagination">
    	<?php
    	}
    	if (!empty($sightings)) {
    		echo $this->element('paging_links');
    	}
    	if(!empty($this->request->params['requested']) || !empty($this->params['isAjax'])) {
    		?>
    </div>
		<?php
	}
	?>
</div>
</div>
<?php if(!isset($this->request->params['named']['user']) && empty($this->request->params['requested']) && empty($this->request->params['isAjax'])) { ?>
<div class="side2 grid_8 alpha omega">
<h3 class="popular"><?php echo __l('Popular Guides');?></h3>
<?php
	echo $this->element('guides-index', array('type' => 'popular', 'view' => 'simple', 'config' => 'site_element_cache_10_min'));
?>
<h3 class="popular"><?php echo __l('Popular Businesses');?></h3>
<?php
	echo $this->element('businesses-index', array('type' => 'popular', 'view' => 'simple', 'config' => 'site_element_cache_10_min'));
?>

<?php 
if($this->Auth->user('id')){
	echo $this->element('users-index', array('type' => 'popular', 'view' => 'simple'));
}
else{
	echo $this->element('users-index', array('type' => 'popular', 'view' => 'simple', 'config' => 'site_element_cache_10_min'));
}	
?>
 <h3 class="popular"><?php echo __l('Popular Places');?></h3>
<?php
	echo $this->element('places-index', array('type' => 'popular', 'view' => 'simple', 'config' => 'site_element_cache_10_min'));
?>
</div>
<?php }?></div>

</div>

