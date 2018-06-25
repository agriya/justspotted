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
class SightingRatingStatsController extends AppController
{
    public $name = 'SightingRatingStats';
    public function admin_index() 
    {
        $this->pageTitle = __l('sightingRatingStats');
        $this->SightingRatingStat->recursive = 0;
        $this->set('sightingRatingStats', $this->paginate());
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->SightingRatingStat->id = $id;
        if (!$this->SightingRatingStat->exists()) {
            throw new NotFoundException(__l('Invalid sighting rating stat'));
        }
        if ($this->SightingRatingStat->delete()) {
            $this->Session->setFlash(__l('Sighting rating stat deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        $this->Session->setFlash(__l('Sighting rating stat was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'action' => 'index'
        ));
    }
}
