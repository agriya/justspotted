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
class PlacesController extends AppController
{
    public $name = 'Places';
	
    public function beforeFilter()
    {
        $this->Security->disabledFields = array(
            'City',
            'State',
            'Place.latitude',
            'Place.longitude',
            'Place.address1',
            'Place.country_id',
			'Place.zoom_level',
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
        $this->pageTitle = __l('Places');
		$conditions['Place.admin_suspend !='] = 1;			
		$order = array(
            'Place.id' => 'DESC'
        );
        if (!empty($this->request->params['pass']['0']) && $this->request->params['pass']['0'] == 'home') {
            $this->pageTitle = __l('Find and Suggest Dishes, Not Only Restaurants.');
        }
        if (!empty($this->request->params['named']['user'])) {
            $place_follows = $this->Place->PlaceFollower->find('all', array(
                'conditions' => array(
                    'User.username = ' => $this->request->params['named']['user']
                ) ,
                'fields' => array(
                    'PlaceFollower.place_id'
                ) ,
                'recursive' => 0
            ));
            if (!empty($place_follows)) {
                foreach($place_follows as $place_follow) {
                    $place_ids[] = $place_follow['PlaceFollower']['place_id']; // get all $place ids, from place follow table

                }
                $conditions['Place.id'] = $place_ids;
            } else {
                $conditions['Place.id'] = ''; // its displays no records

            }
            $this->pageTitle.= ' - ' . __l('Followed by') . ' ' . $this->request->params['named']['user'];
        }
        if (!empty($this->request->params['named']['q'])) {
            $this->request->data['Place']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }

		if (!empty($this->params['named']['from'])) {
			$conditions['Place.id !='] = $this->params['named']['from'];
		} 
        if (!empty($this->params['named']['type']) && $this->params['named']['type'] == 'own') {
            $conditions['Business.user_id'] = $this->Auth->user('id');
        } 
		if (!empty($this->params['named']['business'])) {
            $conditions['Business.slug'] = $this->params['named']['business'];
			$place_count = $this->Place->find('count', array(
				'conditions' => array (
					'Business.slug' => $this->params['named']['business']
				),
				'recursive' => 1
			));
        }
		if (isset($this->request->params['named']['type'])) {
            $order = array(
                'Place.place_follower_count' => 'DESC'
            );
        }
		if (!empty($this->request->params['named']['type']) || $this->request->params['named']['view'] == 'simple') {
            $limit = 10;
        }
		else
		{
			$limit = 20;
		} 
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'PlaceFollower' => array(
                    'conditions' => array(
                        'PlaceFollower.user_id' => $this->Auth->user('id') ,
                    ) ,
                    'limit' => 1,
                ) ,
                'Business' => array(
                    'fields' => array(
                        'Business.id',
                        'Business.name'
                    )
                ) ,
            ) ,
            'order' => $order,
			'limit' => $limit,
            'recursive' => 1
        );
        if (!empty($this->request->data['Place']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->data['Place']['q']
            ));
        }
        $this->set('places', $this->paginate());
        if (!empty($this->params['named']['type']) && $this->params['named']['type'] == 'own') {
            $this->render('business_index');
        }
        if (empty($this->params['named']['type']) && !empty($this->params['named']['business'])) {
			$this->set('place_count', $place_count);
            $this->render('list');
        }
		if (isset($this->request->params['named']['view']) && $this->request->params['named']['view'] == 'simple') {
            $this->render('simple_index');
        }
    }
    public function view($slug = null)
    {
        $this->pageTitle = __l('Place');
        $this->Place->slug = $slug;
        if (!$slug) {
            throw new NotFoundException(__l('Invalid place'));
        }
		if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
			$conditions['Place.admin_suspend !='] = 1;			
		}
		$conditions['Place.slug'] = $slug;
        $place = $this->Place->find('first', array(
            'conditions' => $conditions,
            'contain' => array(
                'PlaceFollower' => array(
                    'conditions' => array(
                        'PlaceFollower.user_id = ' => $this->Auth->user('id')
                    ) ,
                    'limit' => 1,
                ) ,
                'Business' => array (
					'Attachment'
				)
            ) ,
            'recursive' => 2,
        ));
        if (empty($place)) {
			$this->Session->delete('Message.success');
			$this->Session->setFlash(__l('This place is suspended by administrator') , 'default', null, 'error');
            throw new NotFoundException(__l('Invalid request'));
        }
		if(!empty($place['Place']['business_id'])) {
			$item_count = $this->Place->Sighting->find('count', array(
				'conditions' => array(
					'Place.business_id' => $place['Place']['business_id']
				),
				'recursive' => 0,
			));
			$this->set('item_count', $item_count);
		}
        $this->Place->PlaceView->create();
        $this->request->data['PlaceView']['user_id'] = $this->Auth->user('id');
        $this->request->data['PlaceView']['place_id'] = $place['Place']['id'];
        $this->request->data['PlaceView']['ip_id'] = $this->Place->PlaceView->toSaveIp();
        $this->Place->PlaceView->save($this->request->data);
        $this->pageTitle.= ' - ' . $place['Place']['name'];
        $this->set('place', $place);
    }
    public function add()
    { 
        $temp_country_id = '';
        $this->pageTitle = __l('Add New Place');
        if (!empty($this->request->data)) {
            if (!empty($this->request->data['Place']['country_id'])) {
                $temp_country_id = $this->request->data['Place']['country_id'];
                $this->request->data['Place']['country_id'] = $this->Place->Country->findCountryIdFromIso2($this->request->data['Place']['country_id']);
            }
            if (!empty($this->request->data['State']['name'])) {
                $this->request->data['Place']['state_id'] = !empty($this->request->data['State']['id']) ? $this->request->data['State']['id'] : $this->Place->State->findOrSaveAndGetId($this->request->data['State']['name']);
            } else {
                $this->request->data['Place']['state_id'] = $this->request->data['State']['name'];
            }
            $this->request->data['Place']['city_id'] = !empty($this->request->data['City']['id']) ? $this->request->data['City']['id'] : $this->Place->City->findOrSaveCityAndGetId($this->request->data['City']['name'], $this->request->data['Place']['state_id'], $this->request->data['Place']['country_id'], $this->request->data['Place']['latitude'], $this->request->data['Place']['longitude']);
			if($this->Auth->user('is_business_user')){
				$business_id = $this->Place->Business->find('first', array(
					'conditions' => array(
						'Business.user_id' => $this->Auth->user('id')
					) ,
					'fields' => array(
						'Business.id',
					) ,
					'recursive' => -1
				)); 
				$this->request->data['Place']['business_id'] = $business_id['Business']['id'];
			}
			$this->request->data['Place']['user_id'] = $this->Auth->user('id');
            $this->Place->set($this->request->data);
            $this->Place->State->set($this->request->data);
            $this->Place->City->set($this->request->data);
            unset($this->Place->City->validate['City']);
			unset($this->Place->validate['state_id']);
            if ($this->Place->validates() &$this->Place->City->validates() & ($this->Place->State->validates() || empty($this->request->data['Place']['state_id']))) {
                App::import('Vendor', 'geo_hash');
                $this->geohash = new Geohash();
                $this->Place->create();
                $this->request->data['Place']['hash'] = $this->geohash->encode(round($this->request->data['Place']['latitude'], 6) , round($this->request->data['Place']['longitude'], 6));
                $this->Place->save($this->request->data);
                if (!$this->RequestHandler->isAjax()) {
                    $this->Session->setFlash(__l('place has been added') , 'default', null, 'success');
					if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
						$this->redirect(array(
							'action' => 'index'
						));
					} elseif($this->Auth->user('is_business_user')) {
						$this->redirect(array(
							'controller' => 'businesses',
							'action' => 'my_business'
						));
					} else {
						$this->redirect(array(
							'controller' => 'places',
							'action' => 'index'
						));
					}	
                } else {
                    echo $this->Place->getLastInsertId() . '#success';
                    exit;
                }
            } else {
                $this->request->data['Place']['country_id'] = $temp_country_id;
                $this->Session->setFlash(__l('place could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        if (!empty($this->request->params['named']['place'])) {
            $this->request->data['Place']['name'] = str_replace('_', ' ', $this->request->params['named']['place']);
        }
        $placeTypes = $this->Place->PlaceType->find('list', array(
            'order' => array(
                'PlaceType.name' => 'ASC'
            )
        ));
		$countries = $this->Place->Country->find('list', array(
            'fields' => array(
                'Country.iso2',
                'Country.name'
            )
        ));
        $users = $this->Place->User->find('list');
        $this->set(compact('placeTypes', 'users','countries'));
    }
    public function edit($id = null)
    {
        $this->pageTitle = __l('Edit Place');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->Place->id = $id;
        if (!$this->Place->exists()) {
            throw new NotFoundException(__l('Invalid place'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if (!empty($this->request->data['Place']['country_id'])) {
                $temp_country_id = $this->request->data['Place']['country_id'];
                $this->request->data['Place']['country_id'] = $this->Place->Country->findCountryIdFromIso2($this->request->data['Place']['country_id']);
            }
            if (!empty($this->request->data['State']['name'])) {
                $this->request->data['Place']['state_id'] = !empty($this->request->data['State']['id']) ? $this->request->data['State']['id'] : $this->Place->State->findOrSaveAndGetId($this->request->data['State']['name']);
            } else {
                $this->request->data['Place']['state_id'] = $this->request->data['State']['name'];
            }
            if (!empty($this->request->data['State']['name'])) {
                $this->request->data['Place']['city_id'] = !empty($this->request->data['City']['id']) ? $this->request->data['City']['id'] : $this->Place->City->findOrSaveCityAndGetId($this->request->data['City']['name'], $this->request->data['Place']['state_id'], $this->request->data['Place']['country_id'], $this->request->data['Place']['latitude'], $this->request->data['Place']['longitude']);
            }
            $this->Place->set($this->request->data);
            $this->Place->State->set($this->request->data);
            $this->Place->City->set($this->request->data);
            unset($this->Place->City->validate['City']);
            if ($this->Place->validates() &$this->Place->City->validates() &$this->Place->State->validates()) {

				if ($this->Place->save($this->request->data)) {
					$this->Session->setFlash(__l('place has been updated') , 'default', null, 'success');
					if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
						$this->redirect(array(
							'action' => 'index'
						));
					} elseif($this->Auth->user('is_business_user')) {
						$this->redirect(array(
							'controller' => 'businesses',
							'action' => 'my_business'
						));
					} else {
						$this->redirect(array(
							'controller' => 'places',
							'action' => 'index'
						));
					}	
				} else {
					$this->request->data['Place']['country_id'] = $temp_country_id;
					$this->Session->setFlash(__l('place could not be updated. Please, try again.') , 'default', null, 'error');
				}
			}
			else {
					$this->request->data['Place']['country_id'] = $temp_country_id;
					$this->Session->setFlash(__l('place could not be updated. Please, try again.') , 'default', null, 'error');
				}
        } else {
            $this->data = $this->Place->read(null, $id);
			$this->request->data['Place']['country_id'] = $this->request->data['Country']['iso2'];
            if (empty($this->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }

		$this->pageTitle.= ' - ' . $this->data['Place']['name'];
        $placeTypes = $this->Place->PlaceType->find('list');
        $cities = $this->Place->City->find('list');
        $states = $this->Place->State->find('list');
        $countries = $this->Place->Country->find('list', array(
            'fields' => array(
                'Country.iso2',
                'Country.name'
            )
        ));
        $this->set(compact('placeTypes', 'cities', 'states', 'countries'));
    }
    public function admin_index()
    {
        $this->_redirectPOST2Named(array(
            'q'
        ));
        $this->pageTitle = __l('Places');
        $conditions = array();
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Place.created) <= '] = 0;
            $this->pageTitle.= __l(' - today');
        }
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Place.created) <= '] = 7;
            $this->pageTitle.= __l('- in this week');
        }
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Place.created) <= '] = 30;
            $this->pageTitle.= __l(' - in this month');
        }
        if (!empty($this->request->params['named']['q'])) {
            $this->request->data['Place']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        if (isset($this->request->params['named']['item_id'])) {
            $this->loadModel('Sighting');
            $sightings = $this->Sighting->find('list', array(
                'fields' => array(
                    'Sighting.id',
                    'Sighting.place_id',
                ) ,
                'conditions' => array(
                    'Sighting.item_id' => $this->request->params['named']['item_id']
                ) ,
                'recursive' => -1
            ));
            if (!empty($sightings)) {
                $conditions['Place.id'] = $sightings;
            } else {
                $conditions['Place.id'] = 0;
            }
        }
        if (isset($this->request->params['named']['place_type'])) {
            $placeTypeConditions = array(
                'PlaceType.slug' => $this->request->params['named']['place_type']
            );
            $place_type = $this->{$this->modelClass}->PlaceType->find('first', array(
                'conditions' => $placeTypeConditions,
                'fields' => array(
                    'PlaceType.id',
                    'PlaceType.name'
                ) ,
                'recursive' => -1
            ));
            if (empty($place_type)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['Place.place_type_id'] = $place_type['PlaceType']['id'];
            $this->pageTitle.= ' - ' . $place_type['PlaceType']['name'];
        }
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Flagged) {
                $conditions['Place.is_system_flagged'] = 1;
                $this->pageTitle.= __l(' - System Flagged ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Suspend) {
                $conditions['Place.admin_suspend'] = 1;
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
            $conditions['Place.user_id'] = $user['User']['id'];
            $this->pageTitle.= ' - ' . $user['User']['username'];
        }
        $this->Place->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
            'order' => array(
                'Place.id' => 'DESC'
            ) ,
			'recursive' => 2
        );
        if (!empty($this->request->data['Place']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->data['Place']['q']
            ));
        }
        $this->set('places', $this->paginate());
        $moreActions = $this->Place->moreActions;
        $this->set(compact('moreActions'));
        $this->set('flagged', $this->Place->find('count', array(
            'conditions' => array(
                'Place.is_system_flagged = ' => 1,
            )
        )));
        $this->set('all', $this->Place->find('count'));
        $this->set('suspended', $this->Place->find('count', array(
            'conditions' => array(
                'Place.admin_suspend = ' => 1,
            )
        )));
    }
    public function admin_add()
    {
        $this->setAction('add');
    }
    public function admin_edit($id = null)
    {
        $this->pageTitle = __l('Edit Place');
         $this->setAction('edit',$id);
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->Place->id = $id;
        if (!$this->Place->exists()) {
            throw new NotFoundException(__l('Invalid place'));
        }
        if ($this->Place->delete()) {
            $this->Session->setFlash(__l('Place deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        $this->Session->setFlash(__l('Place was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'action' => 'index'
        ));
    }
    public function update($place_id)
    {
        $places = '';
        if ($place_id) {
            $business = $this->Place->Business->find('first', array(
                'conditions' => array(
                    'Business.user_id' => $this->Auth->user('id')
                ) ,
                'recursive' => -1,
                'limit' => 1,
                'order' => array(
                    'Business.id' => 'DESC'
                ) ,
            ));
            if (empty($business)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $this->request->data['Place']['id'] = $place_id;
            $this->request->data['Place']['business_id'] = $business['Business']['id'];
            $this->Place->save($this->request->data);
            $this->Session->setFlash(__l('Place claimed') , 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'businesses',
                'action' => 'my_business'
            ));
        }
    }
	    //<--- Iphone listing and find only
    public function lst()
    {		
        if ($this->RequestHandler->prefers('json')) {
            $this->view = 'Json';
            $conditions = array();
            if (isset($_GET['latitude'])) {
                $this->request->params['named']['latitude'] = $_GET['latitude'];
            }
            if (isset($_GET['longitude'])) {
                $this->request->params['named']['longitude'] = $_GET['longitude'];
            }
			$place_fields = array(
                    'Place.id',
                    'Place.name',
					'Place.slug',
					'Place.address1',					
                );
			$order = array('Place.id' => "DESC");
			if (!empty($_GET['longitude']) && !empty($_GET['latitude'])) {
				$latitude = $_GET['latitude'];
				$longitude = $_GET['longitude'];
				$conditions[] = 'Place.latitude IS NOT NULL';
				$conditions[] = 'Place.longitude IS NOT NULL';
				// geoHash based find and sorting based on distance 
				App::import('Vendor', 'geo_hash');
				$this->geohash = new Geohash();
				$location_hash = $this->geohash->encode(round($this->request->params['named']['latitude'], 6) , round($this->request->params['named']['longitude'], 6));
				$neighbors = $this->geohash->getNeighbors(substr($location_hash, 0, strlen($location_hash) -7));
				array_push($neighbors, substr($location_hash, 0, strlen($location_hash) -7));
				$hash_like = '';
				if (!empty($neighbors)) {
					foreach($neighbors as $key => $neighbor) {
						$hash_like.= " Place.hash LIKE '" . $neighbor . "%' OR";
					}
				}
				$conditions[] = '(' . substr($hash_like, 0, strlen($hash_like) -3) . ')';				
				$place_fields[] = '( 6371 * acos( cos( radians(' . $latitude . ') ) * cos( radians( Place.latitude ) ) * cos( radians( Place.longitude ) - radians(' . $longitude . ') ) + sin( radians(' . $latitude . ') ) * sin( radians( Place.latitude ) ) ) ) AS distance';
				$order = array();
				$order = array('distance' => 'asc');
			}			
			$places = $this->Place->find('all', array(
                'conditions' => $conditions,
                'fields' => $place_fields ,
				'order' => $order ,
				'limit' => 30,
                'recursive' => -1
            ));		
			$this->set('json', (empty($this->viewVars['iphone_response'])) ? $places : $this->viewVars['iphone_response']);
		}	
	}
}
