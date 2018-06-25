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
class SightingFlagsController extends AppController
{
    public $name = 'SightingFlags';
    public function add()
    {
        $this->pageTitle = __l('Add Sighting Flag');
        if (!empty($this->request->data)) {
            $this->request->data['SightingFlag']['ip_id'] = $this->SightingFlag->toSaveIp();
            $this->SightingFlag->create();
            if ($this->SightingFlag->save($this->request->data)) {
                $this->Session->setFlash(__l('Sighting flag has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'controller' => 'sightings',
                    'action' => 'view',
                    $this->request->data['SightingFlag']['sighting_id']
                ));
            } else {
                $this->Session->setFlash(__l('Sighting flag could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        if (isset($this->request->params['named']['sighting_id'])) {
            $this->request->data['SightingFlag']['sighting_id'] = $this->request->params['named']['sighting_id'];
        }
        $sightingFlagCategories = $this->SightingFlag->SightingFlagCategory->find('list');
        $this->set(compact('sightingFlagCategories'));
    }
    public function admin_index()
    {
		$this->_redirectPOST2Named(array(
            'q'
        ));
		$this->pageTitle = __l('Sighting Flags');
		if (!empty($this->request->params['named']['q'])) {
            $this->request->data['SightingFlag']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        $this->SightingFlag->recursive = 0;
		$conditions = array();
		if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(SightingFlag.created) <= '] = 0;
            $this->pageTitle.= __l(' - today');
        }
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(SightingFlag.created) <= '] = 7;
            $this->pageTitle.= __l('- in this week');
        }
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(SightingFlag.created) <= '] = 30;
            $this->pageTitle.= __l(' - in this month');
        }
        if(isset($this->request->params['named']['sighting'])) {
			$conditions['SightingFlag.sighting_id'] = $this->request->params['named']['sighting'];
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
            $conditions['SightingFlag.user_id'] = $user['User']['id'];
            $this->pageTitle.= ' - ' . $user['User']['username'];
        }
		$order = array(
            'SightingFlag.id' => 'DESC'
        );
        $this->paginate = array(
            'conditions' => $conditions,
			'order' => $order,
			'contain' => array(
				'Sighting' => array(
					'Item',
					'Place',
				),
				'User',
				'SightingFlagCategory',
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
			),
        );
		if (!empty($this->request->data['SightingFlag']['q'])) {
            $this->paginate = array_merge($this->paginate, array('search' => $this->request->data['SightingFlag']['q']));
        }
        $this->set('sightingFlags', $this->paginate());
		$moreActions = $this->SightingFlag->moreActions;
		$this->set(compact('moreActions'));
    }
    public function admin_add()
    {
        $this->setAction('add');
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->SightingFlag->id = $id;
        if (!$this->SightingFlag->exists()) {
            throw new NotFoundException(__l('Invalid sighting flag'));
        }
        if ($this->SightingFlag->delete()) {
            $this->Session->setFlash(__l('Sighting flag deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        $this->Session->setFlash(__l('Sighting flag was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'action' => 'index'
        ));
    }
}
