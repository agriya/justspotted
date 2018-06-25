<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<?php
$week_class = '';
$all_class = '';
if(empty($this->request->params['named']['type'])) {
		$week_class = 'active';
}
if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'week') {
		$week_class = 'active';
}
if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'all') {
		$all_class = 'active';
}
?>
<div class="js-pagination user-rating-block-innner"><span class="<?php echo $week_class;?>"> <?php echo $this->Html->link(__l('This Week'),array('controller' => 'user_points','action'=>'activity_ratings', 'type'=> 'week', 'user' => $this->Auth->user('username') ),array('title' => __l('This Week'))); ?> </span>/ <span class="<?php echo $all_class;?>"> <?php echo $this->Html->link(__l('All'),array('controller' => 'user_points','action'=>'activity_ratings', 'type'=> 'all', 'user' => $this->Auth->user('username') ),array('title' => __l('All'))); ?></span></div>

      <div class="people-list-block">
      <div class="round-tl">
      <div class="round-tr">
        <div class="round-tm"> </div>
      </div>
     </div>
      <div class="people-list-inner clearfix">
               <h3><?php echo __l('Recent Activities'); ?></h3>
				<ol class="people-list">
					<li> <?php echo $this->Html->cInt($review_count) . ' ' . $reviewText; ?></li>
					<li>  <?php echo $this->Html->cInt($sighting_count) . ' ' . $sightingText; ?></li>
					<li>  <?php echo $this->Html->cInt($comment_count) . ' ' . __l('comments on your sightings'); ?></li>
					<li>  <?php echo $this->Html->cInt($follow_count) . ' ' . __l('followers'); ?></li>
				</ol>
         </div>
        <div class="round-bl">
          <div class="round-br">
            <div class="round-tm"> </div>
          </div>
        </div>
    </div>