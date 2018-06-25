<?php
/* SVN FILE: $Id: default.ctp 7805 2008-10-30 17:30:26Z AD7six $ */
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.console.libs.templates.skel.views.layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @version       $Revision: 7805 $
 * @modifiedby    $LastChangedBy: AD7six $
 * @lastmodified  $Date: 2008-10-30 23:00:26 +0530 (Thu, 30 Oct 2008) $
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
?>
<?php if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] != 'hashbang')) { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(), "\n";?>
	<title><?php echo Configure::read('site.name');?> | <?php echo sprintf(__l('Admin - %s'), $this->Html->cText($title_for_layout, false)); ?></title>
	<?php
		echo $this->Html->meta('icon'), "\n";
		echo $this->Html->meta('keywords', $meta_for_layout['keywords']), "\n";
		echo $this->Html->meta('description', $meta_for_layout['description']), "\n";
		echo $this->Javascript->codeBlock('var cfg = ' . $this->Javascript->object($js_vars_for_layout) , array('inline' => true));
		echo $this->Html->css('admin.cache', null, array('inline' => true));
		$js_inline = "document.documentElement.className = 'js';";
		$js_inline .= 'var cfg = ' . $this->Javascript->object($js_vars_for_layout) . ';';
		$js_inline .= "(function() {";
		$js_inline .= "var js = document.createElement('script'); js.type = 'text/javascript'; js.async = true;";
		if (!$_jsPath = Configure::read('cdn.js')) {
			$_jsPath = Router::url('/', true);
		}
		$js_inline .= "js.src = \"" . $_jsPath . 'js/default.cache.js' . "\";";
		$js_inline .= "var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(js, s);";
		$js_inline .= "})();";
		echo $this->Javascript->codeBlock($js_inline, array('inline' => true));
		echo $this->element('site_tracker');
	?>
</head>

<?php } ?>
<body>
<div id="<?php echo $this->Html->getUniquePageId();?>" class="content admin-content">
<?php
	$meta = '';
	if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'hashbang') {
		$meta_arr = array(
			'title' => Configure::read('site.name') . ' | ' . $this->Html->cText($title_for_layout, false),
			'keywords' => $meta_for_layout['keywords'],
			'description' => $meta_for_layout['description'],
		);
		$meta = ' js-meta ' . str_replace('"', '\'', json_encode($meta_arr));
	}
