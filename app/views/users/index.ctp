<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="places index js-lazyload js-response">
<?php 
	if(empty($this->request->params['requested']) && empty($this->request->params['isAjax'])) { ?>
<div class="side1 grid_16 alpha omega">
		 <div class="spot-tl">
			<div class="spot-tr">
			  <div class="spot-tm"> </div>
			</div>
		  </div>
		  <div class="spot-lm">
			<div class="spot-rm">
			  <div class="spot-middle center-spot-middle clearfix">
<?php } ?>
			<h2>
			<?php 
				if(!empty($this->pageTitle) && empty($this->request->params['isAjax'])):
					echo $this->pageTitle;
				endif;
			?>
			</h2>
			<?php echo $this->element('paging_counter');?>
		<?php    if(empty($this->request->params['isAjax'])) {
		        $grid_class = "grid_10";
		      } else {
			   $grid_class = "grid_9";
		}?>
			<ol class="guide-list clearfix" >
			<?php
			if (!empty($users)):

			$i = 0;
			foreach ($users as $user):
				$class = null;
				if ($i++ % 2 == 0) {
				$class = ' altrow';
				}
			?>
				<li class="<?php echo $class;?> clearfix">
						<div class="image-block grid_3 omega">
						<?php 
							echo $this->Html->link($this->Html->showImage('UserAvatar', $this->Html->getUserAvatar($user['User']['id']), array('dimension' => 'normal_thumb', 'alt' => sprintf(__l('[Image: %s]'), $user['User']['username']), 'title' => $user['User']['username'], 'escape' => false)), array('controller' => 'users', 'action' => 'view',  $user['User']['username']), array('escape' => false));
						?>
						</div>
						<div class="content-block <?php echo $grid_class; ?> omega">
						<div class="user-address-block">
						<h3>
							<?php
							if(!empty($user['UserProfile']['first_name']) && !empty($user['UserProfile']['last_name'])) {
								$full_name = $user['UserProfile']['first_name'].' '.$user['UserProfile']['last_name'];
								echo $this->Html->link($this->Html->cText($full_name, false), array('controller' => 'users', 'action' => 'view', $user['User']['username']),array('title' => $this->Html->cText($full_name, false))); 
							} else {
								echo $this->Html->link($user['User']['username'], array('controller' => 'users', 'action' => 'view', $user['User']['username']),array('title' => $user['User']['username'])); 
							}
							?>
						</h3>
						<address>
						<?php
							$address = array();
							if(!empty($user['UserProfile']['City']['name'])): 
								$address[] = $user['UserProfile']['City']['name'];
							endif;
							if(!empty($user['UserProfile']['State']['name'])): 
								$address[] = $user['UserProfile']['State']['name'];
							endif;
							if(!empty($user['UserProfile']['Country']['name'])): 
								$address[] = $user['UserProfile']['Country']['name'];
							endif;
							if(!empty($address)):?>
							<span>
							<?php
								echo $this->Html->cText(implode(', ', $address));
								?>
						</span>
						<?php
							endif;
						?>
						</address>
						</div>
						<?php if(!empty($user['UserProfile']['about_me'])): ?>
						<p class="sighting-caption">
							<?php echo $this->Html->cText($this->Html->truncate($user['UserProfile']['about_me'],138),false); ?>
						</p>
						<?php endif; ?>
				   <dl class="sighting-list">
								<dt class="follower"><?php echo __l('Followers:'); ?></dt><dd><?php echo $this->Html->cInt($user['User']['user_follower_count']);?></dd>
								<dt class="sightings"><?php echo __l('Sightings:'); ?></dt><dd><?php echo $this->Html->cInt($user['User']['review_count']);?></dd>
							</dl>
			   </div>
			   <?php if($user['User']['id'] != $this->Auth->user('id')) { ?>
					<div class="grid_2 alpha grid_right">
						<?php
								if($this->Auth->sessionValid()):
									if(in_array($user['User']['id'], $user_followers)){
										$user_follower = array_flip($user_followers); ?>
									  <div class="follow-block unfollow-block grid_right grid_2">
										<?php
										echo $this->Html->link(__l('Unfollow'), array('controller' => 'user_followers', 'action'=>'delete', $user_follower[$user['User']['id']]), array( 'title' => __l('Unfollow'))); ?>
									   </div>
								<?php	}
								endif;
								if(!in_array($user['User']['id'], $user_followers)){ ?>
										<div class="follow-block grid_right grid_2">
										<?php
									echo $this->Html->link(__l('Follow'), array('controller' => 'user_followers', 'action'=>'add', 'user' => $user['User']['username']), array( 'title' => __l('Follow'))); ?>
									</div>
								<?php }
							?>
						</div>
						<?php }	?>
				</li>
			<?php
				endforeach;
			else:
			?>
				<li class="notice">
					<?php echo __l('No Users available');?>
				</li>
			<?php
			endif;
			?>
			</ol>
			<?php if(!empty($this->request->params['isAjax'])) {  ?>
				<div class="js-pagination">
			<?php }	?>
					<?php
					if (!empty($users)) {
						echo $this->element('paging_links');
					}
					?>
				<?php if(!empty($this->request->params['isAjax'])) {  ?>
					</div>
				<?php }	?>
		</div>
<?php if(empty($this->request->params['requested']) && empty($this->request->params['isAjax'])) { ?>
	</div>
	  </div>
	<div class="spot-bl">
    	<div class="spot-br">
    		<div class="spot-bm"> </div>
    	</div>
        </div>
  
</div>
    <div class="side2 grid_8 alpha omega">
    
			<?php if(empty($this->request->params['requested']) && empty($this->request->params['isAjax'])){ ?>
			<div class="people-list-block clearfix">
			  <div class="round-tl">
      <div class="round-tr">
        <div class="round-tm"> </div>
      </div>
    </div>
    <div class="people-list-inner clearfix">
            	<?php echo $this->Form->create('User', array('class' => 'normal search-form1 clearfix', 'action'=>'index')); ?>
                    <h3><?php echo __l('Search');?></h3>
        		    <?php echo $this->Form->input('q', array('label' => 'Search')); ?>
					<?php echo $this->Form->submit(__l('Go'));?>
    	       	<?php echo $this->Form->end(); ?>
	</div>
      <div class="round-bl">
          <div class="round-br">
            <div class="round-tm"> </div>
          </div>
        </div>
			</div>
			<?php } ?>

		
	   <?php 
	   if($this->Auth->user('id')){			
		   if(empty($this->request->params['isAjax'])): echo $this->element('users-index', array('view' => 'top_spotters')); endif;
	   }
	   else{
			if(empty($this->request->params['isAjax'])): echo $this->element('users-index', array('view' => 'top_spotters', 'config' => 'site_element_cache_10_min')); endif;	   
	   }
	   ?>
<?php 
if($this->Auth->user('id')){
	echo $this->element('users-index', array('type' => 'popular', 'view' => 'simple'));
}
else{
	echo $this->element('users-index', array('type' => 'popular', 'view' => 'simple', 'config' => 'site_element_cache_10_min'));
}	
?>
    </div>
<?php }?>
</div>