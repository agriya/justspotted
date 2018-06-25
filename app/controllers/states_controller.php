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
class StatesController extends AppController
{
    public $name = 'States';
    public function admin_index()
    {
        $this->_redirectPOST2Named(array(
            'filter_id',
            'q'
        ));
        $this->pageTitle = __l('States');
        $conditions = array();
        $this->State->validate = array();
        if (!empty($this->request->params['named']['filter_id'])) {
            $this->request->data[$this->modelClass]['filter_id'] = $this->request->params['named']['filter_id'];
        }
        if (!empty($this->request->data[$this->modelClass]['filter_id'])) {
            if ($this->request->data[$this->modelClass]['filter_id'] == ConstMoreAction::Active) {
                $this->pageTitle.= __l(' - Approved');
                $conditions[$this->modelClass . '.is_approved'] = 1;
            } else if ($this->request->data[$this->modelClass]['filter_id'] == ConstMoreAction::Inactive) {
                $this->pageTitle.= __l(' - Unapproved');
                $conditions[$this->modelClass . '.is_approved'] = 0;
            }
            $this->request->params['named']['filter_id'] = $this->request->data[$this->modelClass]['filter_id'];
        }
        if (!empty($this->request->params['named']['q'])) {
            $this->request->data['State']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        $this->State->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
            'fields' => array(
                'State.id',
                'State.name',
                'State.code',
                'State.adm1code',
                'State.is_approved',
                'Country.name'
            ) ,
            'order' => array(
                'State.name' => 'asc'
            ) ,
            'limit' => 15,
        );
        if (!empty($this->request->data['State']['q'])) {
            $this->paginate = array_merge($this->paginate, array('search' => $this->request->data['State']['q']));
        }
        $this->set('states', $this->paginate());
        $filters = $this->State->isFilterOptions;
        $moreActions = $this->State->moreActions;
        $this->set(compact('filters', 'moreActions'));
        $this->set('active', $this->State->find('count', array(
            'conditions' => array(
                'State.is_approved' => 1
            )
        )));
        $this->set('inactive', $this->State->find('count', array(
            'conditions' => array(
                'State.is_approved' => 0
            )
        )));
    }
    public function admin_add()
    {
        $this->pageTitle = __l('Add State');
        if (!empty($this->request->data)) {
            $this->State->create();
            if ($this->State->save($this->request->data)) {
                $this->Session->setFlash(__l('State has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('State could not be added. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data['State']['is_approved'] = 1;
        }
        $countries = $this->State->Country->find('list');
        $this->set(compact('countries'));
    }
    public function admin_edit($id = null)
    {
        $this->pageTitle = __l('Edit State');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->State->save($this->request->data)) {
                $this->Session->setFlash(__l('State has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('State could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->State->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['State']['name'];
        $countries = $this->State->Country->find('list');
        $this->set(compact('countries'));
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->State->delete($id)) {
            $this->Session->setFlash(__l('State deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>