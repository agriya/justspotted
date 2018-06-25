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
class PlaceTypesController extends AppController
{
    public $name = 'PlaceTypes';
    public function admin_index()
    {
        $this->pageTitle = __l('Place Types');
        $this->PlaceType->recursive = 0;
		$this->paginate = array(
			'order' => array(
				'PlaceType.id' => 'DESC'
			),
		);
        $this->set('placeTypes', $this->paginate());
    }
    public function admin_add()
    {
        $this->pageTitle = __l('Add Place Type');
        if ($this->request->is('post')) {
            $this->PlaceType->create();
            if ($this->PlaceType->save($this->request->data)) {
                $this->Session->setFlash(__l('place type has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('place type could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
    }
    public function admin_edit($id = null)
    {
        $this->pageTitle = __l('Edit Place Type');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->PlaceType->id = $id;
        if (!$this->PlaceType->exists()) {
            throw new NotFoundException(__l('Invalid place type'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->PlaceType->save($this->request->data)) {
                $this->Session->setFlash(__l('place type has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('place type could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->data = $this->PlaceType->read(null, $id);
            if (empty($this->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->data['PlaceType']['name'];
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->PlaceType->id = $id;
        if (!$this->PlaceType->exists()) {
            throw new NotFoundException(__l('Invalid place type'));
        }
        if ($this->PlaceType->delete()) {
            $this->Session->setFlash(__l('Place type deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        $this->Session->setFlash(__l('Place type was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'action' => 'index'
        ));
    }
}
