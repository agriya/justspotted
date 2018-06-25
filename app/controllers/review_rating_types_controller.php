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
class ReviewRatingTypesController extends AppController
{
    public $name = 'ReviewRatingTypes';
    public function index()
    {
        $this->pageTitle = __l('ReviewRating Types');
        if (empty($this->request->params['named']['review_id'])) {
            throw new NotFoundException(__l('Invalid sighting rating'));
        }
        $reviewRatingTypes = $this->ReviewRatingType->find('all', array(
            'conditions' => array(
                'ReviewRatingType.is_active = ' => 1
            ) ,
            'contain' => array(
                'ReviewRatingStat' => array(
                    'conditions' => array(
                        'ReviewRatingStat.review_id' => $this->request->params['named']['review_id']
                    )
                ) ,
                'ReviewRating' => array(
                    'conditions' => array(
                        'ReviewRating.review_id' => $this->request->params['named']['review_id']
                    ) ,
                    'User' => array(
                        'fields' => array(
                            'User.id',
                            'User.username'
                        )
                    )
                )
            ) ,
            'recursive' => 2,
        ));
        $this->set('review_id', $this->request->params['named']['review_id']);
        $this->set('sighting_id', $this->request->params['named']['sighting_id']);
        $this->set('reviewRatingTypes', $reviewRatingTypes);
    }
    public function admin_index()
    {
        $this->pageTitle = __l('Review Rating Types');
        $this->ReviewRatingType->recursive = 0;
        $this->paginate = array(
            'order' => array(
                'ReviewRatingType.id' => 'DESC'
            ) ,
        );
        $this->set('reviewRatingTypes', $this->paginate());
    }
    public function admin_add()
    {
        $this->pageTitle = __l('Add Review Rating Type');
        if ($this->request->is('post')) {
            $this->ReviewRatingType->create();
            if ($this->ReviewRatingType->save($this->request->data)) {
                $this->Session->setFlash(__l('Review rating type has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Review rating type could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
    }
    public function admin_edit($id = null)
    {
        $this->pageTitle = __l('Edit Review Rating Type');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->ReviewRatingType->id = $id;
        if (!$this->ReviewRatingType->exists()) {
            throw new NotFoundException(__l('Invalid review rating type'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->ReviewRatingType->save($this->request->data)) {
                $this->Session->setFlash(__l('Review rating type has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Review rating type could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->data = $this->ReviewRatingType->read(null, $id);
            if (empty($this->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->data['ReviewRatingType']['name'];
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->ReviewRatingType->id = $id;
        if (!$this->ReviewRatingType->exists()) {
            throw new NotFoundException(__l('Invalid review rating type'));
        }
        if ($this->ReviewRatingType->delete()) {
            $this->Session->setFlash(__l('Review rating type deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        $this->Session->setFlash(__l('Review rating type was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'action' => 'index'
        ));
    }
}
