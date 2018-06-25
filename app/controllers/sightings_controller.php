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
class SightingsController extends AppController
{
    public $name = 'Sightings';
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
        $this->pageTitle = __l(Configure::read('site.slogan'));
        if (!$this->request->is('post')) {
            if (!empty($_COOKIE['_last_location']) && $_COOKIE['_last_location'] != 'Anywhere' && $_COOKIE['_last_location'] != 'Map Area') {
                $this->request->params['named']['location'] = $_COOKIE['_last_location'];
                if (!empty($_COOKIE['_geo_last_location'])) {
                    $_geo = explode('|', $_COOKIE['_geo_last_location']);
                    $this->request->params['form']['latitude'] = $this->request->params['named']['latitude'] = $_geo[0];
                    $this->request->params['form']['longitude'] = $this->request->params['named']['longitude'] = $_geo[1];
                    $this->request->params['named']['zoom'] = 10;
                }
            }
        }
        $conditions = array();
        $limit = 20;
        $this->_redirectPOST2Named(array(
            'q',
            'latitude',
            'longitude',
            'sighting_search',
            'sw_latitude',
            'sw_longitude',
            'ne_latitude',
            'ne_longitude',
			'location',
        ));
        $order = array(
            'Sighting.id' => 'DESC'
        );
		if (!empty($this->request->params['form']['zoom_level'])) {
			$this->request->params['named']['zoom_level'] = $this->request->params['form']['zoom_level'];
		}
        if (!empty($this->request->params['form']['q'])) {
            $this->request->data['Sighting']['q'] = $this->request->params['named']['q'] = $this->request->params['form']['q'];
            $this->request->params['named']['created'] = $this->request->params['named']['place'] = $this->request->params['named']['guide'] = $this->request->params['named']['item'] = $this->request->params['named']['q'];
        }
        if (!empty($this->request->params['named']['sighting_rating_type']) || !empty($this->request->params['named']['sighting_rating_type_id'])) {
            $this->loadModel('SightingRatingType');
        }
        if (!empty($this->request->params['named']['sighting_rating_type']) && !empty($this->request->params['named']['user'])) {
            $sightingRatings = $this->SightingRatingType->SightingRating->find('all', array(
                'conditions' => array(
                    'User.username' => $this->request->params['named']['user'],
                    'SightingRatingType.slug' => $this->request->params['named']['sighting_rating_type']
                ) ,
                'fields' => array(
                    'SightingRating.sighting_id'
                ) ,
                'recursive' => 0
            ));
            if (empty($sightingRatings)) {
                $conditions['Sighting']['id'] = 0;
            } else {
                foreach($sightingRatings as $sightingRating) {
                    $conditions['Sighting.id'][] = $sightingRating['SightingRating']['sighting_id'];
                }
            }
        }
        if (!empty($this->request->params['named']['sighting_rating_type_id'])) {
            $page = $rating = array();
            if (!empty($this->request->params['named']['page'])) {
                $page = $this->request->params['named']['page'];
            }
            $sightingRatingStats = $this->SightingRatingType->SightingRatingStat->find('all', array(
                'conditions' => array(
                    'SightingRatingStat.sighting_rating_type_id' => $this->request->params['named']['sighting_rating_type_id']
                ) ,
                'order' => array(
                    'SightingRatingStat.id' => 'DESC'
                ) ,
                'page' => $page,
                'recusive' => -1
            ));
            if (empty($sightingRatingStats)) {
                $conditions['Sighting']['id'] = 0;
            } else {
                foreach($sightingRatingStats as $sightingRatingStat) {
                    $conditions['Sighting.id'][] = $sightingRatingStat['SightingRatingStat']['sighting_id'];
                    $rating[$sightingRatingStat['SightingRatingStat']['sighting_id']] = $sightingRatingStat['SightingRatingStat']['count'];
                }
            }
        }
        if (!empty($this->request->params['named']['business'])) {
            $places = $this->Sighting->Place->find('all', array(
                'conditions' => array(
                    'Business.slug =' => $this->request->params['named']['business']
                ) ,
                'fields' => array(
                    'Place.id',
                ) ,
                'recursive' => 0
            ));
            $places_id = array();
            foreach($places as $place) {
                $places_id[] = $place['Place']['id'];
            }
            if (!empty($places)) {
                $conditions['Sighting.place_id'] = $places_id;
            } else {
                if (empty($this->request->params['form']['q'])) {
                    $conditions['Sighting.place_id'] = 0;
                }
            }
            $limit = 5;
        }
        if (!empty($this->request->params['named']['place'])) {
            $place_conditions = array();
            if (!empty($this->request->params['form']['q'])) {
                $place_conditions['Place.name LIKE'] = '%' . $this->request->params['named']['place'] . '%';
            } else {
                $place_conditions['Place.slug'] = $this->request->params['named']['place'];
            }
            $place = $this->Sighting->Place->find('list', array(
                'conditions' => $place_conditions,
                'fields' => array(
                    'Place.id',
                    'Place.id',
                ) ,
                'recursive' => -1
            ));
            if (!empty($place)) {
                $conditions['Sighting.place_id'] = $place;
            } else {
                if (empty($this->request->params['form']['q'])) {
                    $conditions['Sighting.place_id'] = 0;
                }
            }
            $this->set('place', $this->request->params['named']['place']);
        }
        if (isset($this->request->params['named']['guide'])) {
            $guide = $this->Sighting->Guide->find('first', array(
                'conditions' => array(
                    'Guide.slug' => $this->request->params['named']['guide']
                ) ,
                'recursive' => -1
            ));
            $guides_sightings = $this->Sighting->GuidesSighting->find('all', array(
                'conditions' => array(
                    'GuidesSighting.guide_id' => $guide['Guide']['id']
                ) ,
                'recursive' => -1
            ));
            if (!empty($guides_sightings)) {
                $sighting_ids = array();
                foreach($guides_sightings As $guides_sighting) {
                    $sighting_ids[] = $guides_sighting['GuidesSighting']['sighting_id'];
                }
                $conditions['Sighting.id'] = $sighting_ids;
            } else {
                if (empty($this->request->params['form']['q'])) {
                    $conditions['Sighting.id'] = 0;
                }
            }
        }
        // for item i follow from user dashboard
        if (!empty($this->request->params['named']['user']) && empty($this->request->params['named']['sighting_rating_type'])) {
            $user = $this->Sighting->SightingView->User->find('first', array(
                'conditions' => array(
                    'User.username' => $this->request->params['named']['user']
                ) ,
                'fields' => array(
                    'User.id',
                    'User.username',
                ) ,
                'recursive' => -1
            ));
            if (!empty($user)) {
                $user_item_follower = $this->Sighting->Item->ItemFollower->find('list', array(
                    'conditions' => array(
                        'ItemFollower.user_id' => $user['User']['id']
                    ) ,
                    'fields' => array(
                        'ItemFollower.item_id',
                        'ItemFollower.item_id',
                    ) ,
                    'recursive' => -1
                ));
                if (!empty($user_item_follower)) {
                    $conditions['Sighting.item_id'] = $user_item_follower;
                } else {
                    $conditions['Sighting.item_id'] = 0;
                }
                $this->pageTitle.= ' - ' . __l('Items followed by ') . $this->request->params['named']['user'];
            } else {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        if (!empty($this->request->params['named']['item'])) {
            $sighting_item = $this->Sighting->Item->find('list', array(
                'conditions' => array(
					'OR' => array(
	                    'Item.name LIKE ' => '%' . $this->request->params['named']['item'] . '%',
						'Item.slug LIKE ' => '%' . $this->request->params['named']['item'] . '%'
					)
				) ,
                'fields' => array(
                    'Item.id',
                    'Item.id',
                ) ,
                'recursive' => -1
            ));
            if (!empty($sighting_item)) {
                $conditions['Sighting.item_id'] = $sighting_item;
            } else {
                if (empty($this->request->params['form']['q'])) {
                    $conditions['Sighting.item_id'] = 0;
                }
            }
            $this->pageTitle.= ' - ' . __l('Search') . ' - ' . __l('Item') . ' - ' . (!empty($sighting_item['Item']['name']) ? $sighting_item['Item']['name'] : '');
        }
        if (!empty($this->request->params['named']['created'])) {
            $reviews = $this->Sighting->Review->find('list', array(
                'conditions' => array(
                    'User.username' => $this->request->params['named']['created']
                ) ,
                'fields' => array(
                    'Review.id',
                    'Review.sighting_id',
                ) ,
                'group' => 'Review.sighting_id',
                'recursive' => 0
            ));
            if (!empty($reviews)) {
                $conditions['Sighting.id'] = $reviews;
            } else {
                if (empty($this->request->params['form']['q'])) {
                    $conditions['Sighting.id'] = 0;
                }
            }
            $this->pageTitle.= ' - ' . __l('Search') . ' - ' . __l('User') . ' - ' . $this->request->params['named']['created'];
        }
        if (!empty($this->request->params['form']['q']) && empty($reviews) && empty($guides_sightings) && empty($sighting_item) && empty($place) && empty($this->request->params['form']['latitude'])) {
            $conditions['Sighting.id'] = 0;
        }
		if(isset($this->request->params['form']['location'])) {
			$this->request->params['named']['location'] = $this->request->params['form']['location'];
		}
		if (!empty($this->request->params['form']['sw_latitude']) || !empty($this->request->params['named']['sw_latitude']) && empty($this->request->params['form']['latitude']) && empty($this->request->params['named']['latitude'])) {
			$zoom_level = (!empty($this->request->params['form']['zoom_level']) ? $this->request->params['form']['zoom_level'] :$this->request->params['named']['zoom_level']);
			if($zoom_level >= 2) {
				$lon1 = (!empty($this->request->params['named']['sw_longitude']) ? $this->request->params['named']['sw_longitude'] : $this->request->params['form']['sw_longitude']);
				$lon2 = (!empty($this->request->params['named']['ne_longitude']) ? $this->request->params['named']['ne_longitude'] : $this->request->params['form']['ne_longitude']);
				$lat1 = (!empty($this->request->params['named']['sw_latitude']) ? $this->request->params['named']['sw_latitude'] : $this->request->params['form']['sw_latitude']);
				$lat2 = (!empty($this->request->params['named']['ne_latitude']) ? $this->request->params['named']['ne_latitude'] : $this->request->params['form']['ne_latitude']);
				if ($lat1 > $lat2) {
					$temp = $lat1;
					$lat1 = $lat2;
					$lat2 = $temp;
				}
				if ($lon1 > $lon2) {
					$temp = $lon1;
					$lon1 = $lon2;
					$lon2 = $temp;
				}
				$conditions['Place.latitude BETWEEN ? AND ?'] = array(
					$lat1,
					$lat2
				);
				$conditions['Place.longitude BETWEEN ? AND ?'] = array(
					$lon1,
					$lon2
				);
				$conditions[] = 'Place.latitude IS NOT NULL';
				$conditions[] = 'Place.longitude IS NOT NULL';
				
				// setting values for paginations
				$this->request->params['named']['sw_longitude'] = $lon1;
				$this->request->params['named']['ne_longitude'] = $lon2;
				$this->request->params['named']['sw_latitude'] = $lat1;
				$this->request->params['named']['ne_latitude'] = $lat2;
			}
		} elseif ((!empty($this->request->params['form']['latitude']) || !empty($this->request->params['named']['latitude']))) {
			$longitude = (!empty($this->request->params['named']['longitude']) ? $this->request->params['named']['longitude'] : $this->request->params['form']['longitude']);
			$latitude = (!empty($this->request->params['named']['latitude']) ? $this->request->params['named']['latitude'] : $this->request->params['form']['latitude']);
			$conditions[] = 'Place.latitude IS NOT NULL';
			$conditions[] = 'Place.longitude IS NOT NULL';
			// setting values for paginations
			$this->request->params['named']['longitude'] = $longitude;
			$this->request->params['named']['latitude'] = $latitude;
			App::import('Vendor', 'geo_hash');
			$this->geohash = new Geohash();
			$location_hash = $this->geohash->encode(round($latitude, 6) , round($longitude, 6));
			$neighbors = $this->geohash->getNeighbors($location_hash);
			array_push($neighbors, substr($location_hash, 0, strlen($location_hash) -7));
			$hash_like = '';
			if (!empty($neighbors)) {
				foreach($neighbors as $key => $neighbor) {
					$hash_like.= " Place.hash LIKE '" . $neighbor . "%' OR";
				}
			}
			$conditions[] = '(' . substr($hash_like, 0, strlen($hash_like) -3) . ')';
		}
        $this->Sighting->recursive = 0;
        if (!empty($this->request->params['named']['view']) && $this->request->params['named']['view'] == 'json') {
            $contain = array(
                'Item' => array(
                    'fields' => array(
                        'Item.name',
                        'Item.slug',
                    )
                ) ,
                'Place' => array(
                    'fields' => array(
                        'Place.latitude',
                        'Place.longitude',
                        'Place.name',
                        'Place.address2',
                    )
                ) ,
                'Review' => array(
                    'fields' => array(
                        'Review.sighting_id',
                    ) ,
                    'Attachment',
                )
            );
        } else {
            $contain = array(
                'Item',
                'Place' => array(
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
                    'Attachment',
                    'User' => array(
                        'UserAvatar',
                    ) ,
                    'order' => array(
                        'Review.id' => 'DESC'
                    ) ,
                    'limit' => 6,
                ) ,
                'BaseReview' => array(
                    'Attachment',
                )
            );
        }		
		if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
			$conditions['Sighting.admin_suspend !='] = 1;
			$conditions['AND']['Place.admin_suspend !='] = 1;
            $conditions['AND']['Item.admin_suspend !='] = 1;
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => $contain,
            'limit' => $limit,
            'order' => $order,
        );
        $sightings = $this->paginate();
        if (!empty($this->request->params['named']['view']) && $this->request->params['named']['view'] == 'json') {
            $this->view = 'Json';
            if (!empty($sightings)) {
                $sighting_count = count($sightings);
                for ($r = 0; $r < $sighting_count; $r++) {
                    $image_options = array(
                        'dimension' => 'map_small_thumb',
                        'class' => '',
                        'alt' => $sightings[$r]['Item']['name'],
                        'title' => $sightings[$r]['Item']['name'],
                        'type' => 'png'
                    );
                    getimagesize(Router::url('/', true) . $this->getImageUrl('Review', $sightings[$r]['Review'][0]['Attachment'], $image_options));
                    $sighting_image = $this->getImageUrl('Review', $sightings[$r]['Review'][0]['Attachment'], $image_options);
                    $sightings[$r]['Sighting']['small_thumb'] = $sighting_image;
                    $medium_image_options = array(
                        'dimension' => 'medium_thumb',
                        'class' => '',
                        'alt' => $sightings[$r]['Item']['name'],
                        'title' => $sightings[$r]['Item']['name'],
                        'type' => 'png'
                    );
                    getimagesize(Router::url('/', true) . $this->getImageUrl('Review', $sightings[$r]['Review'][0]['Attachment'], $medium_image_options));
                    $sighting_medium_image = $this->getImageUrl('Review', $sightings[$r]['Review'][0]['Attachment'], $medium_image_options);
                    $sightings[$r]['Sighting']['medium_thumb'] = $sighting_medium_image;
                }
            }
            $this->set('json', $sightings);
        } elseif (!empty($this->request->params['named']['sighting_rating_type_id']) && empty($this->request->params['named']['sighting_rating_type'])) {
            if (!empty($sightingRatingStats) && !empty($sightings)) {
                foreach($sightings as $key => $sighting) {
                    $sightings[$key]['Sighting']['display_order'] = (isset($rating[$sighting['Sighting']['id']]) ? $rating[$sighting['Sighting']['id']] : 0);
                }
            }
            $result = Set::sort($sightings, '{n}.Sighting.display_order', 'desc');
            $this->set('sightings', $result);
        } else {
            $this->set('sightings', $sightings);
        }
    }
    //<--- Iphone listing and find only
    public function lst()
    {
        if ($this->RequestHandler->prefers('json')) {
            $this->view = 'Json';
            $flage_place = 0;
            $flage_guide = 0;
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
						'Review.notes',
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
                'SightingRatingStat',
				'SightingRating' => array(
					'fields' => array(
						'SightingRating.id',
						'SightingRating.sighting_id',
						'SightingRating.user_id',
						'SightingRating.sighting_rating_type_id',
						'SightingRating.id',
					),
                    'conditions' => array(
                        'SightingRating.sighting_id' => $id,
						'SightingRating.user_id' => $this->Auth->user('id')
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
            );
            $order = array(
                'Sighting.id' => 'DESC'
            );
            if (isset($_GET['place'])) {
                $this->request->params['named']['place'] = $_GET['place'];
            }
            if (isset($_GET['item'])) {
                $this->request->params['named']['item'] = $_GET['item'];
            }
            if (isset($_GET['latitude'])) {
                $this->request->params['named']['latitude'] = $_GET['latitude'];
            }
            if (isset($_GET['longitude'])) {
                $this->request->params['named']['longitude'] = $_GET['longitude'];
            }
            if (!empty($this->request->params['form']['q'])) {
                $this->request->params['named']['q'] = $this->request->params['form']['q'];
            }
            $sightings_array = array();
            if (isset($this->request->params['form']['nomit'])) {
                $this->loadModel('SightingRatingType');
                $sightingRatingStats = $this->SightingRatingType->SightingRatingStat->find('all', array(
                    'conditions' => array(
                        'SightingRatingStat.sighting_rating_type_id' => 1
                    ) ,
                    'order' => array(
                        'SightingRatingStat.id' => 'DESC'
                    ) ,
                    'recusive' => -1
                ));
                if (!empty($sightingRatingStats)) {
                    foreach($sightingRatingStats as $sightingRatingStat) {
                        $conditions['Sighting.id'][] = $sightingRatingStat['SightingRatingStat']['sighting_id'];
                    }
                }
                if ($this->request->params['form']['nomit'] != 'yes') {
                    //$conditions['NOT']['Sighting.id'] = $conditions['Sighting.id'];
                    unset($conditions['Sighting.id']);
                }
            }
            if (isset($_GET['place_id']) && isset($_GET['place_type'])) {
                $flage_place = 1;
                $condition_place = array();
                $condition_place['Sighting.place_id'] = $_GET['place_id'];
            }
            if (isset($_GET['guide_id']) && isset($_GET['guide_type'])) {
                $flage_guide = 1;
                $sighting_ids = $this->Sighting->GuidesSighting->find('list', array(
                    'conditions' => array(
                        'GuidesSighting.guide_id' => $_GET['guide_id']
                    ),
					'fields' => array(
						'GuidesSighting.sighting_id',
						'GuidesSighting.sighting_id'
					),
                ));
                if (!empty($sighting_ids)) {
                    $condition_guide['Sighting.id'] = $sighting_ids;
                } else {
                    $condition_guide['Sighting.id'] = 0;
                }
            }
            if (!empty($this->request->params['named']['place']) || !empty($this->request->params['named']['q'])) {
                $cond = array();
                if (!empty($this->request->params['named']['place'])) {
                    $cond['Place.slug'] = $this->request->params['named']['place'];
                } elseif (!empty($this->request->params['named']['q'])) {
                    $cond['Place.name LIKE'] = '%' . $this->request->params['named']['q'] . '%';
                }
                $place = $this->Sighting->Place->find('list', array(
                    'conditions' => $cond,
                    'fields' => array(
                        'Place.id',
                        'Place.id',
                    ) ,
                    'recursive' => -1
                ));
                if (!empty($place)) {
                    $conditions['Sighting.place_id'] = $place;
                } else {
                    $conditions['Sighting.place_id'] = 0;
                }
            }
            if (!empty($this->request->params['named']['item']) || !empty($this->request->params['named']['q'])) {
                $cond = array();
                if (!empty($this->request->params['named']['item'])) {
                    $cond['Item.slug'] = $this->request->params['named']['item'];
                } elseif (!empty($this->request->params['named']['q'])) {
                    $cond['Item.name LIKE'] = '%' . $this->request->params['named']['q'] . '%';
                }
                $sighting_item = $this->Sighting->Item->find('list', array(
                    'conditions' => $cond,
                    'fields' => array(
                        'Item.id',
                        'Item.id',
                    ) ,
                    'recursive' => -1
                ));
                if (!empty($sighting_item)) {
                    $conditions['Sighting.item_id'] = $sighting_item;
                } else {
                    $conditions['Sighting.item_id'] = 0;
                }
            }
            if (!empty($this->request->params['named']['item']) || !empty($this->request->params['form']['q']) || isset($this->request->params['form']['nomit']) || !empty($this->request->params['named']['place']) || isset($this->request->params['form']['q'])) {
                if ($conditions['Sighting.item_id'] == 0) {
                    unset($conditions['Sighting.item_id']);
                }
                if ($conditions['Sighting.place_id'] == 0) {
                    unset($conditions['Sighting.place_id']);
                }
                if (empty($conditions)) {
                    $conditions['Sighting.id'] = 0;
                }
				$conditions['Sighting.admin_suspend !='] = 1;
				$conditions['AND']['Place.admin_suspend !='] = 1;
            	$conditions['AND']['Item.admin_suspend !='] = 1;
                $this->paginate = array(
                    'conditions' => $conditions,
                    'contain' => $contain,
                    'order' => $order,
                );
                $sightings_array['Search']['Sighting'] = $this->paginate();
                $place_ids=array();
                foreach($sightings_array['Search']['Sighting'] as $key => $sightings) {
                    $sightings_array['Search']['Sighting'][$key]['Sighting']['nom_it_count']=0;
                    $sightings_array['Search']['Sighting'][$key]['Sighting']['want_it_count']=0;
					$sightings_array['Search']['Sighting'][$key]['Sighting']['tried_it_count']=0;
                    if(!empty($sightings['SightingRatingStat'])){
                            foreach($sightings['SightingRatingStat'] as $sighting_rating_stat){
                                    if($sighting_rating_stat['sighting_rating_type_id']==1){
										$is_present = false;
										foreach($sightings['SightingRating'] as $rating){
											if($sighting_rating_stat['sighting_rating_type_id'] == $rating['sighting_rating_type_id'])
												$is_present = true;
										}		
										$sightings_array['Search']['Sighting'][$key]['Sighting']['nom_it_is_present']=$is_present;
                                        $sightings_array['Search']['Sighting'][$key]['Sighting']['nom_it_count']=$sighting_rating_stat['count'];
                                    }
                                    if($sighting_rating_stat['sighting_rating_type_id']==2){
										$is_present = false;
										foreach($sightings['SightingRating'] as $rating){
											if($sighting_rating_stat['sighting_rating_type_id'] == $rating['sighting_rating_type_id'])
												$is_present = true;
										}
										$sightings_array['Search']['Sighting'][$key]['Sighting']['want_it_is_present']=$is_present;
                                        $sightings_array['Search']['Sighting'][$key]['Sighting']['want_it_count']=$sighting_rating_stat['count'];
                                    }
									if($sighting_rating_stat['sighting_rating_type_id']==3){
										$is_present = false;
										foreach($sightings['SightingRating'] as $rating){
											if($sighting_rating_stat['sighting_rating_type_id'] == $rating['sighting_rating_type_id'])
												$is_present = true;
										}
										$sightings_array['Search']['Sighting'][$key]['Sighting']['tried_it_is_present']=$is_present;     $sightings_array['Search']['Sighting'][$key]['Sighting']['tried_it_count']=$sighting_rating_stat['count'];
                                    }
                            }
                    }
                    unset($sightings['SightingRatingStat']);
                    foreach($sightings['Review'] as $key_review => $review) {
                        if (empty($flage_place) && empty($flage_guide)) {
							if(empty($review['Attachment']['id']))
							{
								$review['Attachment'] = array();
							}
                            $sightings_array['Search']['Sighting'][$key]['Review'][$key_review]['image'] = $this->_iphoneImageURLCreate('Review', $review['Attachment'], $sightings['Item']['name']);
                        }
						if(empty($review['User']['UserAvatar']['id']))
						{
							$review['User']['UserAvatar'] = array();
						}
						$sightings_array['Search']['Sighting'][$key]['Review'][$key_review]['created'] = date('M d, Y', strtotime($review['created'])); 
						$sightings_array['Search']['Sighting'][$key]['Review'][$key_review]['notes'] = (!empty($review['notes']) && (strlen($review['notes']) > 25) ) ? substr($review['notes'], 0, 25). '...' : ((!empty($review['notes'])) ? $review['notes'] : "");
                        $sightings_array['Search']['Sighting'][$key]['Review'][$key_review]['UserAvatar'] = $this->_iphoneImageURLCreate('UserAvatar', $review['User']['UserAvatar'], $review['User']['username']);
                        unset($sightings_array['Search']['Sighting'][$key]['Review'][$key_review]['Attachment']);
                        unset($sightings_array['Search']['Sighting'][$key]['Review'][$key_review]['User']['UserAvatar']);
                    }
                    $place_ids[]=$sightings['Sighting']['place_id'];
                    $sighting_ids[]=$sightings['Sighting']['id'];
                }
                unset($conditions_place);
                if(empty($place_ids)){
                    $conditions_place['Place.id']=0;
                }
                else{
                    $conditions_place['Place.id']=$place_ids;
                }
                if(empty($sighting_ids)){
                    $conditions_sight['Guide.id']=0;
                }
                else{
                    $guide_sightings=$this->Sighting->Guide->GuidesSighting->find('list',array(
                        'conditions'=>array(
                            'GuidesSighting.sighting_id'=>$sighting_ids
                        ),
						'fields' => array(
							'GuidesSighting.sighting_id',
							'GuidesSighting.sighting_id'
						),
                    ));
                    $conditions_sight['Guide.id']=$guide_sightings;
                }
                $guides = $this->Sighting->Guide->find('all', array(
                    'conditions'=>$conditions_sight,
                    'contain' => array(
                        'Attachment'
                    ) ,
                    'limit' => 20
                ));
				$conditions_place['Place.admin_suspend !='] = 1;
				$place = $this->Sighting->Place->find('all', array(
                    'conditions' => $conditions_place,
                    'contain' => array(
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
                    'recursive' => 2
                ));
                $sightings_array['Search']['Place'] = $place;
                foreach($guides as $key => $guide) {
                    $guides[$key]['images'] = $this->_iphoneImageURLCreate('Guide', $guide['Attachment'], $guide['Guide']['name']);
                    unset($guides[$key]['Attachment']);
                }
                $sightings_array['Search']['Guide'] = $guides;
				$sightings_array['search_string'] = $this->request->params['form']['q'];
				$sightings_array['status'] = 0;
            }
            if (empty($this->request->params['named']['item']) && empty($this->request->params['named']['q']) && empty($this->request->params['named']['place']) && !(isset($this->request->params['form']['q']))) {
                $conditions = array();
                $conditions_place = array();
				$contains_place = "";
                if (empty($flage_place) && empty($flage_guide)) {
                    $latitude = $_GET['latitude'];
                    $longitude = $_GET['longitude'];
                    $conditions[] = 'Place.latitude IS NOT NULL';
                    $conditions[] = 'Place.longitude IS NOT NULL';
                    $this->request->params['named']['longitude'] = $longitude;
                    $this->request->params['named']['latitude'] = $latitude;
					// distance based sorting
					   $contains_place = $contain['Place']['fields'][] = '( 6371 * acos( cos( radians(' . $this->request->params['named']['latitude'] . ') ) * cos( radians( Place.latitude ) ) * cos( radians( Place.longitude ) - radians(' . $this->request->params['named']['longitude'] . ') ) + sin( radians(' . $this->request->params['named']['latitude'] . ') ) * sin( radians( Place.latitude ) ) ) ) AS distance';
						$order = array(
							'distance' => 'ASC'
						);			
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
				   $conditions_place = $conditions;
				   $conditions['Sighting.admin_suspend !='] = 1;
				   $conditions['AND']['Place.admin_suspend !='] = 1;
				   $conditions['AND']['Item.admin_suspend !='] = 1;
                }
                if ($flage_place) {
                    unset($conditions);
                    $conditions = $condition_place;
                }
                if ($flage_guide) {
                    unset($conditions);
                    $conditions = $condition_guide;
                }
                $this->paginate = array(
                    'conditions' => $conditions,
                    'contain' => $contain,
                    'order' => $order,
                );
                $sightings_array['nearest']['Sighting'] = $this->paginate();
                foreach($sightings_array['nearest']['Sighting'] as $key => $sightings) {
                    $sightings_array['nearest']['Sighting'][$key]['Sighting']['nom_it_count']=0;
                    $sightings_array['nearest']['Sighting'][$key]['Sighting']['want_it_count']=0;
					$sightings_array['nearest']['Sighting'][$key]['Sighting']['tried_it_count']=0;
                    if(!empty($sightings['SightingRatingStat'])){
                            foreach($sightings['SightingRatingStat'] as $sighting_rating_stat){
                                    if($sighting_rating_stat['sighting_rating_type_id']==1){
                                        $sightings_array['nearest']['Sighting'][$key]['Sighting']['nom_it_count']=$sighting_rating_stat['count'];
                                    }
                                    if($sighting_rating_stat['sighting_rating_type_id']==2){
                                        $sightings_array['nearest']['Sighting'][$key]['Sighting']['want_it_count']=$sighting_rating_stat['count'];
                                    }
									if($sighting_rating_stat['sighting_rating_type_id']==3){
                                        $sightings_array['nearest']['Sighting'][$key]['Sighting']['tried_it_count']=$sighting_rating_stat['count'];
                                    }
                            }
                    }
                    unset($sightings['SightingRatingStat']);
                    foreach($sightings['Review'] as $key_review => $review) {
						$sightings_array['nearest']['Sighting'][$key]['Review'][$key_review]['created'] = date('M d, Y', strtotime($review['created'])); 
						$sightings_array['nearest']['Sighting'][$key]['Review'][$key_review]['notes'] = (!empty($review['notes']) && (strlen($review['notes']) > 25) ) ? substr($review['notes'], 0, 25). '...' : ((!empty($review['notes'])) ? $review['notes'] : "");						
                        $sightings_array['nearest']['Sighting'][$key]['Review'][$key_review]['image'] = $this->_iphoneImageURLCreate('Review', $review['Attachment'], $sightings['Item']['name']);
                        $sightings_array['nearest']['Sighting'][$key]['Review'][$key_review]['UserAvatar'] = $this->_iphoneImageURLCreate('UserAvatar', $review['User']['UserAvatar'], $review['User']['username']);
                        unset($sightings_array['nearest']['Sighting'][$key]['Review'][$key_review]['Attachment']);
                        unset($sightings_array['nearest']['Sighting'][$key]['Review'][$key_review]['User']['UserAvatar']);
                    }
                    $place_ids[]=$sightings['Sighting']['place_id'];
                    $sighting_ids[]=$sightings['Sighting']['id'];
                }
                           if (empty($flage_place) && empty($flage_guide)) {
                           //unset($conditions_place);
                if(empty($place_ids)){
                    //$conditions_place['Place.id']=0;
                }
                else{
                    //$conditions_place['Place.id']=$place_ids;
                }
                if(empty($sighting_ids)){
                    $conditions_sight['Guide.id']=0;
                }
                else{
                    $guide_sightings=$this->Sighting->Guide->GuidesSighting->find('list',array(
                        'conditions'=>array(
                            'GuidesSighting.sighting_id'=>$sighting_ids
                        ),
						'fields' => array(
							'GuidesSighting.sighting_id',
							'GuidesSighting.sighting_id'
						),
                    ));
                    $conditions_sight['Guide.id']=$guide_sightings;
                }
                $guides = $this->Sighting->Guide->find('all', array(
                    'conditions'=>$conditions_sight,
                    'contain' => array(
                        'Attachment'
                    ) ,
                    'limit' => 20
                ));
				$conditions_place['Place.admin_suspend !='] = 1;
				$place_order = array('Place.id' => "DESC");
				$place_fields = array(
                        'Place.id',
                        'Place.latitude',
                        'Place.longitude',
                        'Place.name',
                        'Place.slug',
                        'Place.address1',
                        'Place.place_follower_count',
                        'Place.item_count',
                        'Place.place_view_count',
                    );
				if(!empty($contains_place)){
					$place_order = array();
					$place_fields[] = $contains_place;
					$place_order['distance'] = "ASC";
				}
                $place = $this->Sighting->Place->find('all', array(
                    'conditions' => $conditions_place,
                    'contain' => array(
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
                    'fields' => $place_fields ,
					'order' => $place_order,
					'limit' => 20,
                    'recursive' => 2
                ));
                $sightings_array['nearest']['Place'] = $place;
                foreach($guides as $key => $guide) {
					if(empty($guide['Attachment']['id']))
					{
						$guide['Attachment'] = array();
					}
                    $guides[$key]['images'] = $this->_iphoneImageURLCreate('Guide', $guide['Attachment'], $guide['Guide']['name']);
                    unset($guides[$key]['Attachment']);
                }
                $sightings_array['nearest']['Guide'] = $guides;
			
            }
            }$sightings_array['status'] = 0;
            $this->set('json', (empty($this->viewVars['iphone_response'])) ? $sightings_array : $this->viewVars['iphone_response']);
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
    public function simple_index()
    {
        $this->pageTitle = __l('Sighting');
        if (empty($this->request->params['named']['place_id']) || empty($this->request->params['named']['sighting_id'])) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $conditions = array();
        if (!empty($this->request->params['named']['sighting_id'])) {
            $conditions['Sighting.id != '] = $this->request->params['named']['sighting_id'];
        }
        if (!empty($this->request->params['named']['place_id'])) {
            $conditions['Sighting.place_id'] = $this->request->params['named']['place_id'];
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'Item' => array(
                    'fields' => array(
                        'Item.name'
                    )
                ) ,
                'Place' => array(
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
                    'Attachment',
                )
            ) ,
            'order' => array(
                'Sighting.id' => 'DESC'
            ) ,
            'recursive' => 2,
            'limit' => 4
        );
        $simpleSightings = $this->paginate();
        $this->set('simpleSightings', $simpleSightings);
    }
    public function view($id = null)
    {
		$this->pageTitle = __l('Sighting');
        $this->Sighting->id = $id;
        if (!$this->Sighting->exists()) {
            throw new NotFoundException(__l('Invalid sighting'));
        }
        $conditions = array();
        $conditions['Sighting.id'] = $id;
        if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
			$conditions['Sighting.admin_suspend !='] = 1;
            $conditions['AND']['Place.admin_suspend !='] = 1;
            $conditions['AND']['Item.admin_suspend !='] = 1;
        }
        $sighting = $this->Sighting->find('first', array(
            'conditions' => $conditions,
            'contain' => array(
				'SightingRating' => array (
					'limit' => 1,
				),
                'Item' => array(
                    'ItemFollower'
                ) ,
                'Place' => array(
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
                    'Attachment',
                    'User' => array(
                        'UserAvatar',
                    )
                ) ,
                'BaseReview' => array(
                    'Attachment',
                    'User' => array(
                        'UserAvatar',
                    )
                ) ,
            ) ,
        ));
        if (empty($sighting)) {
			$this->Session->delete('Message.success');
			$this->Session->setFlash(__l('This sighting is suspended by administrator') , 'default', null, 'error');
            throw new NotFoundException(__l('Invalid request'));
        }
		if (!empty($sighting['Item']['name'])) {
            Configure::write('meta.sighting_name', $sighting['Item']['name'] . ' @ ' . $sighting['Place']['name']);
        }
		if (!empty($sighting['Review'][0]['Attachment'])) {
            $image_options = array(
                'dimension' => 'small_large',
                'class' => '',
                'alt' => $sighting['Item']['name'],
                'title' => $sighting['Item']['name'],
                'type' => 'png'
            );
            $sighting_image = $this->Sighting->getImageUrl('Review', $sighting['Review'][0]['Attachment'], $image_options);
            Configure::write('meta.sighting_image', $sighting_image);
        }
		if (!empty($sighting['Review'][0]['notes'])) {
			Configure::write('meta.sighting_notes', $sighting['Review'][0]['notes']);
		}
		
        $this->Sighting->SightingView->create();
        $this->request->data['SightingView']['user_id'] = $this->Auth->user('id');
        $this->request->data['SightingView']['sighting_id'] = $sighting['Sighting']['id'];
        $this->request->data['SightingView']['ip_id'] = $this->Sighting->SightingView->toSaveIp();
        $this->Sighting->SightingView->save($this->request->data);
        $this->pageTitle.= ' - ' . $sighting['Item']['name'] . '&nbsp;@&nbsp;' . $sighting['Place']['name'];
        $this->set('sighting', $sighting);
    }
    public function admin_index()
    {
        $this->_redirectPOST2Named(array(
            'q'
        ));
        $this->pageTitle = __l('Sightings');
        $conditions = array();
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Sighting.created) <= '] = 0;
            $this->pageTitle.= __l(' - today');
        }
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Sighting.created) <= '] = 7;
            $this->pageTitle.= __l('- in this week');
        }
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Sighting.created) <= '] = 30;
            $this->pageTitle.= __l(' - in this month');
        }
        if (!empty($this->request->params['named']['q'])) {
            $this->request->data['Sighting']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Flagged) {
                $conditions['Sighting.is_system_flagged'] = 1;
                $this->pageTitle.= __l(' - System Flagged ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::UserFlagged) {
                $conditions['Sighting.sighting_flag_count >'] = 0;
                $this->pageTitle.= __l(' - User Flagged');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Suspend) {
                $conditions['Sighting.admin_suspend'] = 1;
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
            $conditions['Sighting.user_id'] = $user['User']['id'];
            $this->pageTitle.= ' - ' . $user['User']['username'];
        }
        if (isset($this->request->params['named']['item'])) {
            $itemConditions = array(
                'Item.slug' => $this->request->params['named']['item']
            );
            $item = $this->{$this->modelClass}->Item->find('first', array(
                'conditions' => $itemConditions,
                'fields' => array(
                    'Item.id',
                    'Item.name'
                ) ,
                'recursive' => -1
            ));
            if (empty($item)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['Sighting.item_id'] = $item['Item']['id'];
            $this->pageTitle.= ' - ' . $item['Item']['name'];
        }
        if (isset($this->request->params['named']['place'])) {
            $placeConditions = array(
                'Place.slug' => $this->request->params['named']['place']
            );
            $place = $this->{$this->modelClass}->Place->find('first', array(
                'conditions' => $placeConditions,
                'fields' => array(
                    'Place.id',
                    'Place.name',
                ) ,
                'recursive' => -1
            ));
            if (empty($place)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['Sighting.place_id'] = $place['Place']['id'];
            $this->pageTitle.= ' - ' . $place['Place']['name'];
        }
        if (isset($this->request->params['named']['guide'])) {
            $guideConditions = array(
                'Guide.slug' => $this->request->params['named']['guide']
            );
            $guide = $this->{$this->modelClass}->Guide->find('first', array(
                'conditions' => $guideConditions,
                'fields' => array(
                    'Guide.id',
                    'Guide.name'
                ) ,
                'recursive' => -1
            ));
            if (empty($guide)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $guidesSightings = $this->{$this->modelClass}->GuidesSighting->find('all', array(
                'conditions' => array(
                    'GuidesSighting.guide_id' => $guide['Guide']['id']
                ) ,
                'fields' => array(
                    'GuidesSighting.sighting_id',
                ) ,
                'recursive' => -1
            ));
            if (!empty($guidesSightings)) {
                $sighting_ids = array();
                foreach($guidesSightings As $guidesSighting) {
                    $sighting_ids[] = $guidesSighting['GuidesSighting']['sighting_id'];
                }
                $conditions['Sighting.id'] = $sighting_ids;
            } else {
                $conditions['Sighting.id'] = '';
            }
            $this->pageTitle.= ' - ' . $guide['Guide']['name'];
        }
        if (isset($this->request->params['named']['sighting_flag_category'])) {
            $flagCategoryConditions = array(
                'SightingFlagCategory.slug' => $this->request->params['named']['sighting_flag_category']
            );
            $flagCategory = $this->{$this->modelClass}->SightingFlag->SightingFlagCategory->find('first', array(
                'conditions' => $flagCategoryConditions,
                'fields' => array(
                    'SightingFlagCategory.id',
                    'SightingFlagCategory.name'
                ) ,
                'recursive' => -1
            ));
            if (empty($flagCategory)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $flagSightings = $this->{$this->modelClass}->SightingFlag->find('all', array(
                'conditions' => array(
                    'SightingFlag.sighting_flag_category_id' => $flagCategory['SightingFlagCategory']['id']
                ) ,
                'fields' => array(
                    'SightingFlag.sighting_id',
                ) ,
                'recursive' => -1
            ));
            if (!empty($flagSightings)) {
                $sighting_ids = array();
                foreach($flagSightings As $flagSighting) {
                    $sighting_ids[] = $flagSighting['SightingFlag']['sighting_id'];
                }
                $conditions['Sighting.id'] = $sighting_ids;
            } else {
                $conditions['Sighting.id'] = '';
            }
            $this->pageTitle.= ' - ' . $flagCategory['SightingFlagCategory']['name'];
        }
        $this->Sighting->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'SightingRatingStat',
                'Item',
                'Place'
            ) ,
            'order' => array(
                'Sighting.id' => 'DESC'
            ) ,
            'contain' => array(
                'Review' => array(
                    'fields' => array(
                        'Review.sighting_id',
                    ) ,
                    'Attachment',
                    'limit' => 5,
                ) ,
                'Item' => array(
                    'fields' => array(
                        'Item.name',
                        'Item.slug'
                    ) ,
                ) ,
                'Place' => array(
                    'fields' => array(
                        'Place.name',
                        'Place.slug',
                        'Place.address2'
                    ) ,
                ) ,
                'SightingRatingStat',
            )
        );
        if (!empty($this->request->params['named']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->data['Sighting']['q']
            ));
        }
        $this->set('sightings', $this->paginate());
        $sightingRatingTypes = $this->Sighting->SightingRatingStat->SightingRatingType->find('list');
        $sightingRatingTypes_count = count($sightingRatingTypes);
        $moreActions = $this->Sighting->moreActions;
        $this->set(compact('moreActions', 'sightingRatingTypes', 'sightingRatingTypes_count'));
        $this->set('flagged', $this->Sighting->find('count', array(
            'conditions' => array(
                'Sighting.is_system_flagged = ' => 1,
            )
        )));
		$this->set('userflagged', $this->Sighting->find('count', array(
            'conditions' => array(
                'Sighting.sighting_flag_count > ' => 0,
            )
        )));
        $this->set('all', $this->Sighting->find('count'));
        $this->set('suspended', $this->Sighting->find('count', array(
            'conditions' => array(
                'Sighting.admin_suspend = ' => 1,
            )
        )));
    }
    public function admin_view($id = null)
    {
        $this->pageTitle = __l('Sighting');
        $this->Sighting->id = $id;
        if (!$this->Sighting->exists()) {
            throw new NotFoundException(__l('Invalid sighting'));
        }
        $sighting = $this->Sighting->find('first', array(
            'conditions' => array(
                'Sighting.id = ' => $id
            ) ,
            'recursive' => 0,
        ));
        if (empty($sighting)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->pageTitle.= ' - ' . $sighting['Sighting']['id'];
        $this->set('sighting', $sighting);
    }
    public function admin_add()
    {
        $this->pageTitle = __l('Add Sighting');
        if ($this->request->is('post')) {
            $this->Sighting->create();
            if ($this->Sighting->save($this->request->data)) {
                $this->Session->setFlash(__l('sighting has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('sighting could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        $items = $this->Sighting->Item->find('list');
        $places = $this->Sighting->Place->find('list');
        $guides = $this->Sighting->Guide->find('list');
        $this->set(compact('items', 'places', 'guides'));
    }
    public function admin_edit($id = null)
    {
        $this->pageTitle = __l('Edit Sighting');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->Sighting->id = $id;
        if (!$this->Sighting->exists()) {
            throw new NotFoundException(__l('Invalid sighting'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Sighting->save($this->request->data)) {
                $this->Session->setFlash(__l('sighting has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('sighting could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->data = $this->Sighting->read(null, $id);
            if (empty($this->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->data['Sighting']['id'];
        $items = $this->Sighting->Item->find('list');
        $places = $this->Sighting->Place->find('list');
        $guides = $this->Sighting->Guide->find('list');
        $this->set(compact('items', 'places', 'guides'));
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->Sighting->id = $id;
        if (!$this->Sighting->exists()) {
            throw new NotFoundException(__l('Invalid sighting'));
        }
        if ($this->Sighting->delete()) {
            $this->Session->setFlash(__l('Sighting deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        $this->Session->setFlash(__l('Sighting was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'action' => 'index'
        ));
    }
	public function user_sighting() {
		$conditions = array();
		$sightingconditions = array();
		$conditions['Review.admin_suspend !='] = 1;
        if (!empty($this->request->params['named']['user']) && !empty($this->request->params['named']['guide'])) {
           $sightingconditions['Sighting.user_id'] = $conditions['Review.user_id'] = $this->Auth->user('id');
        }
        if (!empty($this->request->params['named']['guide'])) {
            $guide = $this->Sighting->Guide->find('first', array(
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
		if (!empty($this->request->params['named']['view']) && $this->request->params['named']['view'] == 'simple') {
			$sightingconditions['Sighting.admin_suspend !='] = 1;
			$this->paginate = array(
				'conditions' => $sightingconditions,
				'fields' => array (
					'Sighting.id',
					'Sighting.item_id',
					'Sighting.place_id',
				),
				'contain' => array(
					'Review' => array(
						'conditions' => $conditions,
						'limit' => 1,
						'Attachment',
						'User' => array(
							'fields' => array(
								'User.id',
								'User.username',
							)
						) ,
					),
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
					'GuidesSighting' => array(
						'conditions' => array(
							'GuidesSighting.guide_id' => $guide['Guide']['id']
						) ,
						'limit' => 1
					) ,
				),
				'order' => array(
					'Sighting.id' => 'desc'
				),
				'limit' => 10,
				
			);	
			$this->set('sightings', $this->paginate());
		}	
	}
}
