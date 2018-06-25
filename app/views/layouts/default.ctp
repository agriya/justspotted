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
<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
		<?php echo $this->Html->charset(), "\n";?>
		<title><?php echo Configure::read('site.name');?> | <?php echo $this->Html->cText($title_for_layout, false);?></title>
		<?php	
		echo $this->Html->meta('icon'), "\n\t\t";
		echo $this->Html->meta('keywords', $meta_for_layout['keywords']), "\n\t\t";
		echo $this->Html->meta('description', $meta_for_layout['description']), "\n\t\t";
		echo $this->Html->css('default.cache', null, array('inline' => true)) . "\n\t\t";
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

        // For other than Facebook (facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)), wrap it in comments for XHTML validation...
        if (strpos(env('HTTP_USER_AGENT'), 'facebookexternalhit')===false){
            echo '<!--', "\n";
       } ?>
		<meta content="<?php echo Configure::read('facebook.fb_app_id'); ?>" property="og:app_id" />
		<meta content="<?php echo Configure::read('facebook.fb_app_id'); ?>" property="fb:app_id" />
		<meta property="og:site_name" content="<?php echo Configure::read('site.name'); ?>"/>
<?php if (!empty($meta_for_layout['sighting_name'])) { ?>
		<meta property="og:title" content="<?php echo $meta_for_layout['sighting_name']; ?>"/>
<?php } elseif (!empty($meta_for_layout['guide_name'])) { ?>
		<meta property="og:title" content="<?php echo $meta_for_layout['guide_name']; ?>"/>
<?php } elseif (!empty($meta_for_layout['title'])) { ?>
		<meta property="og:title" content="<?php echo $meta_for_layout['title']; ?>"/>
<?php	}else { ?>
       	<meta property="og:title" content="<?php echo Configure::read('site.name'); ?>"/>
<?php	} ?>
<?php if (!empty($meta_for_layout['sighting_image'])){ ?>
		<meta property="og:image" content="<?php echo Router::url('/', true) . $meta_for_layout['sighting_image']; ?>"/>
<?php } elseif (!empty($meta_for_layout['guide_image'])){ ?>
      	<meta property="og:image" content="<?php echo Router::url('/', true) . $meta_for_layout['guide_image']; ?>"/>
<?php } elseif (!empty($meta_for_layout['image'])){ ?>
      	<meta property="og:image" content="<?php echo $meta_for_layout['image']; ?>"/>
<?php }else{ ?>
       	<meta property="og:image" content="<?php echo Router::url('/', true); ?>img/logo.png"/>
<?php } ?>
<?php if (!empty($meta_for_layout['sighting_notes'])){ ?>
		<meta property="og:description" content="<?php echo $meta_for_layout['sighting_notes']; ?>"/>
<?php	   }
		if (strpos(env('HTTP_USER_AGENT'), 'facebookexternalhit')===false){
		echo '-->', "\n";
		}
        // <--
?>
<?php
echo $this->element('site_tracker');
?>
	</head>
	<body>
<?php } ?>
<div id="<?php echo $this->Html->getUniquePageId();?>" class="content">
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
<?php if ($this->Session->check('Message.error') || $this->Session->check('Message.success') || $this->Session->check('Message.flash')){ ?>
    <div class="js-flash-message flash-message-block">
    <?php
    if ($this->Session->check('Message.error')){
    echo $this->Session->flash('error');
    }
    if ($this->Session->check('Message.success')){
    echo $this->Session->flash('success');
   	}
    if ($this->Session->check('Message.flash')){
    echo $this->Session->flash();
    }
    ?>
    </div>
<?php }
$hearderclass = '';
if(empty($this->request->url) || ($this->request['controller'] == 'sightings' && $this->request['action'] == 'index')) {
    $hearderclass = 'main-header';
}
?>
            <div id="header" class="<?php echo $hearderclass; if($this->Auth->sessionValid() && $this->Auth->user('user_type_id') == ConstUserTypes::Admin){ ?> admin-top <?php } ?>">
                     <div class="header-inner container_24 clearfix">
                        <h1 class="grid_5 alpha omega grid_left"><?php echo $this->Html->link(Configure::read('site.name'), Router::Url('/',true), array('title' => Configure::read('site.slogan'), 'escape' => false, 'class' => 'tool-tip'));?></h1>
                        <div id="sub-header" class="grid_19 alpha omega grid_right">
                        	<div class="clearfix">