?>
   <?php if ($this->Session->check('Message.error') || $this->Session->check('Message.success') || $this->Session->check('Message.flash')): ?>
		<div class="js-flash-message flash-message-block">
			<?php
				if ($this->Session->check('Message.error')):
					echo $this->Session->flash('error');
				endif;
				if ($this->Session->check('Message.success')):
					echo $this->Session->flash('success');
				endif;
				if ($this->Session->check('Message.flash')):
					echo $this->Session->flash();
				endif;
			?>
		</div>
	<?php endif; ?>
	<div class="admin-content-block">
    	<div class="admin-container-24">
		<div id="header" class="clearfix">
	    <div class="clearfix">
			<h1 class="grid_5 mega alpha">
					<?php echo $this->Html->link((Configure::read('site.name').' '.'<span>Admin</span>'), array('controller' => 'users', 'action' => 'stats', 'admin' => true), array('escape' => false, 'title' => (Configure::read('site.name').' '.'Admin')));?>
    		</h1>
    		 	<ul class="admin-menu grid_right clearfix">
		        <li class="view-site"><span><?php echo $this->Html->link(__l('Visit Site'), '/', array('title' => __l('Vist Site')));?></span></li>
		        <?php $class = (!empty($this->request->params['controller']) && $this->request->params['controller'] == 'users' && $this->request->params['action'] == 'admin_diagnostics') ? 'active' : null; ?>
                <li class="<?php echo $class;?>"><span><?php echo $this->Html->link(__l('Diagnostics'), array('controller' => 'users', 'action' => 'diagnostics', 'admin' => true), array('title' => __l('Diagnostics')));?></span></li>
				<li><span><?php echo $this->Html->link(__l('My Account'), array('controller' => 'user_profiles', 'action' => 'edit', $this->Auth->user('id')), array('title' => __l('My Account')));?></span></li>
                <li><span><?php echo $this->Html->link(__l('Change Password'), array('controller' => 'users', 'action' => 'admin_change_password'), array('title' => __l('Change Password')));?></span></li>
				<li class="logout"><span><?php echo $this->Html->link(__l('Logout'), array('controller' => 'users', 'action' => 'logout'), array('title' => __l('Logout')));?></span></li>
  			</ul>
		 </div>
		    <?php
                    echo $this->element('admin-sidebar');
           ?>
    	</div>
		<div id="main" class="clearfix">
		<?php
                $user_menu = array('users', 'activities', 'user_logins', 'user_profiles','user_openids', 'user_followers', 'user_views', 'user_points');
				$sightings_menu = array('sightings',  'sighting_views', 'sighting_flags', 'sighting_ratings');
				$reviews_menu = array('reviews', 'review_comments', 'review_views', 'review_ratings','review_rating_types');
				$items_menu = array('items', 'item_followers');
				$business_menu = array('businesses', 'business_updates', 'business_followers', 'business_views', 'place_claim_requests');
				$places_menu = array('places', 'place_followers','place_views');
				$guides_menu = array('guides', 'guide_followers',  'guide_views');
				$master_menu = array('email_templates', 'sighting_flag_categories','sighting_rating_types', 'place_types', 'guide_categories', 'translations', 'ips', 'languages', 'user_preference_catgories','banned_ips', 'cities', 'states', 'countries','pages');
				$diagnostics_menu = array('devs');
                $settings_menu = array('settings');
               if($this->request->params['controller'] == 'users' && $this->request->params['action'] == 'admin_diagnostics') {
					$class = "diagnostics-title";
				}

               elseif(in_array($this->request->params['controller'], $user_menu)) {
					$class = "users-title";
				} elseif(in_array($this->request->params['controller'],$sightings_menu)) {
					$class = "sightings-title";
				} elseif(in_array($this->request->params['controller'], $reviews_menu)) {
					$class = "reviews-title";
				} elseif(in_array($this->request->params['controller'], $items_menu)) {
					$class = "items-title";
				}elseif(in_array($this->request->params['controller'], $places_menu)) {
					$class = "places-title";
				} elseif(in_array($this->request->params['controller'], $guides_menu)) {
					$class = "guides-title";
				}elseif(in_array($this->request->params['controller'], $settings_menu)) {
					$class = "settings-title";
				}elseif(in_array($this->request->params['controller'], $diagnostics_menu)) {
					$class = "diagnostics-title";
				}elseif(in_array($this->request->params['controller'], $master_menu)) {
					$class = "masters-title";
				} elseif(in_array($this->request->params['controller'], $business_menu)) {
					$class = "business-title";
				}
         if($this->request->params['controller'] == 'users' && $this->request->params['action'] == 'admin_stats'){
          ?>
            <?php echo $content_for_layout;?>
       <?php } else { ?>
          <div class="admin-side1-tl">
            <div class="admin-side1-tr">
                <div class="admin-side1-tc page-title-info clearfix">
                
                <h2 class="<?php echo $class; ?>">
							<?php if($this->request->params['controller'] == 'settings' && $this->request->params['action'] == 'index') { ?>
								<?php echo $this->Html->link(__l('Settings'), array('controller' => 'settings', 'action' => 'index'), array('title' => __l('Back to Settings')));?>
							<?php }elseif($this->request->params['controller'] == 'settings' && $this->request->params['action'] == 'admin_edit' ) { ?>
								<?php echo $this->Html->link(__l('Settings'), array('controller' => 'settings', 'action' => 'index'), array('title' => __l('Back to Settings')));?> &raquo; <?php echo $setting_category['SettingCategory']['name']; ?>
							<?php } elseif(in_array( $this->request->params['controller'], $diagnostics_menu) || $this->request->params['controller'] == 'devs' && $this->request->params['action'] == 'admin_logs') { ?>
							<?php echo $this->Html->link(__l('Diagnostics'), array('controller' => 'users', 'action' => 'diagnostics', 'admin' => true), array('title' => __l('Diagnostics')));?> &raquo; <?php echo $this->pageTitle;?>
							<?php } else { ?>
								<?php echo $this->pageTitle;?>
							<?php } 
								if($this->request->params['controller'] == 'settings' || $this->request->params['controller'] == 'sighting_rating_types' || $this->request->params['controller'] == 'review_rating_types') {
								?>
									<span class="setting-info grid_right info"><?php echo __l('To reflect setting changes, you need to') . ' ' . $this->Html->link(__l('clear cache'), array('controller' => 'devs', 'action' => 'clear_cache', '?f=' . $this->request->url), array('title' => __l('clear cache'), 'class' => 'js-delete'));  ?>.</span>
								<?php
								}
							?>
						</h2>
                          </div>
             </div>
        </div>
         <div class="admin-center-block clearfix">
    			<?php echo $content_for_layout;?>
        </div>
          <?php } ?>
		</div>
	</div>
		<div id="footer" class="clearfix">
			<div class="footer-inner clearfix">
				<div id="agriya" class="clearfix copywrite-info">
					<p>&copy;<?php echo date('Y');?> <?php echo $this->Html->link(Configure::read('site.name'), Router::Url('/',true), array('title' => Configure::read('site.name'), 'escape' => false));?>. <?php echo __l('All rights reserved');?>.</p>
					<p class="powered clearfix"><span><a href="http://foodspotting.dev.agriya.com/" title="<?php echo __l('Powered by Justspotted');?>" target="_blank" class="powered"><?php echo __l('Powered by justspotted');?></a>,</span> <span><?php echo __l('made in'); ?></span> <?php echo $this->Html->link('Agriya Web Development', 'http://www.agriya.com/', array('target' => '_blank', 'title' => 'Agriya Web Development', 'class' => 'company'));?>  <span><?php echo Configure::read('site.version');?></span></p>
					<p><?php echo $this->Html->link('CSSilized by CSSilize', 'http://www.cssilize.com/', array('target' => '_blank', 'title' => 'CSSilized by CSSilize', 'class' => 'cssilize'));?></p>
				</div>
			</div>
		</div>
	</div>
	</div>
	<?php echo $this->element('sql_dump'); ?>
<?php if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] != 'hashbang')) { ?>
</body>
</html>
<?php } ?>