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
class UserSightingRatingStatsController extends AppController
{
    public $name = 'UserSightingRatingStats';
    public function index()
    {
        $conditions = null;
        if (!empty($this->request->params['named']['user_id'])) {
            $conditions = array(
                'UserSightingRatingStat.user_id' => $this->request->params['named']['user_id']
            );
        }
        $this->pageTitle = __l('User Sighting Rating Stats');
        $this->UserSightingRatingStat->recursive = 0;
        $sightingRatingTypes = $this->UserSightingRatingStat->SightingRatingType->find('all', array(
            'recursive' => -1
        ));
        $this->paginate = array(
            'fields' => array(
                'UserSightingRatingStat.id',
                'UserSightingRatingStat.count',
                'UserSightingRatingStat.user_id',
                'UserSightingRatingStat.sighting_rating_type_id'
            ) ,
            'conditions' => $conditions
        );
        $this->set('userSightingRatingStats', $this->paginate());
        $this->set('sightingRatingTypes', $sightingRatingTypes);
    }
    public function admin_index()
    {
        $this->pageTitle = __l('userSightingRatingStats');
        $this->UserSightingRatingStat->recursive = 0;
        $this->set('userSightingRatingStats', $this->paginate());
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->UserSightingRatingStat->id = $id;
        if (!$this->UserSightingRatingStat->exists()) {
            throw new NotFoundException(__l('Invalid user sighting rating stat'));
        }
        if ($this->UserSightingRatingStat->delete()) {
            $this->Session->setFlash(__l('User sighting rating stat deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        $this->Session->setFlash(__l('User sighting rating stat was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'action' => 'index'
        ));
    }
}