<?php                          if($this->Auth->sessionValid()){
                                $class = 'class="menu grid_left clearfix"';
                                }else{
                                $class = 'class="global-links grid_right clearfix"';
                                }
								if($this->Auth->user('is_business_user')){
								$class = 'menu grid_left clearfix';
								} else {
								$class= 'menu menu-link grid_left clearfix';
								}
?>
								<ul class="<?php echo $class; ?>">
<?php								$class = ($this->request->params['controller'] == 'sightings' && $this->request->params['action'] == 'index') ? ' class="active"' : null; ?>
									<li<?php echo $class;?>><span><?php echo $this->Html->link(__l('Sightings'), '/', array('title' => __l('Sightings')));?></span></li>
<?php							$class = ($this->request->params['controller'] == 'guides') ? ' class="active"' : null; ?>
									<li<?php echo $class;?>><span><?php echo $this->Html->link(__l('Guides'), array('controller' => 'guides', 'action' => 'index'), array('title' => __l('Guides')));?></span></li>
<?php                           $class = ($this->request->params['controller'] == 'users' && $this->request->params['action'] == 'index') ? ' class="active"' : null; 
?>
									<li<?php echo $class;?>><span><?php echo $this->Html->link(__l('People'), array('controller' => 'users', 'action' => 'index'), array('title' => __l('People')));?></span></li>
<?php							$class = ($this->request->params['controller'] == 'places') ? ' class="active"' : null;
?>
									<li<?php echo $class;?>><span><?php echo $this->Html->link(__l('Places'), array('controller' => 'places', 'action' => 'index'), array('title' => __l('Places')));?></span></li>
<?php                           $class = ($this->request->params['controller'] == 'businesses' && $this->request->params['action'] == 'index') ? ' class="active"' : null;?>
									<li<?php echo $class;?>><span><?php echo $this->Html->link(__l('Businesses'), array('controller' => 'businesses', 'action' => 'index'), array('title' => __l('Businesses')));?></span></li>
								</ul>
								<ul class="global-links grid_right clearfix">
								<?php
                                if($this->Auth->sessionValid()){
								$class = ($this->request->params['controller'] == 'users' && $this->request->params['action'] == 'dashboard') ? ' class="active"' : null; ?>       <li<?php echo $class;?>><?php echo $this->Html->link(__l('Dashboard'), array('controller' => 'users', 'action' => 'dashboard'), array('title' => __l('Dashboard')));?></li>
<?php						if($this->Auth->user('is_business_user')){ 
								$class = ($this->request->params['controller'] == 'businesses' && $this->request->params['action'] == 'my_business') ? ' class="active"' : null; ?><li<?php echo $class;?>><?php echo $this->Html->link(__l('My Business'), array('controller' => 'businesses', 'action' => 'my_business'), array('title' => __l('My Business')));?></li>
<?php } 						$class = ($this->request->params['controller'] == 'user_profiles' && $this->request->params['action'] == 'edit') ? ' class="active"' : null; ?>
									<li<?php echo $class;?>><?php echo $this->Html->link(__l('My Account'), array('controller' => 'user_profiles', 'action' => 'edit'), array('title' => __l('My Account')));?></li>
<?php							$class = ($this->request->params['controller'] == 'user_points' && $this->request->params['action'] == 'index') ? ' class="active"' : null; ?>
									<li<?php echo $class;?>><?php echo $this->Html->link(__l('Notifications'), array('controller' => 'user_points', 'action' => 'index'), array('title' => __l('Notifications')));?></li>
<?php }else{?>
									<li><?php echo $this->Html->link(__l('Join'), array('controller' => 'users', 'action' => 'register', 'admin' => false), array('title' => __l('Join')));?></li>
                                   					<li class="active"><?php echo $this->Html->link(__l('Login'), array('controller' => 'users', 'action' => 'login'), array('title' => __l('Login')));?></li>
<?php }?>
								</ul>
