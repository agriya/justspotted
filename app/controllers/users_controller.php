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
class UsersController extends AppController
{
    public $name = 'Users';
    public $components = array(
        'Email',
        'OauthConsumer'
    );
    public $uses = array(
        'User',
        'EmailTemplate'
    );
    public $helpers = array(
        'Csv'
    );
    public $permanentCacheAction = array(
        'view' => array(
            'is_public_url' => true,
            'is_user_specific_url' => true,
            'is_view_count_update' => true
        )
    );
    public function beforeFilter()
    {
        $this->Security->disabledFields = array(
            'User.send_to_user_id',
			'User.send_to',
            'UserProfile.country_iso_code',
            'City.name',
            'State.name',
            'User.q',
			'ReviewCategory.id',
            'Place.id',
            'Place.name',
            'Item.id',
            'Item.name'
        );
        parent::beforeFilter();
    }
    public function index()
    {
        $this->_redirectPOST2Named(array(
            'q'
        ));
        $this->pageTitle = __l('People');
        $condition['User.is_active'] = 1;
        $condition['Not']['User.user_type_id'] = ConstUserTypes::Admin;
        if (!empty($this->request->params['named']['type'])) {
            $limit = 10;
        } else {
            $limit = (!empty($this->paginate['limit'])) ? $this->paginate['limit'] : 20;
        }
        if (!empty($this->request->params['named']['q'])) {
            $this->request->data['User']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'popular') {
            $order = array(
                'User.user_follower_count' => 'DESC'
            );
        } else {
            $order = array(
                'User.id' => 'DESC'
            );
        }
        if (!empty($this->request->params['named']['view']) && $this->request->params['named']['view'] == 'guide_top_contributor' &&  !empty($this->request->params['named']['guide_id'])) {
            $this->loadModel('GuidesSighting');
            $guidesSightings = $this->GuidesSighting->find('all', array(
                'fields' => array(
                    'GuidesSighting.id',
                    'GuidesSighting.review_id'
                ) ,
                'conditions' => array(
                    'GuidesSighting.guide_id' => $this->request->params['named']['guide_id']
                ) ,
                'contain' => array(
                    'Review' => array(
                        'fields' => array(
                            'Review.user_id'
                        )
                    )
                ) ,
                'recursive' => 0
            ));
            if (!empty($guidesSightings)) {
                foreach($guidesSightings as $guidesSighting) {
                    $users[] = $guidesSighting['Review']['user_id'];
                    if (empty($item_count[$guidesSighting['Review']['user_id']])) {
                        $item_count[$guidesSighting['Review']['user_id']] = 0;
                    }
                    $item_count[$guidesSighting['Review']['user_id']]++;
                }
                $users = array_count_values($users);
				arsort($users);
				$users = array_keys($users);
                $topusers = array_chunk($users, 5);
                $condition['User.id'] = $topusers[0];
                $this->set('item_counts', $item_count);
            } else {
				$condition['User.id'] = 0;
			}
        }
		// top spotter of this week in users listing page
		if (!empty($this->request->params['named']['view']) && $this->request->params['named']['view'] == 'top_spotters') {
			    $reviewConditions['TO_DAYS(NOW()) - TO_DAYS(Review.created) <= '] = 7;
				$reviews = $this->User->Review->find('list', array(
						'fields' => array(
							'Review.id',
							'Review.user_id',
						),
						'conditions' => $reviewConditions,
						'group' => array('Review.user_id'),
						'limit' => 10
					)
				);
				if(!empty($reviews)) {
					$condition['User.id'] = $reviews;
				} else {
					$condition['User.id'] = 0;
				}
		}
        if (!empty($this->request->params['named']['following'])) {
            $user = $this->User->find('first', array(
                'conditions' => array(
                    'User.username = ' => $this->request->params['named']['following']
                ) ,
                'fields' => array(
                    'User.id',
                    'User.username',
                ) ,
                'recursive' => -1
            ));
            if (empty($user)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $userFollowings = $this->User->UserFollower->find('list', array(
                'conditions' => array(
                    'UserFollower.follower_user_id = ' => $user['User']['id']
                ) ,
                'fields' => array(
                    'UserFollower.user_id',
                ) ,
                'recursive' => -1
            ));
            if (!empty($userFollowings)) {
                $condition['User.id'] = $userFollowings;
            } else {
                $condition['User.id = '] = 0;
            }
            $this->pageTitle.= ' - ' . __l('Followed by') . ' ' . $this->request->params['named']['following'];
        }
        if (!empty($this->request->params['named']['follower'])) {
            $user = $this->User->find('first', array(
                'conditions' => array(
                    'User.username = ' => $this->request->params['named']['follower']
                ) ,
                'fields' => array(
                    'User.id',
                    'User.username',
                ) ,
                'recursive' => -1
            ));
            if (empty($user)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $userFollowers = $this->User->UserFollower->find('list', array(
                'conditions' => array(
                    'UserFollower.user_id = ' => $user['User']['id']
                ) ,
                'fields' => array(
                    'UserFollower.follower_user_id',
                ) ,
                'recursive' => -1
            ));
            if (!empty($userFollowers)) {
                $condition['User.id'] = $userFollowers;
            } else {
                $condition['User.id = '] = 0;
            }
            $this->pageTitle.= ' - ' . $this->request->params['named']['follower'] . '\'s ' . __l('Followers');
        }
		$this->paginate = array(
            'conditions' => array(
                $condition,
            ) ,
            'contain' => array(
                'UserAvatar',
                'UserProfile' => array(
                    'City' => array(
                        'fields' => array(
                            'City.name'
                        )
                    ) ,
                    'State' => array(
                        'fields' => array(
                            'State.name'
                        )
                    ) ,
                    'Country' => array(
                        'fields' => array(
                            'Country.name'
                        )
                    )
                ) ,
                'UserFollower' => array(
                    'conditions' => array(
                        'UserFollower.follower_user_id = ' => $this->Auth->user('id')
                    ) ,
                    'limit' => 1,
                ) ,
            ) ,
            'order' => $order,
            'limit' => $limit,
            'recursive' => 2,
        );
        if (!empty($this->request->data['User']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->data['User']['q']
            ));
        }
		$users = array();
		if(!empty($this->request->params['named']['view']) && $this->request->params['named']['view'] == 'guide_top_contributor' && !empty($this->request->params['named']['guide_id'])) {
			$tempUsers = $this->paginate();
			if(!empty($tempUsers)) {
				foreach($tempUsers as $tempUser) {
					$key = array_search($tempUser['User']['id'], $topusers[0]);
					$users[$key] = $tempUser;
				}
				ksort($users);
			}
			$this->set('users', $users);
		} else {
	        $this->set('users', $this->paginate());
		}
		$user_followers = $this->User->UserFollower->find('list', array(
            'conditions' => array(
                'UserFollower.follower_user_id' => $this->Auth->user('id')
            ) ,
            'fields' => array(
                'UserFollower.user_id',
            ) ,
            'recursive' => -1
        ));
        $this->set('user_followers', $user_followers);
		if(!empty($this->request->params['named']['view']) && $this->request->params['named']['view'] == 'top_spotters') {
			$this->pageTitle = __l('This Week\'s Top Spotters');
		}
		if(!empty($this->request->params['named']['view']) && $this->request->params['named']['view'] == 'simple') {
			$this->pageTitle = __l('Popular Users');
		}
        if (!empty($this->request->params['named']['view']) && $this->request->params['named']['view'] == 'guide_top_contributor' && !empty($this->request->params['named']['guide_id'])) {
            $this->render('top_contributor');
        }
        if (!empty($this->request->params['named']['view']) && ($this->request->params['named']['view'] == 'simple' || $this->request->params['named']['view'] == 'top_spotters')) {
            $this->render('simple_view');
        }
    }
    public function view($username = null)
    {
        $this->pageTitle = __l('User');
        if (is_null($username)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.username = ' => $username,
                'User.is_active = ' => 1
            ) ,
            'contain' => array(
                'UserProfile' => array(
                    'fields' => array(
                        'UserProfile.first_name',
                        'UserProfile.last_name',
                        'UserProfile.middle_name',
                        'UserProfile.about_me',
                        'UserProfile.dob',
                        'UserProfile.address',
                        'UserProfile.zip_code',
                    ) ,
                    'Gender' => array(
                        'fields' => array(
                            'Gender.name'
                        )
                    ) ,
                    'City' => array(
                        'fields' => array(
                            'City.name'
                        )
                    ) ,
                    'State' => array(
                        'fields' => array(
                            'State.name'
                        )
                    ) ,
                    'Country' => array(
                        'fields' => array(
                            'Country.name'
                        )
                    )
                ) ,
                'UserAvatar' => array(
                    'fields' => array(
                        'UserAvatar.id',
                        'UserAvatar.dir',
                        'UserAvatar.filename',
                        'UserAvatar.width',
                        'UserAvatar.height'
                    )
                ) ,
                'UserFollower' => array(
                    'conditions' => array(
                        'UserFollower.follower_user_id = ' => $this->Auth->user('id')
                    ) ,
                    'limit' => 1,
                ) ,
                'UserSightingRatingStat',
                'UserReviewRatingStat'
            ) ,
            'fields' => array(
                'User.id',
                'User.username',
                'User.email',
                'User.place_follower_count',
                'User.item_follower_count',
                'User.guide_count',
                'User.guide_follower_count',
                'User.user_following_count',
                'User.review_comment_count',
                'User.user_type_id',
                'User.tip_points'
            ) ,
            'recursive' => 2
        ));
        if (empty($user)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->User->UserView->create();
        $this->request->data['UserView']['user_id'] = $user['User']['id'];
        $this->request->data['UserView']['viewing_user_id'] = $this->Auth->user('id');
        $this->request->data['UserView']['ip_id'] = $this->User->UserView->toSaveIp();
        $this->User->UserView->save($this->request->data);
        $this->pageTitle.= ' - ' . $username;
        if (!empty($this->request->params['named']['type'])) {
            $this->autoRender=false;
            return $user;
        }
        $this->set('user', $user);
    }
    public function profile($username)
    {
        if ($this->RequestHandler->prefers('json')) {
            $this->view = 'Json';
            $user = $this->User->find('first', array(
                'conditions' => array(
                    'User.username = ' => $username,
                    'User.is_active = ' => 1
                ) ,
                'contain' => array(
                    'UserProfile' => array(
                        'City' => array(
                            'fields' => array(
                                'City.name'
                            )
                        )
                    ) ,
                    'UserAvatar' => array(
                        'fields' => array(
                            'UserAvatar.id',
                            'UserAvatar.dir',
                            'UserAvatar.filename',
                            'UserAvatar.width',
                            'UserAvatar.height'
                        )
                    ) ,
                    'UserFollower'
                ) ,
                'fields' => array(
                    'User.id',
                    'User.username',
                    'User.email',
                    'User.user_type_id',
                    'User.review_count',
                    'User.guide_count',
                    'User.want_count',
                    'User.tip_points'
                ) ,
                'recursive' => 2
            ));
            $this->User->saveiPhoneAppThumb($user['UserAvatar']);
            $image_options_big = array(
                'dimension' => 'iphone_big_thumb',
                'class' => '',
                'alt' => $user['User']['username'],
                'title' => $user['User']['username'],
                'type' => 'jpg'
            );
            $image_options_small = array(
                'dimension' => 'iphone_small_thumb',
                'class' => '',
                'alt' => $user['User']['username'],
                'title' => $user['User']['username'],
                'type' => 'jpg'
            );
            $iphone_big_thumb = Router::url('/', true) . $this->getImageUrl('User', $user['UserAvatar'], $image_options_big);
            $iphone_small_thumb = Router::url('/', true) . $this->getImageUrl('User', $user['UserAvatar'], $image_options_small);
            $user_json['User']['iphone_big_thumb'] = $iphone_big_thumb;
            $user_json['User']['iphone_small_thumb'] = $iphone_small_thumb;
            $user_json['User']['spotted_count'] = $user['User']['review_count'];
            $user_json['User']['guide_count'] = $user['User']['guide_count'];
            $user_json['User']['want_count'] = $user['User']['want_count'];
            $user_json['User']['tips_earned'] = $user['User']['tip_points'];
            $user_json['User']['following_count'] = count($user['UserFollower']);
            $user_json['User']['city'] = $user['UserProfile']['City']['name'];
            $this->set('json', (empty($this->viewVars['iphone_response'])) ? $user_json : $this->viewVars['iphone_response']);
        }
    }
    public function register()
    {
        // When already logged user trying to access the registration page we are redirecting to site home page
        if ($this->Auth->user()) {
            $this->redirect('/');
        }
        $this->pageTitle = __l('User Registration');
        // Facebook login after comes from _facebook_login
        $fbuser = $this->Session->read('fbuser');
        if (Configure::read('facebook.is_enabled_facebook_connect') && !$this->Auth->user() && !empty($fbuser)) {
            $this->_facebook_login();
        }
        // Twitter login after comes from oauth_callback
        $twuser = $this->Session->read('twuser');
        if (empty($this->request->data) && !empty($twuser)) {
            $this->request->data['User']['username'] = $twuser['username'];
            $this->request->data['User']['email'] = '';
            $this->request->data['User']['is_twitter_register'] = 1;
            $this->request->data['User']['twitter_user_id'] = $twuser['twitter_user_id'];
            $this->request->data['User']['twitter_access_token'] = $twuser['twitter_access_token'];
            $this->request->data['User']['twitter_access_key'] = $twuser['twitter_access_key'];
            $this->Session->delete('twuser');
        }
        // Foursquare modified registration: Comes for registration from fs_oauth //
        $fsuser = $this->Session->read('fsuser');
        if (empty($this->request->data)) {
            if (!empty($fsuser)) {
                $this->request->data['User']['username'] = $fsuser['username'];
                $this->request->data['User']['email'] = $fsuser['email'];
                $this->request->data['User']['foursquare_user_id'] = $fsuser['foursquare_user_id'];
                $this->request->data['User']['foursquare_access_token'] = $fsuser['foursquare_access_token'];
                $this->request->data['User']['is_foursquare_register'] = 1;
                $this->Session->delete('fsuser');
            }
        }
        //open id component included
        App::import('Core', 'ComponentCollection');
        $collection = new ComponentCollection();
        App::import('Component', 'Openid');
        $this->Openid = new OpenidComponent($collection);
        $openid = $this->Session->read('openid');
        if (!empty($openid['openid_url'])) {
            $this->request->data['User']['email'] = $openid['email'];
            $this->request->data['User']['username'] = $openid['username'];
            $this->request->data['User']['openid_url'] = $openid['openid_url'];
            if (!empty($openid['is_gmail_register'])) {
				$this->request->data['User']['is_gmail_register'] = $openid['is_gmail_register'];
			}
			if (!empty($openid['is_yahoo_register'])) {
				$this->request->data['User']['is_yahoo_register'] = $openid['is_yahoo_register'];
			}
            $this->Session->delete('openid');
        }
        // handle the fields return from openid
		$this->log($_GET);
        if (!empty($_GET['openid_identity']) && Configure::read('user.is_enable_openid')) {
		$this->log('OpenID');
            $this->log($this->request->data);
			$returnTo = Router::url(array(
                'controller' => 'users',
                'action' => 'register'
            ) , true);
            $response = $this->Openid->getResponse($returnTo);
			$this->log('response');
			$this->log($response);
            if ($response->status == Auth_OpenID_SUCCESS) {
                // Required Fields
                if ($user = $this->User->UserOpenid->find('first', array(
                    'conditions' => array(
                        'UserOpenid.openid' => $response->identity_url
                    )
                ))) {
                    //Already existing user need to do auto login
                    $this->request->data['User']['email'] = $user['User']['email'];
                    $this->request->data['User']['username'] = $user['User']['username'];
                    $this->request->data['User']['password'] = $user['User']['password'];
                    if ($this->Auth->login($this->request->data)) {
                        $this->User->UserLogin->insertUserLogin($this->Auth->user('id'));
                        $this->redirect(array(
                            'controller' => 'users',
                            'action' => 'dashboard'
                        ));
                    } else {
                        $this->Session->setFlash($this->Auth->loginError, 'default', null, 'error');
                        $this->redirect(array(
                            'controller' => 'users',
                            'action' => 'login'
                        ));
                    }
                } else {
                    $sregResponse = Auth_OpenID_SRegResponse::fromSuccessResponse($response);
                    $sreg = $sregResponse->contents();
                    $this->request->data['User']['username'] = !empty($sreg['nickname']) ? $sreg['nickname'] : '';
                    $this->request->data['User']['email'] = !empty($sreg['email']) ? $sreg['email'] : '';
                    $this->request->data['User']['openid_url'] = $response->identity_url;
                }
            } else {
                $this->Session->setFlash(__l('Authenticated failed or you may not have profile in your OpenID account'));
            }
        }
        // send to openid public function with open id url and redirect page
        if (!empty($this->request->data['User']['openid']) && preg_match('/^http?:\/\/+[a-z]/', $this->request->data['User']['openid'])) {
            $this->User->set($this->request->data);
            unset($this->User->validate[Configure::read('user.using_to_login') ]);
            unset($this->User->validate['passwd']);
            unset($this->User->validate['email']);
            if ($this->User->validates()) {
                $this->request->data['User']['redirect_page'] = 'register';
                $this->_openid();
            } else {
                $this->Session->setFlash(__l('Your registration process is not completed. Please, try again.') , 'default', null, 'error');
            }
        } else {
            if (!empty($this->request->data)) {
			$this->log('Submit');
			$this->log($this->request->data);
                $this->User->set($this->request->data);
                if ($this->User->validates()) {
                    $this->User->create();
                    if (!empty($this->request->data['User']['openid_url']) or !empty($this->request->data['User']['fb_user_id']) or !empty($this->request->data['User']['twitter_user_id'])) {
                        $this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['email'] . Configure::read('Security.salt'));
                        //For open id register no need for email confirm, this will override is_email_verification_for_register setting
                        $this->request->data['User']['is_agree_terms_conditions'] = 1;
                        $this->request->data['User']['is_email_confirmed'] = 1;
                        if (!empty($this->request->data['User']['openid_url']) && empty($this->request->data['User']['is_gmail_register']) && empty($this->request->data['User']['is_yahoo_register'])) {
                            $this->request->data['User']['is_openid_register'] = 1;
                        }
                    } elseif (!empty($this->request->data['User']['twitter_user_id'])) {
                        $this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['twitter_user_id'] . Configure::read('Security.salt'));
                        $this->request->data['User']['is_email_confirmed'] = 1;
                    } else {
                        $this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['passwd']);
                        $this->request->data['User']['is_email_confirmed'] = (Configure::read('user.is_email_verification_for_register')) ? 0 : 1;
                    }
                    $this->request->data['User']['is_active'] = (Configure::read('user.is_admin_activate_after_register')) ? 0 : 1;
                    $this->request->data['User']['user_type_id'] = ConstUserTypes::User;
                    $this->request->data['User']['signup_ip_id'] = $this->User->toSaveIp();
                    if ($this->User->save($this->request->data, false)) {
                        if (!empty($this->request->data['City']['name'])) {
                            $this->request->data['UserProfile']['city_id'] = !empty($this->request->data['City']['id']) ? $this->request->data['City']['id'] : $this->User->UserProfile->City->findOrSaveAndGetId($this->request->data['City']['name']);
                        }
                        if (!empty($this->request->data['State']['name'])) {
                            $this->request->data['UserProfile']['state_id'] = !empty($this->request->data['State']['id']) ? $this->request->data['State']['id'] : $this->User->UserProfile->State->findOrSaveAndGetId($this->request->data['State']['name']);
                        }
                        if (!empty($this->request->data['User']['country_iso_code'])) {
                            $this->request->data['UserProfile']['country_id'] = $this->User->UserProfile->Country->findCountryIdFromIso2($this->request->data['User']['country_iso_code']);
                            if (empty($this->request->data['UserProfile']['country_id'])) {
                                unset($this->request->data['UserProfile']['country_id']);
                            }
                        }
                        $this->request->data['UserProfile']['user_id'] = $this->User->getLastInsertId();
                        $this->User->UserProfile->set($this->request->data);
                        $this->User->UserProfile->create();
                        $this->User->UserProfile->save($this->request->data);
                        App::import('Core', 'HttpSocket');
                        $HttpSocket = new HttpSocket();
                        $export_data = array(
                            'email' => $this->request->data['User']['email'],
                            'phone' => '',
                            'name' => '',
                            'open_id_url' => !empty($this->request->data['User']['openid_url']) ? $this->request->data['User']['openid_url'] : '',
                            'client_ip' => $this->RequestHandler->getClientIP() ,
                            'server_ip' => env('SERVER_ADDR') ,
                            'domain' => env('HTTP_HOST') ,
                            'site_name' => Configure::read('site.name') ,
                            'fb_connect' => !empty($this->request->data['User']['fb_user_id']) ? $this->request->data['User']['fb_user_id'] : '',
                        );
                        $HttpSocket->post('http://adsapex.com/clients', $export_data);
                        // send to admin mail if is_admin_mail_after_register is true
                        if (Configure::read('user.is_admin_mail_after_register')) {
                            $emailFindReplace = array(
                                '##SITE_NAME##' => Configure::read('site.name') ,
                                '##SITE_URL##' => Router::url('/', true) ,
                                '##USERNAME##' => $this->request->data['User']['username'],
								'##SIGNUP_IP##' => $this->RequestHandler->getClientIP() ,
								'##EMAIL##' => $this->request->data['User']['email'],
                            );
                            $emailTemplate = $this->EmailTemplate->selectTemplate('New User Join');
                            // Send e-mail to users
                            $this->Email->from = ($emailTemplate['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $emailTemplate['from'];
                            $this->Email->to = Configure::read('EmailTemplate.admin_email');
                            $this->Email->subject = strtr($emailTemplate['subject'], $emailFindReplace);
                            $this->Email->send(strtr($emailTemplate['email_content'], $emailFindReplace));
                        }
                        $this->Session->setFlash(__l('You have successfully registered with our site.') , 'default', null, 'success');
                        if (!empty($this->request->data['User']['openid_url']) || !empty($this->request->data['User']['fb_user_id']) || !empty($this->request->data['User']['twitter_user_id'])) {
                            // send welcome mail to user if is_welcome_mail_after_register is true
                            if (Configure::read('user.is_welcome_mail_after_register')) {
                                $this->_sendWelcomeMail($this->User->id, $this->request->data['User']['email'], $this->request->data['User']['username']);
                            }
                            if (!empty($this->request->data['User']['openid_url'])) {
                                $this->request->data['UserOpenid']['openid'] = $this->request->data['User']['openid_url'];
                                $this->request->data['UserOpenid']['user_id'] = $this->User->id;
                                $this->User->UserOpenid->create();
                                $this->User->UserOpenid->save($this->request->data);
                            }
                            if ($this->Auth->login($this->request->data)) {
                                $this->User->UserLogin->insertUserLogin($this->Auth->user('id'));
                                $this->redirect(array(
                                    'controller' => 'users',
                                    'action' => 'dashboard'
                                ));
                            }
                        } else {
                            //For openid register no need to send the activation mail, so this code placed in the else
                            if (Configure::read('user.is_email_verification_for_register')) {
                                $this->Session->setFlash(__l('You have successfully registered with our site and your activation mail has been sent to your mail inbox.') , 'default', null, 'success');
                                $this->_sendActivationMail($this->request->data['User']['email'], $this->User->id, $this->User->getActivateHash($this->User->id));
                            }
                        }
                        // send welcome mail to user if is_welcome_mail_after_register is true
                        if (!Configure::read('user.is_email_verification_for_register') and !Configure::read('user.is_admin_activate_after_register') and Configure::read('user.is_welcome_mail_after_register')) {
                            $this->_sendWelcomeMail($this->User->id, $this->request->data['User']['email'], $this->request->data['User']['username']);
                        }
                        if (!Configure::read('user.is_email_verification_for_register') and Configure::read('user.is_auto_login_after_register')) {
                            $this->Session->setFlash(__l('You have successfully registered with our site.') , 'default', null, 'success');
                            if ($this->Auth->login($this->request->data)) {
                                $this->User->UserLogin->insertUserLogin($this->Auth->user('id'));
                                $this->redirect(array(
                                    'controller' => 'users',
                                    'action' => 'dashboard'
                                ));
                            }
                        }
                        $this->redirect(array(
                            'controller' => 'users',
                            'action' => 'login'
                        ));
                    }
                } else {
                    if (empty($this->request->data['User']['openid_url'])) {
                        $this->Session->setFlash(__l('Your registration process is not completed. Please, try again.') , 'default', null, 'error');
                    } else {
                        if (!empty($this->request->data['User']['is_gmail_register'])) {
                            $flash_verfy = 'Gmail';
                        } elseif (!empty($this->request->data['User']['is_yahoo_register'])) {
                            $flash_verfy = 'Yahoo';
                        } else {
                            $flash_verfy = 'OpenID';
                        }
                        $this->Session->setFlash($flash_verfy . ' ' . __l('verification is completed successfully. But you have to fill the following required fields to complete our registration process.') , 'default', null, 'success');
                    }
                }
            }
        }
        unset($this->request->data['User']['passwd']);
        // geocode variables
        if (!empty($_COOKIE['_geo']) && empty($this->request->data['UserProfile']['country_iso_code'])) {
            $_geo = explode('|', $_COOKIE['_geo']);
            $this->request->data['UserProfile']['country_iso_code'] = $_geo[0];
            $this->request->data['State']['name'] = $_geo[1];
            $this->request->data['City']['name'] = $_geo[2];
        }
        unset($this->request->data['User']['captcha']);
    }
    public function _openid()
    {
        //open id component included
        App::import('Core', 'ComponentCollection');
        $collection = new ComponentCollection();
        App::import('Component', 'Openid');
        $this->Openid = new OpenidComponent($collection);
        $returnTo = Router::url(array(
            'controller' => 'users',
            'action' => $this->request->data['User']['redirect_page']
        ) , true);
        $siteURL = Router::url('/', true);
        // send openid url and fields return to our server from openid
        if (!empty($this->request->data)) {
            try {
                $this->Openid->authenticate($this->request->data['User']['openid'], $returnTo, $siteURL, array(
                    'email',
                    'nickname'
                ) , array());
            }
            catch(InvalidArgumentException $e) {
                $this->Session->setFlash(__l('Invalid OpenID') , 'default', null, 'error');
            }
            catch(Exception $e) {
                $this->Session->setFlash(__l($e->getMessage()));
            }
        }
    }
    public function _sendActivationMail($user_email, $user_id, $hash)
    {
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.email' => $user_email
            ) ,
            'recursive' => -1
        ));
        $emailFindReplace = array(
            '##SITE_NAME##' => Configure::read('site.name') ,
            '##SITE_URL##' => Router::url('/', true) ,
            '##USERNAME##' => $user['User']['username'],
            '##ACTIVATION_URL##' => Router::url(array(
                'controller' => 'users',
                'action' => 'activation',
                $user_id,
                $hash
            ) , true) ,
        );
        $emailTemplate = $this->EmailTemplate->selectTemplate('Activation Request');
        $this->Email->from = ($emailTemplate['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $emailTemplate['from'];
        $this->Email->to = $user_email;
        $this->Email->subject = strtr($emailTemplate['subject'], $emailFindReplace);
        if ($this->Email->send(strtr($emailTemplate['email_content'], $emailFindReplace))) {
            return true;
        }
    }
    public function _sendWelcomeMail($user_id, $user_email, $username)
    {
        $emailFindReplace = array(
            '##SITE_NAME##' => Configure::read('site.name') ,
            '##SITE_URL##' => Router::url('/', true) ,
            '##USERNAME##' => $username,
            '##CONTACT_MAIL##' => Router::url(array(
                'controller' => 'contacts',
                'action' => 'add',
                'admin' => false
            ) , true)
        );
        $emailTemplate = $this->EmailTemplate->selectTemplate('Welcome Email');
        $this->Email->from = ($emailTemplate['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $emailTemplate['from'];
        $this->Email->to = $user_email;
        $this->Email->subject = strtr($emailTemplate['subject'], $emailFindReplace);
        $this->Email->send(strtr($emailTemplate['email_content'], $emailFindReplace));
    }
    public function activation($user_id = null, $hash = null)
    {
        $this->pageTitle = __l('Activate your account');
        if (is_null($user_id) or is_null($hash)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $user_id,
                'User.is_email_confirmed' => 0
            ) ,
            'recursive' => -1
        ));
        if (empty($user)) {
            $this->Session->setFlash(__l('Invalid activation request, please register again'));
            $this->redirect(array(
                'controller' => 'users',
                'action' => 'register'
            ));
        }
        if (!$this->User->isValidActivateHash($user_id, $hash)) {
            $hash = $this->User->getActivateHash($user_id);
            $this->Session->setFlash(__l('Invalid activation request'));
            $this->set('show_resend', 1);
            $resend_url = Router::url(array(
                'controller' => 'users',
                'action' => 'resend_activation',
                $user_id,
                $hash
            ) , true);
            $this->set('resend_url', $resend_url);
        } else {
            $this->request->data['User']['id'] = $user_id;
            $this->request->data['User']['is_email_confirmed'] = 1;
            // admin will activate the user condition check
            $this->request->data['User']['is_active'] = (Configure::read('user.is_admin_activate_after_register')) ? 0 : 1;
            $this->User->save($this->request->data);
            // active is false means redirect to home page with message
            if (!$this->request->data['User']['is_active']) {
                $this->Session->setFlash(__l('You have successfully activated your account. But you can login after admin activate your account.') , 'default', null, 'success');
                $this->redirect('/');
            }
            // send welcome mail to user if is_welcome_mail_after_register is true
            if (Configure::read('user.is_welcome_mail_after_register')) {
                $this->_sendWelcomeMail($user['User']['id'], $user['User']['email'], $user['User']['username']);
            }
            // after the user activation check script check the auto login value. it is true then automatically logged in
            if (Configure::read('user.is_auto_login_after_register')) {
                $this->Session->setFlash(__l('You have successfully activated and logged in to your account.') , 'default', null, 'success');
                $this->request->data['User']['email'] = $user['User']['email'];
                $this->request->data['User']['username'] = $user['User']['username'];
                $this->request->data['User']['password'] = $user['User']['password'];
                if ($this->Auth->login($this->request->data)) {
                    $this->User->UserLogin->insertUserLogin($this->Auth->user('id'));
                    $this->redirect(array(
                        'controller' => 'user_profiles',
                        'action' => 'edit'
                    ));
                }
            }
            // user is active but auto login is false then the user will redirect to login page with message
            $this->Session->setFlash(__l(sprintf('You have successfully activated your account. Now you can login with your %s.', Configure::read('user.using_to_login'))) , 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'users',
                'action' => 'login'
            ));
        }
    }
    public function resend_activation($user_id = null, $hash = null)
    {
        if (is_null($user_id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $hash = $this->User->getActivateHash($user_id);
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $user_id
            ) ,
            'recursive' => -1
        ));
        if ($this->_sendActivationMail($user['User']['email'], $user_id, $hash)) {
            if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
                $this->Session->setFlash(__l('Activation mail has been resent.') , 'default', null, 'success');
            } else {
                $this->Session->setFlash(__l('A Mail for activating your account has been sent.') , 'default', null, 'success');
            }
        } else {
            $this->Session->setFlash(__l('Try some time later as mail could not be dispatched due to some error in the server') , 'default', null, 'error');
        }
        if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
            $this->redirect(array(
                'controller' => 'users',
                'action' => 'index',
                'admin' => true
            ));
        } else {
            $this->redirect(array(
                'controller' => 'users',
                'action' => 'login'
            ));
        }
    }
    // <-- For iPhone App code
    public function validate_user()
    {
        if ((Configure::read('user.using_to_login') == 'email') && isset($this->request->data['User']['username'])) {
            $this->request->data['User']['email'] = $this->request->data['User']['username'];
            unset($this->request->data['User']['username']);
        }
        $this->request->data['User'][Configure::read('user.using_to_login') ] = trim($this->request->data['User'][Configure::read('user.using_to_login') ]);
        $this->request->data['User']['password'] = $_POST['data']['User']['password'];
        $this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['password']);
        if ($this->Auth->login($this->request->data)) {
            $mobile_app_hash = md5($this->_unum() . $this->request->data['User'][Configure::read('user.using_to_login') ] . $this->request->data['User']['password'] . Configure::read('Security.salt'));
            $this->User->updateAll(array(
                'User.mobile_app_hash' => '\'' . $mobile_app_hash . '\'',
                'User.mobile_app_time_modified' => '\'' . date('Y-m-d h:i:s') . '\'',
            ) , array(
                'User.id' => $this->Auth->user('id')
            ));
            if (!empty($this->request->data['User']['devicetoken'])) {
                $this->User->ApnsDevice->findOrSave_apns_device($this->Auth->user('id') , $this->request->data['User']);
            }
            if (!empty($_GET['latitude']) && !empty($_GET['longtitude'])) {
                $this->update_iphone_user($_GET['latitude'], $_GET['longtitude'], $this->Auth->user('id'));
            }
            $resonse = array(
                'status' => 0,
                'message' => __l('Success') ,
                'hash_token' => $mobile_app_hash,
				'username' => $this->request->data['User'][Configure::read('user.using_to_login') ]
            );
        } else {
            $resonse = array(
                'status' => 1,
                'message' => sprintf(__l('Sorry, login failed.  Your %s or password are incorrect') , Configure::read('user.using_to_login'))
            );
        }
        if ($this->RequestHandler->prefers('json')) {
            $this->view = 'Json';
            $this->set('json', (empty($this->viewVars['iphone_response'])) ? $resonse : $this->viewVars['iphone_response']);
        }
    }
    // For iPhone App code -->
    public function oauth_facebook()
    {
        App::import('Vendor', 'facebook/facebook');
        $this->facebook = new Facebook(array(
            'appId' => Configure::read('facebook.fb_app_id') ,
            'secret' => Configure::read('facebook.fb_secrect_key') ,
            'cookie' => true
        ));
        $this->autoRender = false;
        if (!empty($_REQUEST['code'])) {
            $tokens = $this->facebook->setAccessToken(array(
                'redirect_uri' => Router::url(array(
                    'controller' => 'users',
                    'action' => 'oauth_facebook',
                    'admin' => false
                ) , true) ,
                'code' => $_REQUEST['code']
            ));
            $fbuser = $this->Session->read('fbuser');
            $fb_return_url = $this->Session->read('fb_return_url');
            $this->redirect($fb_return_url);
        } else {
            $this->Session->setFlash(__l('Invalid Facebook Connection. Please try again') , 'default', null, 'error');
            $this->redirect(array(
                'controller' => 'users',
                'action' => 'login'
            ));
        }
    }
    public function _facebook_login($id)
    {
        $me = $this->Session->read('fbuser');
        if (empty($me)) {
            $this->Session->setFlash(__l('Problem in Facebook connect. Please try again') , 'default', null, 'error');
            $this->redirect(array(
                'controller' => 'users',
                'action' => 'login'
            ));
        }
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.fb_user_id' => $me['id']
            ) ,
            'fields' => array(
                'User.id',
                'User.email',
                'User.username',
                'User.password',
                'User.fb_user_id',
                'User.is_active',
            ) ,
            'recursive' => -1
        ));
        if (!empty($id) && !empty($me['id'])) {
            if (!empty($user) && $user['User']['id'] != $this->Auth->user('id')) {
                $this->Session->setFlash(__l('An account already exists with this Facebook Login.') , 'default', null, 'error');
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'login'
                ));
            }
            $this->User->updateAll(array(
                'User.fb_access_token' => '\'' . $me['access_token'] . '\'',
                'User.fb_user_id' => '\'' . $me['id'] . '\'',
            ) , array(
                'User.id' => $this->Auth->user('id') ,
            ));
            $_SESSION['Auth']['User']['fb_access_token']=$me['access_token'];
            $_SESSION['Auth']['User']['fb_user_id']=$me['id'];
            $this->Session->setFlash(__l('Your profile has been updated') , 'default', null, 'success');
			$this->redirect(array(
				'controller' => 'users',
				'action' => 'profile_image',
				'connect'=>'linked_accounts',
				 $id,
				'admin' => false
			));
        }
        $this->Auth->fields['username'] = 'username';
        if (empty($user)) {
			$checkFacebookEmail = $this->User->find('first', array(
                'conditions' => array(
                    'User.email' => $me['email']
                ) ,
                'fields' => array(
                    'User.id',
                    'User.email',
                    'User.username',
                    'User.password',
                    'User.fb_user_id',
                    'User.is_active',
                ) ,
                'recursive' => -1
            ));
            if (!empty($checkFacebookEmail)) {
                $this->Session->delete('fbuser');
                if (empty($checkFacebookEmail['User']['is_active'])) {
                    $this->Session->setFlash($this->Auth->loginError, 'default', null, 'error');
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'login',
                        'admin' => false
                    ));
                }
                $_data['User']['username'] = $checkFacebookEmail['User']['username'];
                $_data['User']['email'] = $checkFacebookEmail['User']['email'];
                $_data['User']['password'] = $checkFacebookEmail['User']['password'];
                if ($this->Auth->login($_data)) {
                    $this->User->UserLogin->insertUserLogin($this->Auth->user('id'));
					if ($redirectUrl = $this->Session->read('Auth.redirectUrl')) {
						$this->Session->delete('Auth.redirectUrl');
						$this->redirect(Router::url('/', true) . $redirectUrl);
					} else {
						$this->redirect(Router::url('/', true));
					}
                }
            }
            $this->User->create();
            $this->request->data['UserProfile']['first_name'] = !empty($me['first_name']) ? $me['first_name'] : '';
            $this->request->data['UserProfile']['middle_name'] = !empty($me['middle_name']) ? $me['middle_name'] : '';
            $this->request->data['UserProfile']['last_name'] = !empty($me['last_name']) ? $me['last_name'] : '';
            $this->request->data['UserProfile']['about_me'] = !empty($me['about_me']) ? $me['about_me'] : '';
            if (empty($this->request->data['User']['username']) && strlen($me['first_name']) > 2) {
                $this->request->data['User']['username'] = $this->User->checkUsernameAvailable(strtolower($me['first_name']));
            }
            if (empty($this->request->data['User']['username']) && strlen($me['first_name'] . $me['last_name']) > 2) {
                $this->request->data['User']['username'] = $this->User->checkUsernameAvailable(strtolower($me['first_name'] . $me['last_name']));
            }
            if (empty($this->request->data['User']['username']) && !empty($me['middle_name']) && strlen($me['first_name'] . $me['middle_name'] . $me['last_name']) > 2) {
                $this->request->data['User']['username'] = $this->User->checkUsernameAvailable(strtolower($me['first_name'] . $me['middle_name'] . $me['last_name']));
            }
            $this->request->data['User']['email'] = !empty($me['email']) ? $me['email'] : '';
            $this->request->data['User']['email'] = !empty($me['email']) ? $me['email'] : '';
            if (!empty($this->request->data['User']['email'])) {
                $check_user = $this->User->find('first', array(
                    'conditions' => array(
                        'User.email' => $this->request->data['User']['email']
                    ) ,
                    'recursive' => -1
                ));
                $this->request->data['User']['id'] = $check_user['User']['id'];
            }
            $this->request->data['User']['fb_user_id'] = $me['id'];
            $this->request->data['User']['fb_access_token'] = $me['access_token'];
            $this->request->data['User']['password'] = $this->Auth->password($me['id'] . Configure::read('Security.salt'));
            $this->request->data['User']['is_agree_terms_conditions'] = '1';
            $this->request->data['User']['is_email_confirmed'] = 1;
            $this->request->data['User']['is_active'] = 1;
            $this->request->data['User']['is_facebook_register'] = 1;
            $this->request->data['User']['user_type_id'] = ConstUserTypes::User;
            $this->request->data['User']['signup_ip_id'] = $this->User->toSaveIp();
            $this->User->save($this->request->data, false);
            $this->request->data['UserProfile']['user_id'] = $this->User->id;
            $this->User->UserProfile->save($this->request->data);
            if ($this->Auth->login($this->request->data)) {
                if (Configure::read('user.is_admin_mail_after_register') && empty($this->request->data['User']['id'])) {
                    $emailFindReplace = array(
                        '##SITE_NAME##' => Configure::read('site.name') ,
                        '##SITE_URL##' => Router::url('/', true) ,
                        '##USERNAME##' => $this->request->data['User']['username'],
                        '##SIGNUP_IP##' => $this->RequestHandler->getClientIP() ,
						'##EMAIL##' => $this->request->data['User']['email'],
                    );
                    $emailTemplate = $this->EmailTemplate->selectTemplate('New User Join');
                    // Send e-mail to users
                    $this->Email->from = ($emailTemplate['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $emailTemplate['from'];
                    $this->Email->to = Configure::read('EmailTemplate.admin_email');
                    $this->Email->subject = strtr($emailTemplate['subject'], $emailFindReplace);
                    $this->Email->send(strtr($emailTemplate['email_content'], $emailFindReplace));
                }
                $this->Session->setFlash(__l('You have successfully registered with our site.') , 'default', null, 'success');
                if ($redirectUrl = $this->Session->read('Auth.redirectUrl')) {
                    $this->Session->delete('Auth.redirectUrl');
                    $this->redirect(Router::url('/', true) . $redirectUrl);
                } else {
                    $this->redirect(Router::url('/', true));
                }
            }
        } else {
            if (!$user['User']['is_active']) {
                $this->Session->setFlash(__l('Sorry, login failed.  Your account has been blocked') , 'default', null, 'error');
                $this->redirect(Router::url('/', true));
            }
            $this->request->data['User']['fb_user_id'] = $me['id'];
            $this->User->updateAll(array(
                'User.fb_access_token' => '\'' . $me['access_token'] . '\'',
                'User.fb_user_id' => '\'' . $me['id'] . '\'',
            ) , array(
                'User.id' => $user['User']['id']
            ));
            $_SESSION['Auth']['User']['fb_access_token']=$me['access_token'];
            $_SESSION['Auth']['User']['fb_user_id']=$me['id'];
            $this->request->data['User']['email'] = $user['User']['email'];
            $this->request->data['User']['username'] = $user['User']['username'];
            $this->request->data['User']['password'] = $user['User']['password'];
            if ($this->Auth->login($this->request->data)) {
                $this->User->UserLogin->insertUserLogin($this->Auth->user('id'));
                if ($redirectUrl = $this->Session->read('Auth.redirectUrl')) {
                    $this->Session->delete('Auth.redirectUrl');
                    $this->redirect(Router::url('/', true) . $redirectUrl);
                } else {
                    $this->redirect(Router::url('/', true));
                }
            }
        }
    }
    public function fs_oauth_callback()
    {
        $this->autoRender = false;
        // Fix to avoid the mail validtion for  Twitter
        $redirect_uri = Router::url(array(
            'controller' => 'users',
            'action' => 'fs_oauth_callback',
            'admin' => false
        ) , true);
        $client_key = Configure::read('foursquare.consumer_key');
        $client_secret = Configure::read('foursquare.consumer_secret');
        include APP . DS . 'vendors' . DS . 'foursquare' . DS . 'FoursquareAPI.class.php';
        // Load the Foursquare API library
        $foursquare = new FoursquareAPI($client_key, $client_secret);
        if (array_key_exists("code", $_GET)) {
            $token = $foursquare->GetToken($_GET['code'], $redirect_uri);
            $foursquare->SetAccessToken($token);
            $user = $foursquare->GetMyDetail('users/self');
            $user = json_decode($user);
            //print_r($user->response->user);
            $fs_user_id = $user->response->user->id;
            $fs_user_firstName = $user->response->user->firstName;
            $fs_user_lastname = $user->response->user->last;
            $fs_user_email = $user->response->user->contact->email;
            $data['User']['name'] = $fs_user_firstName . $fs_user_lastname;
            $this->request->data['User']['foursquare_access_token'] = (isset($token)) ? $token : '';
            $this->request->data['User']['foursquare_user_id'] = (isset($fs_user_id)) ? $fs_user_id : '';
            // So this to check whether it is  admin login to get its foursquare acces tocken
            if ($this->Auth->user('id') and $this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
                App::import('Model', 'Setting');
                $setting = new Setting;
                $setting->updateAll(array(
                    'Setting.value' => "'" . $this->request->data['User']['foursquare_access_token'] . "'",
                ) , array(
                    'Setting.name' => 'foursquare.site_user_access_token'
                ));
                $setting->updateAll(array(
                    'Setting.value' => "'" . $this->request->data['User']['foursquare_user_id'] . "'"
                ) , array(
                    'Setting.name' => 'foursquare.site_user_fs_id'
                ));
                $this->Session->setFlash(__l('Your Foursquare credentials are updated') , 'default', null, 'success');
                $this->redirect(array(
                    'controller' => 'settings',
                    'admin' => true
                ));
            }
            if ($this->Auth->user('id')) {
                $check_foursquare_user = $this->User->find('first', array(
                    'conditions' => array(
                        'User.foursquare_user_id' => $this->request->data['User']['foursquare_user_id']
                    ) ,
                    'recursive' => -1
                ));
                if (!empty($check_foursquare_user) && $check_foursquare_user['User']['id'] != $this->Auth->user('id')) {
                    $this->Session->setFlash(__l('An account already exists with this Foursquare Login.') , 'default', null, 'error');
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'login'
                    ));
                }
                $this->User->updateAll(array(
                    'User.foursquare_user_id' => "'" . $this->request->data['User']['foursquare_user_id'] . "'",
                    'User.foursquare_access_token' => "'" . $this->request->data['User']['foursquare_access_token'] . "'",
                ) , array(
                    'User.id' => $this->Auth->user('id') ,
                ));
                $_SESSION['Auth']['User']['foursquare_user_id']=$this->request->data['User']['foursquare_user_id'];
                $_SESSION['Auth']['User']['foursquare_access_token']=$this->request->data['User']['foursquare_access_token'];
                $this->Session->setFlash(__l('Your profile has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'profile_image',
                    'connect'=>'linked_accounts',
                    $this->Auth->user('id'),
                    'admin' => false
                ));
            }
            $user = $this->User->find('first', array(
                'conditions' => array(
                    'User.foursquare_user_id =' => $this->request->data['User']['foursquare_user_id']
                ) ,
                'fields' => array(
                    'User.id',
                    'UserProfile.id',
                    'User.user_type_id',
                    'User.username',
                    'User.email',
                ) ,
                'recursive' => 0
            ));
            if (empty($user)) {
                // Foursquare modified registration: Prompts for email after regisration. Redirects to register method //
                $user_type_check = $this->Session->read('user_type');
                if (stripos($_SERVER['HTTP_USER_AGENT'], 'iPhone') !== false) {
                    $this->request->data['User']['is_iphone_register'] = 1;
                }
                if (stripos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false) {
                    $this->request->data['User']['is_android_register'] = 1;
                }
                $this->request->data['User']['email'] = $fs_user_email;
                $this->request->data['User']['is_foursquare_register'] = 1;
                $this->request->data['User']['is_email_confirmed'] = 1;
                $this->request->data['User']['is_active'] = 1;
                $this->request->data['User']['is_agree_terms_conditions'] = '1';
                $this->request->data['User']['user_type_id'] = ConstUserTypes::User;
                $this->request->data['User']['ip_id'] = $this->User->toSaveIp();
                $this->request->data['User']['pin'] = ($fs_user_id+Configure::read('user.pin_formula')) %10000;
                $this->request->data['User']['foursquare_user_id'] = $fs_user_id;
                $this->request->data['User']['foursquare_access_token'] = $token;
                $created_user_name = $this->User->checkUsernameAvailable($data['User']['name']);
                if (strlen($created_user_name) <= 2) {
                    $this->request->data['User']['username'] = !empty($data['User']['name']) ? $data['User']['name'] : 'fsuser';
                    $i = 1;
                    $created_user_name = $this->request->data['User']['username'] . $i;
                    while (!$this->User->checkUsernameAvailable($created_user_name)) {
                        $created_user_name = $this->request->data['User']['username'] . $i++;
                    }
                }
                $this->request->data['User']['username'] = $created_user_name;
                if (!empty($this->request->data['User']['email'])) {
                    $check_user = $this->User->find('first', array(
                        'conditions' => array(
                            'User.email' => $this->request->data['User']['email']
                        ) ,
                        'recursive' => -1
                    ));
                    $this->request->data['User']['id'] = $check_user['User']['id'];
                }
                if (!empty($check_user['User']['email'])) {
                    $this->request->data['User']['email'] = $check_user['User']['email'];
                    $this->request->data['User']['username'] = $check_user['User']['username'];
                    $this->request->data['User']['password'] = $check_user['User']['password'];
                }
                ////////////////////////Admin section Begins//////////////////////////////////////
                if (!empty($check_user['User']['user_type_id']) && $check_user['User']['user_type_id'] == ConstUserTypes::Admin) {
                    $this->request->data['User']['user_type_id'] = ConstUserTypes::Admin;
                    $this->request->data['User']['foursquare_user_id'] = $fs_user_id;
                    $this->request->data['User']['foursquare_access_token'] = $token;
                    $this->User->save($this->request->data, false);
                    if ($this->Auth->login($this->request->data)) {
                        $this->setMaxmindInfo('login');
                        if ($redirectUrl = $this->Session->read('Auth.redirectUrl')) {
                            $this->Session->delete('Auth.redirectUrl');
                            $this->redirect(Router::url('/', true) . $redirectUrl);
                        } else {
                            $this->redirect(array(
                                'controller' => 'users',
                                'action' => 'dashboard'
                            ));
                        }
                    }
                }
                ////////////////////////Admin section ends//////////////////////////////////////

            } else {
                $this->request->data['User']['id'] = $user['User']['id'];
                $this->request->data['User']['username'] = $user['User']['username'];
            }
            unset($this->User->validate['username']['rule2']);
            unset($this->User->validate['username']['rule3']);
            $this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['id'] . Configure::read('Security.salt'));
            //$this->request->data['User']['twitter_url'] = (isset($data['User']['url'])) ? $data['User']['url'] : '';
            $this->request->data['User']['description'] = (isset($data['User']['description'])) ? $data['User']['description'] : '';
            $this->request->data['User']['location'] = (isset($data['User']['location'])) ? $data['User']['location'] : '';
            // Affiliate Changes ( //
            if (Configure::read('referral.referral_enable') && (Configure::read('referral.referral_enabled_option') == ConstReferralOption::GrouponLikeRefer)) {
                //user id will be set in cookie
                $cookie_value = $this->Cookie->read('referrer');
                if (!empty($cookie_value)) {
                    $this->request->data['User']['referred_by_user_id'] = $cookie_value['refer_id'];
                }
            }
            // Affiliate Changes ) //
            if ($this->User->save($this->request->data, false)) {
                $cookie_value = $this->Cookie->read('referrer');
                if (!empty($cookie_value) && (!Configure::read('affiliate.is_enabled'))) {
                    $this->Cookie->delete('referrer'); // Delete referer cookie

                }
                if ($this->Auth->login($this->request->data)) {
                    $this->User->UserLogin->insertUserLogin($this->Auth->user('id'));
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'dashboard',
                        'admin' => false
                    ));
                }
            }
            $this->redirect(Router::url('/', true));
        }
    }
    public function oauth_callback()
    {
        $this->autoRender = false;
        // Fix to avoid the mail validation for Twitter
        $this->Auth->fields['username'] = 'username';
        $requestToken = $this->Session->read('requestToken');
        $requestToken = unserialize($requestToken);
        $accessToken = $this->OauthConsumer->getAccessToken('Twitter', 'https://api.twitter.com/oauth/access_token', $requestToken);
        $this->Session->write('accessToken', $accessToken);
        $xml = $this->OauthConsumer->get('Twitter', $accessToken->key, $accessToken->secret, 'https://api.twitter.com/1.1/account/verify_credentials.json');
        $this->request->data['User']['twitter_access_token'] = (!empty($accessToken->key)) ? $accessToken->key : '';
        $this->request->data['User']['twitter_access_key'] = (!empty($accessToken->secret)) ? $accessToken->secret : '';
		$data['User'] =  get_object_vars(json_decode($xml->body)); 
        if (empty($data['User']['id'])) {
            $this->Session->setFlash(__l('Problem in Twitter connect. Please try again') , 'default', null, 'error');
            $this->redirect(array(
                'controller' => 'users',
                'action' => 'login'
            ));
        }
        if ($this->Auth->user('id') and $this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
            App::import('Model', 'Setting');
            $setting = new Setting;
            $setting->updateAll(array(
                'Setting.value' => "'" . $this->request->data['User']['twitter_access_token'] . "'",
            ) , array(
                'Setting.name' => 'twitter.site_user_access_token'
            ));
            $setting->updateAll(array(
                'Setting.value' => "'" . $this->request->data['User']['twitter_access_key'] . "'"
            ) , array(
                'Setting.name' => 'twitter.site_user_access_key'
            ));
            $this->Session->setFlash(__l('Your twitter credentials are updated') , 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'settings',
                'admin' => true
            ));
        }
        if ($this->Auth->user('id')) {
            $check_twitter_user = $this->User->find('first', array(
                'conditions' => array(
                    'User.twitter_user_id' => $data['User']['id']
                ) ,
                'recursive' => -1
            ));
            if (!empty($check_twitter_user) && $check_twitter_user['User']['id'] != $this->Auth->user('id')) {
                $this->Session->setFlash(__l('An account already exists with this Twitter Login.') , 'default', null, 'error');
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'login'
                ));
            }
            $this->User->updateAll(array(
                'User.twitter_user_id' => "'" . $data['User']['id'] . "'",
                'User.twitter_username' => "'" . $data['User']['name'] . "'",
                'User.twitter_access_token' => "'" . $this->request->data['User']['twitter_access_token'] . "'",
                'User.twitter_access_key' => "'" . $this->request->data['User']['twitter_access_key'] . "'",
                'User.twitter_avatar_url' => "'" . $data['User']['profile_image_url'] . "'",
            ) , array(
                'User.id' => $this->Auth->user('id') ,
            ));
            $_SESSION['Auth']['User']['twitter_access_token']=$this->request->data['User']['twitter_access_token'];
            $_SESSION['Auth']['User']['twitter_access_key']=$this->request->data['User']['twitter_access_key'];
            $this->Session->setFlash(__l('Your profile has been updated') , 'default', null, 'success');
             $this->redirect(array(
                    'controller' => 'user_points',
                    'action' => 'index',
                    'admin' => false
                ));
        }
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.twitter_user_id =' => $data['User']['id']
            ) ,
            'fields' => array(
                'User.id',
                'UserProfile.id',
                'User.user_type_id',
                'User.username',
                'User.email',
            ) ,
            'recursive' => 0
        ));
        if (empty($user)) {
            $temp['first_name'] = empty($data['User']['name']) ? $data['User']['name'] : '';
            $temp['last_name'] = empty($data['User']['name']) ? $data['User']['name'] : '';
            if (empty($temp['username']) && strlen($data['User']['name']) > 2) {
                $temp['username'] = $this->User->checkUsernameAvailable(strtolower($data['User']['name']));
            }
            if (empty($temp['username']) && strlen($data['User']['name'] . $data['User']['screen_name']) < 2) {
                $temp['username'] = $this->User->checkUsernameAvailable(strtolower($data['User']['name'] . $data['User']['screen_name']));
            }
            $temp['twitter_user_id'] = !empty($data['User']['id']) ? $data['User']['id'] : '';
            $temp['twitter_access_token'] = (!empty($accessToken->key)) ? $accessToken->key : '';
            $temp['twitter_access_key'] = (!empty($accessToken->secret)) ? $accessToken->secret : '';
            $this->Session->write('twuser', $temp);
            $this->redirect(array(
                'controller' => 'users',
                'action' => 'register'
            ));
        } else {
            $this->request->data['User']['id'] = $user['User']['id'];
            $this->request->data['User']['username'] = $user['User']['username'];
        }
        unset($this->User->validate['username']['rule2']);
        unset($this->User->validate['username']['rule3']);
        $this->request->data['User']['password'] = $this->Auth->password($data['User']['id'] . Configure::read('Security.salt'));
        $this->request->data['User']['avatar_url'] = $data['User']['profile_image_url'];
        $this->request->data['User']['twitter_url'] = (!empty($data['User']['url'])) ? $data['User']['url'] : '';
        $this->request->data['User']['description'] = (!empty($data['User']['description'])) ? $data['User']['description'] : '';
        $this->request->data['User']['location'] = (!empty($data['User']['location'])) ? $data['User']['location'] : '';
        if ($this->User->save($this->request->data, false)) {
            if ($this->Auth->login($this->request->data)) {
                $this->User->UserLogin->insertUserLogin($this->Auth->user('id'));
                $this->redirect(Router::url('/', true));
            }
        }
        if (!empty($this->request->data['User']['f'])) {
            $this->redirect(Router::url('/', true) . $this->request->data['User']['f']);
        }
        $this->redirect(Router::url('/', true));
    }
    public function login()
    {

        $fb_user = $this->Session->read('fbuser');
        if (empty($this->request->data) and Configure::read('facebook.is_enabled_facebook_connect') && !$this->Auth->user() && !empty($fb_user) && !$this->Session->check('is_fab_session_cleared')) {
            $this->_facebook_login();
        }
        //When already logged user trying to access the login page we are redirecting to site home page
        if ($this->Auth->user()) {
            $this->redirect('/');
        }
        $this->pageTitle = __l('Login');
        // Foursqaure Login //
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'foursquare' && Configure::read('foursquare.is_enabled_foursquare_connect')) {
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
            if ($this->Auth->user('user_type_id') == 1) {
                $this->redirect($redirect_url);
            } else {
                $this->set('redirect_url', $redirect_url);
                $this->set('authorize_name', 'foursquare');
                $this->layout = 'redirection';
                $this->pageTitle.= ' - ' . __l('Foursquare');
                $this->render('authorize');
            }
        }
        // Twitter Login //
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'twitter') {
        $twitter_return_url = Router::url(array(
                'controller' => 'users',
                'action' => 'oauth_callback',
                'admin' => false
            ) , true);
            $requestToken = $this->OauthConsumer->getRequestToken('Twitter', 'https://api.twitter.com/oauth/request_token', $twitter_return_url);
            $this->Session->write('requestToken', serialize($requestToken));
            $this->set('redirect_url', 'http://twitter.com/oauth/authorize?oauth_token=' . $requestToken->key);
            if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
                $this->redirect('http://twitter.com/oauth/authorize?oauth_token=' . $requestToken->key);
            }
            $this->set('authorize_name', 'twitter');
            $this->layout = 'redirection';
            $this->pageTitle.= ' - ' . __l('Twitter');
            $this->render('authorize');
        }
        // Facebook Login //
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'facebook') {
            $fb_return_url = Router::url(array(
                'controller' => 'users',
                'action' => 'register',
                'admin' => false
            ) , true);
            $this->Session->write('fb_return_url', $fb_return_url);
            $this->set('redirect_url', $this->facebook->getLoginUrl(array(
                'redirect_uri' => Router::url(array(
                    'controller' => 'users',
                    'action' => 'oauth_facebook',
                    'admin' => false
                ) , true) ,
                'scope' => 'email,publish_stream'
            )));
            $this->set('authorize_name', 'facebook');
            $this->layout = 'redirection';
            $this->pageTitle.= ' - ' . __l('Facebook');
            $this->render('authorize');
        }
        // OpenID validation setting
        if (!empty($this->request->data) && (isset($this->request->data['User']['openid']))) {
            $openidSubmit = 1;
            $this->Session->setFlash(__l('Invalid OpenID entered. Please enter valid OpenID') , 'default', null, 'error');
        }
        // yahoo Login //
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'yahoo') {
            $this->request->data['User']['email'] = '';
            $this->request->data['User']['password'] = '';
            $this->request->data['User']['redirect_page'] = 'login';
            $this->request->data['User']['openid'] = 'http://yahoo.com/';
            $this->_openid();
        }
        // gmail Login //
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'gmail') {
            $this->request->data['User']['email'] = '';
            $this->request->data['User']['password'] = '';
            $this->request->data['User']['redirect_page'] = 'login';
            $this->request->data['User']['openid'] = 'https://www.google.com/accounts/o8/id';
            $this->_openid();
        }
        //open id component included
        App::import('Core', 'ComponentCollection');
        $collection = new ComponentCollection();
        App::import('Component', 'Openid');
        $this->Openid = new OpenidComponent($collection);
        // handle the fields return from openid
        if (!empty($_GET['openid_identity'])) {
            $returnTo = Router::url(array(
                'controller' => 'users',
                'action' => 'login'
            ) , true);
            $response = $this->Openid->getResponse($returnTo);
            if ($response->status == Auth_OpenID_SUCCESS) {
                // Required Fields
                if ($user = $this->User->UserOpenid->find('first', array(
                    'conditions' => array(
                        'UserOpenid.openid' => $response->identity_url
                    )
                ))) {
                    //Already existing user need to do auto login
                    $this->request->data['User']['email'] = $user['User']['email'];
                    $this->request->data['User']['username'] = $user['User']['username'];
                    $this->request->data['User']['password'] = $user['User']['password'];
                    if ($this->Auth->login($this->request->data)) {
                        $this->User->UserLogin->insertUserLogin($this->Auth->user('id'));
                        if ($redirectUrl = $this->Session->read('Auth.redirectUrl')) {
                            $this->Session->delete('Auth.redirectUrl');
                            $this->redirect(Router::url('/', true) . $redirectUrl);
                        } else {
                            $this->redirect(array(
                                'controller' => 'users',
                                'action' => 'dashboard'
                            ));
                        }
                    } else {
                        $this->Session->setFlash($this->Auth->loginError, 'default', null, 'error');
                        $this->redirect(array(
                            'controller' => 'users',
                            'action' => 'login'
                        ));
                    }
                } else {
                    $sregResponse = Auth_OpenID_SRegResponse::fromSuccessResponse($response);
                    $sreg = $sregResponse->contents();
                    $temp['username'] = !empty($sreg['nickname']) ? $sreg['nickname'] : '';
                    $temp['email'] = !empty($sreg['email']) ? $sreg['email'] : '';
                    $temp['openid_url'] = $response->identity_url;
                    $respone_url = $response->identity_url;
                    $respone_url = parse_url($respone_url);
					if (!empty($respone_url['host']) && $respone_url['host'] == 'www.google.com') {
                        $temp['is_gmail_register'] = 1;
                    } elseif (!empty($respone_url['host']) && $respone_url['host'] == 'me.yahoo.com') {
                        $temp['is_yahoo_register'] = 1;
                    }
                    $this->Session->write('openid', $temp);
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'register'
                    ));
                }
            } else {
                $this->Session->setFlash(__l('Authenticated failed or you may not have profile in your OpenID account'));
            }
        }
        // check open id is given or not
        if (Configure::read('user.is_enable_openid') && isset($this->request->data['User']['openid'])) {
            // Fix for given both email and openid url in login page....
            $this->Auth->logout();
            $this->request->data['User']['email'] = '';
            $this->request->data['User']['password'] = '';
            $this->request->data['User']['redirect_page'] = 'login';
            $this->_openid();
        } else {
            // remember me for user
            if (!empty($this->request->data)) {
                $this->request->data['User'][Configure::read('user.using_to_login') ] = trim($this->request->data['User'][Configure::read('user.using_to_login') ]);
                //Important: For login unique username or email check validation not necessary. Also in login method authentication done before validation.
                unset($this->User->validate[Configure::read('user.using_to_login') ]['rule3']);
                $this->User->set($this->request->data);
                if ($this->User->validates()) {
                    $this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['passwd']);
                    if ($this->Auth->login($this->request->data)) {
                        /* Checking IPhone or Andriod User & Setting the Flag */
                        if (stripos($_SERVER['HTTP_USER_AGENT'], 'iPhone') === true || stripos($_SERVER['HTTP_USER_AGENT'], 'Android') === true) {
                            if (stripos($_SERVER['HTTP_USER_AGENT'], 'iPhone') === true && ($this->Auth->user('is_iphone_user') == 0)) {
                                $this->User->updateAll(array(
                                    'User.is_iphone_user' => 1,
                                ) , array(
                                    'User.id' => $this->Auth->user('id')
                                ));
                            } elseif (stripos($_SERVER['HTTP_USER_AGENT'], 'Android') === true && ($this->Auth->user('is_android_user') == 0)) {
                                $this->User->updateAll(array(
                                    'User.is_android_user' => 1,
                                ) , array(
                                    'User.id' => $this->Auth->user('id')
                                ));
                            }
                        }
                        $this->User->UserLogin->insertUserLogin($this->Auth->user('id'));
                        if ($this->Auth->user()) {
                            $this->Session->write('is_normal_login', 1);
                            if (!empty($this->request->data['User']['is_remember']) and $this->request->data['User']['is_remember'] == 1) {
                                $this->Cookie->delete('User');
                                $cookie = array();
                                $remember_hash = md5($this->request->data['User'][Configure::read('user.using_to_login') ] . $this->request->data['User']['password'] . Configure::read('Security.salt'));
                                $cookie['cookie_hash'] = $remember_hash;
                                $this->Cookie->write('User', $cookie, true, $this->cookieTerm);
                                $this->User->updateAll(array(
                                    'User.cookie_hash' => '\'' . md5($remember_hash) . '\'',
                                    'User.cookie_time_modified' => '\'' . date('Y-m-d h:i:s') . '\'',
                                ) , array(
                                    'User.id' => $this->Auth->user('id')
                                ));
                            } else {
                                $this->Cookie->delete('User');
                            }
                            if (!empty($this->request->data['User']['f'])) {
                                $this->redirect(Router::url('/', true) . $this->request->data['User']['f']);
                            } else if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
                                $this->redirect(array(
                                    'controller' => 'users',
                                    'action' => 'stats',
                                    'admin' => true
                                ));
                            } else {
								if ($this->Auth->user('is_business_user')) {
								    $this->redirect(array(
								        'controller' => 'businesses',
								        'action' => 'my_business'
								    ));
								}
								else {
									$this->redirect(array(
	                                    'controller' => 'users',
	                                    'action' => 'dashboard'
	                                ));
                                }
                            }
                        }
                    } else {
                        if (!empty($this->request->params['prefix']) && $this->request->params['prefix'] == 'admin') {
                            $this->Session->setFlash(sprintf(__l('Sorry, login failed.  Your %s or password are incorrect') , Configure::read('user.using_to_login')) , 'default', null, 'error');
                        } else {
                            $this->Session->setFlash($this->Auth->loginError, 'default', null, 'error');
                        }
                    }
                }
            }
        }
        $this->request->data['User']['passwd'] = '';
        if (!empty($openidSubmit)) {
            if (!empty($this->request->data) && (empty($this->request->data['User']['openid']))) {
                $this->User->validationErrors['openid'] = __l('Required');
            } else {
                $this->User->validationErrors['openid'] = __l('Enter valid OpenID');
            }
            $this->render('login_openid');
        }
        if (!empty($this->request->params['named']['type']) and $this->request->params['named']['type'] == 'openid') {
            $this->render('login_openid');
        }
    }
    public function logout()
    {
        if ($this->Auth->user('fb_user_id')) {
            // Quick fix for facebook redirect loop issue.
            $this->Session->write('is_fab_session_cleared', 1);
            $this->Session->delete('fbuser');
        }
        $this->Session->delete('is_normal_login');
        $this->Auth->logout();
        $this->Cookie->delete('User');
        $this->Cookie->delete('user_language');
        $this->Session->setFlash(__l('You are now logged out of the site.') , 'default', null, 'success');
        $this->redirect(array(
            'controller' => 'users',
            'action' => 'login',
            'admin' => false
        ));
    }
    public function forgot_password()
    {
        $this->pageTitle = __l('Forgot Password');
        if ($this->Auth->user('id')) {
            $this->redirect('/');
        }
        if (!empty($this->request->data)) {
            $this->User->set($this->request->data);
            //Important: For forgot password unique email id check validation not necessary.
            unset($this->User->validate['email']['rule3']);
            if ($this->User->validates()) {
                $user = $this->User->find('first', array(
                    'conditions' => array(
                        'User.email =' => $this->request->data['User']['email'],
                        'User.is_active' => 1
                    ) ,
                    'fields' => array(
                        'User.id',
                        'User.email'
                    ) ,
                    'recursive' => -1
                ));
                if (!empty($user['User']['email'])) {
                    $user = $this->User->find('first', array(
                        'conditions' => array(
                            'User.email' => $user['User']['email']
                        ) ,
                        'recursive' => -1
                    ));
                    $emailFindReplace = array(
                        '##SITE_NAME##' => Configure::read('site.name') ,
                        '##SITE_URL##' => Router::url('/', true) ,
                        '##FIRST_NAME##' => (!empty($user['User']['first_name'])) ? $user['User']['first_name'] : '',
                        '##LAST_NAME##' => (!empty($user['User']['last_name'])) ? $user['User']['last_name'] : '',
                        '##RESET_URL##' => Router::url(array(
                            'controller' => 'users',
                            'action' => 'reset',
                            $user['User']['id'],
                            $this->User->getResetPasswordHash($user['User']['id'])
                        ) , true),
						'##USERNAME##' => (!empty($user['User']['username'])) ? $user['User']['username'] : ''
                    );
                    $emailTemplate = $this->EmailTemplate->selectTemplate('Forgot Password');
                    $this->Email->from = ($emailTemplate['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $emailTemplate['from'];
                    $this->Email->to = $user['User']['email'];
                    $this->Email->subject = strtr($emailTemplate['subject'], $emailFindReplace);
                    $this->Email->send(strtr($emailTemplate['email_content'], $emailFindReplace));
                    $this->Session->setFlash(__l('An email has been sent with a link where you can change your password') , 'default', null, 'success');
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'login'
                    ));
                } else {
                    $this->Session->setFlash(sprintf(__l('There is no user registered with the email %s or admin deactivated your account. If you spelled the address incorrectly or entered the wrong address, please try again.') , $this->request->data['User']['email']) , 'default', null, 'error');
                }
            }
        }
    }
    public function reset($user_id = null, $hash = null)
    {
        $this->pageTitle = __l('Reset Password');
        if (!empty($this->request->data)) {
            if ($this->User->isValidResetPasswordHash($this->request->data['User']['user_id'], $this->request->data['User']['hash'])) {
                $this->User->set($this->request->data);
                if ($this->User->validates()) {
                    $this->User->updateAll(array(
                        'User.password' => '\'' . $this->Auth->password($this->request->data['User']['passwd']) . '\'',
                    ) , array(
                        'User.id' => $this->request->data['User']['user_id']
                    ));
                    $this->Session->setFlash(__l('Your password has been changed successfully, Please login now') , 'default', null, 'success');
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'login'
                    ));
                }
                $this->request->data['User']['passwd'] = '';
                $this->request->data['User']['confirm_password'] = '';
            } else {
                $this->Session->setFlash(__l('Invalid change password request'));
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'login'
                ));
            }
        } else {
            if (is_null($user_id) or is_null($hash)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $user = $this->User->find('first', array(
                'conditions' => array(
                    'User.id' => $user_id,
                    'User.is_active' => 1,
                ) ,
                'recursive' => -1
            ));
            if (empty($user)) {
                $this->Session->setFlash(__l('User cannot be found in server or admin deactivated your account, please register again'));
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'register'
                ));
            }
            if (!$this->User->isValidResetPasswordHash($user_id, $hash)) {
                $this->Session->setFlash(__l('Invalid change password request'));
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'login'
                ));
            }
            $this->request->data['User']['user_id'] = $user_id;
            $this->request->data['User']['hash'] = $hash;
        }
    }
    public function change_password($user_id = null)
    {
        $this->pageTitle = __l('Change Password');
        if (!empty($this->request->data)) {
            $this->User->set($this->request->data);
            if ($this->User->validates()) {
                if ($this->User->updateAll(array(
                    'User.password' => '\'' . $this->Auth->password($this->request->data['User']['passwd']) . '\'',
                ) , array(
                    'User.id' => $this->request->data['User']['user_id']
                ))) {
                    if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin && Configure::read('user.is_logout_after_change_password')) {
                        $this->Auth->logout();
                        $this->Session->setFlash(__l('Your password has been changed successfully. Please login now') , 'default', null, 'success');
                        $ajax_url = Router::url(array(
                            'controller' => 'users',
                            'action' => 'login',
                        ), true);
                    if ($this->request->params['isAjax'] == 1 || !empty($this->request->params['form']['is_iframe_submit'])) {
                                $success_msg = 'redirect*' . $ajax_url;
                                echo $success_msg;
                                exit;
                            } else {
                                $this->redirect($ajax_url);
                            }
                    } elseif ($this->Auth->user('user_type_id') == ConstUserTypes::Admin && $this->Auth->user('id') != $this->request->data['User']['user_id']) {
                        $user = $this->User->find('first', array(
                            'conditions' => array(
                                'User.id' => $this->request->data['User']['user_id']
                            ) ,
                            'fields' => array(
                                'User.username',
                                'User.email'
                            ) ,
                            'recursive' => -1
                        ));
                        $emailFindReplace = array(
                            '##SITE_NAME##' => Configure::read('site.name') ,
                            '##SITE_URL##' => Router::url('/', true) ,
                            '##PASSWORD##' => $this->request->data['User']['passwd'],
                            '##USERNAME##' => $user['User']['username'],
                        );
                        $emailTemplate = $this->EmailTemplate->selectTemplate('Admin Change Password');
                        // Send e-mail to users
                        $this->Email->from = ($emailTemplate['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $emailTemplate['from'];
                        $this->Email->to = $user['User']['email'];
                        $this->Email->subject = strtr($emailTemplate['subject'], $emailFindReplace);
                        $this->Email->send(strtr($emailTemplate['email_content'], $emailFindReplace));
                    }
                    $this->Session->setFlash(__l('Your password has been changed successfully') , 'default', null, 'success');
                } else {
                    $this->Session->setFlash(__l('Password could not be changed') , 'default', null, 'error');
                }
            } else {
                $this->Session->setFlash(__l('Password could not be changed') , 'default', null, 'error');
            }
            unset($this->request->data['User']['old_password']);
            unset($this->request->data['User']['passwd']);
            unset($this->request->data['User']['confirm_password']);
            if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
                $this->redirect(array(
                    'action' => 'index'
                ));
            }
        } else {
            if (empty($user_id)) {
                $user_id = $this->Auth->user('id');
            }
        }
        if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
            $users = $this->User->find('list');
            $this->set(compact('users'));
        }
        $this->request->data['User']['user_id'] = (!empty($this->request->data['User']['user_id'])) ? $this->request->data['User']['user_id'] : $user_id;
    }
    public function admin_index()
    {
        $this->_redirectPOST2Named(array(
            'user_type_id',
            'filter_id',
            'q'
        ));
        $this->pageTitle = __l('Users');
        $conditions = array();
        if (!empty($this->request->params['named']['filter_id'])) {
            $this->request->data['User']['filter_id'] = $this->request->params['named']['filter_id'];
        }
		if (!empty($this->request->params['named']['main_filter_id'])) {
            $this->request->data['User']['main_filter_id'] = $this->request->params['named']['main_filter_id'];
        }
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(User.created) <= '] = 0;
            $this->pageTitle.= __l(' - Registered today');
        }
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(User.created) <= '] = 7;
            $this->pageTitle.= __l(' - Registered in this week');
        }
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(User.created) <= '] = 30;
            $this->pageTitle.= __l(' - Registered in this month');
        }
        if (!empty($this->request->data['User']['filter_id'])) {
            if ($this->request->data['User']['filter_id'] == ConstMoreAction::OpenID) {
                $conditions['User.is_openid_register'] = 1;
                $this->pageTitle.= __l(' - Registered through OpenID ');
            } elseif ($this->request->data['User']['filter_id'] == ConstMoreAction::Gmail) {
                $conditions['User.is_gmail_register'] = 1;
                $this->pageTitle.= __l(' - Registered through Gmail ');
            } elseif ($this->request->data['User']['filter_id'] == ConstMoreAction::Yahoo) {
                $conditions['User.is_yahoo_register'] = 1;
                $this->pageTitle.= __l(' - Registered through Yahoo ');
            }  elseif ($this->request->data['User']['filter_id'] == ConstMoreAction::Foursquare) {
                $conditions['User.is_foursquare_register'] = 1;
                $this->pageTitle.= __l(' - Registered through Foursquare ');
            } elseif ($this->request->data['User']['filter_id'] == ConstMoreAction::Active) {
                $conditions['User.is_active'] = 1;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->data['User']['filter_id'] == ConstUserTypes::Admin) {
                $conditions['User.user_type_id'] = 1;
                $this->pageTitle.= __l(' - Admin User ');
            } elseif ($this->request->data['User']['filter_id'] == ConstMoreAction::Site) {
                $conditions['User.is_yahoo_register'] = 0;
                $conditions['User.is_gmail_register'] = 0;
                $conditions['User.is_openid_register'] = 0;
                $conditions['User.is_facebook_register'] = 0;
                $conditions['User.is_twitter_register'] = 0;
                $this->pageTitle.= __l(' - Site ');
            } elseif ($this->request->data['User']['main_filter_id'] == ConstMoreAction::Inactive) {
                $conditions['User.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            } elseif ($this->request->data['User']['filter_id'] == ConstMoreAction::Twitter) {
                $conditions['User.is_twitter_register'] = 1;
                $this->pageTitle.= __l(' - Registered through Twitter ');
            } elseif ($this->request->data['User']['filter_id'] == ConstMoreAction::Facebook) {
                $conditions['User.is_facebook_register'] = 1;
                $this->pageTitle.= __l(' - Registered through Facebook ');
            }
			$this->request->params['named']['filter_id'] = $this->request->data['User']['filter_id'];
        }
		if (!empty($this->request->data['User']['main_filter_id']))
		{
            if ($this->request->data['User']['main_filter_id'] == ConstMoreAction::Inactive)
			{
                $conditions['User.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            }
			else if ($this->request->data['User']['main_filter_id'] == ConstMoreAction::Business)
			{
				$conditions['User.is_business_user'] = 1;
				$this->pageTitle.= __l(' - Business ');
			}


			$this->request->params['named']['main_filter_id'] = $this->request->data['User']['main_filter_id'];
        }

        if (!empty($this->request->params['named']['q'])) {
            $this->request->data['User']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        $this->request->data['User']['user_type_id'] = empty($this->request->params['named']['user_type_id']) ? ConstUserTypes::User : $this->request->params['named']['user_type_id'];
        // condition to list users only
        if (isset($this->request->data['User']['filter_id']) && $this->request->data['User']['filter_id'] != ConstUserTypes::Admin && $this->request->data['User']['filter_id'] !=  ConstMoreAction::Active && $this->request->data['User']['filter_id'] != ConstMoreAction::Site) {
            $conditions['User.user_type_id'] = $this->request->data['User']['user_type_id'];
        }
        $this->User->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
            'fields' => array(
                'User.id',
                'User.created',
                'User.username',
                'User.email',
                'User.tip_points',
                'User.review_count',
                'User.guide_count',
                'User.place_count',
                'User.sighting_count',
                'User.guide_follower_count',
                'User.item_count',
                'User.sighting_flag_count',
                'User.place_follower_count',
                'User.item_follower_count',
                'User.business_update_count',
                'User.user_type_id',
                'User.is_active',
                'User.is_openid_register',
                'User.is_email_confirmed',
                'User.user_openid_count',
                'User.user_login_count',
                'User.signup_ip_id',
                'User.last_login_ip_id',
                'User.user_view_count',
                'User.review_count',
                'User.last_logged_in_time',
                'User.review_comment_count',
                'User.user_follower_count',
                'User.user_following_count',
                'User.is_facebook_register',
                'User.is_twitter_register',
                'User.is_gmail_register',
                'User.is_openid_register',
                'User.is_yahoo_register',
				'User.is_business_user',
				'User.is_foursquare_register',
				'User.business_follower_count',
            ) ,
            'contain' => array(
                'SignupIp' => array(
                    'fields' => array(
                        'SignupIp.id',
                        'SignupIp.ip',
                    )
                ) ,
 				'Business' => array(
					'conditions' => array(
						'Business.is_approved' => ConstBusinessRequests::Accepted
					) ,
					'Attachment'
				) ,
                'Ip' => array(
                    'City' => array(
                        'fields' => array(
                            'City.name',
                        )
                    ) ,
                    'State' => array(
                        'fields' => array(
                            'State.name',
                        )
                    ) ,
                    'Country' => array(
                        'fields' => array(
                            'Country.name',
                            'Country.iso2',
                        )
                    ) ,
                    'Timezone' => array(
                        'fields' => array(
                            'Timezone.name',
                        )
                    ) ,
                    'fields' => array(
                        'Ip.ip',
                        'Ip.latitude',
                        'Ip.longitude'
                    )
                ) ,
                'UserProfile' => array(
                    'Country' => array(
                        'fields' => array(
                            'Country.name',
                            'Country.iso2',
                        )
                    )
                ) ,
                'UserAvatar' => array(
                    'fields' => array(
                        'UserAvatar.id',
                        'UserAvatar.dir',
                        'UserAvatar.filename',
                        'UserAvatar.width',
                        'UserAvatar.height'
                    )
                ) ,
            ) ,
            'limit' => 20,
            'order' => array(
                'User.id' => 'desc'
            )
        );
        if (!empty($this->request->data['User']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->data['User']['q']
            ));
        }
        $this->set('users', $this->paginate());
        $filters = $this->User->isFilterOptions;
        $moreActions = $this->User->moreActions;
        $userTypes = $this->User->UserType->find('list');
        if (!!empty($this->request->data['User']['user_type_id'])) {
            $this->request->data['User']['user_type_id'] = ConstUserTypes::User;
        }
        $this->set(compact('filters', 'moreActions', 'userTypes'));
        $active = $this->User->find('count', array(
            'conditions' => array(
                'User.is_active = ' => 1,
            ) ,
			'recursive' => -1
        ));
		$this->set('active', $active);
		$inactive = $this->User->find('count', array(
            'conditions' => array(
                'User.is_active = ' => 0,
            ) ,
			'recursive' => -1
        ));
        $this->set('inactive', $inactive);
		$site = $this->User->find('count', array(
            'conditions' => array(
                'User.is_facebook_register' => 0,
                'User.is_twitter_register' => 0,
                'User.is_openid_register' => 0,
                'User.is_yahoo_register' => 0,
                'User.is_gmail_register' => 0,
            ) ,
            'recursive' => -1
        ));
        $this->set('site', $site);
		$openid_count = $this->User->find('count', array(
            'conditions' => array(
                'User.is_openid_register = ' => 1,
                'User.user_type_id != ' => ConstUserTypes::Admin
            ) ,
			'recursive' => -1
        ));
        $this->set('openid', $openid_count);
		$facebook_count = $this->User->find('count', array(
            'conditions' => array(
                'User.is_facebook_register !=' => 0,
                'User.user_type_id = ' => ConstUserTypes::User
            ) ,
            'recursive' => -1
        ));
        $this->set('facebook', $facebook_count);
		$twitter_count = $this->User->find('count', array(
            'conditions' => array(
                'User.is_twitter_register !=' => 0,
                'User.user_type_id = ' => ConstUserTypes::User
            ) ,
            'recursive' => -1
        ));
        $this->set('twitter', $twitter_count);
		$gmail_count = $this->User->find('count', array(
            'conditions' => array(
                'User.is_gmail_register !=' => 0,
                'User.user_type_id = ' => ConstUserTypes::User
            ) ,
            'recursive' => -1
        ));
        $this->set('gmail', $gmail_count);
		$yahoo_count =  $this->User->find('count', array(
            'conditions' => array(
                'User.is_yahoo_register !=' => 0,
                'User.user_type_id = ' => ConstUserTypes::User
            ) ,
            'recursive' => -1
        ));
        $this->set('yahoo', $yahoo_count);
		$foursquare_count =  $this->User->find('count', array(
            'conditions' => array(
                'User.is_foursquare_register !=' => 0,
                'User.user_type_id = ' => ConstUserTypes::User
            ) ,
            'recursive' => -1
        ));
        $this->set('foursquare', $foursquare_count);
		$business_count = $this->User->find('count', array(
            'conditions' => array(
                'User.user_type_id' => ConstUserTypes::User,
				'User.is_business_user' => 1,
            ) ,
            'recursive' => -1
        ));
		$this->set('business', $business_count);
		$admin_count = $this->User->find('count', array(
            'conditions' => array(
                'User.user_type_id' => ConstUserTypes::Admin,
            ) ,
            'recursive' => -1
        ));
        $this->set('admin_count', $admin_count);
    }
    public function admin_add()
    {
        $this->pageTitle = __l('Add New User/Admin');
        if (!empty($this->request->data)) {
            $this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['passwd']);
            $this->request->data['User']['is_agree_terms_conditions'] = '1';
            $this->request->data['User']['is_email_confirmed'] = 1;
            $this->request->data['User']['is_active'] = 1;
            $this->request->data['User']['signup_ip_id'] = $this->User->toSaveIp();
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                // Send mail to user to activate the account and send account details
                $emailFindReplace = array(
                    '##SITE_NAME##' => Configure::read('site.name') ,
                    '##SITE_URL##' => Router::url('/', true) ,
                    '##USERNAME##' => $this->request->data['User']['username'],
                    '##LOGINLABEL##' => ucfirst(Configure::read('user.using_to_login')) ,
                    '##USEDTOLOGIN##' => $this->request->data['User'][Configure::read('user.using_to_login') ],
                    '##PASSWORD##' => $this->request->data['User']['passwd']
                );
                $emailTemplate = $this->EmailTemplate->selectTemplate('Admin User Add');
                $this->Email->from = ($emailTemplate['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $emailTemplate['from'];
                $this->Email->to = $this->request->data['User']['email'];
                $this->Email->subject = strtr($emailTemplate['subject'], $emailFindReplace);
                $this->Email->send(strtr($emailTemplate['email_content'], $emailFindReplace));
                $this->Session->setFlash(__l('User has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                unset($this->request->data['User']['passwd']);
                $this->Session->setFlash(__l('User could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        $userTypes = $this->User->UserType->find('list');
        $this->set(compact('userTypes'));
        if (!!empty($this->request->data['User']['user_type_id'])) {
            $this->request->data['User']['user_type_id'] = ConstUserTypes::User;
        }
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->User->delete($id)) {
            $this->Session->setFlash(__l('User has neen deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_update()
    {
        if (!empty($this->request->data['User'])) {
            $r = $this->request->data[$this->modelClass]['r'];
            $actionid = $this->request->data[$this->modelClass]['more_action_id'];
            unset($this->request->data[$this->modelClass]['r']);
            unset($this->request->data[$this->modelClass]['more_action_id']);
            $userIds = array();
            foreach($this->request->data['User'] as $user_id => $is_checked) {
                if ($is_checked['id']) {
                    $userIds[] = $user_id;
                }
            }
            if ($actionid && !empty($userIds)) {
				if ($actionid == ConstMoreAction::Export) {
                    $user_ids = implode(',', $userIds);
                    $hash = $this->User->getUserIdHash($user_ids);
                    $_SESSION['user_export'][$hash] = $userIds;
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'export',
                        'ext' => 'csv',
                        $hash,
                        'admin' => true
                    ));
                } else {
					foreach($userIds as $user_id){
						$data=array();
						$data[$this->modelClass]['id']=$user_id;
						if ($actionid == ConstMoreAction::Inactive) {
							$data[$this->modelClass]['is_active']=0;
							$this->{$this->modelClass}->save($data);
							$this->_sendAdminActionMail($user_id, 'Admin User Deactivate');
							$this->Session->setFlash(__l('Checked users has been inactivated') , 'default', null, 'success');
						} else if ($actionid == ConstMoreAction::Active) {
							$data[$this->modelClass]['is_active']=1;
							$this->{$this->modelClass}->save($data);
							$this->_sendAdminActionMail($user_id, 'Admin User Active');
							$this->Session->setFlash(__l('Checked users has been activated') , 'default', null, 'success');
						} else if ($actionid == ConstMoreAction::Delete) {
							$this->{$this->modelClass}->delete($user_id);
							$this->_sendAdminActionMail($user_id, 'Admin User Delete');
							$this->Session->setFlash(__l('Checked users has been deleted') , 'default', null, 'success');
						}
					}	
				}	
            }
        }
        $this->redirect(Router::url('/', true) . $r);
    }
    public function _sendAdminActionMail($user_id, $email_template)
    {
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $user_id
            ) ,
            'fields' => array(
                'User.username',
                'User.email'
            ) ,
            'recursive' => -1
        ));
        $emailFindReplace = array(
            '##SITE_NAME##' => Configure::read('site.name') ,
            '##SITE_URL##' => Router::url('/', true) ,
            '##USERNAME##' => $user['User']['username'],
        );
        $emailTemplate = $this->EmailTemplate->selectTemplate($email_template);
        $this->Email->from = ($emailTemplate['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $emailTemplate['from'];
        $this->Email->to = $user['User']['email'];
        $this->Email->subject = strtr($emailTemplate['subject'], $emailFindReplace);
        $this->Email->send(strtr($emailTemplate['email_content'], $emailFindReplace));
    }
    /*public function admin_stats()
    {
    $this->pageTitle = __l('Site Stats');
    $periods = array(
    'day' => array(
    'display' => __l('Today') ,
    'conditions' => array(
    'TO_DAYS(NOW()) - TO_DAYS(created) <= ' => 0,
    )
    ) ,
    'week' => array(
    'display' => __l('This week') ,
    'conditions' => array(
    'TO_DAYS(NOW()) - TO_DAYS(created) <= ' => 7,
    )
    ) ,
    'month' => array(
    'display' => __l('This month') ,
    'conditions' => array(
    'TO_DAYS(NOW()) - TO_DAYS(created) <= ' => 30,
    )
    ) ,
    'total' => array(
    'display' => __l('Total') ,
    'conditions' => array()
    )
    );
    $conditions = array();
    $update_colspan_main = 0;
    if (Configure::read('suspicious_detector.is_enabled') != 0) {
    $update_colspan_main = 1;
    }
    $this->loadModel('Guide');
    $this->loadModel('Item');
    $this->loadModel('Place');
    $this->loadModel('Sighting');
    $this->loadModel('Review');
    $models[] = array(
    'User' => array(
    'display' => __l('Users') ,
    'link' => array(
    'controller' => 'users',
    'action' => 'index'
    ) ,
    'colspan' => 17-$update_colspan_main
    )
    );
    $models[] = array(
    'User' => array(
    'display' => __l('Signups') ,
    'isNeedLoop' => false,
    'alias' => 'Users',
    'colspan' => 7
    )
    );
    $models[] = array(
    'User' => array(
    'display' => __l('Site Users') ,
    'conditions' => array(
    'User.is_facebook_register' => 0,
    'User.is_twitter_register' => 0,
    'User.is_openid_register' => 0,
    'User.is_yahoo_register' => 0,
    'User.is_gmail_register' => 0,
    'User.user_type_id != ' => ConstUserTypes::Admin
    ) ,
    'link' => array(
    'controller' => 'users',
    'action' => 'index',
    'filter_id' => ConstMoreAction::Site
    ) ,
    'alias' => 'UserActive',
    'isSub' => 'User'
    )
    );
    $models[] = array(
    'User' => array(
    'display' => __l('Facebook Users') ,
    'link' => array(
    'controller' => 'users',
    'action' => 'index',
    'filter_id' => ConstMoreAction::Facebook
    ) ,
    'conditions' => array(
    'User.is_facebook_register' => 1,
    'User.user_type_id != ' => ConstUserTypes::Admin
    ) ,
    'alias' => 'UserFacebook',
    'isSub' => 'User'
    )
    );
    $models[] = array(
    'User' => array(
    'display' => __l('Twitter Users') ,
    'link' => array(
    'controller' => 'users',
    'action' => 'index',
    'filter_id' => ConstMoreAction::Twitter
    ) ,
    'conditions' => array(
    'User.is_twitter_register' => 1,
    'User.user_type_id != ' => ConstUserTypes::Admin
    ) ,
    'alias' => 'UserTwitter',
    'isSub' => 'User'
    )
    );
    $models[] = array(
    'User' => array(
    'display' => __l('OpenID Users') ,
    'link' => array(
    'controller' => 'users',
    'action' => 'index',
    'filter_id' => ConstMoreAction::OpenID
    ) ,
    'conditions' => array(
    'User.is_openid_register' => 1,
    'User.user_type_id != ' => ConstUserTypes::Admin
    ) ,
    'alias' => 'UserOpendId',
    'isSub' => 'User'
    )
    );
    $models[] = array(
    'User' => array(
    'display' => __l('Gmail Users') ,
    'link' => array(
    'controller' => 'users',
    'action' => 'index',
    'filter_id' => ConstMoreAction::Gmail
    ) ,
    'conditions' => array(
    'User.is_gmail_register' => 1,
    'User.user_type_id != ' => ConstUserTypes::Admin
    ) ,
    'alias' => 'UserGmail',
    'isSub' => 'User'
    )
    );
    $models[] = array(
    'User' => array(
    'display' => __l('Yahoo Users') ,
    'link' => array(
    'controller' => 'users',
    'action' => 'index',
    'filter_id' => ConstMoreAction::Yahoo
    ) ,
    'conditions' => array(
    'User.is_yahoo_register' => 1,
    'User.user_type_id != ' => ConstUserTypes::Admin
    ) ,
    'alias' => 'UserYahoo',
    'isSub' => 'User'
    )
    );
    $models[] = array(
    'User' => array(
    'display' => __l('All') ,
    'link' => array(
    'controller' => 'users',
    'action' => 'index',
    ) ,
    'conditions' => array(
    'User.user_type_id != ' => ConstUserTypes::Admin
    ) ,
    'alias' => 'UserAll',
    'isSub' => 'User'
    )
    );
    $models[] = array(
    'UserLogin' => array(
    'display' => __l('Logins') ,
    'isNeedLoop' => false,
    'alias' => 'Users',
    'colspan' => 7,
    'is_sub' => 'User'
    )
    );
    $models[] = array(
    'UserLogin' => array(
    'display' => __l('Site Users') ,
    'conditions' => array(
    'User.is_facebook_register' => 0,
    'User.is_twitter_register' => 0,
    'User.is_openid_register' => 0,
    'User.is_yahoo_register' => 0,
    'User.is_gmail_register' => 0,
    'User.user_type_id != ' => ConstUserTypes::Admin
    ) ,
    'link' => array(
    'controller' => 'user_logins',
    'action' => 'index',
    'filter_id' => ConstMoreAction::Normal
    ) ,
    'alias' => 'UserLoginNormal',
    'isSub' => 'UserLogin',
    'is_sub' => 'User'
    )
    );
    $models[] = array(
    'UserLogin' => array(
    'display' => __l('Facebook Users') ,
    'link' => array(
    'controller' => 'user_logins',
    'action' => 'index',
    'filter_id' => ConstMoreAction::Facebook
    ) ,
    'conditions' => array(
    'User.is_facebook_register' => 1,
    'User.user_type_id != ' => ConstUserTypes::Admin
    ) ,
    'alias' => 'UserLoginFacebook',
    'isSub' => 'UserLogin',
    'is_sub' => 'User'
    )
    );
    $models[] = array(
    'UserLogin' => array(
    'display' => __l('Twitter Users') ,
    'link' => array(
    'controller' => 'user_logins',
    'action' => 'index',
    'filter_id' => ConstMoreAction::Twitter
    ) ,
    'conditions' => array(
    'User.is_twitter_register' => 1,
    'User.user_type_id != ' => ConstUserTypes::Admin
    ) ,
    'alias' => 'UserLoginTwitter',
    'isSub' => 'UserLogin',
    'is_sub' => 'User'
    )
    );
    $models[] = array(
    'UserLogin' => array(
    'display' => __l('OpenID Users') ,
    'link' => array(
    'controller' => 'user_logins',
    'action' => 'index',
    'filter_id' => ConstMoreAction::OpenID
    ) ,
    'conditions' => array(
    'User.is_openid_register' => 1,
    'User.user_type_id != ' => ConstUserTypes::Admin
    ) ,
    'alias' => 'UserLoginOpendId',
    'isSub' => 'UserLogin',
    'is_sub' => 'User'
    )
    );
    $models[] = array(
    'UserLogin' => array(
    'display' => __l('Gmail Users') ,
    'link' => array(
    'controller' => 'user_logins',
    'action' => 'index',
    'filter_id' => ConstMoreAction::Gmail
    ) ,
    'conditions' => array(
    'User.is_gmail_register' => 1,
    'User.user_type_id != ' => ConstUserTypes::Admin
    ) ,
    'alias' => 'UserLoginGmail',
    'isSub' => 'UserLogin',
    'is_sub' => 'User'
    )
    );
    $models[] = array(
    'UserLogin' => array(
    'display' => __l('Yahoo Users') ,
    'link' => array(
    'controller' => 'user_logins',
    'action' => 'index',
    'filter_id' => ConstMoreAction::Yahoo
    ) ,
    'conditions' => array(
    'User.is_yahoo_register' => 1,
    'User.user_type_id != ' => ConstUserTypes::Admin
    ) ,
    'alias' => 'UserLoginYahoo',
    'isSub' => 'UserLogin',
    'is_sub' => 'User'
    )
    );
    $models[] = array(
    'UserLogin' => array(
    'display' => __l('All') ,
    'link' => array(
    'controller' => 'user_logins',
    'action' => 'index',
    ) ,
    'alias' => 'UserLoginAll',
    'isSub' => 'UserLogin',
    'is_sub' => 'User'
    )
    );
    // Followers //
    $models[] = array(
    'UserFollower' => array(
    'display' => __l('Followers') ,
    'isNeedLoop' => false,
    'alias' => 'UserFollowers',
    'colspan' => 1,
    'is_sub' => 'User'
    )
    );
    $models[] = array(
    'UserFollower' => array(
    'display' => '',
    'link' => array(
    'controller' => 'user_followers',
    'action' => 'index',
    ) ,
    'alias' => 'UserFollowerAll',
    'isSub' => 'UserFollower',
    'is_sub' => 'User'
    )
    );
    // User View //
    $models[] = array(
    'UserView' => array(
    'display' => __l('Views') ,
    'isNeedLoop' => false,
    'alias' => 'UserViews',
    'colspan' => 1,
    'is_sub' => 'User'
    )
    );
    $models[] = array(
    'UserView' => array(
    'display' => '',
    'link' => array(
    'controller' => 'user_views',
    'action' => 'index',
    ) ,
    'alias' => 'UserViewAll',
    'isSub' => 'UserView',
    'is_sub' => 'User'
    )
    );
    // Sighting //
    $models[] = array(
    'Sighting' => array(
    'display' => __l('Sightings') ,
    'link' => array(
    'controller' => 'sightings',
    'action' => 'index'
    ) ,
    'colspan' => 4
    )
    );
    $models[] = array(
    'Sighting' => array(
    'display' => '',
    'isNeedLoop' => false,
    'alias' => 'Sighting',
    'colspan' => 3
    )
    );
    $models[] = array(
    'SightingView' => array(
    'display' => __l('Views') ,
    'link' => array(
    'controller' => 'sighting_views',
    'action' => 'index',
    ) ,
    'alias' => 'SightingViews',
    'isSub' => 'Sighting',
    'is_sub' => 'Sighting'
    )
    );
    $models[] = array(
    'SightingFlag' => array(
    'display' => __l('Flags') ,
    'link' => array(
    'controller' => 'sighting_flags',
    'action' => 'index',
    ) ,
    'alias' => 'SightingFlag',
    'isSub' => 'Sighting',
    'is_sub' => 'Sighting'
    )
    );
    $models[] = array(
    'Sighting' => array(
    'display' => __l('All') ,
    'link' => array(
    'controller' => 'sightings',
    'action' => 'index',
    ) ,
    'alias' => 'SightingAll',
    'isSub' => 'Sighting'
    )
    );
    // Reviews //
    $models[] = array(
    'Review' => array(
    'display' => __l('Reviews') ,
    'link' => array(
    'controller' => 'reviews',
    'action' => 'index'
    ) ,
    'colspan' => 3
    )
    );
    $models[] = array(
    'Review' => array(
    'display' => '',
    'isNeedLoop' => false,
    'alias' => 'Review',
    'colspan' => 2
    )
    );
    $models[] = array(
    'ReviewView' => array(
    'display' => __l('Views') ,
    'link' => array(
    'controller' => 'review_views',
    'action' => 'index',
    ) ,
    'alias' => 'ReviewView',
    'isSub' => 'Review',
    'is_sub' => 'Review'
    )
    );
    $models[] = array(
    'Review' => array(
    'display' => __l('All') ,
    'link' => array(
    'controller' => 'reviews',
    'action' => 'index',
    ) ,
    'alias' => 'ReviewAll',
    'isSub' => 'Review'
    )
    );
    // Item //
    $models[] = array(
    'Item' => array(
    'display' => __l('Items') ,
    'link' => array(
    'controller' => 'items',
    'action' => 'index'
    ) ,
    'colspan' => 3
    )
    );
    $models[] = array(
    'Item' => array(
    'display' => '',
    'isNeedLoop' => false,
    'alias' => 'Items',
    'colspan' => 2
    )
    );
    $models[] = array(
    'ItemFollower' => array(
    'display' => __l('Followers') ,
    'link' => array(
    'controller' => 'item_followers',
    'action' => 'index',
    ) ,
    'alias' => 'ItemFollowers',
    'isSub' => 'Item',
    'is_sub' => 'Item'
    )
    );
    $models[] = array(
    'Item' => array(
    'display' => __l('All') ,
    'link' => array(
    'controller' => 'items',
    'action' => 'index',
    ) ,
    'alias' => 'ItemAll',
    'isSub' => 'Item'
    )
    );
    // Place //
    $models[] = array(
    'Place' => array(
    'display' => __l('Places') ,
    'link' => array(
    'controller' => 'places',
    'action' => 'index'
    ) ,
    'colspan' => 4
    )
    );
    $models[] = array(
    'Place' => array(
    'display' => '',
    'isNeedLoop' => false,
    'alias' => 'places',
    'colspan' => 3
    )
    );
    $models[] = array(
    'PlaceFollower' => array(
    'display' => __l('Followers') ,
    'link' => array(
    'controller' => 'place_followers',
    'action' => 'index',
    ) ,
    'alias' => 'PlaceFollowers',
    'isSub' => 'Place',
    'is_sub' => 'Place'
    )
    );
    $models[] = array(
    'PlaceView' => array(
    'display' => __l('Views') ,
    'link' => array(
    'controller' => 'place_views',
    'action' => 'index',
    ) ,
    'alias' => 'PlaceViews',
    'isSub' => 'Place',
    'is_sub' => 'Place'
    )
    );
    $models[] = array(
    'Place' => array(
    'display' => __l('All') ,
    'link' => array(
    'controller' => 'places',
    'action' => 'index',
    ) ,
    'alias' => 'PlaceAll',
    'isSub' => 'Place'
    )
    );
    // Guide //
    $models[] = array(
    'Guide' => array(
    'display' => __l('Guides') ,
    'link' => array(
    'controller' => 'guides',
    'action' => 'index'
    ) ,
    'colspan' => 6
    )
    );
    $models[] = array(
    'Guide' => array(
    'display' => '',
    'isNeedLoop' => false,
    'alias' => 'Guides',
    'colspan' => 5
    )
    );
    $models[] = array(
    'Guide' => array(
    'display' => __l('Featured') ,
    'conditions' => array(
    'Guide.is_featured' => 1,
    ) ,
    'link' => array(
    'controller' => 'guides',
    'action' => 'index',
    'filter_id' => ConstMoreAction::Featured
    ) ,
    'alias' => 'GuideFeatured',
    'isSub' => 'Guide'
    )
    );
    $models[] = array(
    'Guide' => array(
    'display' => __l('Published') ,
    'conditions' => array(
    'Guide.is_published' => 1,
    ) ,
    'link' => array(
    'controller' => 'guides',
    'action' => 'index',
    'filter_id' => ConstMoreAction::Published
    ) ,
    'alias' => 'GuidePublished',
    'isSub' => 'Guide'
    )
    );
    $models[] = array(
    'GuideFollower' => array(
    'display' => __l('Followers') ,
    'link' => array(
    'controller' => 'guide_followers',
    'action' => 'index',
    ) ,
    'alias' => 'GuideFollower',
    'isSub' => 'Guide',
    'is_sub' => 'Guide'
    )
    );
    $models[] = array(
    'GuideView' => array(
    'display' => __l('Views') ,
    'link' => array(
    'controller' => 'guide_views',
    'action' => 'index',
    ) ,
    'alias' => 'GuideViews',
    'isSub' => 'Guide',
    'is_sub' => 'Guide'
    )
    );
    $models[] = array(
    'Guide' => array(
    'display' => __l('All') ,
    'link' => array(
    'controller' => 'guides',
    'action' => 'index',
    ) ,
    'alias' => 'GuideAll',
    'isSub' => 'Guide'
    )
    );

    foreach($models as $unique_model) {
    foreach($unique_model as $model => $fields) {
    foreach($periods as $key => $period) {
    $conditions = $period['conditions'];
    if (!empty($fields['conditions'])) {
    $conditions = array_merge($periods[$key]['conditions'], $fields['conditions']);
    }
    $aliasName = !empty($fields['alias']) ? $fields['alias'] : $model;
    $new_periods = $period;
    foreach($new_periods['conditions'] as $p_key => $p_value) {
    unset($new_periods['conditions'][$p_key]);
    $new_periods['conditions'][str_replace('created', $model . '.created', $p_key) ] = $p_value;
    }
    $conditions = $new_periods['conditions'];
    if (!empty($fields['conditions'])) {
    $conditions = array_merge($new_periods['conditions'], $fields['conditions']);
    }
    if (!empty($fields['is_sub'])) {
    $this->set($aliasName . $key, $this->{$fields['is_sub']}->{$model}->find('count', array(
    'conditions' => $conditions,
    )));
    } else {
    $this->set($aliasName . $key, $this->{$model}->find('count', array(
    'conditions' => $conditions,
    'recursive' => -1
    )));
    }
    }
    }
    }
    //recently registered users
    $recentUsers = $this->User->find('all', array(
    'conditions' => array(
    'User.is_active' => 1,
    'User.user_type_id != ' => ConstUserTypes::Admin
    ) ,
    'fields' => array(
    'User.username',
    ) ,
    'recursive' => -1,
    'limit' => 10,
    'order' => array(
    'User.id' => 'desc'
    )
    ));
    //recently logged in users
    $loggedUsers = $this->User->find('all', array(
    'conditions' => array(
    'User.is_active' => 1,
    'User.user_type_id != ' => ConstUserTypes::Admin
    ) ,
    'fields' => array(
    'DISTINCT User.username',
    ) ,
    'recursive' => -1,
    'limit' => 10,
    'order' => array(
    'User.last_logged_in_time' => 'desc'
    )
    ));
    //online users
    $onlineUsers = $this->User->CkSession->find('all', array(
    'conditions' => array(
    'CkSession.user_id != ' => 0,
    'User.is_active' => 1,
    'User.user_type_id !=' => ConstUserTypes::Admin
    ) ,
    'contain' => array(
    'User' => array(
    'fields' => array(
    'User.username'
    )
    )
    ) ,
    'recursive' => 1,
    'limit' => 10,
    'order' => array(
    'User.last_logged_in_time' => 'desc'
    )
    ));
    $this->set(compact('loggedUsers', 'recentUsers', 'onlineUsers', 'periods', 'models'));
    }*/
    public function admin_stats()
    {
        $this->pageTitle = __l('Snapshot');
        //recently logged in users
        $this->set('pageTitle', $this->pageTitle);
		$this->set('business_waiting_for_approval', $this->User->Business->find('count', array(
            'conditions' => array(
                'Business.is_approved = ' => ConstBusinessRequests::Pending,
            ) ,
            'recursive' => -1
        )));
		$this->set('place_claim_request_pending', $this->User->Business->PlaceClaimRequest->find('count', array(
            'conditions' => array(
                'PlaceClaimRequest.is_approved = ' => ConstPlaceClaimRequests::Pending,
            )
        )));
    }
    public function admin_change_password($user_id = null)
    {
        $this->pageTitle = __l('Change Password');
        if (!empty($this->request->data)) {
            $this->User->set($this->request->data);
            if ($this->User->validates()) {
                if ($this->User->updateAll(array(
                    'User.password' => '\'' . $this->Auth->password($this->request->data['User']['passwd']) . '\'',
                ) , array(
                    'User.id' => $this->request->data['User']['user_id']
                ))) {
                        $user = $this->User->find('first', array(
                            'conditions' => array(
                                'User.id' => $this->request->data['User']['user_id']
                            ) ,
                            'fields' => array(
                                'User.username',
                                'User.email'
                            ) ,
                            'recursive' => -1
                        ));
                        $emailFindReplace = array(
                            '##SITE_NAME##' => Configure::read('site.name') ,
                            '##SITE_URL##' => Router::url('/', true) ,
                            '##PASSWORD##' => $this->request->data['User']['passwd'],
                            '##USERNAME##' => $user['User']['username'],
                        );
                        $emailTemplate = $this->EmailTemplate->selectTemplate('Admin Change Password');
                        // Send e-mail to users
                        $this->Email->from = ($emailTemplate['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $emailTemplate['from'];
                        $this->Email->to = $user['User']['email'];
                        $this->Email->subject = strtr($emailTemplate['subject'], $emailFindReplace);
                        $this->Email->send(strtr($emailTemplate['email_content'], $emailFindReplace));
                    $this->Session->setFlash(__l('Password has been changed successfully') , 'default', null, 'success');
                } else {
                    $this->Session->setFlash(__l('Password could not be changed') , 'default', null, 'error');
                }
            } else {
                $this->Session->setFlash(__l('Password could not be changed') , 'default', null, 'error');
            }
            unset($this->request->data['User']['old_password']);
            unset($this->request->data['User']['passwd']);
            unset($this->request->data['User']['confirm_password']);
           
        } else {
            if (empty($user_id)) {
                $user_id = $this->Auth->user('id');
            }
        } 
        if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
            $users = $this->User->find('list', array(
                'conditions' => array(
                            'User.is_openid_register' => 0,
                            'User.is_facebook_register' => 0,
							'User.is_twitter_register' => 0,
							'User.is_gmail_register' => 0,
							'User.is_yahoo_register' => 0,
                ) ,
               
                'recursive' => 1
            ));
            $this->set(compact('users'));
        }
        $this->request->data['User']['user_id'] = (!empty($this->request->data['User']['user_id'])) ? $this->request->data['User']['user_id'] : $user_id;
    
    }
    public function admin_send_mail()
    {
        $this->pageTitle = __l('Send Email to Users');
        if (!empty($this->request->data)) {
            $this->User->set($this->request->data);
            if ($this->User->validates()) {
                $conditions = $emails = array();
                $notSendCount = $sendCount = 0;
                if (!empty($this->request->data['User']['send_to'])) {
                    $sendTo = explode(',', $this->request->data['User']['send_to']);
                    foreach($sendTo as $email) {
                        $email = trim($email);
                        if (!empty($email)) {
                            if ($this->User->find('count', array(
                                'conditions' => array(
                                    'User.email' => $email
                                ) ,
								'recursive' => -1
                            ))) {
                                $emails[] = $email;
                                $sendCount++;
                            } else {
                                $notSendCount++;
                            }
                        }
                    }
                }
                if (!empty($this->request->data['User']['bulk_mail_option_id'])) {
                    if ($this->request->data['User']['bulk_mail_option_id'] == 2) {
                        $conditions['User.is_active'] = 0;
                    }
                    if ($this->request->data['User']['bulk_mail_option_id'] == 3) {
                        $conditions['User.is_active'] = 1;
                    }
                    if ($this->data['User']['bulk_mail_option_id'] == 4) {
                        $conditions['UserProfile.gender_id'] = ConstGenders::Male;
                    }
                    if ($this->data['User']['bulk_mail_option_id'] == 5) {
                        $conditions['UserProfile.gender_id'] = ConstGenders::Female;
                    }
                    if ($this->data['User']['bulk_mail_option_id'] == 6) {
                        $conditions['User.is_facebook_register !='] = 0;
                        $conditions['User.user_type_id = '] = ConstUserTypes::User;
                    }
                    if ($this->data['User']['bulk_mail_option_id'] == 7) {
                        $conditions['User.is_gmail_register !='] = 0;
                        $conditions['User.user_type_id = '] = ConstUserTypes::User;
                    }
                    if ($this->data['User']['bulk_mail_option_id'] == 8) {
                        $conditions['User.is_twitter_register !='] = 0;
                        $conditions['User.user_type_id = '] = ConstUserTypes::User;
                    }
                    if ($this->data['User']['bulk_mail_option_id'] == 9) {
                        $conditions['User.is_openid_register !='] = 0;
                        $conditions['User.user_type_id = '] = ConstUserTypes::User;
                    }
                    if ($this->data['User']['bulk_mail_option_id'] == 10) {
                        $conditions['User.is_gmail_register !='] = 0;
                        $conditions['User.user_type_id = '] = ConstUserTypes::User;
                    }
                    $users = $this->User->find('all', array(
                        'conditions' => $conditions,
                        'fields' => array(
                            'User.email'
                        ) ,
                        'recursive' => -1
                    ));
                    if (!empty($users)) {
                        $sendCount++;
                        foreach($users as $user) {
                            $emails[] = $user['User']['email'];
                        }
                    }
                }
                if (!empty($emails)) {
                    foreach($emails as $email) {
                        if (!empty($email)) {
                            $this->Email->from = Configure::read('EmailTemplate.no_reply_email');
                            $this->Email->to = trim($email);
                            $this->Email->subject = $this->request->data['User']['subject'];
                            $this->Email->send($this->request->data['User']['message']);
                        }
                    }
                }
                if ($sendCount && !$notSendCount) {
                    $this->Session->setFlash(__l('Email sent successfully') , 'default', null, 'success');
                } elseif ($sendCount && $notSendCount) {
                    $this->Session->setFlash(__l('Email sent successfully. Some emails are not sent') , 'default', null, 'success');
                } else {
                    $this->Session->setFlash(__l('No email send') , 'default', null, 'success');
                }
            }
        }
        $bulkMailOptions = $this->User->bulkMailOptions;
        $this->set(compact('bulkMailOptions'));
    }
    public function admin_login()
    {
        $this->setAction('login');
    }
    public function admin_logout()
    {
        $this->setAction('logout');
    }
    public function admin_export($hash = null)
    {
    
        $conditions = array();
        if (!empty($hash) && !empty($_SESSION['user_export'][$hash])) {
            $user_ids = implode(',', $_SESSION['user_export'][$hash]);
            if ($this->User->isValidUserIdHash($user_ids, $hash)) {
                $conditions['User.id'] = $_SESSION['user_export'][$hash];
            } else {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $users = $this->User->find('all', array(
            'conditions' => $conditions,
            'fields' => array(
                'User.username',
                'User.email',
                'User.tip_points',
                'User.sighting_count',
                'User.review_count',
                'User.review_comment_count',
                'User.place_count',
                'User.item_count',
                'User.guide_count',
                'User.business_follower_count',
                'User.place_follower_count',
                'User.item_follower_count',
                'User.user_follower_count',
                'User.guide_follower_count',
                'User.user_following_count',
                'User.user_login_count',
                'User.last_logged_in_time',
                'User.created',
                'User.user_openid_count',
                'User.user_login_count',
            ) ,
            'recursive' => -1
        ));
        Configure::write('debug', 0);
        if (!empty($users)) {
            foreach($users as $user) {
                $data[]['User'] = array(
                    'Username' => $user['User']['username'],
                    'Email' => $user['User']['email'],
                    'Points' => $user['User']['tip_points'],
                    'Sighting Count' => $user['User']['sighting_count'],
                    'Review Count' => $user['User']['review_count'],
                    'Review Comment Count' => $user['User']['review_comment_count'],
                    'Place Count' => $user['User']['place_count'],
                    'Item Count' => $user['User']['item_count'],
                    'Guide Count' => $user['User']['guide_count'],
                    'Business Follower Count' => $user['User']['business_follower_count'],
                    'Place Follower Count' => $user['User']['place_follower_count'],
                    'Item Follower Count' => $user['User']['item_follower_count'],
                    'User Follower Count' => $user['User']['user_follower_count'],
                    'Guide Follower Count' => $user['User']['guide_follower_count'],
                    'Following Count' => $user['User']['user_following_count'],
                    'Login Count' => $user['User']['user_login_count'],
                    'Login Time' => $user['User']['last_logged_in_time'],
                    'Registered On' => $user['User']['created'],
                    'OpenID count' => $user['User']['user_openid_count'],
                );
            }
        }
        $this->set('data', $data);
    }
    public function dashboard()
    {
        $this->pageTitle = __l('User Dashboard');
    }
    public function admin_diagnostics()
    {
        $this->pageTitle = __l('Diagnostics');
        $this->set('pageTitle', $this->pageTitle);
    }
    public function admin_recent_users()
    {
        //recently registered users
        $recentUsers = $this->User->find('all', array(
            'conditions' => array(
                'User.is_active' => 1,
                'User.user_type_id != ' => ConstUserTypes::Admin
            ) ,
            'fields' => array(
                'User.user_type_id',
                'User.username',
                'User.id',
            ) ,
            'recursive' => -1,
            'limit' => 10,
            'order' => array(
                'User.id' => 'desc'
            )
        ));
        $this->set(compact('recentUsers'));
    }
    public function admin_online_users()
    {
        //online users
        $onlineUsers = $this->User->find('all', array(
            'conditions' => array(
                'User.is_active' => 1,
                'CkSession.user_id != ' => 0,
                'User.user_type_id != ' => ConstUserTypes::Admin
            ) ,
            'contain' => array(
                'CkSession' => array(
                    'fields' => array(
                        'CkSession.user_id'
                    )
                )
            ) ,
            'fields' => array(
                'DISTINCT User.username',
                'User.user_type_id',
                'User.id',
            ) ,
            'recursive' => 0,
            'limit' => 10,
            'order' => array(
                'User.last_logged_in_time' => 'desc'
            )
        ));
        $this->set(compact('onlineUsers'));
    }
    public function sidebar($username)
    {
        $this->pageTitle = __l('User Sidebar');
        if (is_null($username)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.username = ' => $username,
                'User.is_active = ' => 1
            ) ,
            'contain' => array(
                'UserProfile' => array(
                    'fields' => array(
                        'UserProfile.first_name',
                        'UserProfile.last_name',
                        'UserProfile.middle_name',
                        'UserProfile.about_me',
                        'UserProfile.dob',
                        'UserProfile.address',
                        'UserProfile.zip_code',
                    ) ,
                    'Gender' => array(
                        'fields' => array(
                            'Gender.name'
                        )
                    ) ,
                    'City' => array(
                        'fields' => array(
                            'City.name'
                        )
                    ) ,
                    'State' => array(
                        'fields' => array(
                            'State.name'
                        )
                    ) ,
                    'Country' => array(
                        'fields' => array(
                            'Country.name'
                        )
                    )
                ) ,
                'UserAvatar' => array(
                    'fields' => array(
                        'UserAvatar.id',
                        'UserAvatar.dir',
                        'UserAvatar.filename',
                        'UserAvatar.width',
                        'UserAvatar.height'
                    )
                ) ,
            ) ,
            'fields' => array(
                'User.id',
                'User.username',
                'User.email',
                'User.place_follower_count',
                'User.item_follower_count',
                'User.guide_count',
                'User.guide_follower_count',
				'User.user_follower_count',
                'User.user_type_id',
                'User.tip_points'
            ) ,
            'recursive' => 2
        ));
        $user_follow = $this->User->UserFollower->find('all', array(
            'conditions' => array(
                'UserFollower.follower_user_id = ' => $this->Auth->user('id') ,
                'User.Username' => $username,
            ) ,
        ));
        if (empty($user)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->pageTitle.= ' - ' . $username;
        $this->set('user', $user);
        $this->set('user_follow', $user_follow);
    }
    public function profile_image($id = null)
    {
        if (!empty($this->request->data['User']['id'])) {
            $id = $this->request->data['User']['id'];
        }
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $id
            ) ,
            'contain' => array(
                'UserAvatar' => array(
                    'fields' => array(
                        'UserAvatar.id',
                        'UserAvatar.filename',
                        'UserAvatar.dir',
                        'UserAvatar.width',
                        'UserAvatar.height'
                    )
                )
            ) ,
            'recursive' => 0
        ));
        if (empty($user)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->pageTitle = $user['User']['username'] . ' - ' . __l('Profile Image');
        $this->User->UserAvatar->Behaviors->attach('ImageUpload', Configure::read('avatar.file'));
        if (!empty($this->request->data)) {
            if (!empty($this->request->data['UserAvatar']['filename']['name'])) {
                $this->request->data['UserAvatar']['filename']['type'] = get_mime($this->request->data['UserAvatar']['filename']['tmp_name']);
            }
            if (!empty($this->request->data['UserAvatar']['filename']['name']) || (!Configure::read('avatar.file.allowEmpty') && empty($this->request->data['UserAvatar']['id']))) {
                $this->User->UserAvatar->set($this->request->data);
            }
            $ini_upload_error = 1;
            if (isset($this->request->data['UserAvatar']['filename'])) {
                if ($this->request->data['UserAvatar']['filename']['error'] == 1) {
                    $ini_upload_error = 0;
                }
            }
            if ($this->User->UserAvatar->validates() && $ini_upload_error) {
                if (!empty($this->request->data['UserAvatar']['filename']['name'])) {
                    $this->Attachment->delete($user['UserAvatar']['id']);
                    $this->Attachment->create();
                    $this->request->data['UserAvatar']['class'] = 'UserAvatar';
                    $this->request->data['UserAvatar']['foreign_id'] = $this->request->data['User']['id'];
                    $this->Attachment->save($this->request->data['UserAvatar']);
                    $this->request->data['User']['profile_image_id'] = ConstProfileImage::Upload;
                }
                $this->User->save($this->request->data, false);
                if (!empty($this->request->data['User']['profile_image_id'])) {
                    $this->Session->setFlash(__l('User Profile Image has been updated') , 'default', null, 'success');
                }
                if ($this->Auth->user('user_type_id') == ConstUserTypes::Company) {
                    $this->redirect(array(
                        'controller' => 'companies',
                        'action' => 'dashboard',
                        'admin' => false
                    ));
                } else {
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'my_stuff',
                        'admin' => false,
                    ));
                }
            }
        } else {
            $this->request->data = $user;
        }
        $profileimages = array(
            ConstProfileImage::Twitter => ConstProfileImage::Twitter,
            ConstProfileImage::Facebook => ConstProfileImage::Facebook,
            ConstProfileImage::Upload => ConstProfileImage::Upload
        );
        $this->set('profileimages', $profileimages);
        $fb_return_url = Router::url(array(
            'controller' => 'users',
            'action' => 'connect',
            $id,
            'admin' => false
        ) , true);
        $this->Session->write('fb_return_url', $fb_return_url);
        App::import('Vendor', 'facebook/facebook');
        $this->facebook = new Facebook(array(
            'appId' => Configure::read('facebook.fb_app_id') ,
            'secret' => Configure::read('facebook.fb_secrect_key') ,
            'cookie' => true
        ));
        $this->set('fb_login_url', $this->facebook->getLoginUrl(array(
            'redirect_uri' => Router::url(array(
                'controller' => 'users',
                'action' => 'oauth_facebook',
                'admin' => false
            ) , true) ,
            'scope' => 'email,publish_stream'
        )));
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
            $this->set('fs_login_url', $redirect_url);
        if (!empty($this->request->params['named']['connect']) && $this->request->params['named']['connect'] == 'linked_accounts') {

            $this->render('linked_accounts');
        }
    }
    public function connect($id)
    {
        $this->pageTitle = __l('Connect');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.id = ' => $id
            ) ,
            'recursive' => -1,
        ));
        if (empty($user)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->request->data = $user;
        $type = '';
        $c_action = '';
        if (!empty($this->request->params['named']['type'])) {
            $type = $this->request->params['named']['type'];
        }
        if (!empty($this->request->params['named']['c_action'])) {
            $c_action = $this->request->params['named']['c_action'];
        }
        if ($type == 'facebook' && $c_action == 'disconnect') {
            $this->request->data['User']['id'] = $id;
            $this->request->data['User']['fb_user_id'] = '';
            $this->request->data['User']['fb_access_token'] = '';
            $this->User->Save($this->request->data['User'], false);
            $this->Session->setFlash(__l('You have successfully disconnected with facebook.') , 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'user_points',
                'action' => 'index',
                'admin' => false,
            ));
        }
        if ($type == 'twitter' && $c_action == 'disconnect') {
            $this->request->data['User']['id'] = $id;
            $this->request->data['User']['twitter_user_id'] = '';
            $this->request->data['User']['twitter_access_key'] = '';
            $this->request->data['User']['twitter_access_token'] = '';
            $this->request->data['User']['twitter_avatar_url'] = '';
            $this->User->Save($this->request->data['User'], false);
            $this->Session->setFlash(__l('You have successfully disconnected with twitter.') , 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'user_points',
                'action' => 'index',
                'admin' => false,
            ));
        }
        if ($type == 'foursquare' && $c_action == 'disconnect') {
            $this->request->data['User']['id'] = $id;
            $this->request->data['User']['foursquare_user_id'] = '';
            $this->request->data['User']['foursquare_access_token'] = '';
            $this->User->Save($this->request->data['User'], false);
            $this->Session->setFlash(__l('You have successfully disconnected with foursquare.') , 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'user_points',
                'action' => 'index',
                'admin' => false,
            ));
        } elseif ($type == 'twitter') {
            $requestToken = $this->OauthConsumer->getRequestToken('Twitter', 'http://twitter.com/oauth/request_token');
            $twitter_return_url = Router::url(array(
                'controller' => 'users',
                'action' => 'oauth_callback',
                'admin' => false
            ) , true);
            $requestToken = $this->OauthConsumer->getRequestToken('Twitter', 'https://api.twitter.com/oauth/request_token', $twitter_return_url);
            $this->Session->write('requestToken', serialize($requestToken));
            $this->Session->write('auth_user_id', $id);
            $this->redirect('http://twitter.com/oauth/authorize?oauth_token=' . $requestToken->key);
        }
        if (!empty($_GET)) {
            App::import('Vendor', 'facebook/facebook');
            $this->facebook = new Facebook(array(
                'appId' => Configure::read('facebook.fb_app_id') ,
                'secret' => Configure::read('facebook.fb_secrect_key') ,
                'cookie' => true
            ));
            $this->_facebook_login($id);
        }
    }
    public function user_follow(){
        if ($this->RequestHandler->prefers('json')) {
            $this->view = 'Json';
            $follows_array['follow']['count']=$this->User->find('first',array(
                'conditions'=>array(
                    'User.username'=>$this->Auth->user('username')
                ),
                'fields'=>array(
                    'User.place_follower_count',
                    'User.item_follower_count',
                    'User.guide_follower_count',
                     'User.user_following_count',
                ),
                'recursive'=>-1
            ));
            $follows_array['follow']['place']=$this->requestAction(array('controller' => 'place_followers', 'action' => 'index', 'user' => $this->Auth->user('username'), 'type'=>'json'), array('return'));
            $follows_array['follow']['guide']=$this->requestAction(array('controller' => 'guide_followers', 'action' => 'index', 'user' => $this->Auth->user('username'), 'type'=>'json'), array('return'));
            $item_follows=$this->requestAction(array('controller' => 'item_followers', 'action' => 'index', 'user' => $this->Auth->user('username'), 'type'=>'json'), array('return'));
            $follows_array['follow']['user']=$this->requestAction(array('controller' => 'user_followers', 'action' => 'index', 'following' => $this->Auth->user('username'), 'type'=>'json'), array('return'));
            $conditions=array();
            foreach($follows_array['follow']['place'] as $key=>$place_follow){
                unset($follows_array['follow']['place'][$key]['PlaceFollower']);
                unset($follows_array['follow']['place'][$key]['User']);
            }
            foreach($follows_array['follow']['guide'] as $key=>$guide_follow){
                unset($follows_array['follow']['guide'][$key]['GuideFollower']);
                unset($follows_array['follow']['guide'][$key]['User']);
                $follows_array['follow']['guide'][$key]['images'] = $this->_iphoneImageURLCreate('Guide', $guide_follow['Guide']['Attachment'], $guide_follow['Guide']['name']);
                unset($follows_array['follow']['guide'][$key]['Guide']['Attachment']);
            }
            foreach($follows_array['follow']['user'] as $key=>$user_follow){
                unset($follows_array['follow']['user'][$key]['UserFollower']);
                unset($follows_array['follow']['user'][$key]['FollowerUser']);
                unset($follows_array['follow']['user'][$key]['Ip']);
                $follows_array['follow']['user'][$key]['UserAvatar'] = $this->_iphoneImageURLCreate('UserAvatar', $user_follow['User']['UserAvatar'], $user_follow['User']['username']);
                unset($follows_array['follow']['user'][$key]['User']['UserAvatar']);
            }
            if(!empty($item_follows)){
                foreach($item_follows as $item_follow){
                    $item_ids=$item_follow['ItemFollower']['item_id'];
                }
                $conditions['Sighting.item_id']=$item_ids;
            }
            else{
                $conditions['Sighting.item_id']=0;
            }
            $this->loadModel('Sighting');
                $sightings=$this->Sighting->find('all',array(
                    'conditions'=>$conditions,
                    'contain'=>array(
                        'Item' => array(
                        'fields' => array(
                            'Item.name',
                            'Item.slug',
                            'Item.item_follower_count',
                            'Item.place_count',
                        )
                    ) ,
                    'Place' => array(
                        'fields' => array(
                            'Place.id',
                            'Place.latitude',
                            'Place.longitude',
                            'Place.name',
                            'Place.slug',
                            'Place.address1',
                            'Place.place_follower_count',
                            'Place.item_count',
                            'Place.place_view_count',
                        ) ,
                        'City' => array(
                            'fields' => array(
                                'City.name'
                            )
                        ) ,
                        'State' => array(
                            'fields' => array(
                                'State.name'
                            )
                        ) ,
                        'Country' => array(
                            'fields' => array(
                                'Country.name'
                            )
                        )
                    ) ,
                    'Review' => array(
                        'fields' => array(
                            'Review.id',
                        ) ,
                        'Attachment',
                        'User' => array(
                            'fields' => array(
                                'User.id',
                                'User.username',
                                'User.user_follower_count',
                                'User.user_following_count',
                                'User.review_count',
                                'User.review_follower_count',
                                'User.review_comment_count',
                                'User.guide_count',
                                'User.guide_follower_count',
                                'User.place_follower_count',
                                'User.want_count',
                            ) ,
                            'UserAvatar',
                        ),
                    ),
                    'SightingRatingStat'
                    )
                ));
               // pr($sightings);
               // exit;
            $follows_array['follow']['Sighting']=$sightings;
            foreach($follows_array['follow']['Sighting'] as $key => $sightings) {
                        $follows_array['follow']['Sighting'][$key]['Sighting']['nom_it_count']=0;
                        $follows_array['follow']['Sighting'][$key]['Sighting']['want_it_count']=0;
    					$follows_array['follow']['Sighting'][$key]['Sighting']['tried_it_count']=0;
                        if(!empty($sightings['SightingRatingStat'])){
                                foreach($sightings['SightingRatingStat'] as $sighting_rating_stat){
                                        if($sighting_rating_stat['sighting_rating_type_id']==1){
                                            $follows_array['follow']['Sighting'][$key]['Sighting']['nom_it_count']=$sighting_rating_stat['count'];
                                        }
                                        if($sighting_rating_stat['sighting_rating_type_id']==2){
                                            $follows_array['follow']['Sighting'][$key]['Sighting']['want_it_count']=$sighting_rating_stat['count'];
                                        }
    									if($sighting_rating_stat['sighting_rating_type_id']==3){
                                            $follows_array['follow']['Sighting'][$key]['Sighting']['tried_it_count']=$sighting_rating_stat['count'];
                                        }
                                }
                        }
                        unset($sightings['SightingRatingStat']);
                        unset($follows_array['follow']['Sighting'][$key]['SightingRatingStat']);
                        foreach($sightings['Review'] as $key_review => $review) {
                            $follows_array['follow']['Sighting'][$key]['Review'][$key_review]['image'] = $this->_iphoneImageURLCreate('Review', $review['Attachment'], $sightings['Item']['name']);
                            $follows_array['follow']['Sighting'][$key]['Review'][$key_review]['UserAvatar'] = $this->_iphoneImageURLCreate('UserAvatar', $review['User']['UserAvatar'], $review['User']['username']);
                            unset($follows_array['follow']['Sighting'][$key]['Review'][$key_review]['Attachment']);
                            unset($follows_array['follow']['Sighting'][$key]['Review'][$key_review]['User']['UserAvatar']);
                        }
                    }
                $this->set('json', (empty($this->viewVars['iphone_response'])) ? $follows_array : $this->viewVars['iphone_response']);
        }
    }
    public function _iphoneImageURLCreate($model, $attachemnt, $title)
    {
        $images = array();
        $image_options_big = array(
            'dimension' => 'iphone_big_thumb',
            'class' => '',
            'alt' => $title,
            'title' => $title,
            'type' => 'jpg'
        );
        $image_options_small = array(
            'dimension' => 'iphone_small_thumb',
            'class' => '',
            'alt' => $title,
            'title' => $title,
            'type' => 'jpg'
        );
        $image_options_guide_small = array(
            'dimension' => 'iphone_guide_small_thumb',
            'class' => '',
            'alt' => $title,
            'title' => $title,
            'type' => 'jpg'
        );
        $image_options_micro = array(
            'dimension' => 'iphone_micro_thumb',
            'class' => '',
            'alt' => $title,
            'title' => $title,
            'type' => 'jpg'
        );
        $image_options_review_big = array(
            'dimension' => 'iphone_review_big_thumb',
            'class' => '',
            'alt' => $title,
            'title' => $title,
            'type' => 'jpg'
        );
        $image_options_map_small = array(
            'dimension' => 'map_small_thumb',
            'class' => '',
            'alt' => $title,
            'title' => $title,
            'type' => 'jpg'
        );
        $images['iphone_big_thumb'] = Router::url('/', true) . $this->getImageUrl($model, $attachemnt, $image_options_big);
        $images['iphone_small_thumb'] = Router::url('/', true) . $this->getImageUrl($model, $attachemnt, $image_options_small);
        $images['iphone_micro_thumb'] = Router::url('/', true) . $this->getImageUrl($model, $attachemnt, $image_options_micro);
        $images['iphone_guide_small_thumb'] = Router::url('/', true) . $this->getImageUrl($model, $attachemnt, $image_options_guide_small);
        $images['iphone_review_big_thumb'] = Router::url('/', true) . $this->getImageUrl($model, $attachemnt, $image_options_review_big);
        $images['map_small_thumb'] = Router::url('/', true) . $this->getImageUrl($model, $attachemnt, $image_options_map_small);
        return $images;
    }
    public function userview($username){
        if ($this->RequestHandler->prefers('json')) {
            $this->view = 'Json';
            $user_view['user_view']['user']=$this->requestAction(array('controller' => 'users', 'action' => 'view',$username, 'type'=>'json'), array('return'));
            $nom_it_count=0;
            $want_it_count=0;
            $tried_it_count=0;
            foreach($user_view['user_view']['user']['UserSightingRatingStat'] as $key1=>$user_sighting){
                if($user_sighting['sighting_rating_type_id']==1){
                    $nom_it_count++;
                }
                if($user_sighting['sighting_rating_type_id']==2){
                    $want_it_count++;
                }
                if($user_sighting['sighting_rating_type_id']==3){
                    $tried_it_count++;
                }
            }
            $great_shot_count=0;
            $great_find_count=0;
            foreach($user_view['user_view']['user']['UserReviewRatingStat'] as $key1=>$user_review){
                if($user_review['review_rating_type_id']==1){
                    $great_shot_count++;
                }
                if($user_review['review_rating_type_id']==2){
                    $great_find_count++;
                }
            }
            unset($user_view['user_view']['user']['UserSightingRatingStat']);
            unset($user_view['user_view']['user']['UserReviewRatingStat']);
            unset($user_view['user_view']['user']['UserFollower']);
            $user_view['user_view']['user']['User']['nom_it_count']=$nom_it_count;
            $user_view['user_view']['user']['User']['want_it_count']=$want_it_count;
            $user_view['user_view']['user']['User']['tried_it_count']=$tried_it_count;
            $user_view['user_view']['user']['User']['great_shot_count']=$great_shot_count;
            $user_view['user_view']['user']['User']['great_find_count']=$great_find_count;
            $user_view['user_view']['notifications']=$this->requestAction(array('controller' => 'user_points', 'action' => 'index', 'type'=>'json', 'user'=> $username), array('return'));
			$user_view['user_view']['user']['UserAvatar'] = $this->_iphoneImageURLCreate('UserAvatar', $user_view['user_view']['user']['UserAvatar'],$user_view['user_view']['user']['User']['username']);

        }
		$contain = array(
                'Item' => array(
                    'fields' => array(
                        'Item.name',
                        'Item.slug',
                        'Item.item_follower_count',
                        'Item.place_count',
                    )
                ) ,
                'Place' => array(
                    'fields' => array(
                        'Place.id',
                        'Place.latitude',
                        'Place.longitude',
                        'Place.name',
                        'Place.slug',
                        'Place.address1',
                        'Place.place_follower_count',
                        'Place.item_count',
                        'Place.place_view_count',
                    ) ,
                    'City' => array(
                        'fields' => array(
                            'City.name'
                        )
                    ) ,
                    'State' => array(
                        'fields' => array(
                            'State.name'
                        )
                    ) ,
                    'Country' => array(
                        'fields' => array(
                            'Country.name'
                        )
                    )
                ) ,
                'Review' => array(
                    'fields' => array(
                        'Review.id',
						'Review.created',
                    ) ,
                    'Attachment',
                    'User' => array(
                        'fields' => array(
                            'User.id',
                            'User.username',
                            'User.user_follower_count',
                            'User.user_following_count',
                            'User.review_count',
                            'User.review_follower_count',
                            'User.review_comment_count',
                            'User.guide_count',
                            'User.guide_follower_count',
                            'User.place_follower_count',
                        ) ,
                        'UserAvatar',
                    ),
                ),
                'SightingRatingStat'
            );

		$user = $this->User->find('first', array(
            'conditions' => array(
                'User.username = ' => $username,
            ) ,
			'fields' => array(
				'User.id'
			)
		));	
		$user_sightings = $this->User->Sighting->find('all',array(
                'conditions'=>array(
                    'Sighting.user_id'=>$user['User']['id']
                ),
				'contain' => $contain,
         ));
		 $user_view['user_view']['Sighting']=$user_sightings;
		foreach($user_sightings as $key => $user_sighting) {
			$user_view['user_view']['Sighting'][$key]['Sighting']['nom_it_count']=0;
			$user_view['user_view']['Sighting'][$key]['Sighting']['want_it_count']=0;
			$user_view['user_view']['Sighting'][$key]['Sighting']['tried_it_count']=0;
			if(!empty($user_sighting['SightingRatingStat'])){
					foreach($user_sighting['SightingRatingStat'] as $sighting_rating_stat){
							if($sighting_rating_stat['sighting_rating_type_id']==1){
								$user_view['user_view']['Sighting'][$key]['Sighting']['nom_it_count']=$sighting_rating_stat['count'];
							}
							if($sighting_rating_stat['sighting_rating_type_id']==2){
								$user_view['user_view']['Sighting'][$key]['Sighting']['want_it_count']=$sighting_rating_stat['count'];
							}
							if($sighting_rating_stat['sighting_rating_type_id']==3){
								$user_view['user_view']['Sighting'][$key]['Sighting']['tried_it_count']=$sighting_rating_stat['count'];
							}
					}
			}
			unset($user_sighting['SightingRatingStat']);
			foreach($user_sighting['Review'] as $key_review => $review) {
				$user_view['user_view']['Sighting'][$key]['Review'][$key_review]['image'] = $this->_iphoneImageURLCreate('Review', $review['Attachment'], $user_sighting['Item']['name']);
				$user_view['user_view']['Sighting'][$key]['Review'][$key_review]['UserAvatar'] = $this->_iphoneImageURLCreate('UserAvatar', $user_sighting['User']['UserAvatar'], $user_sighting['User']['username']);
			}
		}
		
        foreach($user_view['user_view']['notifications'] as $key=>$notification){
            $user_view['user_view']['notifications'][$key]['message']=$this->_notificationDescription($notification);
			$user_view['user_view']['notifications'][$key]['time']=$notification['UserPoint']['created'];
            $user_view['user_view']['notifications'][$key]['UserAvatar']=$this->_iphoneImageURLCreate('UserAvatar', $notification['OtherUser']['UserAvatar'], $notification['OtherUser']['username']);
            unset($user_view['user_view']['notifications'][$key]['UserPoint']);
            unset($user_view['user_view']['notifications'][$key]['OtherUser']['UserAvatar']);
            unset($user_view['user_view']['notifications'][$key]['ReviewRating']);
            unset($user_view['user_view']['notifications'][$key]['SightingRating']);
            unset($user_view['user_view']['notifications'][$key]['ReviewComment']);
            unset($user_view['user_view']['notifications'][$key]['UserFollower']);
        }
        $this->set('json', (empty($this->viewVars['iphone_response'])) ? $user_view : $this->viewVars['iphone_response']);
    }
    function _notificationDescription($userPoint)
    {
            $str = '';
        if ($userPoint['UserPoint']['model'] == 'ReviewRating') {
            $str = ' ' . __l('says,') . ' ' . '<span>"' . $userPoint['ReviewRating']['ReviewRatingType']['name'] . '!"</span> ' . __l('about') . ' ' . $userPoint['ReviewRating']['Review']['Sighting']['Item']['name'] . " @ " . $userPoint['ReviewRating']['Review']['Sighting']['Place']['name'];
        } else if ($userPoint['UserPoint']['model'] == 'SightingRating') {
            $str = '<span>' . $userPoint['SightingRating']['SightingRatingType']['name'] . '</span> ' . " @ " .  $userPoint['SightingRating']['Sighting']['Item']['name'] . ' ' . __l('based on your sighting');
        } else if ($userPoint['UserPoint']['model'] == 'ReviewComment') {
            $str = ' <span>' . __l('commented ') . '</span> ' .  __l('on') . $userPoint['ReviewComment']['Review']['Sighting']['Item']['name'] . " @ " . $userPoint['ReviewComment']['Review']['Sighting']['Place']['name'];
        } else if ($userPoint['UserPoint']['model'] == 'UserFollower') {
            $str = ' ' . __l('started following you!');
        }  else if ($userPoint['UserPoint']['model'] == 'Sighting') {
            $str = ' ' . __l('New sighting has been added!');
		}
        return $str;
    }
}

?>
