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
class PlaceFollowersController extends AppController
{
    public $name = 'PlaceFollowers';
    public function index()
    {
        $this->pageTitle = __l('Place Followers');
        $conditions = array();
        if (!empty($this->request->params['named']['follower'])) {
            $place = $this->PlaceFollower->Place->find('first', array(
                'conditions' => array(
                    'Place.slug' => $this->request->params['named']['follower']
                ) ,
                'recursive' => -1
            ));
            if (empty($place)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['PlaceFollower.place_id'] = $place['Place']['id'];
            $place_follower_count = $this->PlaceFollower->find('count', array(
                'conditions' => array(
                    'PlaceFollower.place_id' => $place['Place']['id']
                ) ,
                'recursive' => -1
            ));
			$this->set('place_follower_count', $place_follower_count);
        }
        $limit = 6;
        if (!empty($this->request->params['named']['user'])) {
            $conditions['User.username'] = $this->request->params['named']['user'];
            $contain = array(
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
                'User' => array(
                    'fields' => array(
                        'User.username'
                    )
                )
            );
            $limit = 20;
        } else {
            $contain = array(
                'User' => array(
                    'fields' => array(
                        'User.id',
                        'User.username',
                    ) ,
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
                ) ,
                'Place' => array(
                    'fields' => array(
                        'Place.id',
                        'Place.name',
                        'Place.slug'

                    )
                ) ,
            );
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => $contain,
            'limit' => $limit,
        );
		if (isset($this->request->params['named']['follower'])) {
            $followers = $this->PlaceFollower->find('count', array(
                'conditions' => $conditions,
            	'contain' => $contain,
            )) -6;  
            $this->set('followers', $followers);
        }
        if (!empty($this->request->params['named']['type'])) {
            $this->autoRender=false;
            return $this->paginate();
        }
        $this->set('pageTitle', $this->pageTitle);
        $this->set('placeFollowers', $this->paginate());
        if (!empty($this->request->params['named']['user'])) {
            $this->render('user_view');
        }
    }
    public function add()
    {
        $this->pageTitle = __l('Add Place Follower');
        if (!empty($this->request->params['named']['place'])) {
            $place = $this->PlaceFollower->Place->find('first', array(
                'conditions' => array(
                    'Place.slug' => $this->request->params['named']['place']
                ) ,
                'contain' => array(
                    'PlaceFollower' => array(
                        'conditions' => array(
                            'PlaceFollower.user_id' => $this->Auth->user('id')
                        )
                    )
                ) ,
                'fields' => array(
                    'Place.id',
                    'Place.name',
                    'Place.slug',
                ) ,
                'recursive' => 1
            ));
            if (empty($place)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            if (!empty($place['PlaceFollower'])) {
                $this->Session->setFlash(__l('You\'re already following this place.') , 'default', null, 'error');
                $this->redirect(array(
                    'controller' => 'places',
                    'action' => 'index'
                ));
            }
            $this->PlaceFollower->create();
            $this->request->data['PlaceFollower']['place_id'] = $place['Place']['id'];
            $this->request->data['PlaceFollower']['user_id'] = $this->Auth->user('id');
            $this->request->data['PlaceFollower']['ip_id'] = $this->PlaceFollower->toSaveIp();
            if ($this->PlaceFollower->save($this->request->data)) {
                $this->Session->setFlash(__l('You have added') . ' "' . $place['Place']['name'] . '" ' . __l('to your following list') , 'default', null, 'success');
                $this->redirect(array(
                    'controller' => 'places',
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Place follower could not be added. Please, try again.') , 'default', null, 'error');
                $this->redirect(array(
                    'controller' => 'places',
                    'action' => 'index'
                ));
            }
        }
    }
    public function delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $place_follower = $this->PlaceFollower->find('first', array(
            'conditions' => array(
                'PlaceFollower.id' => $id,
                'PlaceFollower.user_id' => $this->Auth->user('id') ,
            ) ,
            'contain' => array(
                'Place' => array(
                    'fields' => array(
                        'Place.name'
                    )
                )
            ) ,
            'recursive' => 1
        ));
        if ($this->PlaceFollower->delete($place_follower['PlaceFollower']['id'])) {
            $this->Session->setFlash(__l('You have removed') . ' "' . $place_follower['Place']['name'] . '" ' . __l('from your place following list.') , 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'places',
                'action' => 'index'
            ));
        }
        $this->Session->setFlash(__l('Place follower was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'controller' => 'places',
            'action' => 'index'
        ));
    }
    public function admin_index()
    {
        $this->_redirectPOST2Named(array(
            'q'
        ));
        $this->pageTitle = __l('Place Followers');
        $conditions = array();
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(PlaceFollower.created) <= '] = 0;
            $this->pageTitle.= __l(' - today');
        }
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(PlaceFollower.created) <= '] = 7;
            $this->pageTitle.= __l('- in this week');
        }
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(PlaceFollower.created) <= '] = 30;
            $this->pageTitle.= __l(' - in this month');
        }
        if (!empty($this->request->params['named']['q'])) {
            $this->request->data['Place']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        if (!empty($this->request->params['named']['q'])) {
            $this->request->data['PlaceFollower']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        if (isset($this->request->params['named']['place'])) {
            $placeConditions = array(
                'Place.slug' => $this->request->params['named']['place']
            );
            $place = $this->{$this->modelClass}->Place->find('first', array(
                'conditions' => $placeConditions,
                'fields' => array(
                    'Place.id',
                    'Place.name'
                ) ,
                'recursive' => -1
            ));
            if (empty($place)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['PlaceFollower.place_id'] = $place['Place']['id'];
            $this->pageTitle.= ' - ' . $place['Place']['name'];
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
            $conditions['PlaceFollower.user_id'] = $user['User']['id'];
            $this->pageTitle.= ' - ' . $user['User']['username'];
        }
        $this->PlaceFollower->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
            'contain'=>array(
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
                'User'=>array(
                    'fields'=>array(
                        'User.username'
                    )
                ),
                'Place'=>array(
                    'fields'=>array(
                        'Place.name',
                        'Place.slug',
                        'Place.address2',
                        'Place.zip_code',
                    )
                ),
            ),
            'order' => array(
                'PlaceFollower.id' => 'DESC'
            ) ,
        );
        if (!empty($this->request->data['PlaceFollower']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->data['PlaceFollower']['q']
            ));
        }
        $this->set('placeFollowers', $this->paginate());
        $moreActions = $this->PlaceFollower->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->PlaceFollower->id = $id;
        if (!$this->PlaceFollower->exists()) {
            throw new NotFoundException(__l('Invalid place follower'));
        }
        if ($this->PlaceFollower->delete()) {
            $this->Session->setFlash(__l('Place follower deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        $this->Session->setFlash(__l('Place follower was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'action' => 'index'
        ));
    }
}
