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
/**
 * Short description for class.
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       cake
 * @subpackage    cake.app
 */
class AppController extends Controller
{
    public $components = array(
        'RequestHandler',
		'Session',
        'Security',
        'Auth',
        'XAjax',
        'DebugKit.Toolbar',
        'Cookie'
    );
    public $helpers = array(
        'Html',
		'Session',
        'Javascript',
        'AutoLoadPageSpecific',
        'Form',
        'Asset',
        'Auth',
        'Time',
        'Tree',
        'List'
    );
    public $isHome = false;
    public $homePageId;
    var $cookieTerm = '+4 weeks';
    //    var $view = 'Theme';
    var $theme = 'themes';
    public function beforeRender()
    {
        $this->set('meta_for_layout', Configure::read('meta'));
        $this->set('js_vars_for_layout', (isset($this->js_vars)) ? $this->js_vars : '');
        parent::beforeRender();
    }
    public function __construct($request = null)
    {
        parent::__construct($request);
		//Setting cache related code
        $setting_key_value_pairs = Cache::read('setting_key_value_pairs');
        if (empty($setting_key_value_pairs)) {
            App::import('Model', 'Setting');
            $setting_model_obj = new Setting();
            $setting_key_value_pairs = $setting_model_obj->getKeyValuePairs();
            Cache::write('setting_key_value_pairs', $setting_key_value_pairs);
        }
        Configure::write($setting_key_value_pairs);

		$lang_code = Configure::read('site.language');
        if (!empty($_COOKIE['CakeCookie']['user_language'])) {
            $lang_code = $_COOKIE['CakeCookie']['user_language'];
        }
		Configure::write('lang_code', $lang_code);
        $translations = Cache::read($lang_code . '_translations');
        if (empty($translations) && $translations === false) {
			App::import('Model', 'Translation');
			$this->Translation = new Translation();
			$translations = $this->Translation->find('all', array(
				'fields' => array(
					'Translation.key',
					'Translation.lang_text',
					'Language.iso2'
				) ,
				'recursive' => 0
			));
			Cache::write($lang_code . '_translations', $translations);
		}
		if(!empty($translations)) {
			foreach($translations as $translation) {
				$GLOBALS['_langs'][$translation['Language']['iso2']][$translation['Translation']['key']] = $translation['Translation']['lang_text'];
			}
		}
	}
    public function beforeFilter()
    {
        $cur_page = $this->request->params['controller'] . '/' . $this->request->params['action'];
        // check ip is banned or not. redirect it to 403 if ip is banned
        $this->loadModel('BannedIp');
		$bannedIp = $this->BannedIp->checkIsIpBanned($this->RequestHandler->getClientIP());
		if (empty($bannedIp)) {
			$bannedIp = $this->BannedIp->checkRefererBlocked(env('HTTP_REFERER'));
		}
        if (!empty($bannedIp)) {
			if (!empty($bannedIp['BannedIp']['redirect'])) {
	            header('location: ' . $bannedIp['BannedIp']['redirect']);
			}
			else {
	            throw new ForbiddenException(__l('Invalid request'));
			}
        }
		// check site is under maintenance mode or not. admin can set in settings page and then we will display maintenance message, but admin side will work.
		$maintenance_exception_array = array(
			'devs/asset_js',
			'devs/asset_css',
			'devs/robots',
			'devs/sitemap',
		);
		if (Configure::read('site.maintenance_mode') && $this->Auth->user('user_type_id') != ConstUserTypes::Admin && empty($this->request->params['prefix']) && !in_array($cur_page, $maintenance_exception_array)) {
            throw new MaintenanceModeException(__l('Maintenance Mode'));
        }
		if (!$this->Auth->user() && Configure::read('facebook.is_enabled_facebook_connect')) {
            App::import('Vendor', 'facebook/facebook');
            // Prevent the 'Undefined index: facebook_config' notice from being thrown.
            $GLOBALS['facebook_config']['debug'] = NULL;
            // Create a Facebook client API object.
            $this->facebook = new Facebook(array(
                'appId' => Configure::read('facebook.fb_app_id') ,
                'secret' => Configure::read('facebook.fb_secrect_key') ,
                'cookie' => true
            ));
        }
        if (strpos($this->here, '/view/') !== false) {
            trigger_error('*** dev1framework: Do not view page through /view/; use singular/slug', E_USER_ERROR);
        }
        // check the method is exist or not in the controller
        $methods = array_flip($this->methods);
        if (!isset($methods[strtolower($this->request->params['action']) ])) {
            throw new MissingActionException(array(
				'controller' => Inflector::camelize($this->request->params['controller']) . "Controller",
				'action' => $this->request->params['action']
			));
        }
		$timezone_code = Configure::read('site.timezone_offset');
        if (!empty($timezone_code)) {
            date_default_timezone_set($timezone_code);
        }
        // Home page ID
		$geo_city = '';
		$geo_lat = '';
		$geo_lan = '';
		if (!empty($_COOKIE['_geo'])) {
			$_geo = explode('|', $_COOKIE['_geo']);
			$geo_city = $_geo[2];
			$geo_lat = $_geo[3];
			$geo_lan = $_geo[4];
		}
		// Item and page value set to js to fetch the marker
		$param = '';
		$params = '';
		if($this->request->params['controller'] == 'sightings' && $this->request->params['action'] == 'index') {
            $page = (!empty($this->request->params['named']['page']) ? $this->request->params['named']['page'] : '');
            $item = (!empty($this->request->params['named']['item']) ? $this->request->params['named']['item'] : '');
            $location = (!empty($this->request->params['named']['location']) ? $this->request->params['named']['location'] : '');
			if(!empty($page) && !empty($item) && !empty($location)) {
				$param = '/page:'.$page.'/item:'.$item . '/location:'.$location;
				$params = '/item:'.$item . '/location:'.$location;
			} elseif(!empty($page) && !empty($location)) {
				$param = '/page:'.$page . '/location:'.$location;
				$params = '/location:'.$location;
			} elseif(!empty($item) && !empty($location)) {
				$params = $param = '/item:'.$item . '/location:'.$location;
			} elseif(!empty($page) && !empty($item)) {
				$param = '/page:'.$page.'/item:'.$item;
				$params = '/item:'.$item;
			} elseif(!empty($page)) {
				$param = '/page:'.$page;
			} elseif(!empty($item)) {
				$params = $param = '/item:'.$item;
			} elseif(!empty($location)) {
				$params = $param = '/location:'.$location;
			}
		}
		$this->set('geo_city', $geo_city);
		$this->set('geo_lat', $geo_lat);
		$this->set('geo_lan', $geo_lan);
		// <-- For iPhone App code
        if (!empty($_GET['key'])) {
            $this->_handleIPhoneApp();
        }
        // For iPhone App code -->
		$this->homePageId = intval(Configure::read('Page.home_page_id'));
        $this->_checkAuth();
        $this->js_vars = array();
		$this->js_vars['cfg']['path_relative'] = Router::url('/');
        $this->js_vars['cfg']['path_absolute'] = Router::url('/', true);
		$this->js_vars['cfg']['geo_city'] = $geo_city;
		$this->js_vars['cfg']['param'] = $param;
		$this->js_vars['cfg']['params'] = $params;
		$this->js_vars['cfg']['gmap_app_id'] = Configure::read('google.gmap_app_id');
        parent::beforeFilter();
    }
    public function _checkAuth()
    {
        $this->Auth->fields = array(
            'username' => Configure::read('user.using_to_login') ,
            'password' => 'password'
        );
        $exception_array =  Configure::read('site.exception_array');
        $cur_page = $this->request->params['controller'] . '/' . $this->request->params['action'];
        if (!in_array($cur_page, $exception_array) && $this->request->params['action'] != 'flashupload') {
            if (!$this->Auth->user('id')) {
                // check cookie is present and it will auto login to account when session expires
                $cookie_hash = $this->Cookie->read('User.cookie_hash');
                if (!empty($cookie_hash)) {
                    if (is_integer($this->cookieTerm) || is_numeric($this->cookieTerm)) {
                        $expires = time() +intval($this->cookieTerm);
                    } else {
                        $expires = strtotime($this->cookieTerm, time());
                    }
                    App::import('Model', 'User');
                    $user_model_obj = new User();
                    $this->request->data = $user_model_obj->find('first', array(
                        'conditions' => array(
                            'User.cookie_hash =' => md5($cookie_hash) ,
                            'User.cookie_time_modified <= ' => date('Y-m-d h:i:s', $expires) ,
                        ) ,
                        'fields' => array(
                            'User.' . Configure::read('user.using_to_login') ,
                            'User.password'
                        ) ,
                        'recursive' => -1
                    ));
                    // auto login if cookie is present
                    if ($this->Auth->login($this->request->data)) {
                        $user_model_obj->UserLogin->insertUserLogin($this->Auth->user('id'));
                        $this->redirect(Router::url('/', true) . $this->request->url);
                    }
                }
                $this->Session->setFlash(__l('Authorisation Required'));
                $is_admin = false;
                if (isset($this->request->params['prefix']) and $this->request->params['prefix'] == 'admin') {
                    $is_admin = true;
                }
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'login',
                    'admin' => $is_admin,
                    '?f='.$this->request->url
                ));
            }
            if (isset($this->request->params['prefix']) and $this->request->params['prefix'] == 'admin' and $this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
                $this->redirect('/');
            }
        } else {
            $this->Auth->allow('*');
        }
        $this->Auth->autoRedirect = false;
        $this->Auth->userScope = array(
            'User.is_active' => 1,
            'User.is_email_confirmed' => 1
        );
        if (isset($this->Auth)) {
            $this->Auth->loginError = __l(sprintf('Sorry, login failed.  Either your %s or password are incorrect or admin deactivated your account.', Configure::read('user.using_to_login')));
        }
        $this->layout = 'default';
        if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin && (isset($this->request->params['prefix']) and $this->request->params['prefix'] == 'admin')) {
            $this->layout = 'admin';
        }
        if (Configure::read('site.maintenance_mode') && !$this->Auth->user('user_type_id')) {
			$this->layout = 'maintenance';
		}
    }
    public function autocomplete($param_encode = null, $param_hash = null)
    {
        $conditions = false;
		$params = unserialize(gzinflate(base64_url_decode($param_encode)));
		if(isset($params['accontrollers'])){
			$model_list = explode(':', $params['accontrollers']);
			foreach($model_list as $key => $value){
				if($value=='Place' || $value=='Item'){
					 $conditions[$value]['admin_suspend'] = '0';
				}
				if($value=='User'){
					 $conditions[$value]['is_active'] = '1';
				}
			}
		}
        $modelClass = Inflector::singularize($this->name);
        if (isset($this->{$modelClass}->_schema['is_approved'])) {
            $conditions['is_approved'] = '1';
        }
		if (isset($this->{$modelClass}->_schema['is_active'])) {
            $conditions['is_active'] = '1';
        }
		if($modelClass=='Place' || $modelClass=='Item'){
			 $conditions['admin_suspend'] = '0';
		}
        $this->XAjax->autocomplete($param_encode, $param_hash, $conditions);
    }
    public function _redirectGET2Named($whitelist_param_names = null)
	{
		$query_strings = array();
		if (is_array($whitelist_param_names)) {
			foreach($whitelist_param_names as $param_name) {
				if (isset($this->request->query[$param_name])) { // querystring
					$query_strings[$param_name] = $this->request->query[$param_name];
				}
			}
		} else {
			$query_strings = $this->request->query;
			unset($query_strings['url']); // Can't use ?url=foo

		}
		if (!empty($query_strings)) {
			$query_strings = array_merge($this->request->params['named'], $query_strings);
			$this->redirect($query_strings, null, true);
		}
	}
	 function _redirectPOST2Named($paramNames = array())
    {
        //redirect the URL with query string to namedArg like URL structure...
        $query_strings = array();
        foreach($paramNames as $paramName) {
            if (!empty($this->data[Inflector::camelize(Inflector::singularize($this->params->controller))][$paramName])) { //via GET query string
				 $query_strings[$paramName] = $this->data[Inflector::camelize(Inflector::singularize($this->params->controller))][$paramName];
            }
        }
        if (!empty($query_strings)) {
            // preserve other named params
            $query_strings = array_merge($this->request->params['named'], $query_strings);
            $this->redirect($query_strings, null, true);
        }
    }
    public function show_captcha()
    {
        include_once VENDORS . DS . 'securimage' . DS . 'securimage.php';
        $img = new securimage();
        $img->show(); // alternate use:  $img->show('/path/to/background.jpg');
        $this->autoRender = false;
    }
    public function getImageUrl($model, $attachment, $options)
    {
		if (empty($attachment['id'])) {
            $attachment['id'] = constant(sprintf('%s::%s', 'ConstAttachment', $model));
        }
        $default_options = array(
            'dimension' => 'big_thumb',
            'class' => '',
            'alt' => 'alt',
            'title' => 'title',
            'type' => 'jpg'
        );
        $options = array_merge($default_options, $options);
        $image_hash = $options['dimension'] . '/' . $model . '/' . $attachment['id'] . '.' . md5(Configure::read('Security.salt') . $model . $attachment['id'] . $options['type'] . $options['dimension'] . Configure::read('site.name')) . '.' . $options['type'];
        return 'img/' . $image_hash;
    }
    public function captcha_play($session_var = null)
    {
        include_once VENDORS . DS . 'securimage' . DS . 'securimage.php';
        $img = new Securimage();
        $img->session_var = $session_var;
        $this->disableCache();
        $this->RequestHandler->respondAs('mp3', array(
            'attachment' => 'captcha.mp3'
        ));
        $img->audio_format = 'mp3';
        echo $img->getAudibleCode('mp3');
    }
    public function admin_update()
    {
        if (!empty($this->request->data[$this->modelClass])) {
			if ($this->{$this->modelClass}->Behaviors->attached('SuspiciousWordsDetector')) {
				$this->{$this->modelClass}->Behaviors->detach('SuspiciousWordsDetector');
			}
            $r = $this->request->data[$this->modelClass]['r'];
            $actionid = $this->request->data[$this->modelClass]['more_action_id'];
            unset($this->request->data[$this->modelClass]['r']);
            unset($this->request->data[$this->modelClass]['more_action_id']);
            $ids = array();
             foreach($this->request->data[$this->modelClass] as $id => $is_checked) {
                if ($is_checked['id']) {
                   $ids[] = $id;
                }
            }
            if ($actionid && !empty($ids)) {
                foreach($ids as $id){
                    $data=array();
                    $data[$this->modelClass]['id']=$id;
                    switch ($actionid) {
                    case ConstMoreAction::Inactive:
						$field_name = 'is_active';
						if (isset($this->{$this->modelClass}->_schema['is_approved'])) {
							$field_name = 'is_approved';
						}
						$data[$this->modelClass][$field_name]=0;
						$this->{$this->modelClass}->save($data);
                        $this->Session->setFlash(__l('Checked ' . $this->modelClass . ' has been inactivated') , 'default', null, 'success');
                        break;
                    case ConstMoreAction::Active:
						$field_name = 'is_active';
						if (isset($this->{$this->modelClass}->_schema['is_approved'])) {
							$field_name = 'is_approved';
						}
						$data[$this->modelClass][$field_name]=0;
						$this->{$this->modelClass}->save($data);
                        $this->Session->setFlash(__l('Checked ' . $this->modelClass . ' has been activated') , 'default', null, 'success');
                        break;
                    case ConstMoreAction::Delete:
                        $this->{$this->modelClass}->delete($id);
                        $this->Session->setFlash(__l('Checked ' . $this->modelClass . ' has been deleted') , 'default', null, 'success');
                        break;
                    case ConstMoreAction::Featured:
                        $data[$this->modelClass]['is_featured']=1;
                        $this->{$this->modelClass}->save($data);
                        $this->Session->setFlash(__l('Checked ' . $this->modelClass . ' has been featured') , 'default', null, 'success');
                        break;
                    case ConstMoreAction::Notfeatured:
                        $data[$this->modelClass]['is_featured']=0;
                        $this->{$this->modelClass}->save($data);
                        $this->Session->setFlash(__l('Checked ' . $this->modelClass . ' has been non featured') , 'default', null, 'success');
                        break;
                    case ConstMoreAction::Suspend:
                        $data[$this->modelClass]['admin_suspend']=1;
                        $this->{$this->modelClass}->save($data);
                        $this->Session->setFlash(__l('Checked ' . $this->modelClass . ' has been suspended') , 'default', null, 'success');
                        break;
					case ConstMoreAction::Unsuspend:
                        $data[$this->modelClass]['admin_suspend']=0;
                        $this->{$this->modelClass}->save($data);
                        $this->Session->setFlash(__l('Checked ' . $this->modelClass . ' has been unsuspended') , 'default', null, 'success');
                        break;
					case ConstMoreAction::Flagged:
                        $data[$this->modelClass]['is_system_flagged']=1;
                        $this->{$this->modelClass}->save($data);
                        $this->Session->setFlash(__l('Checked ' . $this->modelClass . ' has been flagged') , 'default', null, 'success');
                        break;
                    case ConstMoreAction::Unflagged:
                        $data[$this->modelClass]['is_system_flagged']=0;
                        $this->{$this->modelClass}->save($data);
                        $this->Session->setFlash(__l('Checked ' . $this->modelClass . ' has been unflagged') , 'default', null, 'success');
                        break;
					case ConstMoreAction::Approved:
                        $data[$this->modelClass]['is_approved']=1;
                        $this->{$this->modelClass}->save($data);
                        $this->Session->setFlash(__l('Checked ' . $this->modelClass . ' has been Approved') , 'default', null, 'success');
                        break;
					case ConstMoreAction::Disapproved:
                        $data[$this->modelClass]['is_approved']=2;
                        $this->{$this->modelClass}->save($data);
                        $this->Session->setFlash(__l('Checked ' . $this->modelClass . ' has been Rejected') , 'default', null, 'success');
                        break;
					case ConstMoreAction::Published:
                        $data[$this->modelClass]['is_published']=1;
                        $this->{$this->modelClass}->save($data);
                        $this->Session->setFlash(__l('Checked ' . $this->modelClass . ' has been Published') , 'default', null, 'success');
                        break;
					case ConstMoreAction::Unpublished:
                        $data[$this->modelClass]['is_published']=0;
                        $this->{$this->modelClass}->save($data);
                        $this->Session->setFlash(__l('Checked ' . $this->modelClass . ' has been Unpublished') , 'default', null, 'success');
                        break;
                    }
                }
            }
        }
        $this->redirect(Router::url('/', true) . $r);
    }
	public function admin_update_status($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
		$data=array();
		$data[$this->modelClass]['id']=$id;
        if (!empty($this->request->params['named']['status']) && ($this->request->params['named']['status'] == 'approve')) {
			$field_name = 'is_approved';
			if (isset($this->{$this->modelClass}->_schema['is_approved'])) {
				$field_name = 'is_approved';
			}
			$data[$this->modelClass][$field_name]=1;
			$this->{$this->modelClass}->save($data);
            $this->Session->setFlash(__l($this->modelClass .' has been approved') , 'default', null, 'success');
        } elseif (!empty($this->request->params['named']['status']) && ($this->request->params['named']['status'] == 'disapprove')) {
			$field_name = 'is_approved';
			if (isset($this->{$this->modelClass}->_schema['is_approved'])) {
				$field_name = 'is_approved';
			}
			$data[$this->modelClass][$field_name]=2;
			$this->{$this->modelClass}->save($data);
            $this->Session->setFlash(__l($this->modelClass . ' has been disapproved') , 'default', null, 'success');
        }elseif (!empty($this->request->params['named']['status']) && ($this->request->params['named']['status'] == 'active')) {
			$field_name = 'is_active';
			if (isset($this->{$this->modelClass}->_schema['is_approved'])) {
				$field_name = 'is_approved';
			}
			$data[$this->modelClass][$field_name]=1;
			$this->{$this->modelClass}->save($data);
            $this->Session->setFlash(__l($this->modelClass .' has been activated') , 'default', null, 'success');
        } elseif (!empty($this->request->params['named']['status']) && ($this->request->params['named']['status'] == 'inactive')) {
			$field_name = 'is_active';
			if (isset($this->{$this->modelClass}->_schema['is_approved'])) {
				$field_name = 'is_approved';
			}
			$data[$this->modelClass][$field_name]=0;
			$this->{$this->modelClass}->save($data);
            $this->Session->setFlash(__l($this->modelClass . ' has been inactivated') , 'default', null, 'success');
        } elseif (!empty($this->request->params['named']['status']) && ($this->request->params['named']['status'] == 'flag')) {
			$data[$this->modelClass]['is_system_flagged']=1;
			$this->{$this->modelClass}->save($data);
            $this->Session->setFlash(__l($this->modelClass .' has been flagged') , 'default', null, 'success');
        } elseif (!empty($this->request->params['named']['status']) && ($this->request->params['named']['status'] == 'unflag')) {
			$data[$this->modelClass]['is_system_flagged']=0;
			$this->{$this->modelClass}->save($data);
            $this->Session->setFlash(__l($this->modelClass . ' has been unflagged') , 'default', null, 'success');
        } elseif (!empty($this->request->params['named']['status']) && ($this->request->params['named']['status'] == 'suspend')) {
			$data[$this->modelClass]['admin_suspend']=1;
			$this->{$this->modelClass}->save($data);
            $this->Session->setFlash(__l($this->modelClass .' has been suspended') , 'default', null, 'success');
        } elseif (!empty($this->request->params['named']['status']) && ($this->request->params['named']['status'] == 'unsuspend')) {
			$data[$this->modelClass]['admin_suspend']=0;
			$this->{$this->modelClass}->save($data);
            $this->Session->setFlash(__l($this->modelClass .' has been unsuspended') , 'default', null, 'success');
        }
		if(empty($_GET['f']) && $this->Auth->user('user_type_id') == ConstUserTypes::Admin){
			$_GET['f'] = 'admin/'.$this->request->params['controller'].'/index';
			if(!empty($this->request->params['named']['page'])){
				$_GET['f'] = 'admin/'.$this->request->params['controller'].'/index/page:'.$this->request->params['named']['page'];
			}
		}
		$this->redirect(Router::url('/', true) . $_GET['f']);
    }
	public function isAutoSuspendEnabled($model){
		if(Configure::read('suspicious_detector.is_enabled') && Configure::read('suspicious_detector.auto_suspend_' . $model . '_on_system_flag')) {
			return 1;
		}else{
			return 0;
		}
	}
	// <-- For iPhone App code
    function _handleIPhoneApp()
    {
        $this->Security->enabled = false;
        $this->loadModel('User');
        if (!empty($_POST['data']) && in_array($this->request->params['action'], array(
            'validate_user',
            'add',
            'buy'
        ))) {
            foreach($_POST['data'] as $controller => $values) {
                $this->request->data[Inflector::camelize(Inflector::singularize($controller)) ] = $values;
            }
        }
		/*if (stripos($_SERVER['HTTP_USER_AGENT'], 'iPhone') === false && stripos($_SERVER['HTTP_USER_AGENT'], 'Android') === false) {
			$this->set('iphone_response', array(
				'status' => 1,
				'message' => __l('Unknown Application')
			));
        } elseif (Configure::read('site.iphone_app_key') != $_GET['key']) {
			$this->set('iphone_response', array(
				'status' => 2,
				'message' => __l('Invalid App key')
			));
        }
        else{ */
			if (!empty($_GET['username']) && $this->request->params['action'] != 'validate_user') {
				$this->request->data['User'][Configure::read('user.using_to_login') ] = trim($_GET['username']);
				$user = $this->User->find('first', array(
					'conditions' => array(
						'User.mobile_app_hash' => $_GET['passwd']
					) ,
					'fields' => array(
						'User.password'
					) ,
					'recursive' => -1
				));
				if (empty($user)) {
					$this->set('iphone_response', array(
						'status' => 1,
						'message' => sprintf(__l('Sorry, login failed.  Your %s or password are incorrect') , Configure::read('user.using_to_login'))
					));
				} else {
					$this->request->data['User']['password'] = $user['User']['password'];
					if (!$this->Auth->login($this->request->data)) {
						$this->set('iphone_response', array(
							'status' => 1,
							'message' => sprintf(__l('Sorry, login failed.  Your %s or password are incorrect') , Configure::read('user.using_to_login'))
						));
					}
					if ($this->Auth->user('id') && !empty($_GET['latitude']) && !empty($_GET['longtitude'])) {
						$this->update_iphone_user($_GET['latitude'], $_GET['longtitude'], $this->Auth->user('id'));
					}
				}
			}
		//}
        if ($this->request->params['action'] == 'buy') {
            $this->request->data['Deal']['user_id'] = $this->Auth->user('id');
            $this->request->data['Deal']['is_gift'] = 0;
        } elseif ($this->request->params['controller'] == 'user_payment_profiles' && $this->request->params['action'] == 'add') {
            $this->request->data['UserPaymentProfile']['user_id'] = $this->Auth->user('id');
        }
    }
     function _unum()
    {
        $acceptedChars = '0123456789';
        $max = strlen($acceptedChars) -1;
        $unique_code = '';
        for ($i = 0; $i < 8; $i++) {
            $unique_code.= $acceptedChars{mt_rand(0, $max) };
        }
        return $unique_code;
    }
}
?>
