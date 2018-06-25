<h5 class="hidden-info"><?php echo __l('Admin side links'); ?></h5>
<ul class="admin-links clearfix">
     <?php
    $class = ($this->request->params['controller'] == 'users' && $this->request->params['action'] == 'admin_stats') ? 'admin-active' : null; ?>
	<li class="no-bor grid_3 <?php echo $class;?>">
     <span class="amenu-left">
             <span class="amenu-right">
                 <span class="menu-center home">
                     <span><?php echo __l('Site Stats'); ?></span>
                 </span>
            </span>
         </span>
          <div class="admin-sub-block">
              <div class="admin-sub-lblock">
                    <div class="admin-sub-rblock">
                        <div class="admin-sub-cblock">
                        	<ul class="">
                            <li>
                                <h4><?php echo __l('Dashboard '); ?></h4>
                                 <ul>
                                 <li>
                        		  <?php echo $this->Html->link(__l('Site Stats'), array('controller' => 'users', 'action' => 'stats'),array('title' => __l('Site Stats'))); ?>
                                 </li>
                               	</ul>
                                </li>
                            </ul>
                        </div>
                	</div>
        	 </div>
             <div class="admin-bot-lblock">
				<div class="admin-bot-rblock">
					<div class="admin-bot-cblock"></div>
				</div>
            </div>
        </div>
    </li>
    <?php $controller = array('users', 'user_profiles',  'user_logins', 'activities', 'user_comments','user_openids','user_views','user_followers');
 $class = (in_array( $this->request->params['controller'], $controller ) && !in_array($this->request->params['action'], array('admin_logs', 'admin_stats', 'admin_referred_users')) ) ? 'admin-active' : null; ?>
  <li class="no-bor grid_3 <?php echo $class;?>">
    <span class="amenu-left">
             <span class="amenu-right">
                 <span class="menu-center users">
                     <span><?php echo __l('Users'); ?></span>
                 </span>
            </span>
         </span>
          <div class="admin-sub-block">
              <div class="admin-sub-lblock">
                    <div class="admin-sub-rblock">
                        <div class="admin-sub-cblock">
                        	<ul class="">
                            <li>
                                <h4><?php echo __l('Users'); ?></h4>
                                  <ul>
                            			<?php $class = ($this->request->params['controller'] == 'users') ? ' active' : null; ?>
                            			<li class="<?php echo $class;?>"><?php echo $this->Html->link(__l('Users'), array('controller' => 'users', 'action' => 'index'),array('title' => __l('Users'))); ?></li>
                                        <li class="merchant-info  <?php echo $class;?>"><?php echo $this->Html->link(__l('Business Users'), array('controller' => 'users', 'action' => 'index', 'main_filter_id' => ConstMoreAction::Business),array('title' => __l('Business Users'))); ?><span><?php echo __l('Users with business access');?></span></li>
                                        <li class="<?php echo $class;?>"><?php echo $this->Html->link(__l('Send Email to Users'), array('controller' => 'users', 'action' => 'send_mail'),array('title' => __l('Send Email to Users'))); ?></li>
                                	</ul>
                                    <h4><?php echo __l('Activities'); ?></h4>
                                    <ul>
                                    	<li class="merchant-info  <?php echo $class;?>" ><?php echo $this->Html->link(__l('Recent Activities'), array('controller' => 'activities', 'action' => 'feeds'),array('title' => __l('Recent Activities'))); ?><span><?php echo __l('Module wide audit');?></span></li>
										<?php $class = ($this->request->params['controller'] == 'user_points') ? ' class="active"' : null; ?>
                            			<li <?php echo $class;?>><?php echo $this->Html->link(__l('User Points'), array('controller' => 'user_points', 'action' => 'index'),array('title' => __l('User Points'))); ?></li>
                                        <?php $class = ($this->request->params['controller'] == 'user_logins') ? ' class="active"' : null; ?>
                            			<li <?php echo $class;?>><?php echo $this->Html->link(__l('User Logins'), array('controller' => 'user_logins', 'action' => 'index'),array('title' => __l('User Logins'))); ?></li>
                            			<?php $class = ($this->request->params['controller'] == 'user_followers') ? ' class="active"' : null; ?>
                            			<li <?php echo $class;?>><?php echo $this->Html->link(__l('User Followers'), array('controller' => 'user_followers', 'action' => 'index'),array('title' => __l('User Followers'))); ?></li>
                            			<?php $class = ($this->request->params['controller'] == 'user_views') ? ' class="active"' : null; ?>
                            			<li <?php echo $class;?>><?php echo $this->Html->link(__l('User Views'), array('controller' => 'user_views', 'action' => 'index'),array('title' => __l('User Views'))); ?></li>
                            			<?php $class = ($this->request->params['controller'] == 'users' && $this->request->params['action'] == 'admin_send_mail') ? ' class="active"' : null; ?>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                	</div>
        	 </div>
             <div class="admin-bot-lblock">
				<div class="admin-bot-rblock">
					<div class="admin-bot-cblock"></div>
				</div>
            </div>
        </div>
    </li>
    <?php $controller = array('businesses', 'business_updates','business_views','business_followers', 'sightings',  'sighting_views', 'sighting_flags','items','item_followers','places','place_views','place_followers','reviews','review_comments','review_ratings','review_views','sighting_ratings');
	$class = ( in_array( $this->request->params['controller'], $controller ) ) ? 'admin-active' : null; ?>
	<li class="sightings-chart-block grid_7 no-bor <?php echo $class;?>">
        <span class="amenu-left">
             <span class="amenu-right">
                 <span class="menu-center sightings">
                     <span><?php echo __l('Businesses,&nbsp;Sightings Related'); ?></span>
                 </span>
            </span>
         </span>
          <div class="admin-sub-block">
              <div class="admin-sub-lblock">
                    <div class="admin-sub-rblock">
                        <div class="admin-sub-cblock">
							<div class="sightings-chart-block">
								<ul class="sightings-chart-list clearfix item-section guides-chart-list business-chart-list">
											<li class="business-block guides-block">
			<span class="business">
				<?php echo $this->Html->link(__l('Businesses'), array('controller' => 'users', 'action' => 'index', 'main_filter_id' => ConstMoreAction::Business, 'admin'=>true),array('title' => __l('Businesses'))); ?>
				</span>
					<ul class="views-section clearfix">
						<li class="views-inner-section">
							<span class="views access-request">
								<?php echo $this->Html->link(__l('Access requests'), array('controller' => 'businesses', 'action' => 'index','admin'=>true), array('title' => __l('Access requests'))); ?>
							</span>
						</li>
						<li class="views-inner-section1">
							<span class="views place-request">
								<?php echo $this->Html->link(__l('Place claim requests'), array('controller' => 'place_claim_requests', 'action' => 'index', 'admin'=>true),array('title' => __l('Place claim requests'))); ?>
                            </span>
                    	</li>
						<li class="views-inner-section">
							<span class="views updates">
								<?php echo $this->Html->link(__l('Updates'), array('controller' => 'business_updates', 'action' => 'index','admin'=>true),array('title' => __l('Updates'))); ?>
							</span>
						</li>
						<li class="views-inner-section">
				    	<span class="views">
								<?php echo $this->Html->link(__l('Views'), array('controller' => 'business_views', 'action' => 'index','admin'=>true),array('title' => __l('Views'))); ?>
							</span>
			          	</li>
						<li class="views-inner-section">
							<span class="followers">
							<?php echo $this->Html->link(__l('Followers'), array('controller' => 'business_followers', 'action' => 'index','admin'=>true),array('title' => __l('Followers'))); ?>
							</span>
						</li>
					</ul>
			</li>
									<li class="sightings-left-section">
										<div class="">
											<span class="sightings-inner">
												<?php echo $this->Html->link(__l('Sightings'), array('controller' => 'sightings', 'action' => 'index'),array('title' => __l('Sightings'))); ?>                                            
                                            </span>
											<span class="chart-info"><?php echo __l('e.g., Pizza @ McDonalds, 202, Blah,CA, USA'); ?></span>
										</div>
										<ul class="clearfix item-section">
                                        	<li class="place-block">
												<span class="place">
													<?php echo $this->Html->link(__l('Places'), array('controller' => 'places', 'action' => 'index'),array('title' => __l('Places'))); ?>
                                                </span>
												<span class="chart-info place-chart-info">e.g., McDonalds, 202, Blah,CA, USA</span>
											<ul class="views-section place-views-section clearfix">
												<li class="views-cont-block">
													<span class="views">
														<?php echo $this->Html->link(__l('Views'), array('controller' => 'place_views', 'action' => 'index'),array('title' => __l('Views'))); ?>
                                                    </span>
												</li>
												<li class="followers-cont-block">
													<span class="followers">
														<?php echo $this->Html->link(__l('Followers'), array('controller' => 'place_followers', 'action' => 'index'),array('title' => __l('Followers'))); ?>

                                                    </span>
												</li>
										</ul>
    									</li>
											<li class="item-block">
												<span class="item">
													<?php echo $this->Html->link(__l('Items'), array('controller' => 'items', 'action' => 'index'),array('title' => __l('Items'))); ?>
                                                </span>
												<span class="chart-info item-chart-info"><?php echo __l('e.g., Pizza'); ?></span>
													<ul class="views-section place-views-section clearfix">
														<li class="rating-cont-block rating-cont-block1">
															<span class="followers">
																<?php echo $this->Html->link(__l('Followers'), array('controller' => 'item_followers', 'action' => 'index'),array('title' => __l('Followers'))); ?>
                                                            </span>
														</li>
													</ul>
											</li>
										</ul>
									</li>
									<li class="sightings-right-section">
										<ul class="sightings-right-content">
											<li class="reviews-block">
												<span class="reviews">
													<?php echo $this->Html->link(__l('Reviews'), array('controller' => 'reviews', 'action' => 'index'),array('title' => __l('Reviews'))); ?>                                                    
                                                                                                                                                
                                                </span>
											<ul class="views-section place-views-section clearfix">
												<li class="comments-cont-block">
													<span class="comments">
														<?php echo $this->Html->link(__l('Comments'), array('controller' => 'review_comments', 'action' => 'index'),array('title' => __l('Comments'))); ?>                                                    
                                                    
                                                    </span>
												</li>
													<li class="rating-cont-block">
													<span class="ratings">
														<?php echo $this->Html->link(__l('Ratings'), array('controller' => 'review_ratings', 'action' => 'index'),array('title' => __l('Ratings'))); ?>                                                                      
                                                    </span>
												</li>
												<li class="reviews-right-cont-block">
													<span class="views">
														<?php echo $this->Html->link(__l('Views'), array('controller' => 'review_views', 'action' => 'index'),array('title' => __l('Views'))); ?>                                                    
                                                    </span>
												</li>
										</ul>
											</li>
											<li class="ratings-block">
												<span class="ratings">
													<?php echo $this->Html->link(__l('Ratings'), array('controller' => 'sighting_ratings', 'action' => 'index'),array('title' => __l('Ratings'))); ?>                                                    
                                                
                                                </span>
											</li>
											<li class="ratings-block flags-block">
												<span class="sighting-flags">
													<?php echo $this->Html->link(__l('Flags'), array('controller' => 'sighting_flags', 'action' => 'index'),array('title' => __l('Flags'))); ?>                                                    
                                                
                                                </span>
											</li>
											<li class="ratings-block views-block">
												<span class="views">
													<?php echo $this->Html->link(__l('Views'), array('controller' => 'sighting_views', 'action' => 'index'),array('title' => __l('Views'))); ?>                                                    
                                                
                                                </span>
											</li>
										</ul>
									</li>
								</ul>
							</div>
                        </div>
                	</div>
        	 </div>
             <div class="admin-bot-lblock">
				<div class="admin-bot-rblock">
					<div class="admin-bot-cblock"></div>
				</div>
            </div>
        </div>
	</li>
	<?php $controller = array('guides', 'guide_followers',  'guide_views');
	$class = ( in_array( $this->request->params['controller'], $controller ) ) ? 'admin-active' : null; ?>
	<li class="guides-chart-block no-bor grid_3 <?php echo $class;?>">
     <span class="amenu-left">
             <span class="amenu-right">
                 <span class="menu-center guides">
                     <span><?php echo __l('Guides'); ?></span>
                 </span>
            </span>
         </span>
          <div class="admin-sub-block">
              <div class="admin-sub-lblock">
                    <div class="admin-sub-rblock">
                        <div class="admin-sub-cblock">
						<div class="sightings-chart-block">
							<ul class="clearfix item-section guides-chart-list guides-chart-list1">
											<li class="guides-block">
											<div class="page-info guides-page-info"><span><?php echo __l('Guides are collection of sightings created by users');?></span></div>
												<span class="guides">
												<?php echo $this->Html->link(__l('Guides'), array('controller' => 'guides', 'action' => 'index'),array('title' => __l('Guides'))); ?>                                                                             
                                                </span>
													<ul class="views-section place-views-section clearfix">
														<li class="views-cont-block">
															<span class="views">
												<?php echo $this->Html->link(__l('Views'), array('controller' => 'guide_views', 'action' => 'index'),array('title' => __l('Views'))); ?>                                                                                                                                         
                                                            </span>
														</li>
														<li class="followers-cont-block">
															<span class="followers">
												<?php echo $this->Html->link(__l('Followers'), array('controller' => 'guide_followers', 'action' => 'index'),array('title' => __l('Followers'))); ?>                             
                                                            </span>
														</li>
													</ul>
											</li>
										</ul>
							</div>
                        </div>
                	</div>
        	 </div>
             <div class="admin-bot-lblock">
				<div class="admin-bot-rblock">
					<div class="admin-bot-cblock"></div>
				</div>
            </div>
        </div>
	</li>
    <?php $class = ($this->request->params['controller'] == 'settings') ? 'admin-active' : null; ?>
	<li class="masters  grid_3 setting-masters-block masters-block <?php echo $class;?>">
     <span class="amenu-left">
             <span class="amenu-right">
                 <span class="menu-center settings">
                     <span><?php echo __l('Settings'); ?></span>
                 </span>
            </span>
         </span>
     <div class="admin-sub-block">
           <div class="admin-sub-lblock">
            <div class="admin-sub-rblock">
                <div class="admin-sub-cblock">
                   <ul class="clearfix">
                          <li class="setting-overview setting-overview1 clearfix"><?php echo $this->Html->link(__l('Overview'), array('controller' => 'settings', 'action' => 'index'),array('title' => __l('Overview'), 'class' => 'setting-overview')); ?></li>
                  <li>       <h4 class="setting-title"><?php echo __l('Settings'); ?></h4></li>
 <li class="admin-sub-links-left grid_left">
                        <ul>
                         <li><?php echo $this->Html->link(__l('System'), array('controller' => 'settings', 'action' => 'edit', 1),array('title' => __l('System'))); ?></li>
                         <li><?php echo $this->Html->link(__l('Development'), array('controller' => 'settings', 'action' => 'edit', 3),array('title' => __l('Development'))); ?></li>
                         <li><?php echo $this->Html->link(__l('Account '), array('controller' => 'settings', 'action' => 'edit', 5),array('title' => __l('Account'))); ?></li>
                         <li><?php echo $this->Html->link(__l('CDN'), array('controller' => 'settings', 'action' => 'edit', 35),array('title' => __l('CDN'))); ?></li>
                         <li><?php echo $this->Html->link(__l('Point System'), array('controller' => 'settings', 'action' => 'edit', 39),array('title' => __l('Point System'))); ?></li>
                        </ul>
                    </li>
                   <li class="admin-sub-links-right grid_left">
                      <ul>
                         <li><?php echo $this->Html->link(__l('SEO'), array('controller' => 'settings', 'action' => 'edit', 2),array('title' => __l('SEO'))); ?></li>
                         <li><?php echo $this->Html->link(__l('Regional'), array('controller' => 'settings', 'action' => 'edit', 4),array('title' => __l('Regional'))); ?></li>
                         <li><?php echo $this->Html->link(__l('Third Party API'), array('controller' => 'settings', 'action' => 'edit', 6),array('title' => __l('Third Party API'))); ?></li>
                         <li><?php echo $this->Html->link(__l('Mobile Apps'), array('controller' => 'settings', 'action' => 'edit', 37),array('title' => __l('Mobile Apps'))); ?></li>
                         <li><?php echo $this->Html->link(__l('Suspicious Words'), array('controller' => 'settings', 'action' => 'edit', 44),array('title' => __l('Suspicious Words'))); ?></li>
                     </ul>
                   </li>
                  </ul>
                </div>
	        </div>
		 </div>
             <div class="admin-bot-lblock">
				<div class="admin-bot-rblock">
					<div class="admin-bot-cblock"></div>
				</div>
            </div>
        </div>
	</li>
	<?php $controller = array('email_templates',  'pages', 'translations', 'languages',  'banned_ips', 'cities', 'states', 'countries', 'ips', 'place_types', 'guide_categories');
	$class = ( in_array( $this->request->params['controller'], $controller ) ) ? 'admin-active' : null; ?>
	<li class="masters grid_3 setting-masters-block <?php echo $class;?>">
          <span class="amenu-left">
             <span class="amenu-right">
                 <span class="menu-center masters">
                     <span><?php echo __l('Masters'); ?></span>
                 </span>
            </span>
         </span>
          <div class="admin-sub-block">
              <div class="admin-sub-lblock">
                    <div class="admin-sub-rblock">

                        <div class="admin-sub-cblock">
                        <ul>
                        <li>
                        <div class="page-info master-page-info"><?php echo __l('Warning! Please edit with caution.');?></div>
                    <ul class="clearfix">
	           <li class="admin-sub-links-left grid_left">
                    <h4><?php echo __l('Regional'); ?></h4>
                        <ul>
                        <li>
                        <?php echo $this->Html->link(__l('Cities'), array('controller' => 'cities', 'action' => 'index'),array('title' => __l('Cities'))); ?>
                        </li>
                        <li>
                        <?php echo $this->Html->link(__l('Countries'), array('controller' => 'countries', 'action' => 'index'),array('title' => __l('Countries'))); ?>
                        </li>
                        <li>
                        <?php echo $this->Html->link(__l('States'), array('controller' => 'states', 'action' => 'index'),array('title' => __l('States'))); ?>
                        </li>
                        <li>
                        <?php echo $this->Html->link(__l('Banned IPs'), array('controller' => 'banned_ips', 'action' => 'index'),array('title' => __l('Banned IPs'))); ?>
                        </li>
                        </ul>
                        <h4><?php echo __l('Languages'); ?></h4>
                        <ul>
                        <li>
                        <?php echo $this->Html->link(__l('Languages'), array('controller' => 'languages', 'action' => 'index'),array('title' => __l('Languages'))); ?>
                        </li>
                        <li>
                        <?php echo $this->Html->link(__l('Translations'), array('controller' => 'translations', 'action' => 'index'),array('title' => __l('Translations'))); ?>
                        </li>
                        </ul>
                        <h4><?php echo __l('Static pages'); ?></h4>
                        <ul>
                        <li>
                        <?php echo $this->Html->link(__l('Manage Static Pages'), array('controller' => 'pages', 'action' => 'index', 'plugin' => NULL),array('title' => __l('Manage Static Pages')));?>
                        </li>
                        </ul>

               </li>
            <li class="admin-sub-links-right grid_left">

                <h4><?php echo __l('Email'); ?></h4>
                <ul>
                <li>
                <?php echo $this->Html->link(__l('Email Template'), array('controller' => 'email_templates', 'action' => 'index'),array('title' => __l('Email Template'))); ?>
                </li>
                </ul>
                <h4><?php echo __l('Others'); ?></h4>
                <ul>
                <li>
                <?php echo $this->Html->link(__l('Sighting Rating Types'), array('controller' => 'sighting_rating_types', 'action' => 'index'), array('title' => __l('Sighting Rating Types'))); ?>
                </li>
				<li>
                <?php echo $this->Html->link(__l('Review Rating Types'), array('controller' => 'review_rating_types', 'action' => 'index'), array('title' => __l('Review Rating Types'))); ?>
                </li>
                 <li>
                <?php echo $this->Html->link(__l('Sighting Flag Categories'), array('controller' => 'sighting_flag_categories', 'action' => 'index'), array('title' => __l('Sighting Flag Categories'))); ?>
                </li>
                <li>
                <?php echo $this->Html->link(__l('Guide Categories'), array('controller' => 'guide_categories', 'action' => 'index'), array('title' => __l('Guide Categories'))); ?>
                </li>
                <li>
                <?php echo $this->Html->link(__l('Place Types'), array('controller' => 'place_types', 'action' => 'index'), array('title' => __l('Place Types'))); ?>
                </li>
				<li>
                <?php echo $this->Html->link(__l('IPs'), array('controller' => 'ips', 'action' => 'index'), array('title' => __l('IPs'))); ?>
                </li>
                </ul>
                </li>
            </ul>
            </li>
            </ul>
                    </div>
                	</div>
        	 </div>
             <div class="admin-bot-lblock">
				<div class="admin-bot-rblock">
					<div class="admin-bot-cblock"></div>
				</div>
            </div>
        </div>
	</li>

</ul>
