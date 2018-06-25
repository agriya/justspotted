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
class PlaceClaimRequest extends AppModel
{
    public $name = 'PlaceClaimRequest';
    //$validate set in __construct for multi-language support
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'Business' => array(
            'className' => 'Business',
            'foreignKey' => 'business_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true
        ) ,
        'Place' => array(
            'className' => 'Place',
            'foreignKey' => 'place_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true
        )
    );
    function afterSave()
    {
        $this->__updatePlace($this->data);
    }
    function __construct($id = false, $table = null, $ds = null) 
    {
        parent::__construct($id, $table, $ds);
        $this->moreActions = array(
            ConstMoreAction::Approved => __l('Approve'),
            ConstMoreAction::Disapproved => __l('Reject'),
        );
    }
    function __updatePlace($data)
    {
        if($data['PlaceClaimRequest']['is_approved']==ConstPlaceClaimRequests::Approved){
            $PlaceClaimRequest=$this->find('first',array(
                'conditions'=>array(
                    'PlaceClaimRequest.id'=>$this->id
                ),
                'recursive'=>-1
            ));
            if($PlaceClaimRequest){
                App::import('Model', 'Place');
                $this->Place = new Place();
                $place['Place']['id']=$PlaceClaimRequest['PlaceClaimRequest']['place_id'];
                $place['Place']['business_id']=$PlaceClaimRequest['PlaceClaimRequest']['business_id'];
                $this->Place->save($place);
            }
        }
    }
}
