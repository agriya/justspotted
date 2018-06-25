<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="userPoints index">
<div class="clearfix">
<?php 
if(empty($this->request->params['requested'])) : ?>
<?php if(empty($this->request->params['isAjax'])){ ?>
        <div class="side1  grid_16 alpha omega ">
<?php } ?>
	<?php if(!empty($this->request->params['isAjax']) && (empty($this->params['named']['page']))){ ?>
	<div class="spot-tl">
        <div class="spot-tr">
            <div class="spot-tm"> </div>
        </div>
    </div>
    <div class="spot-lm">
        <div class="spot-rm">
            <div class="spot-middle center-spot-middle clearfix">
<?php } endif; ?>
<?php if(!empty($this->request->params['requested'])) : ?>

<?php else :
if(!empty($this->request->params['isAjax'])  && (empty($this->params['named']['page']))){?>
    <h2><?php echo $this->pageTitle; ?></h2>
    <?php }
    if(empty($this->request->params['isAjax'])){ ?>
         <?php echo $this->element('users-top_links'); ?>
	<?php } ?>
<?php endif; if(!empty($this->request->params['isAjax'])){?>
<div class="js-response">
<ol class="list <?php echo empty($this->request->params['requested']) ? 'notification-list' : 'notification-list notification-list1';?>">
<?php
if (!empty($userPoints)):

$i = 0;
foreach ($userPoints as $userPoint):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = 'class="altrow"';
	}
?>
	<li <?php echo $class;?>>
	   <div class="clearfix">
        <div class="grid_1">
    	<?php 
			echo $this->Html->link($this->Html->showImage('UserAvatar', $userPoint['OtherUser']['UserAvatar'], array('dimension' => 'small_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($userPoint['OtherUser']['username'], false)), 'title' => $this->Html->cText($userPoint['OtherUser']['username'], false))), array('controller'=> 'users', 'action' => 'view', $userPoint['OtherUser']['username']), array('escape' => false));
			?>
			</div>
		<div class="<?php echo empty($this->request->params['requested']) ? 'grid_12' : 'grid_6';?>">
		 <p class="clearfix">  <?php if(!empty($userPoint['UserPoint']['point'])) { ?>
			<span class="count-block"> <span><?php echo '+'.$this->Html->cInt($userPoint['UserPoint']['point'], false);?></span></span>
		<?php } ?>
		<?php echo $this->Html->link($this->Html->cText($userPoint['OtherUser']['username'], false), array('controller' => 'users', 'action' => 'view', $userPoint['OtherUser']['username']), array('title' => $userPoint['OtherUser']['username'])); ?>
		<?php echo $this->Html->notificationDescription($userPoint); ?>
		</p>
		<p class="clearfix posted-date"> <?php echo $this->Time->timeAgoInWords($userPoint['UserPoint']['created']);?></p>
		</div>
		</div>
	</li>
<?php
    endforeach;
else:
?>
	<li>
		<p class="notice"><?php echo __l('No Notifications available');?></p>
	</li>
<?php
endif;
?>
</ol>
<?php
if (!empty($userPoints) && empty($this->request->params['requested'])) { ?>
    <div class="js-pagination"><?php
    echo $this->element('paging_links'); ?>
    </div>
    <?php
} ?>
</div> <?php }
				if(!empty($this->request->params['requested']) && $total_records > 10) :
				?><?php echo $this->Html->link(__l('See More'), array('controller' => 'user_points', 'action' => 'index'), array('class' => 'see-more grid_right', 'title' => __l('See More')));?><?php
				endif;	
				?>
<?php if(empty($this->request->params['requested'])) :
        if(!empty($this->request->params['isAjax'])  && (empty($this->params['named']['page']))){?>
            </div>
        </div>
    </div>
    <div class="spot-bl">
        <div class="spot-br">
            <div class="spot-bm"> </div>
        </div>
    </div>
    <?php } ?>
    <?php
	if(empty($this->request->params['requested']) && empty($this->request->params['isAjax'])){?>
	</div>
	<?php }
    if(empty($this->request->params['isAjax'])){?>
    <div class="side2 grid_8 alpha omega">
		<?php echo $this->element('users-sidebar', array('username' => $this->Auth->user('username'), 'config' => 'site_element_cache_5_min')); ?>
    </div>
    <?php } ?>
<?php endif; ?>
</div>
</div>