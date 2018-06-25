<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="side1 grid_16 alpha omega">
<?php echo $this->element('reviews-add', array('config' => 'site_element_cache_5_min')); ?>
<div class="spot-tl">
        <div class="spot-tr">
          <div class="spot-tm"> </div>
        </div>
      </div>
	  <div class="spot-lm">
        <div class="spot-rm">
          <div class="spot-middle sightings-inner clearfix">
    		<h3><?php echo __l('Following'); ?></h3>
             <?php if(empty($this->request->params['isAjax']) && empty($this->request->params['named']['stat']) && empty($this->request->params['named']['type'])): ?>
            	<div class="js-tabs">
            		<ul class="clearfix tab-menu">
            			<li><em></em><?php echo $this->Html->link(__l('Places'), array('controller' => 'places','action' => 'index', 'user' => $this->Auth->user('username')),array('title' => __l('Places')));?></li>
            			<li><em></em><?php echo $this->Html->link(__l('Users'), array('controller' => 'users','action' => 'index', 'following' => $this->Auth->user('username')),array('title' => __l('Users'))); ?></li>
            			<li><em></em><?php echo $this->Html->link(__l('Guides'),array('controller' => 'guides','action'=>'index', 'following' => $this->Auth->user('username')),array('title' => __l('Guides'))); ?></li>
            			<li><em></em><?php echo $this->Html->link(__l('Items'),array('controller' => 'sightings','action'=>'index', 'user' => $this->Auth->user('username') ),array('title' => __l('Items'))); ?></li>
            			<li><em></em><?php echo $this->Html->link(__l('Businesses'),array('controller' => 'businesses','action'=>'index', 'following' => $this->Auth->user('username') ),array('title' => __l('Businesses'))); ?></li>
						<li><em></em><?php echo $this->Html->link(__l('Business Updates'), array('controller' => 'business_updates', 'action' => 'index', 'user' => $this->Auth->user('username')), array('escape' => false, 'title' => __l('Business Updates')));?></li>
            		</ul>
            	</div>
        <?php else : ?>
   <?php endif; ?>
</div>
</div>
</div>

<div class="spot-bl">
            <div class="spot-br">
              <div class="spot-bm"> </div>
            </div>
          </div>
<div>
</div>
</div>

<div class="side2 grid_8 alpha omega">
		<div class="js-response user-rating-block">
  			<?php echo $this->element('user_points-activity_ratings', array('key' => $this->Auth->user('id'), 'config' => 'site_element_cache_5_min')); ?>
		</div>
		<div class="user-point-index">
	  	<?php echo $this->element('user_points-index', array('key' => $this->Auth->user('id'), 'config' => 'site_element_cache_5_min')); ?>
</div></div>
