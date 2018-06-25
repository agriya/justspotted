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
class SightingRating extends AppModel
{
    public $name = 'SightingRating';
    //$validate set in __construct for multi-language support
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'Sighting' => array(
            'className' => 'Sighting',
            'foreignKey' => 'sighting_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => false
        ) ,
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => false
        ) ,
        'SightingRatingType' => array(
            'className' => 'SightingRatingType',
            'foreignKey' => 'sighting_rating_type_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true
        ) ,
        'Ip' => array(
            'className' => 'Ip',
            'foreignKey' => 'ip_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => false
        )
    );
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'sighting_id' => array(
                'rule1' => array(
                    'rule' => 'numeric',
                    'allowEmpty' => false,
                    'message' => __l('Required') ,
                ) ,
            ) ,
            'user_id' => array(
                'rule1' => array(
                    'rule' => 'numeric',
                    'allowEmpty' => false,
                    'message' => __l('Required') ,
                ) ,
            ) ,
            'sighting_rating_type_id' => array(
                'rule1' => array(
                    'rule' => 'numeric',
                    'allowEmpty' => false,
                    'message' => __l('Required') ,
                ) ,
            ) ,
            'ip_id' => array(
                'rule1' => array(
                    'rule' => 'numeric',
                    'allowEmpty' => false,
                    'message' => __l('Required') ,
                ) ,
            ) ,
        );
		$this->moreActions = array(
            ConstMoreAction::Delete => __l('Delete')
        );
    }
    function afterSave($created)
    {
        if ($created) {
            $this->__updateRating($this->data);
            $this->__updateUserPoint($this->data);
        }
    }
    function __updateUserPoint($data)
    {
        App::import('Model', 'UserPoint');
        $this->UserPoint = new UserPoint();
        if (!empty($data['SightingRating']['sighting_rating_type_id']) && !empty($data['SightingRating']['sighting_id'])) {
            $sight_rating_type = $this->SightingRatingType->find('first', array(
                'conditions' => array(
                    'SightingRatingType.id' => $data['SightingRating']['sighting_rating_type_id']
                ) ,
                'recusive' => -1
            ));
            if (!empty($sight_rating_type['SightingRatingType']['tip_points'])) {
                $sighting = $this->Sighting->find('first', array(
                    'conditions' => array(
                        'Sighting.id' => $data['SightingRating']['sighting_id']
                    ) ,
                    'contain' => array(
                        'Review' => array(
                            'fields' => array(
                                'Review.user_id'
                            ) ,
                            'limit' => 1,
                            'order' => array(
                                'Review.id' => 'ASC'
                            )
                        ) ,
                    ) ,
                    'recursive' => 1,
                ));
                $userpoint['UserPoint']['owner_user_id'] = $sighting['Review'][0]['user_id'];
                $userpoint['UserPoint']['other_user_id'] = $data['SightingRating']['user_id'];
                $userpoint['UserPoint']['foreign_id'] = $this->id;
                $userpoint['UserPoint']['model'] = 'SightingRating';
                $userpoint['UserPoint']['point'] = $sight_rating_type['SightingRatingType']['tip_points'];
                $this->UserPoint->save($userpoint);
            }
        }
    }
    function __updateRating($data)
    {
        // insert/update SightingRatingStat
        $rating_count = $this->find('count', array(
            'conditions' => array(
                'SightingRating.sighting_id' => $data['SightingRating']['sighting_id'],
                'SightingRating.sighting_rating_type_id' => $data['SightingRating']['sighting_rating_type_id']
            )
        ));
        $rating_stat = $this->Sighting->SightingRatingStat->find('first', array(
            'conditions' => array(
                'SightingRatingStat.sighting_id' => $data['SightingRating']['sighting_id'],
                'SightingRatingStat.sighting_rating_type_id' => $data['SightingRating']['sighting_rating_type_id']
            ) ,
            'recursive' => -1
        ));
        if (!empty($rating_stat)) {
            $this->data['SightingRatingStat']['id'] = $rating_stat['SightingRatingStat']['id'];
        } else {
            $this->Sighting->SightingRatingStat->create();
        }
        $this->data['SightingRatingStat']['sighting_id'] = $data['SightingRating']['sighting_id'];
        $this->data['SightingRatingStat']['sighting_rating_type_id'] = $data['SightingRating']['sighting_rating_type_id'];
        $this->data['SightingRatingStat']['count'] = $rating_count;
        $this->Sighting->SightingRatingStat->save($this->data['SightingRatingStat']);
        // insert/update UserSightingRatingStat
        $user_rating_count = $this->find('count', array(
            'conditions' => array(
                'SightingRating.user_id' => $data['SightingRating']['user_id'],
                'SightingRating.sighting_rating_type_id' => $data['SightingRating']['sighting_rating_type_id']
            )
        ));
        $user_rating_stat = $this->SightingRatingType->UserSightingRatingStat->find('first', array(
            'conditions' => array(
                'UserSightingRatingStat.user_id' => $data['SightingRating']['user_id'],
                'UserSightingRatingStat.sighting_rating_type_id' => $data['SightingRating']['sighting_rating_type_id']
            ) ,
            'recursive' => -1
        ));
        if (!empty($user_rating_stat)) {
            $this->data['UserSightingRatingStat']['id'] = $user_rating_stat['UserSightingRatingStat']['id'];
        } else {
            $this->SightingRatingType->UserSightingRatingStat->create();
        }
        $this->data['UserSightingRatingStat']['user_id'] = $data['SightingRating']['user_id'];
        $this->data['UserSightingRatingStat']['sighting_rating_type_id'] = $data['SightingRating']['sighting_rating_type_id'];
        $this->data['UserSightingRatingStat']['count'] = $user_rating_count;
        $this->SightingRatingType->UserSightingRatingStat->save($this->data['UserSightingRatingStat']);
    }
    function beforeDelete()
    {
        $this->data = $this->find('first', array(
            'conditions' => array(
                'SightingRating.id' => $this->id,
            ) ,
            'recursive' => -1
        ));
        if (!empty($this->data)) return true;
        else return false;
    }
    function afterDelete()
    {
        $this->__updateRating($this->data);
        $this->__removeUserPoint($this->data);
    }
    function __removeUserPoint($data)
    {
        App::import('Model', 'UserPoint');
        $this->UserPoint = new UserPoint();
        $this->UserPoint->deleteAll(array(
            'UserPoint.other_user_id' => $data['SightingRating']['user_id'],
            'UserPoint.foreign_id' => $data['SightingRating']['id'],
            'UserPoint.model' => 'SightingRating'
        ));
    }
}
