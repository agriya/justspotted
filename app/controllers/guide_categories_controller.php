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
class GuideCategoriesController extends AppController
{
    public $name = 'GuideCategories';
    public function index()
    {
        $this->pageTitle = __l('Guide Categories');
        $this->GuideCategory->recursive = 0;
		$this->paginate = array(
			'order' => array(
				'GuideCategory.id' => 'DESC'
			),
		);
        $this->set('guideCategories', $this->paginate());
    }
    public function admin_index()
    {
        $this->pageTitle = __l('Guide Categories');
        $this->GuideCategory->recursive = 0;
		$this->paginate = array(
			'order' => array(
				'GuideCategory.id' => 'DESC'
			),
		);
        $this->set('guideCategories', $this->paginate());
    }
    public function admin_add()
    {
        $this->pageTitle = __l('Add Guide Category');
        if ($this->request->is('post')) {
            $this->GuideCategory->create();
            if ($this->GuideCategory->save($this->request->data)) {
                $this->Session->setFlash(__l('guide category has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('guide category could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
    }
    public function admin_edit($id = null)
    {
        $this->pageTitle = __l('Edit Guide Category');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->GuideCategory->id = $id;
        if (!$this->GuideCategory->exists()) {
            throw new NotFoundException(__l('Invalid guide category'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->GuideCategory->save($this->request->data)) {
                $this->Session->setFlash(__l('guide category has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('guide category could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->data = $this->GuideCategory->read(null, $id);
            if (empty($this->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->data['GuideCategory']['name'];
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->GuideCategory->id = $id;
        if (!$this->GuideCategory->exists()) {
            throw new NotFoundException(__l('Invalid guide category'));
        }
        if ($this->GuideCategory->delete()) {
            $this->Session->setFlash(__l('Guide category deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        $this->Session->setFlash(__l('Guide category was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'action' => 'index'
        ));
    }
}
