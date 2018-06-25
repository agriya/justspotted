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
class Review extends AppModel
{
    public $name = 'Review';
	var $actsAs = array(
        'Taggable',
        'SuspiciousWordsDetector' => array(
            'fields' => array(                
                'notes',
                'tag'
            )
        ) ,
    );
    //$validate set in __construct for multi-language support
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'ReviewCategory' => array(
            'className' => 'ReviewCategory',
            'foreignKey' => 'review_category_id',
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
        'Sighting' => array(
            'className' => 'Sighting',
            'foreignKey' => 'sighting_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true
        )
    );
    public $hasOne = array(
		'Attachment' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_id',
            'conditions' => array(
                'Attachment.class =' => 'Review'
            ) ,
            'dependent' => true
        ) ,
	);
    public $hasMany = array(
        'ReviewComment' => array(
            'className' => 'ReviewComment',
            'foreignKey' => 'review_id',
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
        'ReviewRatingStat' => array(
            'className' => 'ReviewRatingStat',
            'foreignKey' => 'review_id',
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
        'ReviewRating' => array(
            'className' => 'ReviewRating',
            'foreignKey' => 'review_id',
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
        'ReviewView' => array(
            'className' => 'ReviewView',
            'foreignKey' => 'review_id',
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
            'foreignKey' => 'review_id',
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
    );
    public $hasAndBelongsToMany = array(
        'ReviewTag' => array(
            'className' => 'ReviewTag',
            'joinTable' => 'reviews_review_tags',
            'foreignKey' => 'review_id',
            'associationForeignKey' => 'review_tag_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => ''
        )
    );
	function afterSave($created) {
		if(isset($this->data['Review']['is_system_flagged'])) {
				$review = $this->find('first', array(
				'conditions' => array(
					'Review.id' => $this->id
				),
                'contain' => array(
                    'Sighting' => array(
						'BaseReview'
					)
                ) ,
            ));
			// suspend sighting, when base review is flagged
			if($this->id == $review['Sighting']['BaseReview'][0]['id']) {
				$sightingdata['Sighting']['id'] = $review['Sighting']['id'];
				$sightingdata['Sighting']['is_system_flagged'] = $this->data['Review']['is_system_flagged'];
				$sightingdata['Sighting']['detected_suspicious_words'] = $this->data['Review']['detected_suspicious_words'];
				$this->Sighting->save($sightingdata);
			}
		}
		if(isset($this->data['Review']['admin_suspend'])) {
			// suspend sighting, when base review is flagged
			if($this->id == $review['Sighting']['BaseReview'][0]['id']) {
				$sightingdata['Sighting']['id'] = $review['Sighting']['id'];
				$sightingdata['Sighting']['admin_suspend'] = $this->data['Review']['admin_suspend'];
				$sightingdata['Sighting']['detected_suspicious_words'] = $this->data['Review']['detected_suspicious_words'];
				$this->Sighting->save($sightingdata);
			}
		}
	}
    function __construct($id = false, $table = null, $ds = null) 
    {
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'user_id' => array(
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
}