<?php if(!$this->Auth->sessionValid()){ ?> 
								<div class="welcome-info grid_right"> <span><?php echo __l('Welcome, Guest'); ?> </span></div>
<?php }?>				</div>
<?php if($this->Auth->sessionValid() && $this->Auth->user('user_type_id') == ConstUserTypes::Admin){ ?>
						<div class="clearfix admin-wrapper">
                                                	<h5 class="admin-site-logo grid_left"><?php   	echo $this->Html->link((Configure::read('site.name').' '.'<span>Admin</span>'), array('controller' => 'users', 'action' => 'stats', 'admin' => true), array('escape' => false, 'title' => (Configure::read('site.name').' '.'Admin')));?></h5>
							<p class="logged-info grid_left"><?php  echo __l(' You are logged in as Admin');?></p>
                            				<ul class="admin-menu grid_right clearfix">
                                                    		<li class="logout"><span><?php echo $this->Html->link(__l('Logout'), array('controller' => 'users', 'action' => 'logout'), array('title' => __l('Logout')));?></span></li>
                                                     	</ul>
                            </div>
                            <?php } ?>
                            <div class="clearfix">
							<?php if ($this->Auth->sessionValid()){?>
                            <div class="welcome-block clearfix grid_right">
                                <span> <?php 	echo __l('Welcome, '); ?>    </span>
								<?php
                            if($this->Auth->user('user_type_id') == ConstUserTypes::User){
								echo $this->Html->link($this->Html->showImage('UserAvatar', $this->Html->getUserAvatar($this->Auth->user('id')), array('dimension' => 'micro_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Auth->user('username')), 'title' => $this->Auth->user('username'), 'escape' => false)), array('controller' => 'users', 'action' => 'view',  $this->Auth->user('username')), array('escape' => false)).' '.$this->Html->link($this->Auth->user('username'), array('controller' => 'users', 'action' => 'view', $this->Auth->user('username')),array('title' => $this->Auth->user('username')));
								if($this->Auth->user('is_openid_register')){
								}
							}else{
							echo $this->Html->link($this->Auth->user('username'), array('controller' => 'users', 'action' => 'stats', 'admin' => true),array('title' => $this->Auth->user('username')));
							}
							?>
							<?php echo $this->Html->link(__l('Logout'), array('controller' => 'users', 'action' => 'logout'), array('class'=>'logout', 'title' => __l('Logout')));?>
                             </div>
							<?php }
							if(!$this->Auth->sessionValid()){ ?>
                             <div class="open-id-block grid_right clearfix">
                                	<h5 class="grid_left"><?php echo __l('Sign In using: '); ?></h5>
                                    <ul class="open-id-list grid_left clearfix">
                                <?php
                                        if(Configure::read('facebook.is_enabled_facebook_connect')){
                                    ?>
                                        <li class="facebook"><?php echo $this->Html->link(__l('Sign in with Facebook'), array('controller' => 'users', 'action' => 'login','type'=>'facebook'), array('title' => __l('Sign in with Facebook'), 'escape' => false)); ?></li>
                                        <?php
                                        } ?>
                                        <?php
                                        if(Configure::read('twitter.is_enabled_twitter_connect')){?>
                                        <li class="twitter"><?php echo $this->Html->link(__l('Sign in with Twitter'), array('controller' => 'users', 'action' => 'login',  'type'=> 'twitter', 'admin'=>false), array('class' => 'Twitter', 'title' => __l('Sign in with Twitter')));?></li>
                                        <?php
                                        }?>
                                        <?php
                                    if(Configure::read('foursquare.is_enabled_foursquare_connect')){?>
                                        <li class="foursquare"><?php echo $this->Html->link(__l('Sign in with Foursquare'), array('controller' => 'users', 'action' => 'login',  'type'=> 'foursquare', 'admin'=>false), array('class' => 'Foursquare', 'title' => __l('Sign in with Foursquare')));?></li>
                                    <?php
                                    }?>
                                    <?php
                                    if(Configure::read('user.is_enable_yahoo_openid')){?>
                                        <li class="yahoo"><?php echo $this->Html->link(__l('Sign in with Yahoo'), array('controller' => 'users', 'action' => 'login', 'type'=>'yahoo'), array('title' => __l('Sign in with Yahoo')));?></li>
                                    <?php
                                    }?>
                                    <?php
                                    if(Configure::read('user.is_enable_gmail_openid')){?>
                                        <li class="gmail"><?php echo $this->Html->link(__l('Sign in with Gmail'), array('controller' => 'users', 'action' => 'login', 'type'=>'gmail'), array('title' => __l('Sign in with Gmail')));?></li>
                                    <?php
                                    }?>
                                    <?php
                                    if(Configure::read('user.is_enable_openid')){?>
                                        <li class="openid"><?php 	echo $this->Html->link(__l('Sign in with Open ID'), array('controller' => 'users', 'action' => 'login','type'=>'openid'), array('class'=>'','title' => __l('Sign in with Open ID')));?></li>
                                    <?php
                                    }?>
                                    </ul>
                                   </div>
								 <?php } 
							?>
                         <div class="grid_right">
                                <?php
                                $languages = $this->Html->getLanguage();
                                if(Configure::read('user.is_allow_user_to_switch_language') && !empty($languages)) {
                                echo $this->Form->create('Language', array('action' => 'change_language', 'class' => 'normal language-form'));
                                echo $this->Form->input('language_id', array('class' => 'js-autosubmit', 'empty' => __l('Please Select'), 'options' => $languages, 'value' => isset($_COOKIE['CakeCookie']['user_language']) ?  $_COOKIE['CakeCookie']['user_language'] : Configure::read('site.language')));
                                echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url));
                                ?>
                                <div class="hide">
                                    <?php echo $this->Form->submit('Submit');  ?>
                                </div>
                                <?php
                                    echo $this->Form->end();
                                   }
                                    ?>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <div id="main" class="clearfix">
                <?php
                    if(($this->request->params['controller'] == 'sightings' && $this->request->params['action'] == 'index')) {
                    echo $this->element('sighting-search');
                    }
                ?>
                    <div class="main-inner container_24">
                    <?php
                            if (!(($this->request->params['controller'] == 'sightings' && $this->request->params['action'] == 'index')or($this->request->params['controller'] == 'users' && $this->request->params['action'] == 'index')or($this->request->params['controller'] == 'guides' && $this->request->params['action'] == 'index')or($this->request->params['controller'] == 'sightings' && $this->request->params['action'] == 'view')or($this->request->params['controller'] == 'places' && $this->request->params['action'] == 'view')or($this->request->params['controller'] == 'guides' && $this->request->params['action'] == 'view') or ($this->request->params['controller'] == 'businesses' && $this->request->params['action'] == 'view') or($this->request->params['controller'] == 'users' && $this->request->params['action'] == 'view')or($this->request->params['controller'] == 'reviews' && $this->request->params['action'] == 'view') || ($this->request->params['controller'] == 'users' && $this->request->params['action'] == 'dashboard')||($this->request->params['controller'] == 'user_points' && $this->request->params['action'] == 'index')||($this->request->params['controller'] == 'businesses' && $this->request->params['action'] == 'my_business') ||($this->request->params['controller'] == 'places' && $this->request->params['action'] == 'index')||($this->request->params['controller'] == 'businesses' && $this->request->params['action'] == 'index')||($this->request->params['controller'] == 'businesses' && $this->request->params['action'] == 'add'))) { ?>
                            <div class="spot-tl">
                                <div class="spot-tr">
                                    <div class="spot-tm"> </div>
                                </div>
                            </div>
                            <div class="spot-lm">
                                <div class="spot-rm">
                                    <div class="spot-middle center-spot-middle clearfix">
                                    <?php } ?>
                                    <?php echo $content_for_layout;?>
                                    <?php if (!(($this->request->params['controller'] == 'sightings' && $this->request->params['action'] == 'index')or($this->request->params['controller'] == 'users' && $this->request->params['action'] == 'index')or($this->request->params['controller'] == 'guides' && $this->request->params['action'] == 'index')or($this->request->params['controller'] == 'sightings' && $this->request->params['action'] == 'view')or($this->request->params['controller'] == 'places' && $this->request->params['action'] == 'view')or($this->request->params['controller'] == 'guides' && $this->request->params['action'] == 'view')or ($this->request->params['controller'] == 'businesses' && $this->request->params['action'] == 'view') or ($this->request->params['controller'] == 'users' && $this->request->params['action'] == 'view')or($this->request->params['controller'] == 'reviews' && $this->request->params['action'] == 'view') || ($this->request->params['controller'] == 'users' && $this->request->params['action'] == 'dashboard')||($this->request->params['controller'] == 'user_points' && $this->request->params['action'] == 'index')||($this->request->params['controller'] == 'businesses' && $this->request->params['action'] == 'my_business')||($this->request->params['controller'] == 'places' && $this->request->params['action'] == 'index')||($this->request->params['controller'] == 'businesses' && $this->request->params['action'] == 'index')||($this->request->params['controller'] == 'businesses' && $this->request->params['action'] == 'add'))) { ?>
                                    </div>
                                </div>
                            </div>
                            <div class="spot-bl">
                                <div class="spot-br">
                                    <div class="spot-bm"> </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                </div>
                <?php if($this->Auth->user('user_type_id') == ConstUserTypes::Admin){ ?>
                <div id="footer" class="footer-block">
                <?php } else { ?>
                <div id="footer">
                <?php } ?>
                  <div class="footer-inner container_24 clearfix">
                        <div class="grid_16 footer-left alpha">
                            <ul class="footer-nav grid_5 alpha">
                                <li><?php echo $this->Html->link(__l('About Us'), array('controller' => 'pages', 'action' => 'view', 'about', 'admin' => false), array('title' => __l('About Us')));?></li>
                                <li><?php echo $this->Html->link(__l('Terms & Policies'), array('controller' => 'pages', 'action' => 'view', 'term-and-policies', 'admin' => false), array('title' => __l('Terms & Policies')));?></li>
								<li><?php echo $this->Html->link(__l('How it Works'), array('controller' => 'pages', 'action' => 'view', 'how-it-works', 'admin' => false), array('title' => __l('How it Works')));?></li>
                            </ul>
                            <ul class="footer-nav grid_5">
                                <li><?php echo $this->Html->link(__l('Contact Us'), array('controller' => 'contacts', 'action' => 'add', 'admin' => false), array('title' => __l('Contact Us ')));?></li>
                                <?php
								if(!$this->Auth->sessionValid() || $this->Auth->user('is_business_user') == 0) {
                                ?>
								<li><?php echo $this->Html->link(__l('Request Business Access'), array('controller' => 'businesses', 'action' => 'add', 'admin' => false), array('title' => __l('Request Business Access')));?></li>
                                <?php
								}
                                ?>
                            </ul>
                            <ul class="footer-nav grid_5 clearfix">
                                <li class="twitter"><a href="https://twitter.com/#!/justspotted" title="Twitter" target="_blank">Twitter</a></li>
                                <li class="face"><a href="https://www.facebook.com/pages/Justspotted/281838665184786?sk=wall" title="Facebook" target="_blank">Facebook</a></li>
                            </ul>
                        </div>
                        <div class="grid_8 omega alpha">
                            <p class="copy grid_8 alpha omega grid_right">&copy;<?php echo date('Y');?> <?php echo $this->Html->link(Configure::read('site.name'), Router::Url('/',true), array('title' => Configure::read('site.name'), 'escape' => false));?>. <?php echo __l('All rights reserved');?>.</p>
                            <div class="clearfix">
                                <div id="agriya" class="clearfix grid_8 alpha copywrite-info">
                                    <p class="powered clearfix"><span><a href="http://justspotted.dev.agriya.com/" title="<?php echo __l('Powered by Justspotted');?>" target="_blank" class="powered"><?php echo __l('Powered by justspotted');?></a>,</span> <span><?php echo __l('made in'); ?></span> <?php echo $this->Html->link('Agriya Web Development', 'http://www.agriya.com/', array('target' => '_blank', 'title' => 'Agriya Web Development', 'class' => 'company'));?>  <span><?php echo Configure::read('site.version');?></span></p>
                                 </div>
                            </div>
                              <p class="clearfix"><?php echo $this->Html->link('CSSilized by CSSilize, PSD to XHTML Conversion', (env('HTTPS') )? '#' : 'http://www.cssilize.com/', array('target' => '_blank', 'title' => 'CSSilized by CSSilize, PSD to XHTML Conversion', 'class' => 'cssilize'));?></p>
                        
                        </div>
                    </div>
                </div>
            </div>
<?php echo $this->element('sql_dump'); ?>
<?php if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] != 'hashbang')) { ?>
        </body>
</html>
<?php } ?>
