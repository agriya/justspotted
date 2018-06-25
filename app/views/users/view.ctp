<?php /* SVN: $Id: $ */ ?>
<div class="users view clearfix sightings-view-block">
    <h2><?php echo __l('User - ').ucfirst($this->Html->cText($user['User']['username'], false)); ?></h2>
    <div class="clearfix">
    <div class="side1 grid_16 alpha omega">
    <div class="spot-tl">
        <div class="spot-tr">
          <div class="spot-tm"> </div>
        </div>
      </div>
        <div class="spot-lm">
        <div class="spot-rm">
          <div class="spot-middle sightings-inner clearfix">
            <div class="js-tabs">
          	  <ul class="list tab-menu clearfix">
            	  <?php
            		 $active = '';
            		if(empty($this->request->params['named'])) {
            				$active = "class='active'";
            		} ?>
            			<li <?php echo $active; ?> >
            			<em></em>
                        <?php echo $this->Html->link(__l('Spotted'), array('controller' => 'reviews', 'action' => 'index', 'user'=>$user['User']['username']), array('escape' => false, 'title' => __l('Spotted by') . ' ' . $user['User']['username'])); ?> </li>
            			<?php echo $current_params = !empty($this->request->params['named']['sighting_rating_type']) ? $this->request->params['named']['sighting_rating_type'] : '';
            			echo $this->element('sighting_rating_types-menu', array('current' => $current_params, 'user' => $user['User']['username'], 'type' => 'profile', 'config' => 'site_element_cache_30_min'));
            			?>
            			<?php
            			if(!empty($this->request->params['named']['filter']) && $this->request->params['named']['filter'] == 'guide') {
            				$active = "class='active'";
            			} ?>
            			<li <?php echo $active; ?>>
                    	<em></em>
            			<?php
            				echo $this->Html->link(__l('Guides'), array('controller' => 'guides', 'action' => 'index', 'user' => $user['User']['username']), array('escape' => false, 'title' => __l('Guides')));
            			?>
            			</li>
	           	</ul>
            </div>
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
    <?php
	echo $this->element('users-sidebar', array('username' => $user['User']['username'], 'config' => 'site_element_cache_5_min'));
	?>
	</div>
</div>
</div>