<div class="sightingRatings raters clearfix">
		<ol class="rates-list clearfix" start="">
			<?php
			if (!empty($sightingRatings)) {
			$i = 0;
			foreach ($sightingRatings as $sightingRating):
				$class = null;
					$username = $sightingRating['User']['username'];
					$user_avatar = $sightingRating['User']['UserAvatar'];
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
				if($total_count['SightingRatingStat']['count'] > 6) :
					$others = $total_count['SightingRatingStat']['count'] - 6; ?>
					<li class="more-users <?php echo $class;?>">
						<?php  echo $this->Html->cInt($others) .'Others'; ?>
					</li>
				<?php endif; 
			} else {
			?>
				<li>
					<p class="notice"><?php echo __l('No records available'); ?></p>
				</li>
			<?php
			}
			?>
		</ol>
</div>
