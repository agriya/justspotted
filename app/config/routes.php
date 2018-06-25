<?php
/**
 * Just Spotted
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    justspotted
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2018 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */ *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.2.9
 * @version       $Revision: 7820 $
 * @modifiedby    $LastChangedBy: renan.saddam $
 * @lastmodified  $Date: 2008-11-03 23:57:56 +0530 (Mon, 03 Nov 2008) $
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
Router::parseExtensions('rss', 'csv', 'json', 'txt', 'xml');
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/views/pages/home.ctp)...
 */
//	Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
//  pages/install as home page...
Router::connect('/', array(
    'controller' => 'sightings',
    'action' => 'index',
));
/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
Router::connect('/admin', array(
    'controller' => 'users',
    'action' => 'stats',
    'prefix' => 'admin',
    'admin' => 1
));
// Code to show the images uploaded by upload behaviour
Router::connect('/img/:size/*', array(
    'controller' => 'images',
    'action' => 'view'
) , array(
    'size' => '(?:[a-zA-Z_]*)*'
));
Router::connect('/files/*', array(
    'controller' => 'images',
    'action' => 'view',
    'size' => 'original'
));
Router::connect('/img/*', array(
    'controller' => 'images',
    'action' => 'view',
    'size' => 'original'
));
// common routes
Router::connect('/sitemap', array(
    'controller' => 'devs',
    'action' => 'sitemap'
));
Router::connect('/robots', array(
    'controller' => 'devs',
    'action' => 'robots'
));
Router::connect('/contactus', array(
    'controller' => 'contacts',
    'action' => 'add'
));
// For user module
Router::connect('/users/twitter/login', array(
    'controller' => 'users',
    'action' => 'login',
    'type' => 'twitter'
));
Router::connect('/users/facebook/login', array(
    'controller' => 'users',
    'action' => 'login',
    'type' => 'facebook'
));
Router::connect('/users/yahoo/login', array(
    'controller' => 'users',
    'action' => 'login',
    'type' => 'yahoo'
));
Router::connect('/users/gmail/login', array(
    'controller' => 'users',
    'action' => 'login',
    'type' => 'gmail'
));
Router::connect('/users/openid/login', array(
    'controller' => 'users',
    'action' => 'login',
    'type' => 'openid'
));
Router::connect('/places/user/:user', array(
	'controller' => 'places',
	'action' => 'index'),
	array('user' => '[^\/]+')
);
Router::connect('/sightings/place/:place', array(
	'controller' => 'sightings',
	'action' => 'index'),
	array('place' => '[^\/]+')
);
Router::connect('/sightings/item/:item', array(
	'controller' => 'sightings',
	'action' => 'index'),
	array('item' => '[^\/]+')
);
Router::connect('/guides/user/:user', array(
	'controller' => 'guides',
	'action' => 'index'),
	array('user' => '[^\/]+')
);
Router::connect('/guides/following/:following', array(
	'controller' => 'guides',
	'action' => 'index'),
	array('following' => '[^\/]+')
);
Router::connect('/guide_followers/add/guide/:guide', array(
	'controller' => 'guide_followers',
	'action' => 'add'),
	array('guide' => '[^\/]+')
);	
Router::connect('/sightings/user/:user', array(
	'controller' => 'sightings',
	'action' => 'index'),
	array('user' => '[^\/]+')
);
Router::connect('/users/following/:following', array(
	'controller' => 'users',
	'action' => 'index'),
	array('following' => '[^\/]+')
);
Router::connect('/users/follower/:follower', array(
	'controller' => 'users',
	'action' => 'index'),
	array('follower' => '[^\/]+')
);
Router::connect('/place_followers/user/:user', array(
	'controller' => 'place_followers',
	'action' => 'index'),
	array('user' => '[^\/]+')
);
Router::connect('/guides/category/:category', array(
	'controller' => 'guides',
	'action' => 'index'),
	array('user' => '[^\/]+')
);
Router::connect('/guides/filter/:filter', array(
	'controller' => 'guides',
	'action' => 'index'),
	array('filter' => '[^\/]+')
);
Router::connect('/user_followers/add/user/:user', array(
	'controller' => 'user_followers',
	'action' => 'add'),
	array('user' => '[^\/]+')
);
Router::connect('/item_followers/add/item/:item', array(
	'controller' => 'item_followers',
	'action' => 'add'),
	array('item' => '[^\/]+')
);
//Review rating 
Router::connect('/review_ratings/add/review/:review_id/type/:review_rating_type_id', array(
    'controller' => 'review_ratings',
    'action' => 'add',
    'review_id' => '[^\/]*',
    'review_rating_type_id' => '[^\/]*'
));
Router::connect('/sighting_ratings/add/sighting/:sighting_id/type/:sighting_rating_type_id', array(
    'controller' => 'sighting_ratings',
    'action' => 'add',
    'sighting_id' => '[^\/]*',
    'sighting_rating_type_id' => '[^\/]*'
));
Router::connect('/js/*', array(
	'controller' => 'devs',
	'action' => 'asset_js'
));
Router::connect('/css/*', array(
	'controller' => 'devs',
	'action' => 'asset_css'
));
Router::connect('/place_followers/add/place/:place', array(
	'controller' => 'place_followers',
	'action' => 'add'),
	array('place' => '[^\/]+')
);
Router::connect('/request_business_access', array(
	'controller' => 'businesses',
	'action' => 'add')
);
Router::connect('/business/dashboard', array(
	'controller' => 'businesses',
	'action' => 'my_business')
);
Router::connect('/notifications', array(
	'controller' => 'user_points',
	'action' => 'index')
);
?>