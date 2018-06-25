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
class BusinessUpdatesController extends AppController
{
    public $name = 'BusinessUpdates';
    public function beforeFilter()
    {
        $this->Security->disabledFields = array(
            'Item.id',
            'Item.name',
            'BusinessUpdate.updates',
            'BusinessUpdate.business_id',
            'BusinessUpdate.user_id',
            'Place'
        );
        $not_allowed_action_ary = array('view','add', 'edit','delete');
        if(!$this->Auth->user('is_business_user') && $this->params['controller'] == 'business_updates' && in_array($this->params['action'],$not_allowed_action_ary))
        {
			throw new NotFoundException(__l('Invalid request'));
		}
        parent::beforeFilter();
    }
    public function index()
    { 
        $this->pageTitle = __l('businessUpdates');
        $this->BusinessUpdate->recursive = 0;
        $conditions = array();
		$limit = '';
		if (!empty($this->params['named']['from']) && $this->params['named']['type'] == 'own') {
			$conditions =  array(
						 'AND' => array(
						 array('Business.user_id' => $this->Auth->user('id')),
						 array('BusinessUpdate.id !=' => $this->params['named']['from'])
						 ));
        }
        else if (!empty($this->params['named']['type']) && $this->params['named']['type'] == 'own') {
            $conditions['Business.user_id'] = $this->Auth->user('id');
        }
		else if (empty($this->params['named']['type']) && !empty($this->params['named']['business'])) {
            $conditions['Business.slug'] = $this->params['named']['business'];
        }
        else if (empty($this->params['named']['type']) && !empty($this->params['named']['place'])) {
            $conditions['Place.slug'] = $this->params['named']['place'];
        }
        if (!empty($this->params['named']['user'])) {
            $place_follows = $this->BusinessUpdate->Place->PlaceFollower->find('all', array(
                'conditions' => array(
                    'User.username = ' => $this->request->params['named']['user']
                ) ,
                'fields' => array(
                    'PlaceFollower.place_id'
                ) ,
                'recursive' => 0
            ));
        	if(!empty($place_follows)) {
        	    $place_ids = array();
                foreach($place_follows as $place_follow) {
                    $place_ids[] = $place_follow['PlaceFollower']['place_id'];
                }
				$conditions['OR']['BusinessUpdate.place_id'] = $place_ids;
			}
			else {
                $condition['OR']['BusinessUpdate.place_id'] = '';
            }

			$business_follows = $this->BusinessUpdate->Business->BusinessFollower->find('all', array(
                'conditions' => array(
                    'User.username = ' => $this->request->params['named']['user']
                ) ,
                'fields' => array(
                    'BusinessFollower.business_id'
                ) ,
                'recursive' => 0
            ));
        	if(!empty($business_follows)) {
        		$business_ids = array();
                foreach($business_follows as $business_follow) {
                    $business_ids[] = $business_follow['BusinessFollower']['business_id'];
                }
				$conditions['OR']['BusinessUpdate.business_id'] = $business_ids;
			}
			else{
				$conditions['OR']['BusinessUpdate.business_id'] = '';
			}
			$limit = 20;
		}
		$limit = ($limit)? $limit : 5;
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'User' => array(
                    'fields' => array(
                        'User.id',
                        'User.username'
                    )
                ) ,
                'Item' => array(
                    'fields' => array(
                        'Item.id',
                        'Item.name',
                        'Item.slug'
                    )
                ) ,
                'Business' => array(
                    'fields' => array(
                        'Business.id',
                        'Business.name',
						'Business.slug'
                    )
                ) ,
                'Place' => array(
                    'fields' => array(
                        'Place.id',
                        'Place.name',
                        'Place.slug',
						'Place.address2',
						'Place.zip_code',
						'Place.slug',
                    )
                ) ,
            ) ,
            'order' => array(
                'BusinessUpdate.id' => 'DESC'
            ) ,
            'limit' => $limit,
            'recursive' => 2,
        );
		$this->set('businessUpdates', $this->paginate());
        if (!empty($this->params['named']['type']) && $this->params['named']['type'] == 'own') {
            $this->render('business');
        }
        if (!empty($this->params['named']['user'])) {
            $this->render('my_updates');
        }
    }
    public function add()
    {
        $this->pageTitle = __l('Add Business Update');
		unset($this->BusinessUpdate->Item->validate['name']);
        if ($this->request->is('post'))
		{
			$isPlaceIdSet = false;
			$redirect_ok = false;
			$err_msg = false;
			if($this->BusinessUpdate->validates())
			{
				$this->request->data['Item']['id'] = !empty($this->request->data['Item']['id']) ? $this->request->data['Item']['id'] : $this->BusinessUpdate->Item->findOrSaveAndGetId($this->request->data['Item']['name']);
				$this->request->data['BusinessUpdate']['item_id'] = $this->request->data['Item']['id'];
				if(!empty($this->request->data['Place']))
				{
					foreach($this->request->data['Place'] as $place_id => $place)
					{
						if($place['place'] != 0)
						{
							$isPlaceIdSet = true;
							$this->BusinessUpdate->create();
							$this->request->data['BusinessUpdate']['place_id'] = $place['place'];
				            if ($this->BusinessUpdate->save($this->request->data['BusinessUpdate']))
							{
				                $redirect_ok = true;
				            }
							else
							{
				                $err_msg = true;
				            }
						}
					}
				}
				if(!$isPlaceIdSet)
				{
		            if ($this->BusinessUpdate->save($this->request->data))
					{
		                $redirect_ok = true;
		            }
					else
					{
		                $err_msg = true;
		            }
				}
				if($redirect_ok)
				{
					$this->Session->setFlash(__l('business update has been added') , 'default', null, 'success');
					$ajax_url = Router::url(array(
                            'controller' => 'businesses',
                            'action' => 'my_business',
                        ), true);
                    if ($this->request->params['isAjax'] == 1 || !empty($this->request->params['form']['is_iframe_submit'])) {
                                $success_msg = 'redirect*' . $ajax_url;
                                echo $success_msg;
                                exit;
                            } else {
                                $this->redirect($ajax_url);
                            }
				}
				if($err_msg)
				{
					$this->Session->setFlash(__l('business update could not be added. Please, try again.') , 'default', null, 'error');
				}
			}

        }
		else
		{
            $business = $this->BusinessUpdate->Business->find('first', array(
                'conditions' => array(
                    'Business.user_id' => $this->Auth->user('id')
                ) ,
                'recursive' => -1,
                'limit' => 1,
                'order' => array(
                    'Business.id' => 'DESC'
                ) ,
            ));
            $this->request->data['BusinessUpdate']['business_id'] = $business['Business']['id'];
        }
        if($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
			$users = $this->BusinessUpdate->User->find('list');
	        $businesses = $this->BusinessUpdate->Business->find('list');
        }
        $places = $this->BusinessUpdate->Place->find('all', array(
                'conditions' => array(
                    'Place.business_id' => $this->request->data['BusinessUpdate']['business_id']
                ),
                'fields'=>array (
                    'Place.name',
                    'Place.address2',
                    'Place.id'
                ),
                'recursive'=>-1
            ));
        $this->set(compact('users', 'businesses', 'places'));
    }
    public function edit($id = null)
    {
        $this->pageTitle = __l('Edit Business Update');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->BusinessUpdate->id = $id;
        if (!$this->BusinessUpdate->exists()) {
            throw new NotFoundException(__l('Invalid business update'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data['Item']['id'] = !empty($this->request->data['Item']['id']) ? $this->request->data['Item']['id'] : $this->BusinessUpdate->Item->findOrSaveAndGetId($this->request->data['Item']['name']);
			$this->request->data['BusinessUpdate']['item_id'] = $this->request->data['Item']['id'];
			if ($this->BusinessUpdate->save($this->request->data)) {
                $this->Session->setFlash(__l('business update has been updated') , 'default', null, 'success');
				if($this->request->params['prefix'] == 'admin'){
                	$this->redirect(array(
					'action' => 'index'
                ));
				}else{
					$this->redirect(array(
                    'controller' => 'businesses',
					'action' => 'my_business'
                ));
				}
            } else {
                $this->Session->setFlash(__l('business update could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->data = $this->BusinessUpdate->read(null, $id);
            if (empty($this->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        unset($this->BusinessUpdate->Item->validate['name']);
    }
    public function delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->BusinessUpdate->id = $id;
        if (!$this->BusinessUpdate->exists()) {
            throw new NotFoundException(__l('Invalid business update'));
        }
        if ($this->BusinessUpdate->delete()) {
            $this->Session->setFlash(__l('Business update deleted') , 'default', null, 'success');
            $this->redirect(array(
				'controller' => 'businesses',
                'action' => 'my_business'
            ));
        }
        $this->Session->setFlash(__l('Business update was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'action' => 'index'
        ));
    }
    public function admin_index()
    {
		$this->_redirectPOST2Named(array(
            'q'
        ));
		$conditions = array();
        $this->pageTitle = __l('Business Updates');
		if (isset($this->request->params['named']['q'])) {
            $this->request->data['Business']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
		if (!empty($this->params['named']['business'])) {
            $conditions['Business.slug'] = $this->params['named']['business'];
        }
		if (!empty($this->params['named']['username'])) {
        	$user = $this->BusinessUpdate->User->find('first', array(
                'conditions' => array(
                    'User.username' => $this->params['named']['username']
                ),
                'fields'=>array (
                    'User.id'
                ),
                'recursive'=>-1
            ));
			if(!empty($user)) {
				$conditions['Business.user_id'] = $user['User']['id'];
			}
		}	
        if (isset($this->request->data['Business']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->params['named']['q']
            ));
        }
        $this->paginate = array(
            'conditions' => $conditions,
			'order' => array(
                'BusinessUpdate.id' => 'desc'
            )
            );

        //$this->BusinessUpdate->recursive = 0;
		$moreActions = $this->BusinessUpdate->moreActions;
        $this->set(compact('moreActions'));
        $this->set('businessUpdates', $this->paginate());
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
}
