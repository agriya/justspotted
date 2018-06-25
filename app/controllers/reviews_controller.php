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
class ReviewsController extends AppController
{
    public $name = 'Reviews';
    public function beforeFilter()
    {
        $this->Security->disabledFields = array(
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
        $this->pageTitle = __l('Reviews');
        $conditions = array();
        $group = array();
        $limit = 20;
        if (!empty($this->request->params['named']['view']) && $this->request->params['named']['view'] == 'my_popular') {
            $limit = 4;
        }
        $conditions['Review.admin_suspend !='] = 1;
        if (!empty($this->request->params['named']['sighting_id'])) {
            $conditions['Review.sighting_id'] = $this->request->params['named']['sighting_id'];
        }
        if (isset($this->request->params['named']['user'])) {
            $user = $this->Review->User->find('first', array(
                'conditions' => array(
                    'User.username' => $this->request->params['named']['user']
                ) ,
                'recursive' => -1
            ));
            if (empty($user)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['Review.user_id'] = $user['User']['id'];
        }
        if (!empty($this->request->params['named']['user']) && !empty($this->request->params['named']['guide'])) {
            $conditions['Review.user_id'] = $this->Auth->user('id');
        }
        if (!empty($this->request->params['named']['guide'])) {
            $guide = $this->Review->Sighting->Guide->find('first', array(
                'conditions' => array(
                    'Guide.slug' => $this->request->params['named']['guide']
                ) ,
                'recursive' => -1
            ));
            if (empty($guide)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $this->set('guide', $guide);
        }
        if (!empty($this->request->params['named']['view']) && $this->request->params['named']['view'] == 'spotters') {
            $orderby = 'desc';
            $recursive = 1;
            $group = array(
                'Review.user_id',
            );
            $contain = array(
                'User' => array(
                    'fields' => array(
                        'User.id',
                        'User.username',
                    ) ,
                    'UserAvatar',
                ) ,
            );
        } elseif (!empty($this->request->params['named']['view']) && $this->request->params['named']['view'] == 'simple') {
            $orderby = 'desc';
            $recursive = 1;
            $limit = 10;
            $contain = array(
                'Attachment',
                'User' => array(
                    'fields' => array(
                        'User.id',
                        'User.username',
                    )
                ) ,
                'Sighting' => array(
                    'fields' => array(
                        'Sighting.id',
                        'Sighting.item_id',
                        'Sighting.place_id',
                    ) ,
                    'Place' => array(
                        'fields' => array(
                            'Place.id',
                            'Place.name',
                            'Place.slug',
                            'Place.place_type_id',
                        ) ,
                    ) ,
                    'Item' => array(
                        'fields' => array(
                            'Item.id',
                            'Item.name',
                            'Item.slug',
                        ) ,
                    ) ,
                ) ,
                'GuidesSighting' => array(
                    'conditions' => array(
                        'GuidesSighting.guide_id' => $guide['Guide']['id']
                    ) ,
                    'limit' => 1
                ) ,
            );
        } else {
            $orderby = 'asc';
            $recursive = 2;
            $contain = array(
                'Attachment',
                'User' => array(
                    'fields' => array(
                        'User.id',
                        'User.username',
                    )
                ) ,
                'ReviewCategory' => array(
                    'fields' => array(
                        'ReviewCategory.id',
                        'ReviewCategory.name',
                    )
                ) ,
                'Sighting' => array(
                    'fields' => array(
                        'Sighting.id',
                        'Sighting.item_id',
                        'Sighting.place_id',
                    ) ,
                    'Place' => array(
                        'fields' => array(
                            'Place.id',
                            'Place.name',
                            'Place.slug',
                            'Place.place_type_id',
                        ) ,
                    ) ,
                    'Item' => array(
                        'fields' => array(
                            'Item.id',
                            'Item.name',
                            'Item.slug',
                        ) ,
                    ) ,
                ) ,
            );
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'order' => array(
                'Review.id' => $orderby
            ) ,
            'group' => $group,
            'limit' => $limit,
            'contain' => $contain,
            'recursive' => $recursive
        );
        $this->set('reviews', $this->paginate());
        if (!empty($this->request->params['named']['view']) && $this->request->params['named']['view'] == 'simple') {
            $this->render('simple_index');
        } else if (!empty($this->request->params['named']['view']) && $this->request->params['named']['view'] == 'my_popular') {
            $this->render('my_reviews');
        } elseif (!empty($this->request->params['named']['view']) && $this->request->params['named']['view'] == 'spotters') {
            $this->render('spotters');
        }
    }
    public function top_spotter()
    {
        $this->pageTitle = __l('Top Spotters');
        if (empty($this->request->params['named']['place_id'])) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->paginate = array(
            'conditions' => array(
                'Sighting.place_id = ' => $this->request->params['named']['place_id']
            ) ,
            'contain' => array(
                'User' => array(
                    'fields' => array(
                        'User.username',
                        'User.review_count',
                    ) ,
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
                'Sighting' => array(
                    'fields' => array(
                        'Sighting.id',
                        'Sighting.item_id',
                        'Sighting.place_id',
                    ) ,
                ) ,
            ) ,
            'order' => array(
                'User.review_count' => 'DESC'
            ) ,
            'recursive' => 3,
            'group' => array(
                'Review.user_id'
            ) ,
            'limit' => 10
        );
        $user_followers = $this->Review->User->UserFollower->find('list', array(
            'conditions' => array(
                'UserFollower.user_id' => $this->Auth->user('id')
            ) ,
            'fields' => array(
                'UserFollower.id',
                'UserFollower.follower_user_id',
            ) ,
            'recursive' => -1
        ));
        $topspotters = $this->paginate();
        $this->set('user_followers', $user_followers);
        $this->set('topspotters', $topspotters);
    }
    public function view($id = null)
    {
        $this->pageTitle = __l('Review');
        if (empty($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $conditions = array();
        $conditions['Review.id'] = $id;
        if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
            $conditions['Review.admin_suspend !='] = 1;
        }
        $review = $this->Review->find('first', array(
            'conditions' => $conditions,
            'contain' => array(
                'Attachment',
                'User' => array(
                    'fields' => array(
                        'User.id',
                        'User.username',
                    ) ,
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
                    )
                ) ,
                'ReviewCategory' => array(
                    'fields' => array(
                        'ReviewCategory.id',
                        'ReviewCategory.name',
                    )
                ) ,
                'Sighting' => array(
                    'fields' => array(
                        'Sighting.id',
                        'Sighting.item_id',
                        'Sighting.place_id',
                    ) ,
                    'Place' => array(
                        'fields' => array(
                            'Place.id',
                            'Place.name',
                            'Place.slug',
                            'Place.address2',
                            'Place.place_type_id',
                        ) ,
                        'City' => array(
                            'fields' => array(
                                'City.id',
                                'City.name',
                                'City.latitude',
                                'City.longitude',
                            )
                        ) ,
                        'Country' => array(
                            'fields' => array(
                                'Country.id',
                                'Country.name',
                            )
                        ) ,
                        'State' => array(
                            'fields' => array(
                                'State.id',
                                'State.name',
                            )
                        ) ,
                    ) ,
                    'Item' => array(
                        'fields' => array(
                            'Item.id',
                            'Item.name',
                            'Item.slug',
                        ) ,
                    ) ,
                ) ,
            ) ,
            'recursive' => 3,
        ));
        if (empty($review)) {
            $this->Session->delete('Message.success');
            $this->Session->setFlash(__l('This review is suspended by administrator') , 'default', null, 'error');
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->Review->ReviewView->create();
        $this->request->data['ReviewView']['user_id'] = $this->Auth->user('id');
        $this->request->data['ReviewView']['review_id'] = $review['Review']['id'];
        $this->request->data['ReviewView']['ip_id'] = $this->Review->ReviewView->toSaveIp();
        $this->Review->ReviewView->save($this->request->data);
        $this->request->data['ReviewComment']['review_id'] = $review['Review']['id'];
        $this->pageTitle.= ' - ' . $review['Sighting']['Item']['name'] . ' @ ' . $review['Sighting']['Place']['name'];
        $this->set('review', $review);
    }
    public function add()
    {
        $this->pageTitle = __l('Post Review');
        $imgData = '';
        $resonse = array();
        $this->Review->Attachment->Behaviors->attach('ImageUpload', Configure::read('review.file'));
        if (!empty($this->data['review']['guide'])) {
            $this->request->params['named']['guide'] = $this->data['review']['guide'];
        }
        if (isset($this->request->params['named']['guide']) && $this->request->params['named']['guide']) {
            $guide = $this->Review->Sighting->Guide->find('first', array(
                'conditions' => array(
                    'Guide.slug' => $this->request->params['named']['guide']
                ) ,
                'recursive' => -1
            ));
        }
        if ($this->request->is('post')) {
            if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
                $this->request->data['Review']['user_id'] = $this->Auth->user('id');
            }
            if ($this->RequestHandler->prefers('json')) {
                $this->view = 'Json';
                $imgData = base64_decode($this->request->params['form']['image']);
				if(!empty($this->request->params['form']['new_place_name'])){
					$this->request->data['Place']['name'] = $this->request->params['form']['new_place_name'];
				}else{
					$place = $this->Review->Sighting->Place->find('first', array(
						'conditions' => array(
							'Place.slug' => $this->request->params['form']['place_name']
						) ,
						'fields' => array(
							'Place.id',
							'Place.name',
							'Place.slug',
						) ,
						'recursive' => -1
					));
					if (!empty($place)) {
						$this->request->data['Place']['name'] = $place['Place']['name'];
						$this->request->data['Place']['id'] = $place['Place']['id'];
						$this->request->data['Place']['slug'] = $place['Place']['slug'];
						$this->set('place', $place);
					}else{
						$this->request->data['Place']['name'] = $this->request->params['form']['place_name'];
					}
				}
                $this->request->data['Item']['name'] = $this->request->params['form']['item_name'];
				$this->request->data['Review']['notes'] = $this->request->params['form']['notes'];
                $this->request->data['Place']['user_id'] = $this->request->data['Review']['user_id'];
                $this->request->data['Item']['user_id'] = $this->request->data['Review']['user_id'];
                //$this->request->data['ReviewCategory']['name'] = $this->request->params['form']['category_name'];
				if(!empty($this->request->params['form']['new_place_name'])) {
					$this->request->data['Place']['country_id'] = $this->Review->Sighting->Place->Country->findCountryIdFromIso2($this->request->params['form']['country_id']);
					$this->request->data['Place']['state_id'] = $this->Review->Sighting->Place->State->findOrSaveAndGetId($this->request->params['form']['state_name']);
					$this->request->data['Place']['city_id'] = $this->Review->Sighting->Place->City->findOrSaveCityAndGetId($this->request->params['form']['city_name'], $this->request->data['Place']['state_id'], $this->request->data['Place']['country_id'], $this->request->query['latitude'], $this->request->query['longitude']);
					$this->request->data['Place']['zip_code'] = $this->request->params['form']['zip_code'];	
					$this->request->data['Place']['address1'] = $this->request->params['form']['address1'];	
				}

            }
            if ($this->request->params['isAjax'] == 1 || !empty($this->request->params['form']['is_iframe_submit'])) {
                $this->layout = 'ajax';
            }
            if (!empty($this->request->data['Place']['id'])) {
                $place = $this->Review->Sighting->Place->find('first', array(
                    'conditions' => array(
                        'Place.id' => $this->request->data['Place']['id']
                    ) ,
                    'fields' => array(
                        'Place.id',
                        'Place.name',
                    ) ,
                    'recursive' => -1
                ));
                if (!empty($place)) {
                    $this->request->data['Place']['name'] = $place['Place']['name'];
                    $this->set('place', $place);
                }
            } else {
				$ini_place_error = '';
				if(!empty($this->request->data['Place']['name']) && !$this->RequestHandler->prefers('json')) {
					$ini_place_error = $this->request->data['Place']['name'];
					$this->request->data['Place']['name'] = '';
				}
			}
            $this->Review->create();
            $this->Review->set($this->request->data);
            $this->Review->Sighting->Place->set($this->request->data);
            $this->Review->Sighting->Item->set($this->request->data);
            $ini_upload_error = 1;
            if (!$this->RequestHandler->prefers('json')) {
                if (!empty($this->request->data['Attachment']['filename']['name'])) {
                    $this->request->data['Attachment']['filename']['type'] = get_mime($this->request->data['Attachment']['filename']['tmp_name']);
                }
                if (!empty($this->request->data['Attachment']['filename']['name']) || (!Configure::read('review.file.allowEmpty') && empty($this->request->data['Attachment']['id']))) {
                    $this->Review->Attachment->set($this->request->data);
                }
                if ($this->request->data['Attachment']['filename']['error'] == 1) {
                    $ini_upload_error = 0;
                }
                if (!$this->Review->Attachment->validates()) {
                    $ini_upload_error = 0;
                }
            } else {
                if (empty($imgData)) $ini_upload_error = 0;
            }
            if ($this->Review->validates() &$this->Review->Sighting->Place->validates() &$this->Review->Sighting->Item->validates() && $ini_upload_error) {
                if (!empty($this->request->data['ReviewCategory']['name'])) {
                    $this->request->data['Review']['review_category_id'] = !empty($this->request->data['ReviewCategory']['id']) ? $this->request->data['ReviewCategory']['id'] : $this->Review->ReviewCategory->findOrSaveAndGetId($this->request->data['ReviewCategory']['name']);
                }
                if (empty($this->request->data['Review']['sighting_id'])) {
                    if (empty($this->request->data['Place']['id']) && !empty($this->request->data['Place']['name'])) {
						$place = $this->Review->Sighting->Place->find('first', array(
							'conditions' => array(
								'Place.name' => $this->request->data['Place']['name']
							) ,
							'fields' => array(
								'Place.id',
							) ,
							'recursive' => -1
						));
						if(!empty($place)) {
							$this->request->data['Place']['id'] = $place['Place']['id'];
						} else {
							App::import('Vendor', 'geo_hash');
							$this->geohash = new Geohash();
							$this->Review->Sighting->Place->create();
							$_placeData['Place']['name'] = $this->request->data['Place']['name'];
							$_placeData['Place']['user_id'] = $this->request->data['Review']['user_id'];
							$_placeData['Place']['latitude'] = $this->request->query['latitude'];
							$_placeData['Place']['longitude'] = $this->request->query['longitude'];
							$_placeData['Place']['address1'] = $this->request->data['Place']['name'];
							$_placeData['Place']['zoom_level'] = 9;
							if(!empty($this->request->params['form']['new_place_name'])) {
								$_placeData['Place']['address1'] = $this->request->data['Place']['address1'];
								$_placeData['Place']['country_id'] = $this->request->data['Place']['country_id'];
								$_placeData['Place']['state_id'] = $this->request->data['Place']['state_id'];
								$_placeData['Place']['city_id'] = $this->request->data['Place']['city_id'];
								$_placeData['Place']['zip_code'] = $this->request->data['Place']['zip_code'];	
								$country = $this->Review->Sighting->Place->Country->find('first', array(
									'conditions' => array(
										'Country.id' => $this->request->data['Place']['country_id']
									) ,
									'fields' => array(
										'Country.name',
									) ,
									'recursive' => -1
								));
								$_placeData['Place']['address2'] = $this->request->data['Place']['address1'].", ".$this->request->params['form']['city_name'].", ".$this->request->params['form']['state_name'].", ".$country['Country']['name']." ".$this->request->data['Place']['zip_code'];													
								$lat_long = $this->getCoordinates($_placeData['Place']['address2']);
								if(!empty($lat_long) && is_array($lat_long)){
									$_placeData['Place']['latitude'] = $lat_long['lat'];
									$_placeData['Place']['longitude'] = $lat_long['long'];	
								}
							}
							if(!empty($_placeData['Place']['latitude']) &&  !empty($_placeData['Place']['longitude'])){
								$_placeData['Place']['hash'] = $this->geohash->encode(round($_placeData['Place']['latitude'], 6) , round($_placeData['Place']['longitude'], 6));														
							}else{
								$_placeData['Place']['hash'] = $this->geohash->encode(round($this->request->query['latitude'], 6) , round($this->request->query['longitude'], 6));							
							}
							$this->Review->Sighting->Place->save($_placeData);
							$this->request->data['Place']['id'] = $this->Review->Sighting->Place->getLastInsertId();
						}	
                    }
                    $this->request->data['Item']['id'] = !empty($this->request->data['Item']['id']) ? $this->request->data['Item']['id'] : $this->Review->Sighting->Item->findOrSaveAndGetId($this->request->data['Item']['name']);
                    $sighting = $this->Review->Sighting->find('first', array(
                        'conditions' => array(
                            'Sighting.item_id' => $this->request->data['Item']['id'],
                            'Sighting.place_id' => $this->request->data['Place']['id'],
                        ) ,
                        'recursive' => -1
                    ));
                    // Save Sighting, If not presnt for a item-place //
                    if (empty($sighting)) {
                        $this->Review->Sighting->create();
                        $data_sighting = array();
                        $data_sighting['Sighting']['item_id'] = $this->request->data['Item']['id'];
                        $data_sighting['Sighting']['place_id'] = $this->request->data['Place']['id'];
                        $data_sighting['Sighting']['user_id'] = $this->request->data['Review']['user_id'];
                        $data_sighting['Sighting']['uploaded_via'] = $this->Review->loginType($_SERVER['HTTP_USER_AGENT']);
                        $this->Review->Sighting->save($data_sighting);
                        $this->request->data['Review']['sighting_id'] = $this->Review->Sighting->getLastInsertId();
                    } else {
                        $this->request->data['Review']['sighting_id'] = $sighting['Sighting']['id'];
                    }
                }
                if (!empty($this->request->data['Review']['sighting_id'])) {
                    $this->request->data['Review']['user_id'] = $this->Auth->user('id');
                    $this->request->data['Review']['uploaded_via'] = $this->Review->Sighting->loginType($_SERVER['HTTP_USER_AGENT']);
                    if ($this->Review->save($this->request->data)) {
                        // Saving Attachment //
                        $review_id = $this->Review->getLastInsertId();
                        if ($this->RequestHandler->prefers('json') && !empty($imgData)) {
                            // iphone to get review image encrpted data save
                            new Folder(APP . 'media' . DS . 'Review' . DS . $review_id, true);
                            chmod(APP . 'media' . DS . 'Review' . DS . $review_id, 0777);
                            $filename = md5(date('Ymdgisu')) . '.jpg';
                            $image_path = APP . 'media' . DS . 'Review' . DS . $review_id . DS . $filename;
                            // write the imgData to the file
                            $fp = fopen($image_path, 'w');
                            fwrite($fp, $imgData);
                            fclose($fp);
                            $attachment['Attachment']['filename']['type'] = get_mime($image_path);
                            $this->Review->Attachment->enableUpload(false); //don't trigger upload behavior on save
                            $attachment['Attachment']['class'] = 'Review';
                            $attachment['Attachment']['description'] = 'Review';
                            $attachment['Attachment']['foreign_id'] = $review_id;
                            $attachment['Attachment']['dir'] = 'Review/' . $review_id;
                            $attachment['Attachment']['mimetype'] = 'image/jpeg';
                            $this->Review->Attachment->create();
                            $attachment['Attachment']['filename'] = $filename;
                            $this->Review->Attachment->set($attachment);
                            $this->Review->Attachment->save($attachment);
                            $resonse = array(
                                'status' => 0,
                                'message' => 'sighting add successfully'
                            );
                        } else {
                            if (!empty($this->request->data['Attachment']['filename']['name'])) {
                                $this->Review->Attachment->create();
                                $this->request->data['Attachment']['class'] = 'Review';
                                $this->request->data['Attachment']['foreign_id'] = $review_id;
                                $this->Review->Attachment->save($this->request->data['Attachment']);
                            }
                        }
                        if (!empty($this->request->data['Review']['guide'])) {
                            $guide = $this->Review->Sighting->Guide->find('first', array(
                                'conditions' => array(
                                    'Guide.slug' => $this->request->data['Review']['guide']
                                ) ,
                                'recursive' => -1
                            ));
                            if (!empty($guide)) {
                                $this->loadModel('GuidesSighting');
                                $this->GuidesSighting->create();
                                $data_guide_sighting = array();
                                $data_guide_sighting['GuidesSighting']['review_id'] = $review_id;
                                $data_guide_sighting['GuidesSighting']['guide_id'] = $guide['Guide']['id'];
                                $data_guide_sighting['GuidesSighting']['sighting_id'] = $this->request->data['Review']['sighting_id'];
                                $this->GuidesSighting->save($data_guide_sighting);
                            }
                        }
                        $is_tweet = 0;
                        $is_facebook = 0;
                        $is_foursquare = 0;
                        if ((!empty($this->request->data['Review']['is_tweet']))) {
                            $is_tweet = 1;
                        }
                        if ((!empty($this->request->data['Review']['is_facebook']))) {
                            $is_facebook = 1;
                        }
                        if ((!empty($this->request->data['Review']['is_foursquare']))) {
                            $is_foursquare = 1;
                        }
                        $this->_SocialNetworkPost($review_id, $is_tweet, $is_facebook, $is_foursquare);
                        $this->Session->setFlash(__l('review has been added') , 'default', null, 'success');
                        $ajax_url = Router::url(array(
                            'controller' => 'sightings',
                            'action' => 'view',
                            $this->request->data['Review']['sighting_id'],
                            'admin' => false
                        ));
                        if ($this->RequestHandler->prefers('json')) {
                            $resonse = array(
                                'status' => 0,
                                'message' => 'review has been added',
                            );
                        } else {
                            if ($this->request->params['isAjax'] == 1 || !empty($this->request->params['form']['is_iframe_submit'])) {
                                $success_msg = 'redirect*' . $ajax_url;
                                echo $success_msg;
                                exit;
                            } else {
                                $this->redirect($ajax_url);
                            }
                        }
                    } else {
                        $this->Session->setFlash(__l('Review could not be added. Please, try again.') , 'default', null, 'error');
                    }
                } else {
                    $this->Session->setFlash(__l('Review could not be added. Please, try again.') , 'default', null, 'error');
                }
            } else {
                if (!empty($this->Review->Attachment->validationErrors)) {
                    $this->request->params['form']['is_iframe_submit'] = 1;
                }
                if (!(!$this->RequestHandler->isAjax() && empty($this->request->params['form']['is_iframe_submit']))) {
                    $this->layout = 'ajax';
                }
                if ($this->request->data['Attachment']['filename']['error'] == 1) {
                    $this->Review->Attachment->validationErrors['filename'] = sprintf(__l('The file uploaded is too big, only files less than %s permitted') , ini_get('upload_max_filesize'));
                }
				if(!empty($ini_place_error)) {
					$this->Review->Sighting->Place->validationErrors['name'] = __l('Enter valid place');
				}
                if ($this->RequestHandler->prefers('json')) {
                    $resonse = array(
                        'status' => 1,
                        'message' => 'Review could not be added. Please, try again.',
                    );
                }
                $this->Session->setFlash(__l('Review could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        if (!empty($this->request->data['Review']['sighting_id'])) {
            $sighting_id = $this->request->data['Review']['sighting_id'];
        } elseif (!empty($this->request->params['named']['sighting_id'])) {
            $sighting_id = $this->request->params['named']['sighting_id'];
        }
        if (!empty($this->request->params['named']['place'])) {
            $place_slug = $this->request->params['named']['place'];
        }
        if (!empty($place_slug)) {
            $place = $this->Review->Sighting->Place->find('first', array(
                'conditions' => array(
                    'Place.slug' => $place_slug
                ) ,
                'fields' => array(
                    'Place.id',
                    'Place.name',
                ) ,
                'recursive' => -1
            ));
            if (!empty($place)) {
                $this->request->data = $place;
                $this->set('place', $place);
            } else {
                throw new NotFoundException(__l('Invalid place'));
            }
        }
        if (!empty($sighting_id)) {
            $sighting = $this->Review->Sighting->find('first', array(
                'conditions' => array(
                    'Sighting.id' => $sighting_id
                ) ,
                'contain' => array(
                    'Item' => array(
                        'fields' => array(
                            'Item.id',
                            'Item.name',
                        )
                    ) ,
                    'Place' => array(
                        'fields' => array(
                            'Place.id',
                            'Place.name',
                        )
                    ) ,
                ) ,
                'recursive' => 0
            ));
            if (!empty($sighting)) {
                $this->request->data = $sighting;
                $this->request->data['Review']['sighting_id'] = $sighting['Sighting']['id'];
                $this->set('sighting', $sighting);
            } else {
                throw new NotFoundException(__l('Invalid review'));
            }
        }
		if(!empty($ini_place_error)) {
			$this->request->data['Place']['name'] = $ini_place_error;
		}
        if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
            $users = $this->Review->User->find('list');
            $this->set(compact('users'));
        }
        if ($this->RequestHandler->prefers('json')) {
            $this->view = 'Json';
            $this->set('json', (empty($this->viewVars['iphone_response'])) ? $resonse : $this->viewVars['iphone_response']);
        }
    }
    public function edit($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->Review->id = $id;
        if (!$this->Review->exists()) {
            throw new NotFoundException(__l('Invalid review'));
        }
        $reviews = $this->Review->find('first', array(
            'conditions' => array(
                'Review.id' => $id
            ) ,
            'contain' => array(
                'Attachment' => array(
                    'fields' => array(
                        'Attachment.id',
                        'Attachment.filename',
                        'Attachment.width',
                        'Attachment.height',
                        'Attachment.mimetype'
                    )
                ) ,
                'Sighting' => array(
                    'fields' => array(
                        'Sighting.id',
                        'Sighting.place_id',
                        'Sighting.item_id',
                        'Sighting.review_count'
                    ) ,
                    'Item' => array(
                        'fields' => array(
                            'Item.name',
                            'Item.slug',
                        )
                    ) ,
                    'Place' => array(
                        'fields' => array(
                            'Place.name',
                            'Place.slug',
                        ) ,
                    )
                )
            ) ,
            'recursive' => 2
        ));
        $this->pageTitle = __l('Edit Sighting') . ' - ' . $reviews['Sighting']['Item']['name'] . " @ " . $reviews['Sighting']['Place']['name'];
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->Review->set($this->request->data);
            $this->Review->Attachment->Behaviors->attach('ImageUpload', Configure::read('review.file'));
            $this->Review->Sighting->Item->set($this->request->data);
            $ini_upload_error = 1;
            if (!empty($this->request->data['Attachment']['filename']['name'])) {
                $this->request->data['Attachment']['filename']['type'] = get_mime($this->request->data['Attachment']['filename']['tmp_name']);
            }
            if (!empty($this->request->data['Attachment']['filename']['name'])) {
                $this->Review->Attachment->set($this->request->data);
            }
            if ($this->request->data['Attachment']['filename']['error'] == 1) {
                $ini_upload_error = 0;
            }
            if (!$this->Review->Attachment->validates()) {
                $ini_upload_error = 0;
            }
			if (!empty($this->request->data['Place']['id'])) {
                $place = $this->Review->Sighting->Place->find('first', array(
                    'conditions' => array(
                        'Place.id' => $this->request->data['Place']['id']
                    ) ,
                    'fields' => array(
                        'Place.id',
                        'Place.name',
                    ) ,
                    'recursive' => -1
                ));
                if (!empty($place)) {
                    $this->request->data['Place']['name'] = $place['Place']['name'];
                    $this->set('place', $place);
                }
            } else {
				$ini_place_error = '';
				if(!empty($this->request->data['Place']['name']) && !$this->RequestHandler->prefers('json')) {
					$ini_place_error = $this->request->data['Place']['name'];
					$this->request->data['Place']['name'] = '';
				}
			}
            $this->Review->Sighting->Place->set($this->request->data);
            if ($this->Review->validates() &$this->Review->ReviewCategory->validates() &$this->Review->Sighting->Place->validates() &$this->Review->Sighting->Item->validates() && $ini_upload_error) {
                if (!empty($this->request->data['ReviewCategory']['name'])) {
                    $this->request->data['Review']['review_category_id'] = !empty($this->request->data['ReviewCategory']['id']) ? $this->request->data['ReviewCategory']['id'] : $this->Review->ReviewCategory->findOrSaveAndGetId($this->request->data['ReviewCategory']['name']);
                }
                $this->request->data['Place']['id'] = !empty($this->request->data['Place']['id']) ? $this->request->data['Place']['id'] : $this->Review->Sighting->Place->findOrSaveAndGetId($this->request->data['Place']['name']);
                $this->request->data['Item']['id'] = !empty($this->request->data['Item']['id']) ? $this->request->data['Item']['id'] : $this->Review->Sighting->Item->findOrSaveAndGetId($this->request->data['Item']['name']);
                $sighting = $this->Review->Sighting->find('first', array(
                    'conditions' => array(
                        'Sighting.item_id' => $this->request->data['Item']['id'],
                        'Sighting.place_id' => $this->request->data['Place']['id'],
                    ) ,
                    'recursive' => -1
                ));
                // Save Sighting, If not presnt for a item-place //
                if (empty($sighting)) {
                    $data_sighting = array();
                    if ($reviews['Sighting']['review_count'] == 1) {
                        $data_sighting['Sighting']['id'] = $reviews['Sighting']['id'];
                    } else {
                        $this->Review->Sighting->create();
                    }
                    $data_sighting['Sighting']['item_id'] = $this->request->data['Item']['id'];
                    $data_sighting['Sighting']['place_id'] = $this->request->data['Place']['id'];
                    $data_sighting['Sighting']['user_id'] = $this->request->data['Review']['user_id'];
                    $this->Review->Sighting->save($data_sighting);
					if(!empty($data_sighting['Sighting']['id'])) {
						$this->request->data['Review']['sighting_id'] = $data_sighting['Sighting']['id'];
					} else {
						$this->request->data['Review']['sighting_id'] = $this->Review->Sighting->getLastInsertId();
					}
                } else {
                    $this->request->data['Review']['sighting_id'] = $sighting['Sighting']['id'];
                }
                if (!empty($this->request->data['Review']['sighting_id'])) {
                    $this->request->data['Review']['user_id'] = $this->Auth->user('id');
                    if ($this->Review->save($this->request->data)) {
                        // Saving Attachment //
                        if (!empty($this->request->data['Attachment']['filename']['name'])) {
                            if (!empty($reviews['Attachment'])) {
                                $this->request->data['Attachment']['id'] = $reviews['Attachment']['id'];
                            }
                            $this->request->data['Attachment']['class'] = 'Review';
                            $this->request->data['Attachment']['foreign_id'] = $this->request->data['Review']['id'];
                            $this->Review->Attachment->save($this->request->data['Attachment']);
                        }
                        $this->Session->setFlash(__l('Review has been updated successfully') , 'default', null, 'success');
                        $this->redirect(array(
                            'controller' => 'reviews',
                            'action' => 'view',
							'admin' => false,
                            $id
                        ));
                    } else {
                        $this->Session->setFlash(__l('Review could not be added. Please, try again.') , 'default', null, 'error');
                    }
                } else {
                    $this->Session->setFlash(__l('Review could not be added. Please, try again.') , 'default', null, 'error');
                }
            } else {
                if ($this->request->data['Attachment']['filename']['error'] == 1) {
                    $this->Review->Attachment->validationErrors['filename'] = sprintf(__l('The file uploaded is too big, only files less than %s permitted') , ini_get('upload_max_filesize'));
                }
				if(!empty($ini_place_error)) {
					$this->Review->Sighting->Place->validationErrors['name'] = __l('Enter valid place');
				}
                $this->Session->setFlash(__l('Review could not be added. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->data = $this->Review->find('first', array(
                'conditions' => array(
                    'Review.id' => $id
                ) ,
                'contain' => array(
                    'ReviewCategory',
                    'Sighting' => array(
                        'Item',
                        'Place',
                    ) ,
                    'ReviewTag'
                )
            ));
            if (empty($this->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $this->request->data['Place'] = $this->data['Sighting']['Place'];
            $this->request->data['Item'] = $this->data['Sighting']['Item'];
            unset($this->request->data['Sighting']['Place']);
            unset($this->request->data['Sighting']['Item']);
            $this->request->data['Review']['tag'] = $this->Review->formatTags($this->data['ReviewTag']);
        }
		if(!empty($ini_place_error)) {
			$this->request->data['Place']['name'] = $ini_place_error;
		}
        $users = $this->Review->User->find('list');
        $this->set(compact('users', 'reviews'));
    }
    public function delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->Review->id = $id;
        if (!$this->Review->exists()) {
            throw new NotFoundException(__l('Invalid review'));
        }
        $sighting = $this->Review->find('first', array(
            'conditions' => array(
                'Review.id' => $id
            ) ,
            'contain' => array(
                'Sighting' => array(
                    'BaseReview'
                )
            )
        ));
        if ($sighting['Sighting']['review_count'] == 1) {
			$this->Review->delete();
            $this->Review->Sighting->id = $sighting['Sighting']['id'];
			$this->Review->Sighting->delete();
			$this->Session->setFlash(__l('Review deleted') , 'default', null, 'success');
            if($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
				$this->redirect(array(
					'action' => 'index'
				));
			} else {
				$this->redirect(array(
					'controller' => 'sightings',
					'action' => 'index',
					'admin' => false
				));
			}
        }
        if ($this->Review->delete()) {
            $this->Session->setFlash(__l('Review deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        $this->Session->setFlash(__l('Review was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'action' => 'index'
        ));
    }
    public function admin_index()
    {
        $this->_redirectPOST2Named(array(
            'q',
            'review_category_id'
        ));
        $this->pageTitle = __l('Reviews');
        $conditions = array();
        if (isset($this->request->params['named']['sighting'])) {
            $conditions['Review.sighting_id'] = $this->request->params['named']['sighting'];
        }
        if (isset($this->request->params['named']['review_category_id'])) {
            $this->request->data['Review']['review_category_id'] = $this->request->params['named']['review_category_id'];
            $conditions['Review.review_category_id'] = $this->request->params['named']['review_category_id'];
        }
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Review.created) <= '] = 0;
            $this->pageTitle.= __l(' - Registered today');
        }
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Review.created) <= '] = 7;
            $this->pageTitle.= __l(' - Registered in this week');
        }
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Review.created) <= '] = 30;
            $this->pageTitle.= __l(' - Registered in this month');
        }
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Flagged) {
                $conditions['Review.is_system_flagged'] = 1;
                $this->pageTitle.= __l(' - System Flagged ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Suspend) {
                $conditions['Review.admin_suspend'] = 1;
                $this->pageTitle.= __l(' - Admin Suspend ');
            }
        }
        if (isset($this->request->params['named']['username'])) {
            $userConditions = array(
                'User.username' => $this->request->params['named']['username']
            );
            $user = $this->{$this->modelClass}->User->find('first', array(
                'conditions' => $userConditions,
                'fields' => array(
                    'User.id',
                    'User.username'
                ) ,
                'recursive' => -1
            ));
            if (empty($user)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['Review.user_id'] = $user['User']['id'];
            $this->pageTitle.= ' - ' . $user['User']['username'];
        }
        if (isset($this->request->params['named']['category'])) {
            $categoryConditions = array(
                'ReviewCategory.slug' => $this->request->params['named']['category']
            );
            $category = $this->{$this->modelClass}->ReviewCategory->find('first', array(
                'conditions' => $categoryConditions,
                'fields' => array(
                    'ReviewCategory.id',
                    'ReviewCategory.name'
                ) ,
                'recursive' => -1
            ));
            if (empty($category)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['Review.review_category_id'] = $category['ReviewCategory']['id'];
            $this->pageTitle.= ' - ' . $category['ReviewCategory']['name'];
        }
        if (!empty($this->request->params['named']['q'])) {
            $this->request->data['Review']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'order' => array(
                'Review.id' => 'DESC'
            ) ,
            'contain' => array(
                'ReviewCategory',
                'User',
                'Sighting' => array(
                    'Item',
                    'Place',
                ) ,
                'ReviewRatingStat',
                'Attachment',
            ) ,
        );
        if (!empty($this->request->data['Review']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->data['Review']['q']
            ));
        }
        $this->set('reviews', $this->paginate());
        $moreActions = $this->Review->moreActions;
        $reviewRatingTypes = $this->Review->ReviewRatingStat->ReviewRatingType->find('list');
        $reviewRatingTypes_count = count($reviewRatingTypes);
        $reviewCategories = $this->Review->ReviewCategory->find('list');
        $this->set(compact('moreActions', 'reviewCategories', 'reviewRatingTypes', 'reviewRatingTypes_count'));
        $this->set('flagged', $this->Review->find('count', array(
            'conditions' => array(
                'Review.is_system_flagged = ' => 1,
            )
        )));
        $this->set('all', $this->Review->find('count'));
        $this->set('suspended', $this->Review->find('count', array(
            'conditions' => array(
                'Review.admin_suspend = ' => 1,
            )
        )));
    }
    public function admin_add()
    {
        $this->setAction('add');
    }
    public function admin_edit($id = null)
    {
        $this->setAction('edit', $id);
    }
    public function admin_delete($id = null)
    {
        $this->setAction('delete', $id);
    }
    public function _SocialNetworkPost($id, $is_tweet, $is_facebook, $is_foursquare)
    {
        $review = $this->Review->find('first', array(
            'conditions' => array(
                'Review.id = ' => $id
            ) ,
            'contain' => array(
                'Attachment',
                'Sighting' => array(
                    'fields' => array(
                        'Sighting.id',
                        'Sighting.item_id',
                        'Sighting.place_id',
                    ) ,
                    'Place' => array(
                        'fields' => array(
                            'Place.id',
                            'Place.name',
                            'Place.address2',
                            'Place.place_type_id',
                        ) ,
                        'City' => array(
                            'fields' => array(
                                'City.id',
                                'City.name',
                                'City.latitude',
                                'City.longitude',
                            )
                        ) ,
                        'Country' => array(
                            'fields' => array(
                                'Country.id',
                                'Country.name',
                            )
                        ) ,
                        'State' => array(
                            'fields' => array(
                                'State.id',
                                'State.name',
                            )
                        ) ,
                    ) ,
                    'Item' => array(
                        'fields' => array(
                            'Item.id',
                            'Item.name',
                            'Item.slug',
                        ) ,
                    ) ,
                ) ,
            ) ,
        ));
        $image_options = array(
            'dimension' => 'small_large',
            'class' => 'Review',
            'alt' => $review['Sighting']['Item']['name'],
            'title' => $review['Sighting']['Item']['name'],
            'type' => 'jpg'
        );
        $image_url = '';
        if (!empty($review['Attachment'])) {
			getimagesize(Router::url('/', true) . $this->getImageUrl('Review', $review['Attachment'], $image_options));
            $image_url = Router::url('/', true) . $this->getImageUrl('Review', $review['Attachment'], $image_options);
        }
        // Posting In Social Networking //
        if ($is_facebook) {
            // Importing Facebook //
            App::import('Vendor', 'facebook/facebook');
            $this->facebook = new Facebook(array(
                'appId' => Configure::read('facebook.fb_api_key') ,
                'secret' => Configure::read('facebook.fb_secrect_key') ,
                'cookie' => true
            ));
            // Post in facebook //
            $fb_message = "Here we get " . $review['Sighting']['Item']['name'] . ' (' . $review['Sighting']['Item']['name'] . ' @ ' . $review['Sighting']['Place']['name'] . ') on @' . Configure::read('site.name') . ': ' . Router::url(array(
                'controller' => 'reviews',
                'action' => 'view',
                $review['Review']['id'],
            ) , true);
            $data['message'] = $fb_message;
            $data['image_url'] = $image_url;
            $data['url'] = Router::url(array(
                'controller' => 'reviews',
                'action' => 'view',
                $review['Review']['id'],
            ) , true);
            $data['description'] = $review['Review']['notes'];
            $fb_access_token = $this->Auth->user('fb_access_token');
            $fb_user_id = $this->Auth->user('fb_user_id');
            if (!empty($fb_access_token) && !empty($fb_user_id)) {
                $data['fb_access_token'] = $this->Auth->user('fb_access_token');
                $data['fb_user_id'] = $this->Auth->user('fb_user_id');
                $this->_postInFacebook($data);
            }
        }
        if ($is_tweet) {
            // Importing Twitter //
            App::import('Core', 'ComponentCollection');
            $collection = new ComponentCollection();
            App::import('Component', 'OauthConsumer');
            $this->OauthConsumer = new OauthConsumerComponent($collection);
            // Post in twitter //
            $sightings_url = Router::url(array(
                'controller' => 'sightings',
                'action' => $review['Sighting']['id'],
            ) , true);
            $tw_message = "Here we get " . $review['Sighting']['Item']['name'] . ' (' . $review['Sighting']['Item']['name'] . ' @ ' . $review['Sighting']['Place']['name'] . ') on @' . Configure::read('site.name') . ': ' . Router::url(array(
                'controller' => 'reviews',
                'action' => 'view',
                $review['Review']['id'],
            ) , true);
            $data['message'] = $tw_message;
            //$data['twitter_access_token'] = Configure::read('twitter.site_user_access_token');
            //$data['twitter_access_key'] = Configure::read('twitter.site_user_access_key');
            //$this->_postInTwitter($data);
            $twitter_access_token = $this->Auth->user('twitter_access_token');
            $twitter_access_key = $this->Auth->user('twitter_access_key');
            if (!empty($twitter_access_token) && !empty($twitter_access_key)) {
                $data['twitter_access_token'] = $this->Auth->user('twitter_access_token');
                $data['twitter_access_key'] = $this->Auth->user('twitter_access_key');
                $this->_postInTwitter($data);
            }
        }
        if ($is_foursquare) {
            // Importing FourSquare //
            $client_key = Configure::read('foursquare.consumer_key');
            $client_secret = Configure::read('foursquare.consumer_secret');
            $token = $this->Auth->user('foursquare_access_token');
            include_once APP . 'vendors' . DS . 'foursquare' . DS . 'FoursquareAPI.class.php';
            // Load the Foursquare API library
            $foursquare = new FoursquareAPI($client_key, $client_secret);
            $foursquare->SetAccessToken($token);
            // Post in FourSquare //
            $fs_message = "Here we get " . $review['Sighting']['Item']['name'] . ' ( ' . $review['Sighting']['Item']['name'] . ' @ ' . $review['Sighting']['Place']['name'] . ' ) on @' . Configure::read('site.name') . ' bit.ly/w0j5KZ';
            $foursquare_venue_id = Configure::read('foursquare.site_foursquare_venue_id');
            $params['text'] = $fs_message;
            $params['url'] = Router::url('/', true);
            $params['venueId'] = $foursquare_venue_id;
            $tipsresult = $foursquare->postTips($params);
        }
    }
    function _postInFacebook($data)
    {
        try {
            $this->facebook->api('/' . (!empty($data['fb_user_id']) ? $data['fb_user_id'] : Configure::read('facebook.page_id')) . '/feed', 'POST', array(
                'access_token' => (!empty($data['fb_access_token']) ? $data['fb_access_token'] : Configure::read('facebook.fb_access_token')) ,
                'message' => $data['message'],
                'picture' => $data['image_url'],
                'icon' => $data['image_url'],
                'link' => $data['url'],
                'caption' => Router::url('/', true) ,
                'description' => $data['description']
            ));
        }
        catch(Exception $e) {
            $this->log('Post on facebook error');
        }
    }
    function _postInTwitter($data)
    {
        $xml = $this->OauthConsumer->post('Twitter', (!empty($data['twitter_access_token']) ? $data['twitter_access_token'] : Configure::read('twitter.site_user_access_token')) , (!empty($data['twitter_access_key']) ? $data['twitter_access_key'] : Configure::read('twitter.site_user_access_key')) , 'http://api.twitter.com/1/statuses/update.json', array(
            'status' => $data['message']
        ));
    }
	public function getURL($url){
		 	$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_URL, $url);
			$tmp = curl_exec($ch);
			curl_close($ch);
			if ($tmp != false){
			 	return $tmp;
			}
	}
		
		/**
		* Get Latitude/Longitude/Altitude based on an address
		* @param string $address The address for converting into coordinates
		* @return array An array containing Latitude/Longitude/Altitude data
		*/
	public function getCoordinates($address){
		$address = str_replace(' ','+',$address);
		$url = 'http://maps.google.com/maps/geo?q=' . $address . '&output=xml&key=' . Configure::read('google.gmap_app_id');
		$data = $this->getURL($url);
		if ($data){
			$xml = new SimpleXMLElement($data);
			$requestCode = $xml->Response->Status->code;
			if ($requestCode == 200){
				//all is ok
				$coords = $xml->Response->Placemark->Point->coordinates;
				$coords = explode(',',$coords);
				if (count($coords) > 1){
					if (count($coords) == 3){
						return array('lat' => $coords[1], 'long' => $coords[0], 'alt' => $coords[2]);
					} else {
						return array('lat' => $coords[1], 'long' => $coords[0], 'alt' => 0);
					}
				}
			}
		}
		//return default data
		return array('lat' => 0, 'long' => 0, 'alt' => 0);
	}	

    //<--- Iphone listing and find only
    public function lst($id)
    {
		$reviews_array = array();
        if ($this->RequestHandler->prefers('json')) {
			$this->Review->ReviewView->create();
			$this->request->data['ReviewView']['user_id'] = $this->Auth->user('id');
			$this->request->data['ReviewView']['review_id'] = $id;
			$this->request->data['ReviewView']['ip_id'] = $this->Review->ReviewView->toSaveIp();
			$this->Review->ReviewView->save($this->request->data);		
            $this->view = 'Json';
			$conditions['Review.admin_suspend !='] = 1;
			if (!empty($id)) {
				$conditions['Review.id'] = $id;
			}
			$contain = array(
                'Attachment',
				'ReviewComment' => array(
					'fields' => array(
						'ReviewComment.id',
						'ReviewComment.review_id',
						'ReviewComment.created',
						'ReviewComment.user_id',
						'ReviewComment.comment',
					),
					'User' => array(
						'fields' => array(
							'User.id',
							'User.username',
						),
						'UserAvatar',
					)
				),
                'Sighting' => array(
                    'fields' => array(
                        'Sighting.id',
                        'Sighting.item_id',
                        'Sighting.place_id',
                    ) ,
                    'Place' => array(
                        'fields' => array(
                            'Place.id',
                            'Place.name',
                            'Place.slug',
                            'Place.place_type_id',
                        ) ,
                    ) ,
                    'Item' => array(
                        'fields' => array(
                            'Item.id',
                            'Item.name',
                            'Item.slug',
                        ) ,
                    ) ,
                ) ,
                'ReviewRatingStat' => array(
					'fields' => array(
						'ReviewRatingStat.id',
						'ReviewRatingStat.review_id',
						'ReviewRatingStat.review_rating_type_id',
						'ReviewRatingStat.count',
					),
                    'conditions' => array(
                        'ReviewRatingStat.review_id' => $id
                    ), 
					'ReviewRatingType' => array(
						'fields' => array(
							'ReviewRatingType.id',
							'ReviewRatingType.name',
							'ReviewRatingType.slug',
						)
					),
                ) ,
                'ReviewRating' => array(
					'fields' => array(
						'ReviewRating.id',
						'ReviewRating.review_id',
						'ReviewRating.user_id',
						'ReviewRating.review_rating_type_id',
						'ReviewRating.id',
					),
                    'conditions' => array(
                        'ReviewRating.review_id' => $id,
						'ReviewRating.user_id' => $this->Auth->user('id')
                    ) ,
                    'User' => array(
                        'fields' => array(
                            'User.id',
                            'User.username'
                        ),
						'UserAvatar' => array(
							'fields' => array(
								'UserAvatar.id'
							)
						), 
                    )
                ),
                'User' => array(
                    'fields' => array(
                        'User.id',
                        'User.username',
                    ),
					'UserAvatar',
                ) ,				
				
            );
			$review = $this->Review->find('first', array(			
				'conditions' => $conditions,    
				'fields' => array(
                            'Review.id',
							'Review.created',
							'Review.user_id',
							'Review.sighting_id',
							'Review.sighting_id',
							'Review.notes',
							'Review.review_view_count',
							'Review.review_comment_count'
                        ), 
				'contain' => $contain,
				'recursive' => 3
			));
			$reviewRatingTypes = $this->Review->ReviewRatingStat->ReviewRatingType->find('all', array(
					'fields' => array(
						'ReviewRatingType.id',
						'ReviewRatingType.name',
						'ReviewRatingType.slug'
					),
					'recursive' => -1)
					);
			//echo "<pre>";			
			//print_r($review);
			//print_r($reviewRatingTypes);
			$reviews_array['Review'] = $review['Review'];
			$reviews_array['Review']['created'] = date('M d, Y', strtotime($review['Review']['created'])); 
			$reviews_array['Sighting'] = $review['Sighting'];			
			$reviews_array['User'] = $review['User'];		
			$reviews_array['User']['user_img'] = $this->_iphoneImageURLCreate('UserAvatar', $review['User']['UserAvatar'], $review['User']['username'], array('map_small_thumb'));
			unset($reviews_array['User']['UserAvatar']);
			$reviews_array['Review']['review_img'] = $this->_iphoneImageURLCreate('Review', $review['Attachment'], $sightings['Item']['name'], array('iphone_review_big_thumb'));
			if(!empty($review['ReviewComment'])){
				foreach($review['ReviewComment'] as $key => $value){
					$value['created'] = date('M d, Y', strtotime($value['created']));
					$value['user_img'] = $this->_iphoneImageURLCreate('UserAvatar', $value['User']['UserAvatar'], $value['User']['username'], array('map_small_thumb'));
					unset($value['User']['UserAvatar']);
					$reviews_array['ReviewComment'][] = $value;
				}
			}else{
				$reviews_array['ReviewComment'] = array();
			}
			$reviews_array['RatingType'] = $reviewRatingTypes;
			if(!empty($reviewRatingTypes)){
				foreach($reviewRatingTypes as $key => $review_type){
					$is_present = false;
					$count = 0;
					foreach($review['ReviewRating'] as $rating){
						if($review_type['ReviewRatingType']['id'] == $rating['review_rating_type_id'])
							$is_present = true;
					}
					foreach($review['ReviewRatingStat'] as $rating){
						if($review_type['ReviewRatingType']['id'] == $rating['review_rating_type_id'])
							$count = $rating['count'];
					}
					$reviews_array['RatingType'][$key]['url'] = '/review_ratings/add/sighting_id:'.$review['Sighting']['id'] .'/review_id:'.$review['Review']['id'].'/review_rating_type_id:'. $review_type['ReviewRatingType']['id'];
					$reviews_array['RatingType'][$key]['is_present'] = $is_present;
					$reviews_array['RatingType'][$key]['count'] = $count;
				}				
			}
			if(isset($this->request->params['named']['command'])){
				$reviews_array['status'] = 0;
			}
			
			//print_r($reviews_array);
        }
		$this->set('json',  $reviews_array );
    }	
	

    public function _iphoneImageURLCreate($model, $attachemnt, $title, $thumbs = array())
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
		if(in_array('iphone_big_thumb',$thumbs ))
			$images['iphone_big_thumb'] = Router::url('/', true) . $this->getImageUrl($model, $attachemnt, $image_options_big);
		if(in_array('iphone_small_thumb', $thumbs ))
			$images['iphone_small_thumb'] = Router::url('/', true) . $this->getImageUrl($model, $attachemnt, $image_options_small);
		if(in_array('iphone_micro_thumb', $thumbs))
			$images['iphone_micro_thumb'] = Router::url('/', true) . $this->getImageUrl($model, $attachemnt, $image_options_micro);
		if(in_array('iphone_guide_small_thumb', $thumbs ))
			$images['iphone_guide_small_thumb'] = Router::url('/', true) . $this->getImageUrl($model, $attachemnt, $image_options_guide_small);
		if(in_array('iphone_review_big_thumb', $thumbs))
			$images['iphone_review_big_thumb'] = Router::url('/', true) . $this->getImageUrl($model, $attachemnt, $image_options_review_big);
		if(in_array('map_small_thumb', $thumbs ))
			$images['map_small_thumb'] = Router::url('/', true) . $this->getImageUrl($model, $attachemnt, $image_options_map_small);
        return $images;
    }	
	
}
