<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="people-list-block clearfix">
  <div class="round-tl">
      <div class="round-tr">
        <div class="round-tm"> </div>
      </div>
    </div>
<div class="people-list-inner">
<div class="placeFollowers index js-response js-responses">
<h3 class="popular"><?php echo __l('Followers'); if(!empty($this->request->params['named']['follower'])){ echo ' ('.$this->Html->cInt($place_follower_count).')'; }?></h3>
<?php //echo $this->element('paging_counter');?>
<div class="clearfix">
<ol class="user-list clearfix">
<?php
if (!empty($placeFollowers)):

$i = 0;
foreach ($placeFollowers as $placeFollower):
	$class = null;
	if(isset($this->request->params['named']['following'])) {
		$username = $placeFollower['FollowerUser']['username'];
		$user_avatar = $placeFollower['FollowerUser']['UserAvatar'];
	}
	if(isset($this->request->params['named']['follower'])) {
		$username = $placeFollower['User']['username'];
		$user_avatar = $placeFollower['User']['UserAvatar'];
	}
	if(isset($this->request->params['named']['user'])) { ?>
		<li><a href="#"><?php echo $placeFollower['Place']['name']; ?></a></li>
	<?php }
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
	
else:
?>
	<li class="notice">
		<p><?php echo __l('No Followers available');?></p>
	</li>
<?php
endif;
if(isset($this->request->params['named']['follower']) && $followers > 0) {
				?>
				<li class="more-users"><span><?php echo $this->Html->cInt($followers). ' ' . __l('Others'); ?></span></li>
					
				<?php
			}
?>
</ol>
</div>

</div>
</div>

        <div class="round-bl">
          <div class="round-br">
            <div class="round-tm"> </div>
          </div>
        </div>

</div>