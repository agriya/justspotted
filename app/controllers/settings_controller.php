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
class SettingsController extends AppController
{
    public $uses = array(
        'Setting'
    );
    public function admin_index()
    {
        $this->pageTitle = __l('Settings');
        // except image and auto layout parent category.
        $conditions = array();
        $conditions['SettingCategory.parent_id'] = 0;
        $conditions['NOT']['SettingCategory.id'] = array(
            7,
            8
        );
        $setting_categories = $this->Setting->SettingCategory->find('all', array(
            'conditions' => $conditions,
            'recursive' => -1
        ));
        $this->set('setting_categories', $setting_categories);
        $this->set('pageTitle', $this->pageTitle);
    }
    public function admin_edit($category_id = 1)
    {
		$this->pageTitle = __l('Settings');
        $this->disableCache();
        if (!empty($this->request->data)) {
            // Save settings
            if (!empty($this->request->data['Setting']['delete_thumb_images'])) {
                $imageSettings = $this->Setting->find('all', array(
                    'conditions' => array(
                        'Setting.setting_category_id' => $this->request->data['Setting']['setting_category_id'],
                        'SettingCategory.name' => 'Images'
                    ) ,
                    'fields' => array(
                        'Setting.id',
                        'Setting.name',
                        'Setting.value'
                    ) ,
                    'recursive' => 0
                ));
                foreach($imageSettings as $imageSetting) {
                    if ($this->request->data['Setting'][$imageSetting['Setting']['id']]['name'] != trim($imageSetting['Setting']['value'])) {
                        $thumb_size = explode('.', $imageSetting['Setting']['name']);
                        $dir = WWW_ROOT . 'img' . DS . $thumb_size[1];
                        $this->_traverse_directory($dir, 0);
                    }
                }
                unset($this->request->data['Setting']['delete_thumb_images']);
            }
            $category_id = $this->request->data['Setting']['setting_category_id'];
            unset($this->request->data['Setting']['setting_category_id']);
            if (!empty($this->request->data['Setting']['not_allow_beyond_original']) || !empty($this->request->data['Setting']['allow_handle_aspect'])) {
                $settings = $this->Setting->find('all', array(
                    'conditions' => array(
                        'Setting.setting_category_id = ' => $category_id
                    ) ,
                    'recursive' => 0
                ));
                foreach($settings as $setting) {
                    $field_name = explode('.', $setting['Setting']['name']);
                    if (!empty($field_name[2]) && ($field_name[2] == 'is_not_allow_resize_beyond_original_size' || $field_name[2] == 'is_handle_aspect')) {
                        if ($field_name[2] == 'is_not_allow_resize_beyond_original_size') {
                            $setting_data['Setting']['id'] = $setting['Setting']['id'];
                            $setting_data['Setting']['value'] = in_array($setting['Setting']['id'], $this->request->data['Setting']['not_allow_beyond_original']) ? 1 : 0;
                            $this->Setting->save($setting_data['Setting']);
                        } else if ($field_name[2] == 'is_handle_aspect') {
                            $setting_data['Setting']['id'] = $setting['Setting']['id'];
                            $setting_data['Setting']['value'] = in_array($setting['Setting']['id'], $this->request->data['Setting']['allow_handle_aspect']) ? 1 : 0;
                            $this->Setting->save($setting_data['Setting']);
                        }
                    }
                }
                unset($this->request->data['Setting']['not_allow_beyond_original']);
                unset($this->request->data['Setting']['allow_handle_aspect']);
            }
            foreach($this->request->data['Setting'] as $id => $value) {
                $settings['Setting']['id'] = $id;
                $settings['Setting']['value'] = $value['name'];
                $this->Setting->save($settings['Setting']);
            }
            if (isset($this->data['Sighting'])) {
                $this->loadModel('SightingRatingType');
                foreach($this->data['Sighting'] as $key => $value) {
                    $sightingRatingData['SightingRatingType']['id'] = $key;
                    $sightingRatingData['SightingRatingType']['tip_points'] = $value['point'];
                    $this->SightingRatingType->save($sightingRatingData);
                }
            }
			if (isset($this->data['Review'])) {
                $this->loadModel('ReviewRatingType');
                foreach($this->data['Review'] as $key => $value) {
                    $reviewRatingData['ReviewRatingType']['id'] = $key;
                    $reviewRatingData['ReviewRatingType']['tip_points'] = $value['point'];
                    $this->ReviewRatingType->save($reviewRatingData);
                }
            }
            Cache::delete('setting_key_value_pairs');
            $this->Session->setFlash(__l('Config settings updated') , 'default', null, 'success');
        }
        if ($category_id == 39) {
            $this->loadModel('SightingRatingType');
            $sightingRatingTypes = $this->SightingRatingType->find('all', array(
				'conditions' => array(
                'SightingRatingType.is_active = ' => 1
            ) ,
                'recursive' => -1
            ));
            $this->set('sightingRatingTypes', $sightingRatingTypes);

			$this->loadModel('ReviewRatingType');
            $reviewRatingTypes = $this->ReviewRatingType->find('all', array(
				'conditions' => array(
                'ReviewRatingType.is_active = ' => 1
            ) ,
                'recursive' => -1
            ));
            $this->set('reviewRatingTypes', $reviewRatingTypes);
        }
        $this->request->data['Setting']['setting_category_id'] = $category_id;
        $settings = $this->Setting->find('all', array(
            'conditions' => array(
                'Setting.setting_category_parent_id = ' => $category_id
            ) ,
            'order' => array(
                'Setting.order' => 'asc'
            ) ,
            'recursive' => 0
        ));
        $beyondOriginals = array();
        $aspects = array();
        foreach($settings as $setting) {
            $field_name = explode('.', $setting['Setting']['name']);
            if (!empty($field_name[2])) {
                if ($field_name[2] == 'is_not_allow_resize_beyond_original_size') {
                    $beyondOriginals[$setting['Setting']['id']] = Inflector::humanize(Inflector::underscore($field_name[1]));
                    $this->request->data['Setting']['not_allow_beyond_original'][] = ($setting['Setting']['value']) ? $setting['Setting']['id'] : '';
                } else if ($field_name[2] == 'is_handle_aspect') {
                    $aspects[$setting['Setting']['id']] = Inflector::humanize(Inflector::underscore($field_name[1]));
                    $this->request->data['Setting']['allow_handle_aspect'][] = ($setting['Setting']['value']) ? $setting['Setting']['id'] : '';
                }
            }
        }
        $setting_category = $this->Setting->SettingCategory->find('first', array(
            'conditions' => array(
                'SettingCategory.id' => $category_id,
            ) ,
            'recursive' => -1
        ));
		if($category_id == ConstSettingsSubCategory::Regional){
			$this->loadModel('Timezone');
			$timezones = $this->Timezone->find('all', array(
				'fields' => array(
					'Timezone.name',
					'Timezone.code',
					'Timezone.gmt_offset'
				) ,
				'recursive' => -1
			));
			if (!empty($timezones)) {
				foreach($timezones as $timezone) {
					$timezoneOptions[$timezone['Timezone']['code']] = $timezone['Timezone']['name'];
				}
			}
			$this->set(compact('timezoneOptions', 'timezoneOptions'));
		}
        $fb_login_url = Router::url(array(
            'controller' => 'settings',
            'action' => 'update_facebook'
        ) , true);
        $tw_login_url = Router::url(array(
            'controller' => 'settings',
            'action' => 'update_twitter'
        ) , true);
        $fs_login_url = Router::url(array(
            'controller' => 'settings',
            'action' => 'admin_update_foursquare'
        ) , true);
        $this->set('fb_login_url', $fb_login_url);
        $this->set('tw_login_url', $tw_login_url);
        $this->set('fs_login_url', $fs_login_url);
        $this->set('setting_category', $setting_category);
        $this->set(compact('settings', 'beyondOriginals', 'aspects'));
        if($setting_category){
			$this->pageTitle .= ' - '.$setting_category['SettingCategory']['name'];
		}
        $this->set('pageTitle', $this->pageTitle);
    }
    public function _traverse_directory($dir, $dir_count)
    {
        $handle = opendir($dir);
        while (false !== ($readdir = readdir($handle))) {
            if ($readdir != '.' && $readdir != '..') {
                $path = $dir . '/' . $readdir;
                if (is_dir($path)) {
                    @chmod($path, 0777);
                    ++$dir_count;
                    $this->_traverse_directory($path, $dir_count);
                }
                if (is_file($path)) {
                    @chmod($path, 0777);
                    @unlink($path);
                    //so that page wouldn't hang
                    flush();
                }
            }
        }
        closedir($handle);
        @rmdir($dir);
        return true;
    }
    public function admin_update_foursquare()
    {
        $foursqaure_return_url = Router::url(array(
            'controller' => 'users',
            'action' => 'fs_oauth_callback',
            'admin' => false
        ) , true);
        $client_key = Configure::read('foursquare.consumer_key');
        $client_secret = Configure::read('foursquare.consumer_secret');
        include APP . 'vendors' . DS . 'foursquare' . DS . 'FoursquareAPI.class.php';
        // Load the Foursquare API library
        $foursquare = new FoursquareAPI($client_key, $client_secret);
        $redirect_url = $foursquare->AuthenticationLink($foursqaure_return_url);
        $this->redirect($redirect_url);
        $this->autoRender = false;
    }
    public function admin_update_twitter()
    {
        $this->pageTitle = __l('Update Twitter');
        App::import('Core', 'ComponentCollection');
        $collection = new ComponentCollection();
        App::import('Component', 'OauthConsumer');
        $this->OauthConsumer = new OauthConsumerComponent($collection);
        $twitter_return_url = Router::url(array(
            'controller' => 'users',
            'action' => 'oauth_callback',
            'admin' => false
        ) , true);
        $requestToken = $this->OauthConsumer->getRequestToken('Twitter', 'https://api.twitter.com/oauth/request_token', $twitter_return_url);
        $this->Session->write('requestToken', serialize($requestToken));
        $requestToken1=$this->Session->read('requestToken');
        $this->redirect('http://twitter.com/oauth/authorize?oauth_token=' . $requestToken->key);
        $this->autoRender = false;
    }
    public function admin_update_facebook()
    {
        $this->pageTitle = __l('Update Facebook');
        $fb_return_url = Router::url(array(
            'controller' => 'settings',
            'action' => 'fb_update',
            'admin' => false
        ) , true);
        $this->Session->write('fb_return_url', $fb_return_url);
        App::import('Vendor', 'facebook/facebook');
        $this->facebook = new Facebook(array(
            'appId' => Configure::read('facebook.fb_app_id') ,
            'secret' => Configure::read('facebook.fb_secrect_key') ,
            'cookie' => true
        ));
        $fb_login_url = $this->facebook->getLoginUrl(array(
            'redirect_uri' => Router::url(array(
                'controller' => 'users',
                'action' => 'oauth_facebook',
                'admin' => false
            ) , true) ,
            'scope' => 'email,offline_access,publish_stream'
        ));
        $this->redirect($fb_login_url);
        $this->autoRender = false;
    }
    public function fb_update()
    {
        App::import('Vendor', 'facebook/facebook');
        $this->facebook = new Facebook(array(
            'appId' => Configure::read('facebook.fb_app_id') ,
            'secret' => Configure::read('facebook.fb_secrect_key') ,
            'cookie' => true
        ));
        if ($fb_session = $this->Session->read('fbuser')) {
            $settings = $this->Setting->find('all', array(
                'conditions' => array(
                    'Setting.name' => array(
                        'facebook.fb_access_token',
                        'facebook.fb_user_id'
                    )
                ) ,
                'fields' => array(
                    'Setting.id',
                    'Setting.name'
                ) ,
                'recursive' => -1
            ));
            foreach($settings as $setting) {
                $this->request->data['Setting']['id'] = $setting['Setting']['id'];
                if ($setting['Setting']['name'] == 'facebook.fb_user_id') {
                    $this->request->data['Setting']['value'] = $fb_session['id'];
                } elseif ($setting['Setting']['name'] == 'facebook.fb_access_token') {
                    $this->request->data['Setting']['value'] = $fb_session['access_token'];
                }
                if ($this->Setting->save($this->request->data)) {
                    $this->Session->setFlash(__l('Facebook credentials updated') , 'default', null, 'success');
                } else {
                    $this->Session->setFlash(__l('Facebook credentials could not be updated. Please, try again.') , 'default', null, 'error');
                }
            }
        }
        $this->redirect(array(
            'action' => 'index',
            'admin' => true
        ));
    }
}
?>