<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="people-list-block clearfix">
  <div class="round-tl">
      <div class="round-tr">
        <div class="round-tm"> </div>
      </div>
    </div>
    <div class="people-list-inner">
<div class="businessFollowers index js-response js-responses">
<h3><?php echo __l('Followers'). ' ('.$this->Html->cInt($business_follower_count).')'; ?></h3>
<?php if(empty($this->request->params['named'])) : ?>
<?php echo $this->element('paging_counter');?>
<?php endif; ?>
<ol class="people-list" start="">
<?php
if (!empty($businessFollowers)):

$i = 0;
foreach ($businessFollowers as $businessFollower):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' altrow';
	}
?>
	<li class="<?php echo $class;?> clearfix">
    	<div class="img-block">
		<?php
			echo $this->Html->link($this->Html->showImage('UserAvatar', $businessFollower['User']['UserAvatar'], array('dimension' => 'small_thumb', 'alt' => sprintf(__l('[Image: %s]'), $businessFollower['User']['username']), 'title' => $businessFollower['User']['username'], 'escape' => false)), array('controller' => 'users', 'action' => 'view',  $businessFollower['User']['username']), array('escape' => false))
		?>
        </div>
            <div class="grid_5 follw-description">
                <h4><?php echo $this->Html->link($this->Html->cText($businessFollower['User']['username'], false), array('controller' => 'users', 'action' => 'view',  $businessFollower['User']['username']), array('escape' => false)); ?></h4>
                <address>
                <?php
					$address = array();
					if(!empty($businessFollower['User']['UserProfile']['City']['name'])): 
						$address[] = $businessFollower['User']['UserProfile']['City']['name'];
					endif;
					if(!empty($businessFollower['User']['UserProfile']['State']['name'])): 
						$address[] = $businessFollower['User']['UserProfile']['State']['name'];
					endif;
					if(!empty($businessFollower['User']['UserProfile']['Country']['name'])): 
						$address[] = $businessFollower['User']['UserProfile']['Country']['name'];
					endif;
					if(!empty($address)):
						echo $this->Html->cText(implode(', ', $address));
					endif;
				?>
                </address>
          </div>
	</li>
<?php
    endforeach;
else:
?>
	<li class="notice">
		<p><?php echo __l('No Business Followers available');?></p>
	</li>
<?php
endif;
?>
</ol>
<?php
if (!empty($businessFollowers)) {
        ?>
            <div class="js-pagination">
                <?php echo $this->element('paging_links'); ?>
            </div>
        <?php
}
?>

</div>
</div>

        <div class="round-bl">
          <div class="round-br">
            <div class="round-tm"> </div>
          </div>
        </div>

</div>
