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
class ItemsController extends AppController
{
    public $name = 'Items';
    public function add()
    {
        $this->pageTitle = __l('Add Item');
        if ($this->request->is('post')) {
            $this->Item->create();
            if ($this->Item->save($this->request->data)) {
                $this->Session->setFlash(__l('Item has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Item could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
    }
    public function admin_index()
    {
        $this->_redirectPOST2Named(array(
            'q'
        ));
        $conditions = array();
        $this->pageTitle = __l('Items');
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Item.created) <= '] = 0;
            $this->pageTitle.= __l(' - today');
        }
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Item.created) <= '] = 7;
            $this->pageTitle.= __l('- in this week');
        }
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Item.created) <= '] = 30;
            $this->pageTitle.= __l(' - in this month');
        }
        if (!empty($this->request->params['named']['q'])) {
            $this->request->data['Item']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Flagged) {
                $conditions['Item.is_system_flagged'] = 1;
                $this->pageTitle.= __l(' - System Flagged ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Suspend) {
                $conditions['Item.admin_suspend'] = 1;
                $this->pageTitle.= __l(' - Admin Suspend ');
            }
        }
        if (isset($this->request->params['named']['place_id'])) {
            $this->loadModel('Sighting');
            $sightings = $this->Sighting->find('list', array(
                'fields' => array(
                    'Sighting.id',
                    'Sighting.item_id',
                ) ,
                'conditions' => array(
                    'Sighting.place_id' => $this->request->params['named']['place_id']
                ) ,
                'recursive' => -1
            ));
            if (!empty($sightings)) {
                $conditions['Item.id'] = $sightings;
            } else {
                $conditions['Item.id'] = 0;
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
            $conditions['Item.user_id'] = $user['User']['id'];
            $this->pageTitle.= ' - ' . $user['User']['username'];
        }
        $this->Item->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
            'order' => array(
                'Item.id' => 'DESC'
            ) ,
        );
        if (!empty($this->request->data['Item']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->data['Item']['q']
            ));
        }
        $this->set('items', $this->paginate());
        $moreActions = $this->Item->moreActions;
        $this->set(compact('moreActions'));
        $this->set('flagged', $this->Item->find('count', array(
            'conditions' => array(
                'Item.is_system_flagged = ' => 1,
            )
        )));
        $this->set('all', $this->Item->find('count'));
        $this->set('suspended', $this->Item->find('count', array(
            'conditions' => array(
                'Item.admin_suspend = ' => 1,
            )
        )));
    }
    public function admin_add()
    {
        $this->setAction('add');
    }
    public function admin_edit($id = null)
    {
        $this->pageTitle = __l('Edit Item');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->Item->id = $id;
        if (!$this->Item->exists()) {
            throw new NotFoundException(__l('Invalid item'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Item->save($this->request->data)) {
                $this->Session->setFlash(__l('Item has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Item could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->data = $this->Item->read(null, $id);
            if (empty($this->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->data['Item']['name'];
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->Item->id = $id;
        if (!$this->Item->exists()) {
            throw new NotFoundException(__l('Invalid item'));
        }
        if ($this->Item->delete()) {
            $this->Session->setFlash(__l('Item deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        $this->Session->setFlash(__l('Item was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'action' => 'index'
        ));
    }
}
