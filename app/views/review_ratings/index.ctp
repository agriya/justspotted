<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="places index js-lazyload js-response">
<h2>
<?php echo __l('People who said') . ' ' . $reviewRatings[0]['ReviewRatingType']['name'] . '!' ; ?>
</h2>
<ol class="guide-list clearfix" >
<?php
if (!empty($reviewRatings)):

$i = 0;
foreach ($reviewRatings as $reviewRating):
	$class = null;
	if ($i++ % 2 == 0) {
	$class = ' altrow';
	}
?>
	<li class="<?php echo $class;?> clearfix">
			<div class="grid_4 omega">
			<?php
			 	echo $this->Html->link($this->Html->showImage('UserAvatar', $this->Html->getUserAvatar($reviewRating['User']['id']), array('dimension' => 'medium_thumb', 'alt' => sprintf(__l('[Image: %s]'), $reviewRating['User']['username']), 'title' => $reviewRating['User']['username'], 'escape' => false)), array('controller' => 'users', 'action' => 'view',  $reviewRating['User']['username']), array('escape' => false));
			?>
            </div>
			<div class="content-block grid_11 omega">
          
            <h3>
				<?php
				if(!empty($reviewRating['User']['UserProfile']['first_name']) && !empty($reviewRating['UserProfile']['last_name'])) {
					$full_name = $reviewRating['User']['UserProfile']['first_name'].' '.$reviewRating['User']['UserProfile']['last_name'];
					echo $this->Html->link($full_name, array('controller' => 'users', 'action' => 'view', $reviewRating['User']['username']),array('title' => $full_name));
				} else {
					echo $this->Html->link($reviewRating['User']['username'], array('controller' => 'users', 'action' => 'view', $reviewRating['User']['username']),array('title' => $reviewRating['User']['username']));
				}
				?>
			</h3>
			<address>
            <?php
				$address = array();
				if(!empty($reviewRating['User']['UserProfile']['City']['name'])):
					$address[] = $reviewRating['User']['UserProfile']['City']['name'];
				endif;
				if(!empty($reviewRating['User']['UserProfile']['State']['name'])):
					$address[] = $reviewRating['User']['UserProfile']['State']['name'];
				endif;
				if(!empty($reviewRating['User']['UserProfile']['Country']['name'])):
					$address[] = $reviewRating['User']['UserProfile']['Country']['name'];
				endif;
				if(!empty($address)):?>
                <span>
            	<?php
					echo implode(', ', $address);
					?>
            </span>
			<?php
				endif;
			?>
			</address>
		
   </div>
  <div class="grid_2 alpha grid_right">
			<?php
					if($this->Auth->sessionValid()):
						if(in_array($reviewRating['User']['id'], $user_followers)){
							$user_follower = array_flip($user_followers); ?>
						  <div class="follow-block unfollow-block grid_right grid_2">
							<?php
							echo $this->Html->link(__l('Unfollow'), array('controller' => 'user_followers', 'action'=>'delete', $user_follower[$reviewRating['User']['id']]), array( 'title' => __l('Unfollow'))); ?>
                           </div>
                    <?php	}
					endif;
					if(!in_array($reviewRating['User']['id'], $user_followers)){ ?>
					        <div class="follow-block grid_right grid_2">
					        <?php
						echo $this->Html->link(__l('Follow'), array('controller' => 'user_followers', 'action'=>'add', 'user' => $reviewRating['User']['username']), array( 'title' => __l('Follow'))); ?>
						</div>
					<?php }
				?>
            </div>
	</li>
<?php
    endforeach;
else:
?>
	<li>
		<p class="notice"><?php echo __l('No Users available');?></p>
	</li>
<?php
endif;
?>
</ol>
</div>