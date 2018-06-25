<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="guides index  js-lazyload js-response">
<?php
    if(empty($this->request->params['named']['following']) && empty($this->request->params['named']['user']) && !empty($this->request->params['named']['filter']) && $this->request->params['named']['filter'] != 'featured'){ ?>
       <?php echo $this->element('guides-index', array('view' => 'featured', 'filter' => 'featured', 'config' => 'site_element_cache_10_min'));
    }?>
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
<?php
if(!empty($this->pageTitle) && empty($this->request->params['isAjax'])): ?>
	<h2><?php echo $this->pageTitle; ?></h2>
<?php endif; ?>
<div class="clearfix">
    <?php if(empty($this->request->params['requested'])){?>
        <div class="grid_right">
            <?php echo $this->Html->link(__l('Create a Guide'), array('controller' => 'guides', 'action' => 'add'), array('class'=>'create-guide','title' => __l('Create a Guide')));?>
        </div>
    <?php } ?>
    <div class="grid_left">
        <?php echo $this->element('paging_counter');?>
    </div>
</div>
	<?php
		$grid_class = "";

	        if(empty($this->request->params['isAjax'])) {
		         $grid_class = "grid_10";
		      } else {
			   $grid_class = "grid_9";
		}
	?>
<ol class="guide-list clearfix" start="<?php echo $this->Paginator->counter(array(
    'format' => '%start%'
));?>">
<?php
if (!empty($guides)):

$i = 0;
foreach ($guides as $guide):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' altrow';
	}
	?>
	<li class="clearfix">
		<div class="image-block grid_3 omega">
            <?php 
				echo $this->Html->link($this->Html->showImage('Guide', $guide['Attachment'], array('dimension' => 'normal_thumb', 'alt' => sprintf(__l('[Image: %s]'), $guide['Guide']['name']), 'title' => $guide['Guide']['name'])), array('controller'=> 'guides', 'action' => 'view', $guide['Guide']['slug']), array('escape' => false)); 
			?>
			 <?php  if($guide['Guide']['is_published']==0){ ?>
				<span class="unpublish"><?php echo ('Unpublish'); ?></span>
			  <?php  }  ?>
            </div>
			<div class="content-block <?php echo $grid_class; ?> omega">
			<h3>
			<?php echo $this->Html->link($this->Html->cText($this->Html->truncate($guide['Guide']['name'],40)), array('controller'=> 'guides', 'action' => 'view', $guide['Guide']['slug']), array('escape' => false));?>	
            </h3>
            <p class="sighting-caption"><?php echo $this->Html->cText($this->Html->truncate($guide['Guide']['tagline'],60));?></p>
	    	<div class="clearfix">
        	 <p class="city-name grid_left"><?php echo __l('Created by:');?> <?php echo $this->Html->link($guide['User']['username'], array('controller' => 'users', 'action' => 'view', $guide['User']['username']), array('escape' => false));?></p>
        	<?php if(!empty($guide['City']['name'])) { ?>
        	<p class="city-name grid_left">
				<?php	echo  __l('City: ') . ' ' . $this->Html->cText($guide['City']['name']); ?>
         	</p>
        	<?php } ?>
	   		<p class="city-name grid_left"><?php echo __l('Created on:');?> <?php echo $this->Time->timeAgoInWords($guide['Guide']['created']);?></p>
			</div>
              <dl class="sighting-list clearfix">
                <dt class="follower"><?php echo __l('Followers'); ?></dt><dd><?php echo $this->Html->cInt($guide['Guide']['guide_follower_count']);?></dd>
                <dt class="view"><?php echo __l('Views'); ?></dt><dd><?php echo $this->Html->cInt($guide['Guide']['guide_view_count']);?></dd>
                <dt class="sightings"><?php echo __l('Sightings'); ?></dt><dd><?php echo $this->Html->cInt($guide['Guide']['sighting_count']);?></dd>
            </dl>
	</div>
	<div class="grid_2  alpha grid_right">
            <div class="action-block">
                 <?php
                  if($this->Auth->user('id') && $this->Auth->user('id') == $guide['Guide']['user_id']){ ?>
                    <?php
                   echo $this->Html->link(__l('Edit'), array('controller' => 'guides', 'action'=>'edit', $guide['Guide']['id']), array('class' => 'edit', 'title' => __l('Edit'))); ?>
                 <?php  } ?>
            </div>
              <?php
				if($guide['Guide']['is_published']==0){ ?>
				  <div class="follow-block grid_right grid_2">
                <?php
                    echo $this->Html->link(__l('Publish'), array('controller' => 'guides', 'action'=>'update', 'guide'=>$guide['Guide']['slug']), array( 'class'=>'js-delete','title' => __l('Publish'))); ?>
            </div>
              <?php  }
                else{
				if($this->Auth->sessionValid()):
					if(!empty($guide['GuideFollower'])) { ?>

								  <div class="follow-block unfollow-block grid_right grid_2">
					<?php	echo $this->Html->link(__l('Unfollow'), array('controller' => 'guide_followers', 'action'=>'delete', $guide['GuideFollower'][0]['id']), array( 'title' => __l('Unfollow'))); ?>
					</div>
				<?php	}
				endif;
				if(empty($guide['GuideFollower'])) { ?>
							  <div class="follow-block grid_right grid_2">
				<?php	echo $this->Html->link(__l('Follow'), array('controller' => 'guide_followers', 'action'=>'add', 'guide' => $guide['Guide']['slug']), array( 'title' => __l('Follow'))); ?>
				</div>
			<?php	}
				}
			?>
 </div>
	</li>
