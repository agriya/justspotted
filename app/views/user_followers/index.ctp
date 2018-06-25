<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="userFollowers index clearfix">
	<ol class="user-list clearfix">
		<?php
		if (!empty($userFollowers)) {
		$i = 0;
		foreach ($userFollowers as $userFollower):
			$class = null;
			if(isset($this->request->params['named']['follower'])) {
				$username = $userFollower['FollowerUser']['username'];
				$user_avatar = $userFollower['FollowerUser']['UserAvatar'];
			}
			if(isset($this->request->params['named']['following'])) {
				$username = $userFollower['User']['username'];
				$user_avatar = $userFollower['User']['UserAvatar'];
			}
			if ($i++ % 2 == 0) {
				$class = 'altrow';
			}
		?>
			<li class="<?php echo $class;?>">
				<?php 
					echo $this->Html->link($this->Html->showImage('UserAvatar', $user_avatar, array('dimension' => 'small_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($username, false)), 'title' => $this->Html->cText($username, false))), array('controller'=> 'users', 'action' => 'view', $username), array('escape' => false));
				?>
			</li>
		<?php
			endforeach;
			if(isset($this->request->params['named']['following']) && $followings > 0) {
				?>
					<li class="more-users">
                        <span>
                            <?php echo $this->Html->link($followings . ' ' . __l('Others'), array('controller' => 'users', 'action' => 'index', 'following' => $this->request->params['named']['following'])); ?>
                        </span>
                    </li>
				<?php 
			} elseif(isset($this->request->params['named']['follower']) && $followers > 0) {
				?>
					<li class="more-users">
                        <span>
                            <?php echo $this->Html->link($followers . ' ' . __l('Others'), array('controller' => 'users', 'action' => 'index', 'follower' => $this->request->params['named']['follower'])); ?>
                        </span>
                    </li>
				<?php
			}
		} else {
		?>
			<li class="notice">
				<p><?php
					if(isset($this->request->params['named']['following'])) {
						echo __l('No Follows available');
					} else {
						echo __l('No Followers available');
					}
					?></p>
			</li>
		<?php
		}
		?>
	</ol>
</div>
