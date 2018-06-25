<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
        <div class="people-list-block clearfix">
            <div class="round-tl">
              <div class="round-tr">
                <div class="round-tm"> </div>
              </div>
            </div>
            <div class="people-list-inner">
<div class="places index js-response js-responses">
<h3 class="popular"><?php echo __l('Top Spotters');?></h3>
<?php if(empty($this->request->params['named'])) : ?>
<?php echo $this->element('paging_counter');?>
<?php endif; ?>
<ol class="people-list">
<?php
if (!empty($topspotters)):

$i = 0;
foreach ($topspotters as $topspotter):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' altrow';
	}
?>
	<li class="<?php echo $class;?> clearfix">
		<div class="img-block">
			<?php
				 	echo $this->Html->link($this->Html->showImage('UserAvatar', $topspotter['User']['UserAvatar'], array('dimension' => 'small_thumb', 'alt' => sprintf(__l('[Image: %s]'), $topspotter['User']['username']), 'title' => $topspotter['User']['username'], 'escape' => false)), array('controller' => 'users', 'action' => 'view',  $topspotter['User']['username']), array('escape' => false));
			?>
            </div>
            <div class="grid_4 follw-description">
                <h4><?php echo $this->Html->link($this->Html->cText($topspotter['User']['username'], false), array('controller' => 'users', 'action' => 'view',  $topspotter['User']['username']), array('escape' => false)); ?></h4>
                <address>
                <?php
					$address = array();
					if(!empty($topspotter['User']['UserProfile']['City']['name'])):
						$address[] = $topspotter['User']['UserProfile']['City']['name'];
					endif;
					if(!empty($topspotter['User']['UserProfile']['State']['name'])):
						$address[] = $topspotter['User']['UserProfile']['State']['name'];
					endif;
					if(!empty($topspotter['User']['UserProfile']['Country']['name'])):
						$address[] = $topspotter['User']['UserProfile']['Country']['name'];
					endif;
					if(!empty($address)):
						echo $this->Html->cText(implode(', ', $address));
					endif;
				?>
                </address>
          </div>

			<?php
					if($this->Auth->sessionValid()):
						if(in_array($topspotter['User']['id'], $user_followers)){
							$user_follower = array_flip($user_followers); ?>
												  <div class="follow-block unfollow-block grid_right grid_2">
					<?php		echo $this->Html->link(__l('Unfollow'), array('controller' => 'user_followers', 'action'=>'delete', $user_follower[$topspotter['User']['id']]), array( 'title' => __l('Unfollow'))); ?>
 </div>
          <?php             	}
					endif;
					if(!in_array($topspotter['User']['id'], $user_followers)){ ?>
										  <div class="follow-block grid_right grid_2">
					<?php	echo $this->Html->link(__l('Follow'), array('controller' => 'user_followers', 'action'=>'add', 'user' => $topspotter['User']['username']), array( 'title' => __l('Follow'))); ?>
				</div>	<?php }
				?>
	
	</li>
<?php
    endforeach;
else:
?>
	<li class="notice">
		<p><?php echo __l('No Spotters available');?></p>
	</li>
<?php
endif;
?>
</ol>
<?php
if (!empty($topspotters)) {
        ?>
            <div class="js-pagination">
                <?php echo $this->element('paging_links'); ?>
            </div>
        <?php
}?>
</div>
</div>
            <div class="round-bl">
              <div class="round-br">
                <div class="round-tm"> </div>
              </div>
            </div>
</div>
