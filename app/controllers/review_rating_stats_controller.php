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
class ReviewRatingStatsController extends AppController
{
    public $name = 'ReviewRatingStats';
    public function admin_index() 
    {
        $this->pageTitle = __l('reviewRatingStats');
        $this->ReviewRatingStat->recursive = 0;
        $this->set('reviewRatingStats', $this->paginate());
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->ReviewRatingStat->id = $id;
        if (!$this->ReviewRatingStat->exists()) {
            throw new NotFoundException(__l('Invalid review rating stat'));
        }
        if ($this->ReviewRatingStat->delete()) {
            $this->Session->setFlash(__l('Review rating stat deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        $this->Session->setFlash(__l('Review rating stat was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'action' => 'index'
        ));
    }
}
