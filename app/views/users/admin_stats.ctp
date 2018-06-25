<div class="users stats js-response js-responses clearfix js-admin-stats-block">
    <div class="grid_19 omega alpha">
	<?php echo $this->element('admin-charts-stats'); ?>
    </div>
    <div class="grid_5 dashboard-side2 omega alpha grid_right">
     <div class="admin-side1-tl ">
                <div class="admin-side1-tr">
                  <div class="admin-side1-tc">
                    <h2><?php echo __l('Timings'); ?></h2>
                  </div>
                </div>
            </div>
		<div class="admin-center-block dashboard-center-block">
            <ul class="admin-dashboard-links">
                <li>
                	<?php $title = ' title="' . strftime(Configure::read('site.datetime.tooltip') , strtotime('now')) . ' ' . Configure::read('site.timezone_offset') . '"'; ?>
                    <?php echo __l('Current time: '); ?><span <?php echo $title; ?>><?php echo strftime(Configure::read('site.datetime.format')); ?></span>
                </li>
                <li>
                    <?php echo __l('Last login: '); ?><?php echo $this->Html->cDateTimeHighlight($this->Auth->user('last_logged_in_time')); ?>
                </li>
            </ul>
		</div>
		<div class="admin-side1-tl admin-side1-tl1">
                <div class="admin-side1-tr admin-side1-tr1">
                  <div class="admin-side1-tc admin-side1-tc1">
                    <h2><?php echo __l('Actions to Be Taken'); ?></h2>
                  </div>
                </div>
            </div>
		<div class="admin-center-block admin-center-block1 dashboard-center-block1 dashboard-center-block">
            <ul class="admin-dashboard-links">
                <li>
					<h4><?php echo __l('Access Requests'); ?></h4>
                	<?php echo $this->Html->link('<span>' . __l('Waiting for Approval') . '</span>' . ' (' .$this->Html->cInt($business_waiting_for_approval, false) .')', array('controller' => 'businesses', 'action' => 'index', 'main_filter_id' => ConstBusinessRequests::Pending), array('escape' => false)); ?>
                </li>
                <li>
					<h4><?php echo __l('Place Claim Requests'); ?></h4>
                    <?php echo $this->Html->link('<span>' . __l('Pending Request') . '</span>' . ' (' . $this->Html->cInt($place_claim_request_pending, false) . ')', array('controller' => 'place_claim_requests', 'action' => 'index', 'filter_id' => ConstPlaceClaimRequests::Pending), array('escape' => false)); ?>
                </li>
            </ul>
            </div>
            <div class="js-cache-load js-cache-load-recent-users {'data_url':'admin/users/recent_users', 'data_load':'js-cache-load-recent-users'}">
	       		<?php echo $this->element('users-admin_recent_users'); ?>
            </div>
            <div class="js-cache-load js-cache-load-online-users {'data_url':'admin/users/online_users', 'data_load':'js-cache-load-online-users'}">
        	   <?php echo $this->element('users-admin_online_users'); ?>
            </div>

     <div class="admin-side1-tl ">
                <div class="admin-side1-tr">
                  <div class="admin-side1-tc">
                    <h2><?php echo __l('JustSpotted'); ?></h2>
                  </div>
                </div>
            </div>
		<div class="admin-center-block dashboard-center-block">
            <ul class="admin-dashboard-links">
                <li class="version-info">
                    <?php echo __l('Version').' ' ?>
					<span>
					<?php echo Configure::read('site.version'); ?>
					</span>
                </li>
                <li>
                    <?php echo $this->Html->link(__l('Product Support'), 'http://customers.agriya.com/', array('target' => '_blank', 'title' => __l('Product Support'))); ?>
                </li>
                <li>
                    <?php echo $this->Html->link(__l('Product Manual'), 'http://dev1products.dev.agriya.com/doku.php?id=justspotted' ,array('target' => '_blank','title' => __l('Product Manual'))); ?>
                </li>
                <li>
                    <?php echo $this->Html->link(__l('CSSilize'), 'http://www.cssilize.com/', array('target' => '_blank', 'title' => __l('CSSilize'))); ?>
					<small>PSD to XHTML Conversion and JustSpotted theming</small>
                </li>
                <li>
                    <?php echo $this->Html->link(__l('Agriya Blog'), 'http://blogs.agriya.com/' ,array('target' => '_blank','title' => __l('Agriya Blog'))); ?>
					<small>Follow Agriya news</small>
                </li>
            </ul>
		</div>
	</div>
</div>