<?php
    endforeach;
else:
?>
	<li class="notice">
		<?php echo __l('No Guides available');?>
	</li>
<?php
endif;
?>
</ol>

<?php
if (!empty($guides)) {
	if(!empty($this->params['isAjax'])):
?>
<div class="js-pagination">
<?php
	endif;
    echo $this->element('paging_links'); 
	if(!empty($this->params['isAjax'])):
?>
</div>  
<?php
	endif; 
}
?>

<?php if(empty($this->request->params['requested']) && empty($this->request->params['isAjax'])) { ?>
    </div>
                </div>
            </div>
            <div class="spot-bl">
                <div class="spot-br">
                  <div class="spot-bm"> </div>
                </div>
              </div>
</div>
<div class="side2 grid_8 alpha omega">


   	<?php if(empty($this->request->params['requested'])){?>
   <?php if(empty($this->request->params['isAjax'])){ ?>
      <div class="people-list-block">
      <div class="round-tl">
      <div class="round-tr">
        <div class="round-tm"> </div>
      </div>
     </div>
      <div class="people-list-inner clearfix">
            	<?php echo $this->Form->create('Guide', array('class' => 'normal search-form1 clearfix', 'action'=>'index')); ?>
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
  <?php } } ?>

<div class="people-list-block">
  <div class="round-tl">
      <div class="round-tr">
        <div class="round-tm"> </div>
      </div>
    </div>
    <div class="people-list-inner">
<div class="guideCategories index js-response js-responses">
<h3><?php echo __l('Search Guides');?></h3>
<ul class="categories-list">
    <?php $class=(($this->request->params['controller']=='guides' && $this->request->params['action']=='index' && (empty($this->request->params['named']['sort']) && empty($this->request->params['named']['direction']) && empty($this->request->params['named']['following']) && empty($this->request->params['named']['user']) && empty($this->request->params['named']['filter']) && empty($this->request->params['named']['category']))) ? 'active' : '');?>
    <li class="altrow clearfix <?php echo $class;?>">
        <?php
			echo $this->Html->link(__l('Latest'), array('controller' => 'guides', 'action' => 'index'), array('title' => __l('Latest'), 'escape' => false));
		?>
    </li>
    <?php $class=(($this->request->params['controller']=='guides' && $this->request->params['action']=='index' && (!empty($this->request->params['named']['sort']) && $this->request->params['named']['sort']=='guide_follower_count') && (!empty($this->request->params['named']['direction']) && $this->request->params['named']['direction']=='desc')) ? 'active' : '');?>
    <li class="clearfix <?php echo $class;?>">
        <?php
			echo $this->Html->link(__l('Popular'), array('controller' => 'guides', 'action' => 'index', 'sort' => 'guide_follower_count','direction'=>'desc'), array('title' => __l('Popular'), 'escape' => false));
		?>
    </li>
    <?php if($this->Auth->sessionValid()) { ?>
    <?php $class=(($this->request->params['controller']=='guides' && $this->request->params['action']=='index' && (!empty($this->request->params['named']['following']) && $this->request->params['named']['following']==$this->Auth->user('username'))) ? 'active' : '');?>
    <li class="altrow clearfix <?php echo $class;?>">
        <?php
			echo $this->Html->link(__l('Following'), array('controller' => 'guides', 'action' => 'index', 'following' => $this->Auth->user('username')), array('title' => __l('Following'), 'escape' => false));
		?>
    </li>
    <?php $class=(($this->request->params['controller']=='guides' && $this->request->params['action']=='index' && (!empty($this->request->params['named']['user']) && $this->request->params['named']['user']==$this->Auth->user('username'))) ? 'active' : '');?>
    <li class="clearfix <?php echo $class;?>">
        <?php
			echo $this->Html->link(__l('My Guides'), array('controller' => 'guides', 'action' => 'index', 'user' => $this->Auth->user('username')), array('title' => __l('My Guide'), 'escape' => false));
		?>
    </li>
    <?php } ?>
    <?php $class=(($this->request->params['controller']=='guides' && $this->request->params['action']=='index' && (!empty($this->request->params['named']['filter']) && $this->request->params['named']['filter']=='featured')) ? 'active' : '');?>
    <li class="altrow clearfix <?php echo $class;?>">
        <?php
			echo $this->Html->link(__l('Featured'), array('controller' => 'guides', 'action' => 'index', 'filter' => 'featured'), array('title' => __l('Featured'), 'escape' => false));
		?>
    </li>

</ul>
</div>
</div>

        <div class="round-bl">
          <div class="round-br">
            <div class="round-tm"> </div>
          </div>
        </div>

</div>
<?php 
    $category='';
    if(!empty($this->request->params['named']['category'])){
        $category=$this->request->params['named']['category'];
    }
	echo $this->element('guide_categories-index', array('category'=>$category,'config' => 'site_element_cache_5_min'));
?>
<h3 class="popular"><?php echo __l('Popular Guides');?></h3>
<?php
	echo $this->element('guides-index', array('type' => 'popular', 'view' => 'simple', 'config' => 'site_element_cache_10_min'));
?>

</div>
<?php }?>
</div>
