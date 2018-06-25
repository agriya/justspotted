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
class GuidesSighting extends AppModel
{
    public $name = 'GuidesSighting';
    //$validate set in __construct for multi-language support
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'Guide' => array(
            'className' => 'Guide',
            'foreignKey' => 'guide_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => false
        ) ,
        'Sighting' => array(
            'className' => 'Sighting',
            'foreignKey' => 'sighting_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => false
        ) ,
        'Review' => array(
            'className' => 'Review',
            'foreignKey' => 'review_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => false
        )
    );
    function afterSave($created)
    {
        if ($created) {
            $this->__updateCount($this->data);
        }
    }
    function __updateCount($data)
    {
        $guideData['Guide']['id'] = $data['GuidesSighting']['guide_id'];
        $guideData['Guide']['sighting_count'] = $this->find('count', array(
            'conditions' => array(
                'GuidesSighting.guide_id' => $data['GuidesSighting']['guide_id']
            ) ,
            'recursive' => -1
        ));
        $this->Guide->save($guideData);
    }
    function beforeDelete($id)
    {
        $data = $this->data = $this->find('first', array(
            'conditions' => array(
                'GuidesSighting.id' => $this->id,
            ) ,
            'recursive' => -1
        ));
        if (!empty($this->data)) return true;
        else return false;
    }
    function afterDelete()
    {
        $this->__updateCount($this->data);
    }
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'guide_id' => array(
                'rule1' => array(
                    'rule' => 'numeric',
                    'allowEmpty' => false,
                    'message' => __l('Required') ,
                ) ,
            ) ,
            'sighting_id' => array(
                'rule1' => array(
                    'rule' => 'numeric',
                    'allowEmpty' => false,
                    'message' => __l('Required') ,
                ) ,
            ) ,
        );
    }
}
