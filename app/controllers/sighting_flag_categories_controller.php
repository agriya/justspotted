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
class SightingFlagCategoriesController extends AppController
{
    public $name = 'SightingFlagCategories';
    public function admin_index() 
    {
        $this->pageTitle = __l('Sighting Flag Categories');
        $this->SightingFlagCategory->recursive = 0;
		$this->paginate = array(
			'order' => array(
				'SightingFlagCategory.id' => 'DESC'
			),	
		);
        $this->set('sightingFlagCategories', $this->paginate());
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Sighting Flag Category');
        if ($this->request->is('post')) {
            $this->SightingFlagCategory->create();
            if ($this->SightingFlagCategory->save($this->request->data)) {
                $this->Session->setFlash(__l('sighting flag category has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('sighting flag category could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Sighting Flag Category');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->SightingFlagCategory->id = $id;
        if (!$this->SightingFlagCategory->exists()) {
            throw new NotFoundException(__l('Invalid sighting flag category'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->SightingFlagCategory->save($this->request->data)) {
                $this->Session->setFlash(__l('sighting flag category has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('sighting flag category could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->data = $this->SightingFlagCategory->read(null, $id);
            if (empty($this->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->data['SightingFlagCategory']['name'];
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->SightingFlagCategory->id = $id;
        if (!$this->SightingFlagCategory->exists()) {
            throw new NotFoundException(__l('Invalid sighting flag category'));
        }
        if ($this->SightingFlagCategory->delete()) {
            $this->Session->setFlash(__l('Sighting flag category deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        $this->Session->setFlash(__l('Sighting flag category was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'action' => 'index'
        ));
    }
}
