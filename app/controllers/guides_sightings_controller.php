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
class GuidesSightingsController extends AppController
{
    public $name = 'GuidesSightings';
    public function index()
    {
        $this->pageTitle = __l('Guides Sightings');
        $conditions = array();
        if (!empty($this->request->params['named']['sighting_id'])) {
            $conditions['GuidesSighting.sighting_id'] = $this->request->params['named']['sighting_id'];
			$conditions['Guide.is_published'] = 1;
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'Guide' => array(
                    'Attachment'
                )
            ) ,
            'order' => array(
                'GuidesSighting.id' => 'desc'
            ) ,
			'group' => array(
				'GuidesSighting.guide_id'
			),
            'recursive' => 2
        );
        $this->set('guidesSightings', $this->paginate());
    }
    public function add()
    {
        $this->pageTitle = __l('Add Guides Sighting');
        if (isset($this->request->params['named']['guide_id'])) {
            $guide_id = $this->request->params['named']['guide_id'];
        }
        if (isset($this->request->params['named']['review_id'])) {
            $review_id = $this->request->params['named']['review_id'];
        }
        if (!empty($review_id)) {
            $review = $this->GuidesSighting->Review->find('first', array(
                'conditions' => array(
                    'Review.id' => $review_id
                ) ,
                'recursive' => -1
            ));
            if (empty($review)) {
                throw new NotFoundException(__l('Invalid request'));
            } elseif ($review['Review']['user_id'] != $this->Auth->user('id')) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        if (!empty($guide_id)) {
            $guide = $this->GuidesSighting->Guide->find('first', array(
                'conditions' => array(
                    'Guide.id' => $guide_id
                ) ,
                'recursive' => -1
            ));
            if (empty($guide)) {
                throw new NotFoundException(__l('Invalid request'));
            } elseif (!$guide['Guide']['is_anyone_add_additional_sightings_to_this_guide'] && ($guide['Guide']['user_id'] != $this->Auth->user('id'))) {
                throw new NotFoundException(__l('Invalid request'));
            }
            if (!empty($guide['Guide']['no_of_max_sightings']) && $guide['Guide']['no_of_max_sightings'] <= $guide['Guide']['sighting_count']) {
                $this->Session->setFlash(__l('No of max review added for this guide. You can\'t add more reviews') , 'default', null, 'error');
                $this->redirect(array(
                    'controller' => 'guides',
                    'action' => 'view',
                    $guide['Guide']['slug']
                ));
            }
        }
        $this->GuidesSighting->create();
        $data = array();
        $data['GuidesSighting']['sighting_id'] = $review['Review']['sighting_id'];
        $data['GuidesSighting']['review_id'] = $review['Review']['id'];
        $data['GuidesSighting']['guide_id'] = $guide['Guide']['id'];
        $this->GuidesSighting->save($data);
        $guidesSighting_id = $this->GuidesSighting->getLastInsertId();
        $this->set('review_id', $review_id);
        $this->set('guidesSighting_id', $guidesSighting_id);
        $this->set('guide', $guide);
        if (!$this->RequestHandler->isAjax()) {
            $this->redirect(array(
                'controller' => 'guides',
                'action' => 'view',
                $guide['Guide']['slug']
            ));
        }
    }
    public function delete($id = null)
    {
        if (is_null($id) || !$this->request->params['named']['guide_id'] || !$this->request->params['named']['review_id']) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (isset($this->request->params['named']['review_id'])) {
            $review_id = $this->request->params['named']['review_id'];
        }
        if (isset($this->request->params['named']['guide_id'])) {
            $guide_id = $this->request->params['named']['guide_id'];
        }
        $guide = $this->GuidesSighting->Guide->find('first', array(
            'conditions' => array(
                'Guide.id' => $guide_id
            ) ,
            'recursive' => -1
        ));
        if (empty($guide)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->GuidesSighting->id = $id;
        if (!$this->GuidesSighting->exists()) {
            throw new NotFoundException(__l('Invalid guides sighting'));
        }
        $this->set('review_id', $review_id);
        $this->set('guide', $guide);
        if ($this->GuidesSighting->delete()) {
            if (!$this->RequestHandler->isAjax()) {
                $this->Session->setFlash(__l('Guides sighting deleted') , 'default', null, 'success');
                $this->redirect(array(
                    'controller' => 'guides',
                    'action' => 'view',
                    $guide['Guide']['slug']
                ));
            }
        } else {
            $this->Session->setFlash(__l('Guides sighting was not deleted') , 'default', null, 'error');
            $this->redirect(array(
                'controller' => 'guides',
                'action' => 'view',
                $guide['Guide']['slug']
            ));
        }
    }
}
