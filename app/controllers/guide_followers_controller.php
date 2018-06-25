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
class GuideFollowersController extends AppController
{
    public $name = 'GuideFollowers';
    public function index()
    {
		$conditions = array();
		if(!empty($this->request->params['named']['follower'])){
			$guide = $this->GuideFollower->Guide->find('first', array(
				'conditions' => array(
					'Guide.slug' => $this->request->params['named']['follower']
				),
				'recursive' => -1
			));
			if(empty($guide)){
				throw new NotFoundException(__l('Invalid request'));
			}
			$conditions['GuideFollower.guide_id'] = $guide['Guide']['id'];
			$guide_follower_count = $this->GuideFollower->find('count', array(
				'conditions' => array(
					'GuideFollower.guide_id' => $guide['Guide']['id']
				),
				'recursive' => -1
			));
			$this->set('guide_follower_count', $guide_follower_count);
		}
		if (!empty($this->request->params['named']['user'])) {
            $conditions['User.username'] = $this->request->params['named']['user'];
        }
        if (empty($this->request->params['named']))
		{
			$conditions['GuideFollower.user_id'] = $this->Auth->user('id');
		}
        $this->pageTitle = __l('Guide Followers');
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
                'Guide' => array(
					'fields' => array(
						'Guide.id',
						'Guide.name',
						'Guide.slug',
						'Guide.description',
						'Guide.guide_follower_count',
						'Guide.sighting_count',
					),
					'Attachment'
				)               
            )
		);
		if (!empty($this->request->params['named']['type'])) {
            $this->autoRender=false;
            return $this->paginate();
        }
        $this->set('guideFollowers', $this->paginate());
    }   
    public function add()
    {
        $this->pageTitle = __l('Add Guide Follower');
        if (!empty($this->request->params['named']['guide'])) {
            $guide = $this->GuideFollower->Guide->find('first', array(
                'conditions' => array(
                    'Guide.slug' => $this->request->params['named']['guide']
                ) ,
				'contain' => array(
					'GuideFollower' => array(
						'conditions' => array(
							'GuideFollower.user_id' => $this->Auth->user('id')
						)
					)
				),
                'fields' => array(
                    'Guide.id',
                    'Guide.name',
                    'Guide.slug',
                ) ,
                'recursive' => 1
            ));
			if(empty($guide)){
				throw new NotFoundException(__l('Invalid request'));
			}
			if(!empty($guide['GuideFollower'])){
                $this->Session->setFlash(__l('You\'re already following this guide.') , 'default', null, 'error');
                $this->redirect(array(
                    'controller' => 'guides',
                    'action' => 'index'
                ));
			}
            $this->GuideFollower->create();
            $this->request->data['GuideFollower']['guide_id'] = $guide['Guide']['id'];
            $this->request->data['GuideFollower']['user_id'] = $this->Auth->user('id');
            $this->request->data['GuideFollower']['ip_id'] = $this->GuideFollower->toSaveIp();
            if ($this->GuideFollower->save($this->request->data)) {
                $this->Session->setFlash(__l('You have added').' "'.$guide['Guide']['name'].'" '.__l('to your following list') , 'default', null, 'success');
                $this->redirect(array(
                    'controller' => 'guides',
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Guide follower could not be added. Please, try again.') , 'default', null, 'error');
                $this->redirect(array(
                    'controller' => 'guides',
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
        $guide_follower = $this->GuideFollower->find('first', array(
			'conditions' => array(
				'GuideFollower.id' => $id,
				'GuideFollower.user_id' => $this->Auth->user('id'),
			),
			'contain' => array(
				'Guide' => array(
					'fields' => array(
						'Guide.name'
					)
				)
			),
			'recursive' => 1		
		));
        if ($this->GuideFollower->delete($guide_follower['GuideFollower']['id'])) {
            $this->Session->setFlash(__l('You have removed').' "'.$guide_follower['Guide']['name'].'" '.__l('from your guide following list.'), 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'guides',
                'action' => 'index'
            ));
        }
        $this->Session->setFlash(__l('Guide follower was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'controller' => 'guides',
            'action' => 'index'
        ));
    }
    public function admin_index()
    {
		$conditions = array();
		$this->_redirectPOST2Named(array(
            'q'
        ));
		$this->pageTitle = __l('Guide Followers');
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(GuideFollower.created) <= '] = 0;
            $this->pageTitle.= __l(' - today');
        }
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(GuideFollower.created) <= '] = 7;
            $this->pageTitle.= __l('- in this week');
        }
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(GuideFollower.created) <= '] = 30;
            $this->pageTitle.= __l(' - in this month');
        }
		if (!empty($this->request->params['named']['q'])) {
            $this->request->data['GuideFollower']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
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
            $conditions['GuideFollower.user_id'] = $user['User']['id'];
            $this->pageTitle.= ' - ' . $user['User']['username'];
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

			$conditions['GuideFollower.guide_id'] = $guide['Guide']['id'];	
            $this->pageTitle.= ' - ' . $guide['Guide']['name'];
        }
        $this->GuideFollower->recursive = 0;
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
                'Guide'=>array(
                    'fields'=>array(
                        'Guide.name',
                        'Guide.slug'
                    )
                ),
            ),
			'order' => array(
				'GuideFollower.id' => 'DESC'
			),
        );
		if (!empty($this->request->data['GuideFollower']['q'])) {
            $this->paginate = array_merge($this->paginate, array('search' => $this->request->data['GuideFollower']['q']));
        }
        $this->set('guideFollowers', $this->paginate());
		$moreActions = $this->GuideFollower->moreActions;
		$this->set(compact('moreActions'));
    }    
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->GuideFollower->id = $id;
        if (!$this->GuideFollower->exists()) {
            throw new NotFoundException(__l('Invalid guide follower'));
        }
        if ($this->GuideFollower->delete()) {
            $this->Session->setFlash(__l('Guide follower deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        $this->Session->setFlash(__l('Guide follower was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'action' => 'index'
        ));
    }
}
