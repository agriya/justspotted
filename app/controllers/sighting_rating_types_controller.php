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
class SightingRatingTypesController extends AppController
{
    public $name = 'SightingRatingTypes';
    public function index()
    {
        $this->pageTitle = __l('sightingRatingTypes');
        if (empty($this->request->params['named']['sighting_id'])) {
            throw new NotFoundException(__l('Invalid sighting rating'));
        }
        $sightingRatingType = $this->SightingRatingType->find('all', array(
            'conditions' => array(
                'SightingRatingType.is_active = ' => 1
            ) ,
            'contain' => array(
                'SightingRatingStat' => array(
                    'conditions' => array(
                        'SightingRatingStat.sighting_id' => $this->request->params['named']['sighting_id']
                    )
                ) ,
            ) ,
            'recursive' => 2,
        ));
        $this->set('sighting_id', $this->request->params['named']['sighting_id']);
        $this->set('sightingRatingTypes', $sightingRatingType);
		if(!empty($this->request->params['named']['view']) && $this->request->params['named']['view'] == "raters") {
            $this->render('raters');
		}
    }
    public function menu()
    {
        if (!(!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'profile')) {
            $conditions['SightingRatingType.is_filtering_enabled = '] = 1;
        }
        $conditions['SightingRatingType.is_active = '] = 1;
        $sightingRatingTypes = $this->SightingRatingType->find('all', array(
            'conditions' => $conditions,
            'order' => array(
                'SightingRatingType.id' => 'DESC'
            ) ,
            'recursive' => -1
        ));
        $this->set('sightingRatingTypes', $sightingRatingTypes);
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'profile') {
            $this->render('profile_menu');
        }
    }
    public function admin_index()
    {
        $this->pageTitle = __l('Sighting Rating Types');
        $this->SightingRatingType->recursive = 0;
        $this->paginate = array(
            'order' => array(
                'SightingRatingType.id' => 'DESC'
            ) ,
        );
        $this->set('sightingRatingTypes', $this->paginate());
    }
    public function admin_add()
    {
        $this->pageTitle = __l('Add Sighting Rating Type');
        if ($this->request->is('post')) {
            $this->SightingRatingType->create();
            if ($this->SightingRatingType->save($this->request->data)) {
                $this->Session->setFlash(__l('sighting rating type has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('sighting rating type could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
    }
    public function admin_edit($id = null)
    {
        $this->pageTitle = __l('Edit Sighting Rating Type');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->SightingRatingType->id = $id;
        if (!$this->SightingRatingType->exists()) {
            throw new NotFoundException(__l('Invalid sighting rating type'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->SightingRatingType->save($this->request->data)) {
                $this->Session->setFlash(__l('sighting rating type has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('sighting rating type could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->data = $this->SightingRatingType->read(null, $id);
            if (empty($this->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->data['SightingRatingType']['name'];
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->SightingRatingType->id = $id;
        if (!$this->SightingRatingType->exists()) {
            throw new NotFoundException(__l('Invalid sighting rating type'));
        }
        if ($this->SightingRatingType->delete()) {
            $this->Session->setFlash(__l('Sighting rating type deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        $this->Session->setFlash(__l('Sighting rating type was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'action' => 'index'
        ));
    }
}
