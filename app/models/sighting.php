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
class Sighting extends AppModel
{
    public $name = 'Sighting';
    //$validate set in __construct for multi-language support
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'Item' => array(
            'className' => 'Item',
            'foreignKey' => 'item_id',
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
        'Place' => array(
            'className' => 'Place',
            'foreignKey' => 'place_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true
        ) ,
    );
    public $hasMany = array(
        'Review' => array(
            'className' => 'Review',
            'foreignKey' => 'sighting_id',
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
        'BaseReview' => array(
            'className' => 'Review',
            'foreignKey' => 'sighting_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => array(
                'BaseReview.id' => 'ASC'
            ) ,
            'limit' => 1,
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'SightingRatingStat' => array(
            'className' => 'SightingRatingStat',
            'foreignKey' => 'sighting_id',
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
        'SightingRating' => array(
            'className' => 'SightingRating',
            'foreignKey' => 'sighting_id',
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
        'SightingView' => array(
            'className' => 'SightingView',
            'foreignKey' => 'sighting_id',
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
        'SightingFlag' => array(
            'className' => 'SightingFlag',
            'foreignKey' => 'sighting_id',
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
		'GuidesSighting' => array(
            'className' => 'GuidesSighting',
            'foreignKey' => 'sighting_id',
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
	public $hasAndBelongsToMany = array(
        'Guide' => array(
            'className' => 'Guide',
            'joinTable' => 'guides_sightings',
            'foreignKey' => 'sighting_id',
            'associationForeignKey' => 'guide_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => ''
        ) ,
    );
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'item_id' => array(
                'rule1' => array(
                    'rule' => 'numeric',
                    'allowEmpty' => false,
                    'message' => __l('Required') ,
                ) ,
            ) ,
            'place_id' => array(
                'rule1' => array(
                    'rule' => 'numeric',
                    'allowEmpty' => false,
                    'message' => __l('Required') ,
                ) ,
            ) ,
            'sighting_view_count' => array(
                'rule1' => array(
                    'rule' => 'numeric',
                    'allowEmpty' => false,
                    'message' => __l('Required') ,
                ) ,
            ) ,
        );
        $this->moreActions = array(
            ConstMoreAction::Suspend => __l('Suspend'),
            ConstMoreAction::Unsuspend => __l('Unsuspend'),
            ConstMoreAction::Flagged => __l('Flagged'),
            ConstMoreAction::Unflagged => __l('Clear Flag'),
			ConstMoreAction::Delete => __l('Delete')
        );
    }
    function afterSave($created)
    {
		if ($created) {
            $count = $this->find('count', array(
                'conditions' => array(
                    'Sighting.item_id = ' => $this->data['Sighting']['item_id'],
                )
            ));
            $itemData['Item']['id'] = $this->data['Sighting']['item_id'];
            $itemData['Item']['place_count'] = $count;
            $this->Item->save($itemData);
			$count = $this->find('count', array(
                'conditions' => array(
                    'Sighting.place_id = ' => $this->data['Sighting']['place_id'],
                )
            ));
            $placeData['Place']['id'] = $this->data['Sighting']['place_id'];
            $placeData['Place']['item_count'] = $count;
            $this->Place->save($placeData);
            $this->__updateUserPoint($this->data);
        }
    }
    function __updateUserPoint($data)
    {
        App::import('Model', 'UserPoint');
        $this->UserPoint = new UserPoint();
        $userpoint['UserPoint']['owner_user_id'] = $_SESSION['Auth']['User']['id'];
        $userpoint['UserPoint']['foreign_id'] = $this->id;
        $userpoint['UserPoint']['model'] = 'Sighting';
        $userpoint['UserPoint']['point'] = Configure::read('tip_point.sighting');
        $this->UserPoint->save($userpoint);
    }
}
