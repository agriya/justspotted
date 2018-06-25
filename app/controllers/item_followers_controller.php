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
class ItemFollowersController extends AppController
{
    public $name = 'ItemFollowers';
    public function index()
    {
        $conditions = array();
        if (!empty($this->request->params['named']['user'])) {
            $conditions['User.username'] = $this->request->params['named']['user'];
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'Item' => array(
                    'fields' => array(
                        'Item.name',
                        'Item.slug',
                    )
                ) ,
                'User' => array(
                    'fields' => array(
                        'User.username'
                    )
                )
            ) ,
            'recursive' => 0
        );
        $this->pageTitle = __l('Item Followers');
        $this->ItemFollower->recursive = 0;
        if (!empty($this->request->params['named']['type'])) {
            $this->autoRender=false;
            return $this->paginate();
        }
        $this->set('itemFollowers', $this->paginate());
    }
    public function add()
    {
        $this->pageTitle = __l('Add Item Follower');
        if (!empty($this->request->params['named']['item'])) {
            $item = $this->ItemFollower->Item->find('first', array(
                'conditions' => array(
                    'Item.slug' => $this->request->params['named']['item']
                ) ,
                'contain' => array(
                    'ItemFollower' => array(
                        'conditions' => array(
                            'ItemFollower.user_id' => $this->Auth->user('id')
                        )
                    )
                ) ,
                'fields' => array(
                    'Item.id',
                    'Item.name',
                    'Item.slug',
                ) ,
                'recursive' => 1
            ));
            if (empty($item)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            if (!empty($item['ItemFollower'])) {
                $this->Session->setFlash(__l('You\'re already following this item.') , 'default', null, 'error');
                $this->redirect(array(
                    'controller' => 'sightings',
                    'action' => 'index'
                ));
            }
            $this->ItemFollower->create();
            $this->request->data['ItemFollower']['item_id'] = $item['Item']['id'];
            $this->request->data['ItemFollower']['user_id'] = $this->Auth->user('id');
            $this->request->data['ItemFollower']['ip_id'] = $this->ItemFollower->toSaveIp();
            if ($this->ItemFollower->save($this->request->data)) {
                $this->Session->setFlash(__l('You have added') . ' "' . $item['Item']['name'] . '" ' . __l('to your following list') , 'default', null, 'success');
                $this->redirect(array(
                    'controller' => 'sightings',
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Item follower could not be added. Please, try again.') , 'default', null, 'error');
                $this->redirect(array(
                    'controller' => 'sightings',
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
        $item_follower = $this->ItemFollower->find('first', array(
            'conditions' => array(
                'ItemFollower.id' => $id,
                'ItemFollower.user_id' => $this->Auth->user('id') ,
            ) ,
            'contain' => array(
                'Item' => array(
                    'fields' => array(
                        'Item.name'
                    )
                )
            ) ,
            'recursive' => 1
        ));
        if ($this->ItemFollower->delete($item_follower['ItemFollower']['id'])) {
            $this->Session->setFlash(__l('You have removed') . ' "' . $item_follower['Item']['name'] . '" ' . __l('from your item following list.') , 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'sightings',
                'action' => 'index'
            ));
        }
        $this->Session->setFlash(__l('Item follower was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'controller' => 'items',
            'action' => 'index'
        ));
    }
    public function admin_index()
    {
        $conditions = array();
        $this->_redirectPOST2Named(array(
            'q'
        ));
        $this->pageTitle = __l('Item Followers');
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(ItemFollower.created) <= '] = 0;
            $this->pageTitle.= __l(' - today');
        }
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(ItemFollower.created) <= '] = 7;
            $this->pageTitle.= __l('- in this week');
        }
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(ItemFollower.created) <= '] = 30;
            $this->pageTitle.= __l(' - in this month');
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
            $conditions['ItemFollower.item_id'] = $item['Item']['id'];
            $this->pageTitle.= ' - ' . $item['Item']['name'];
        }
        if (!empty($this->request->params['named']['q'])) {
            $this->request->data['ItemFollower']['q'] = $this->request->params['named']['q'];
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
            $conditions['ItemFollower.user_id'] = $user['User']['id'];
            $this->pageTitle.= ' - ' . $user['User']['username'];
        }
        $this->ItemFollower->recursive = 2;
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
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
                'Item',
                'User'
            ) ,
            'order' => array(
                'ItemFollower.id' => 'DESC'
            ) ,
        );
        if (!empty($this->request->data['ItemFollower']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->data['ItemFollower']['q']
            ));
        }
        $this->set('itemFollowers', $this->paginate());
        $moreActions = $this->ItemFollower->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->ItemFollower->id = $id;
        if (!$this->ItemFollower->exists()) {
            throw new NotFoundException(__l('Invalid item follower'));
        }
        if ($this->ItemFollower->delete()) {
            $this->Session->setFlash(__l('Item follower deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        $this->Session->setFlash(__l('Item follower was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'action' => 'index'
        ));
    }
}
