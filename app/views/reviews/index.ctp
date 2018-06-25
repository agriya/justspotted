<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="reviews index js-response js-lazyload js-sighting-review-block-<?php echo (!empty($this->request->params['named']['sighting_id'])) ? $this->request->params['named']['sighting_id'] : '-';?>">
<?php if(empty($this->request->params['requested'])): ?>
<?php if(empty($this->params['isAjax'])): echo '<h3>'.__l('Reviews').'</h3>'; endif;?>
<?php endif; ?>
<ol class="spot-list clearfix" start="<?php echo $this->Paginator->counter(array(
    'format' => '%start%'
));?>">
<?php
    if(!empty($this->request->params['named']['type'])){
						$type="home";
					}
					else{
						$type="other";
					}
if (!empty($reviews)):

$i = 0;
foreach ($reviews as $review):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' altrow';
	}
?>
<?php
    if(empty($this->request->params['isAjax'])) {
        $grid_class = "grid_11 omega alpha";
    } else {
        if(empty($this->request->params['named']['type'])){
            $grid_class = "grid_10 omega alpha";
        }
        else{
			$grid_class = "grid_11 omega alpha";
        }
	}
	 ?>
	
<li class="<?php echo $class;?> clearfix review-list">
    <div class="sighting-frame grid_4">
    	<?php echo $this->Html->link($this->Html->showImage('Review', $review['Attachment'], array('dimension' => 'small_big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $review['Sighting']['Item']['name']), 'title' => __l('View Review'))), array('controller' => 'reviews', 'action' => 'view',  $review['Review']['id']), array('escape' => false));?>
  	</div>
  	<div class="sighting-right-block <?php echo $grid_class; ?>">
  	<div class="sighting-head">
			<h3><?php echo $this->Html->link($this->Html->showImage('UserAvatar', $this->Html->getUserAvatar($review['User']['id']), array('dimension' => 'micro_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Auth->user('username')), 'title' => $this->Auth->user('username'), 'escape' => false)), array('controller' => 'users', 'action' => 'view',  $review['User']['username']), array('escape' => false)).' '.$this->Html->link($review['User']['username'], array('controller' => 'users', 'action' => 'view', $review['User']['username']),array('title' => $review['User']['username']));?></h3>
			<?php echo __l('Created on'); ?>
            <span class="date"><?php echo $this->Time->timeAgoInWords($review['Review']['created']);?></span>
			<p class="sighting-caption"><?php echo $this->Html->cText($review['Review']['notes']);?></p>
    </div>
		<?php if($this->Auth->sessionValid() && ($this->Auth->user('user_type_id') == ConstUserTypes::Admin || $this->Auth->user('id') == $review['Review']['user_id'])) { ?>
         <div class="delete-block">
            	<?php echo $this->Html->link(__l('Edit'), array('controller' => 'reviews', 'action' => 'edit', $review['Review']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?>
				<?php echo $this->Html->link(__l('Delete'), array('controller' => 'reviews', 'action' => 'delete', $review['Review']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
          </div>
        <?php } ?>
    <div class="comment-box-block">
          <div class="round-tl">
            <div class="round-tr">
              <div class="round-tm"> </div>
            </div>
          </div>
        <div class="comment-box-inner clearfix">
                 <ul class="great-list  clearfix">
          				<?php echo $this->element('review_rating_types-index', array('sighting_id' => $review['Review']['sighting_id'],'type'=>$type, 'review_id' => $review['Review']['id']));?>
                    	<li class="view">
                         <span class="count-info">
                          <?php echo $this->Html->cInt($review['Review']['review_view_count']);?>
                         <?php echo __l('Views'); ?>

                         </span>
                         </li>
        				<li class="view">
        				<span class="count-info">
        				<?php echo $this->Html->cInt($review['Review']['review_comment_count']);?>
                        <?php echo __l('Comments'); ?>
                        
                        </span>
                        </li>
            	</ul>
    		<div class="comment-block">
                <div class="js-response-link<?php echo $review['Review']['id']; ?>">
    			<?php
                    if(empty($this->request->params['named']['type']) || $this->request->params['named']['type'] != 'all'){
                        if($review['Review']['review_comment_count'] >5){
                            echo $this->Html->link(__l('View all ') . $review['Review']['review_comment_count'] . __l(' comments'),array('controller' => 'review_comments', 'action' => 'index', $review['Review']['id'],'type'=>'all'),array('class'=>'js-link {"review_id" :"' . $review['Review']['id'] . '"}'));
                        }
                    }
    				echo $this->element('review_comments-index', array('review' => $review,'type'=>$type, 'config' => 'site_element_cache_2_min'));
                ?>
                </div>
                <?php
    				if($this->Auth->sessionValid()):
    					echo $this->element('../review_comments/add', array('review_id' => $review['Review']['id'], 'config' => 'site_element_cache_2_min'));
    				endif;
    			?>
    		</div>
        </div>
        <div class="round-bl">
                <div class="round-br">
                  <div class="round-tm"> </div>
                </div>
          </div>
    </div>
	</div>

	</li>
<?php
    endforeach;
else:
?>
	<li class="notice">
		<?php echo __l('No Reviews available');?>
	</li>
<?php
endif;
?>
</ol>

<?php
if (!empty($reviews)) { 
	if(!empty($this->params['isAjax'])):
?>
<div class="js-pagination">
<?php
	endif;
    echo $this->element('paging_links'); 
	if(!empty($this->params['isAjax'])):
?>
</div>  
<?php
	endif; 
}
?>
</div>
