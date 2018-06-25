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
class Place extends AppModel
{
    public $name = 'Place';
    public $displayField = 'name';
    public $actsAs = array(
        'Sluggable' => array(
            'label' => array(
                'name'
            )
        ) ,
        'SuspiciousWordsDetector' => array(
            'fields' => array(
                'name',
            )
        ) ,
    );
    //$validate set in __construct for multi-language support
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'PlaceType' => array(
            'className' => 'PlaceType',
            'foreignKey' => 'place_type_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true
        ) ,
		'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true
        ) ,
        'City' => array(
            'className' => 'City',
            'foreignKey' => 'city_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => false
        ) ,
        'State' => array(
            'className' => 'State',
            'foreignKey' => 'state_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => false
        ) ,
        'Country' => array(
            'className' => 'Country',
            'foreignKey' => 'country_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => false
        ),
        'Business' => array(
            'className' => 'Business',
            'foreignKey' => 'business_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true
        )
    );
    public $hasMany = array(
        'PlaceFollower' => array(
            'className' => 'PlaceFollower',
            'foreignKey' => 'place_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'PlaceView' => array(
            'className' => 'PlaceView',
            'foreignKey' => 'place_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'Sighting' => array(
            'className' => 'Sighting',
            'foreignKey' => 'place_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
        'PlaceClaimRequest' => array(
            'className' => 'PlaceClaimRequest',
            'foreignKey' => 'place_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'place_type_id' => array(
                'rule1' => array(
                    'rule' => 'numeric',
                    'allowEmpty' => false,
                    'message' => __l('Required') ,
                ) ,
            ) ,
            'name' => array(
                'rule1' => array(
                    'rule' => 'notempty',
                    'allowEmpty' => false,
                    'message' => __l('Required') ,
                ) ,
            ) ,
            'address1' => array(
                'rule' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required') ,
                    'allowEmpty' => false
                )
            ) ,
            'city_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'state_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'country_id' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'zip_code' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
			'address2' => array(
                'rule2' => array(
                    'rule' => 'is_check_address',
                    'message' => __l('Must be Enter Detail Address') ,
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required') ,
                    'allowEmpty' => false
                )
            )
        );
		$this->moreActions = array(
            ConstMoreAction::Suspend => __l('Suspend'),
            ConstMoreAction::Unsuspend => __l('Unsuspend'),
            ConstMoreAction::Flagged => __l('Flagged'),
            ConstMoreAction::Unflagged => __l('Clear Flag'),
            ConstMoreAction::Delete => __l('Delete')
        );
    }
	function is_check_address(){
		if(empty($this->data['Place']['address1']) || empty($this->data['Place']['latitude']) || empty($this->data['Place']['longitude']) || empty($this->data['Place']['country_id']) || empty($this->data['Place']['city_id']) ){
			return false;
		}
		return true;
	}
}
