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
class BusinessFollowersController extends AppController
{
    public $name = 'BusinessFollowers';
    public function index()
    {
		$conditions = array();
		if(!empty($this->request->params['named']['follower'])){
			$business = $this->BusinessFollower->Business->find('first', array(
				'conditions' => array(
					'Business.slug' => $this->request->params['named']['follower']
				),
				'recursive' => -1
			));
			if(empty($business)){
				throw new NotFoundException(__l('Invalid request'));
			}
			$conditions['BusinessFollower.business_id'] = $business['Business']['id'];
			$business_follower_count = $this->BusinessFollower->find('count', array(
				'conditions' => $conditions,
				'recursive' => -1
			));
		}
        if (empty($this->request->params['named']))
		{
			$conditions['BusinessFollower.user_id'] = $this->Auth->user('id');
		}
        $this->pageTitle = __l('Business Followers');
		$this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'User' => array(
					'fields' => array(
						'User.id',
						'User.username',
					),
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
					),
				),
                'Business' => array(
					'fields' => array(
						'Business.id',
						'Business.name',
						'Business.slug',
					)
				)               
            )
		);
        $this->set('businessFollowers', $this->paginate());
		$this->set('business_follower_count', $business_follower_count);
    }   
    public function add()
    {
        $this->pageTitle = __l('Add Business Follower');
        if (!empty($this->request->params['named']['business'])) {
            $business = $this->BusinessFollower->Business->find('first', array(
                'conditions' => array(
                    'Business.slug' => $this->request->params['named']['business']
                ) ,
				'contain' => array(
					'BusinessFollower' => array(
						'conditions' => array(
							'BusinessFollower.user_id' => $this->Auth->user('id')
						)
					)
				),
                'fields' => array(
                    'Business.id',
                    'Business.name',
                    'Business.slug',
                ) ,
                'recursive' => 1
            ));
			if(empty($business)){
				throw new NotFoundException(__l('Invalid request'));
			}
			if(!empty($business['BusinessFollower'])){
                $this->Session->setFlash(__l('You\'re already following this business.') , 'default', null, 'error');
                $this->redirect(array(
                    'controller' => 'businesses',
                    'action' => 'index'
                ));
			}
            $this->BusinessFollower->create();
            $this->request->data['BusinessFollower']['business_id'] = $business['Business']['id'];
            $this->request->data['BusinessFollower']['user_id'] = $this->Auth->user('id');
            $this->request->data['BusinessFollower']['ip_id'] = $this->BusinessFollower->toSaveIp();
            if ($this->BusinessFollower->save($this->request->data)) {
                $this->Session->setFlash(__l('You have added').' "'.$business['Business']['name'].'" '.__l('to your following list') , 'default', null, 'success');
                $this->redirect(array(
                    'controller' => 'businesses',
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Business follower could not be added. Please, try again.') , 'default', null, 'error');
                $this->redirect(array(
                    'controller' => 'businesses',
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
        $business_follower = $this->BusinessFollower->find('first', array(
			'conditions' => array(
				'BusinessFollower.id' => $id,
				'BusinessFollower.user_id' => $this->Auth->user('id'),
			),
			'contain' => array(
				'Business' => array(
					'fields' => array(
						'Business.name'
					)
				)
			),
			'recursive' => 1		
		));
        if ($this->BusinessFollower->delete($business_follower['BusinessFollower']['id'])) {
            $this->Session->setFlash(__l('You have removed').' "'.$business_follower['Business']['name'].'" '.__l('from your business following list.'), 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'businesses',
                'action' => 'index'
            ));
        }
        $this->Session->setFlash(__l('Business follower was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'controller' => 'businesses',
            'action' => 'index'
        ));
    }
    public function admin_index()
    {
		$conditions = array();
		$this->_redirectPOST2Named(array(
            'q'
        ));
		$this->pageTitle = __l('Business Followers');
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(BusinessFollower.created) <= '] = 0;
            $this->pageTitle.= __l(' - today');
        }
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(BusinessFollower.created) <= '] = 7;
            $this->pageTitle.= __l('- in this week');
        }
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(BusinessFollower.created) <= '] = 30;
            $this->pageTitle.= __l(' - in this month');
        }
		if (!empty($this->request->params['named']['q'])) {
            $this->request->data['BusinessFollower']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
		if (isset($this->request->params['named']['business'])) {
            $businessConditions = array(
                'Business.slug' => $this->request->params['named']['business']
            );
            $business = $this->{$this->modelClass}->Business->find('first', array(
                'conditions' => $businessConditions,
                'fields' => array(
                    'Business.id',
                    'Business.name'
                ) ,
                'recursive' => -1
            ));
            if (empty($business)) {
                throw new NotFoundException(__l('Invalid request'));
            }

			$conditions['BusinessFollower.business_id'] = $business['Business']['id'];	
            $this->pageTitle.= ' - ' . $business['Business']['name'];
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
            $conditions['BusinessFollower.user_id'] = $user['User']['id'];
            $this->pageTitle.= ' - ' . $user['User']['username'];
        }
        $this->BusinessFollower->recursive = 0;
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
                'Business'=>array(
                    'fields'=>array(
                        'Business.name',
                        'Business.slug'
                    )
                ),
            ),
			'order' => array(
				'BusinessFollower.id' => 'DESC'
			),
        );
		if (!empty($this->request->data['BusinessFollower']['q'])) {
            $this->paginate = array_merge($this->paginate, array('search' => $this->request->data['BusinessFollower']['q']));
        }
        $this->set('businessFollowers', $this->paginate());
		$moreActions = $this->BusinessFollower->moreActions;
		$this->set(compact('moreActions'));
    }    
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->BusinessFollower->id = $id;
        if (!$this->BusinessFollower->exists()) {
            throw new NotFoundException(__l('Invalid business follower'));
        }
        if ($this->BusinessFollower->delete()) {
            $this->Session->setFlash(__l('Business follower deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        $this->Session->setFlash(__l('Business follower was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'action' => 'index'
        ));
    }
}
