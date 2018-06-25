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
class ReviewCategoriesController extends AppController
{
    public $name = 'ReviewCategories';
    public function admin_index() 
    {
		$this->_redirectPOST2Named(array(
            'q'
        ));
		$this->pageTitle = __l('reviewCategories');
		if (!empty($this->request->params['named']['q'])) {
            $this->request->data['ReviewCategory']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        $this->ReviewCategory->recursive = 0;
		$this->paginate = array(
			'order' => array(
				'ReviewCategory.id' => 'DESC'
			),	
		);
		if (!empty($this->request->data['ReviewCategory']['q'])) {
            $this->paginate = array_merge($this->paginate, array('search' => $this->request->data['ReviewCategory']['q']));
        }
        $this->set('reviewCategories', $this->paginate());
		$moreActions = $this->ReviewCategory->moreActions;
		$this->set(compact('moreActions'));
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Review Category');
        if ($this->request->is('post')) {
            $this->ReviewCategory->create();
            if ($this->ReviewCategory->save($this->request->data)) {
                $this->Session->setFlash(__l('review category has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('review category could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Review Category');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->ReviewCategory->id = $id;
        if (!$this->ReviewCategory->exists()) {
            throw new NotFoundException(__l('Invalid review category'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->ReviewCategory->save($this->request->data)) {
                $this->Session->setFlash(__l('review category has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('review category could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->data = $this->ReviewCategory->read(null, $id);
            if (empty($this->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->data['ReviewCategory']['title'];
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->ReviewCategory->id = $id;
        if (!$this->ReviewCategory->exists()) {
            throw new NotFoundException(__l('Invalid review category'));
        }
        if ($this->ReviewCategory->delete()) {
            $this->Session->setFlash(__l('Review category deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        $this->Session->setFlash(__l('Review category was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'action' => 'index'
        ));
    }
}
