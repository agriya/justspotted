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
 */
/* SVN: $Id: config.php 91 2008-07-08 13:13:19Z rajesh_04ag02 $ */
/**
 * Custom configurations
 */
if (!defined('DEBUG')) {
	define('DEBUG', 0);
	// permanent cache re1ated settings
	define('PERMANENT_CACHE_CHECK', (!empty($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR'] != '127.0.0.1') ? true : false);
	// site default language
	define('PERMANENT_CACHE_DEFAULT_LANGUAGE', 'en');
	define('IS_ENABLE_HTML5_HISTORY_API', false);
	// cookie variable name for site language
	define('PERMANENT_CACHE_COOKIE', 'user_language');
	// sub admin is available in site or not
	define('PERMANENT_CACHE_HAVE_SUB_ADMIN', false);
	define('IS_ENABLE_HASHBANG_URL', false);
	$_is_hashbang_supported_bot = (strpos($_SERVER['HTTP_USER_AGENT'], 'Googlebot') !== false);
	define('IS_HASHBANG_SUPPORTED_BOT', $_is_hashbang_supported_bot);
}
if (!defined('PERMANENT_CACHE_GZIP_SALT')) {
	define('PERMANENT_CACHE_GZIP_SALT', "e9a556134534545ab47c6c81c14f06c0b8sdfsdf");	
}
// site actions that needs random attack protection...
$config['site']['_hashSecuredActions'] = array(
    'edit',
    'delete',
    'update',
    'download',
    'v'
);
$config['photo']['file'] = array(
    'allowedMime' => array(
        'image/jpeg',
		'image/jpg',
        'image/gif',
        'image/png'
    ) ,
    'allowedExt' => array(
        'jpg',
        'jpeg',
        'gif',
        'png'
    ) ,
    'allowedSize' => '5',
    'allowedSizeUnits' => 'MB',
	'allowEmpty' => false
);
$config['avatar']['file'] = array(
    'allowedMime' => array(
        'image/jpeg',
        'image/jpg',
        'image/gif',
        'image/png'
    ) ,
    'allowedExt' => array(
        'jpg',
        'jpeg',
        'gif',
        'png'
    ) ,
    'allowedSize' => '5',
    'allowedSizeUnits' => 'MB',
    'allowEmpty' => true
);
$config['review']['file'] = array(
    'allowedMime' => array(
        'image/jpeg',
        'image/jpg',
        'image/gif',
        'image/png'
    ) ,
    'allowedExt' => array(
        'jpg',
        'jpeg',
        'gif',
        'png'
    ) ,
    'allowedSize' => '5',
    'allowedSizeUnits' => 'MB',
    'allowEmpty' => false
);
$config['guide']['file'] = array(
    'allowedMime' => array(
        'image/jpeg',
		'image/jpg',
        'image/gif',
        'image/png'
    ) ,
    'allowedExt' => array(
        'jpg',
        'jpeg',
        'gif',
        'png'
    ) ,
    'allowedSize' => '5',
    'allowedSizeUnits' => 'MB',
	'allowEmpty' => true
);
$config['video']['file'] = array(
    'allowedMime' => array(
        'video/mpeg',
		'video/quicktime',
        'video/flv',
        'video/x-ms-wmv'
    ) ,
    'allowedExt' => array(
        'mpeg',
        'wmv',
        'mov',
        'flv'
    ) ,
    'allowedSize' => '50',
    'allowedSizeUnits' => 'MB',
	'allowEmpty' => false
);
// CDN...
$config['cdn']['images'] = '';
$config['cdn']['css'] = '';
$config['cdn']['js'] = '';

//$config['cdn']['images'] = 'http://images.localhost';
//$config['cdn']['css'] = 'http://css.localhost/';
//$config['cdn']['js'] = 'http://js.localhost/';

// $_SERVER['HTTP_HOST']
$config['site']['site_url_for_cron'] = 'http://www.agriya.com';
$config['Page']['home_page_id'] = 1;
/*
date_default_timezone_set('Asia/Calcutta');

Configure::write('Config.language', 'spa');
setlocale (LC_TIME, 'es');
*/
/*
 ** to do move to settings table
*/
$config['sitemap']['models'] = array(
	'Sighting' => array(
		'fields' => array(
			'id'
		)
	) ,
    'Guide',
	'Place',
	'Item',
);
$config['site']['exception_array'] = array(
            'pages/view',
            'pages/display',
            'users/register',
            'users/login',
            'item_followers/index',
            'users/logout',
            'users/reset',
            'user_sighting_rating_stats/index',
            'sighting_ratings/index',
            'users/forgot_password',
            'users/openid',
            'users/oauth_callback',
            'users/activation',
            'users/resend_activation',
            'reviews/top_spotter',
            'users/view',
            'users/show_captcha',
            'users/captcha_play',
            'users/oauth_facebook',
            'images/view',
            'devs/robots',
            'devs/sitemap',
            'contacts/add',
            'users/admin_login',
            'users/admin_logout',
            'languages/change_language',
            'contacts/show_captcha',
            'contacts/captcha_play',
			'places/index',
			'guides/index',
			'reviews/index',
			'review_comments/index',
			'sightings/index',
			'users/index',
			'places/view',
			'sighting_rating_types/index',
			'sightings/view',
			'user_followers/index',
			'place_followers/index',
			'review_rating_types/index',
			'guides/view',
			'reviews/view',
			'guides/index',
			'guide_categories/index',
			'guides_sightings/index',
			'guide_followers/index',
			'sighting_rating_types/menu',
			'devs/asset_css',
            'devs/asset_js',
			'sightings/simple_index',
			'users/validate_user',
			'users/sidebar',
			'sightings/lst',
			'users/fs_oauth_callback',
			'businesses/view',
			'businesses/index',
			'business_updates/index',
			'sightings/autocomplete',
			'business_followers/index',
			'users/userview',
			'review_ratings/index',
			'reviews/lst'
		);
?>