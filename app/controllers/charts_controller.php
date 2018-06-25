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
class ChartsController extends AppController
{
    public $name = 'Charts';
    public $lastDays;
    public $lastMonths;
    public $lastYears;
    public $lastWeeks;
    public $selectRanges;
    public $lastDaysStartDate;
    public $lastMonthsStartDate;
    public $lastYearsStartDate;
    public $lastWeeksStartDate;
    public function initChart()
    {
        //# last days date settings
        $days = 6;
        $this->lastDaysStartDate = date('Y-m-d', strtotime("-$days days"));
        for ($i = $days; $i > 0; $i--) {
            $j=$i-1;
            $this->lastDays[] = array(
                'display' => date('D, M d', strtotime("-$i days")) ,
                'conditions' => array(
                    "DATE_FORMAT(#MODEL#.created, '%Y-%m-%d')" => _formatDate('Y-m-d', date('Y-m-d', strtotime("-$j days")) , true) ,
                )
            );
        }
        $this->lastDays[] = array(
            'display' => date('D, M d') ,
            'conditions' => array(
                "DATE_FORMAT(#MODEL#.created, '%Y-%m-%d')" => _formatDate('Y-m-d', date('Y-m-d', strtotime("1 days")) , true)
            )
        );
        //# last weeks date settings
        $timestamp_end = strtotime('last Saturday');
        $weeks = 3;
        $this->lastWeeksStartDate = date('Y-m-d', $timestamp_end-((($weeks*7) -1) *24*3600));
        for ($i = $weeks; $i > 0; $i--) {
            $start = $timestamp_end-((($i*7) -1) *24*3600);
            $end = $start+(6*24*3600);
            $this->lastWeeks[] = array(
                'display' => date('M d', $start) . ' - ' . date('M d', $end) ,
                'conditions' => array(
                    '#MODEL#.created >=' => _formatDate('Y-m-d', date('Y-m-d', $start) , true) ,
                    '#MODEL#.created <=' => _formatDate('Y-m-d', date('Y-m-d', $end) , true) ,
                )
            );
        }
        $this->lastWeeks[] = array(
            'display' => date('M d', $timestamp_end+24*3600) . ' - ' . date('M d') ,
            'conditions' => array(
                '#MODEL#.created >=' => _formatDate('Y-m-d', date('Y-m-d', $timestamp_end+24*3600) , true) ,
                '#MODEL#.created <=' => _formatDate('Y-m-d', date('Y-m-d') , true)
            )
        );
        //# last months date settings
        $months = 2;
        $this->lastMonthsStartDate = date('Y-m-01', strtotime("-$months months"));
        for ($i = $months; $i > 0; $i--) {
            $this->lastMonths[] = array(
                'display' => date('M, Y', strtotime("-$i months")) ,
                'conditions' => array(
                    "DATE_FORMAT(#MODEL#.created, '%Y-%m')" => _formatDate('Y-m', date('Y-m-d', strtotime("-$i months")) , true)
                )
            );
        }
        $this->lastMonths[] = array(
            'display' => date('M, Y') ,
            'conditions' => array(
                "DATE_FORMAT(#MODEL#.created, '%Y-%m')" => _formatDate('Y-m', date('Y-m-d') , true)
            )
        );
        //# last years date settings
        $years = 2;
        $this->lastYearsStartDate = date('Y-01-01', strtotime("-$years years"));
        for ($i = $years; $i > 0; $i--) {
            $this->lastYears[] = array(
                'display' => date('Y', strtotime("-$i years")) ,
                'conditions' => array(
                    "DATE_FORMAT(#MODEL#.created, '%Y')" => _formatDate('Y', date('Y-m-d', strtotime("-$i years")) , true)
                )
            );
        }
        $this->lastYears[] = array(
            'display' => date('Y') ,
            'conditions' => array(
                "DATE_FORMAT(#MODEL#.created, '%Y')" => _formatDate('Y', date('Y-m-d') , true)
            )
        );
        $this->selectRanges = array(
            'lastDays' => __l('Last 7 days') ,
            'lastWeeks' => __l('Last 4 weeks') ,
            'lastMonths' => __l('Last 3 months') ,
            'lastYears' => __l('Last 3 years')
        );
    }
    public function admin_chart_users()
    {
        if (isset($this->request->params['named']['user_type_id'])) {
            $this->request->data['Chart']['user_type_id'] = $this->request->params['named']['user_type_id'];
        }
		if(isset($this->request->data['Chart']['is_ajax_load'])){
			$this->request->params['named']['is_ajax_load'] = $this->request->data['Chart']['is_ajax_load'];
		}
        if (isset($this->request->params['named']['is_ajax_load'])) {
            $this->initChart();
            $this->loadModel('User');
            if (isset($this->request->params['named']['select_range_id'])) {
                $this->request->data['Chart']['select_range_id'] = $this->request->params['named']['select_range_id'];
            }
            if (isset($this->request->data['Chart']['select_range_id'])) {
                $select_var = $this->request->data['Chart']['select_range_id'];
            } else {
                $select_var = 'lastDays';
            }
            $user_type_id = ConstUserTypes::User;
            $this->request->data['Chart']['select_range_id'] = $select_var;
            $this->request->data['Chart']['user_type_id'] = $user_type_id;
            $model_datas['Normal'] = array(
                'display' => __l('Normal') ,
                'conditions' => array(
                    'User.is_facebook_register' => 0,
                    'User.is_twitter_register' => 0,
                    'User.is_openid_register' => 0,
                    'User.is_gmail_register' => 0,
                    'User.is_yahoo_register' => 0,
					'User.is_foursquare_register' => 0,
                    //'User.is_iphone_user' => 0,
                    //'User.is_android_user' => 0,
                )
            );
            $model_datas['Twitter'] = array(
                'display' => __l('Twitter') ,
                'conditions' => array(
                    'User.is_twitter_register' => 1,
                ) ,
            );
			$model_datas['Foursquare'] = array(
                'display' => __l('Foursquare') ,
                'conditions' => array(
                    'User.is_foursquare_register' => 1,
                ) ,
            );
            if (Configure::read('facebook.is_enabled_facebook_connect')) {
                $model_datas['Facebook'] = array(
                    'display' => __l('Facebook') ,
                    'conditions' => array(
                        'User.is_facebook_register' => 1,
                    )
                );
            }
            if (Configure::read('user.is_enable_openid') || Configure::read('user.is_enable_gmail_openid') || Configure::read('user.is_enable_yahoo_openid')) {
                $model_datas['OpenID'] = array(
                    'display' => __l('OpenID') ,
                    'conditions' => array(
                        'User.is_openid_register' => 1,
                    )
                );
            }
            $model_datas['Gmail'] = array(
                'display' => __l('Gmail') ,
                'conditions' => array(
                    'User.is_gmail_register' => 1,
                )
            );
            $model_datas['Yahoo'] = array(
                'display' => __l('Yahoo') ,
                'conditions' => array(
                    'User.is_yahoo_register' => 1,
                )
            );
            $model_datas['iPhone'] = array(
                'display' => __l('iPhone') ,
                'conditions' => array(
                    //'User.is_iphone_register' => 1,
                )
            );
            $model_datas['Android'] = array(
                'display' => __l('Android') ,
                'conditions' => array(
                    //'User.is_android_register' => 1,
                )
            );
            $model_datas['All'] = array(
                'display' => __l('All') ,
                'conditions' => array()
            );
            $common_conditions = array(
                'User.user_type_id' => $user_type_id
            );
            $_data = $this->_setLineData($select_var, $model_datas, 'User', 'User', $common_conditions);
            $this->set('chart_data', $_data);
            $this->set('chart_periods', $model_datas);
            $this->set('selectRanges', $this->selectRanges);
            // overall pie chart
            $select_var.= 'StartDate';
            $startDate = $this->$select_var;
            $endDate = date('Y-m-d');
            $total_users = $this->User->find('count', array(
                'conditions' => array(
                    'User.user_type_id' => $user_type_id,
                    'created >=' => _formatDate('Y-m-d H:i:s', $startDate, true) ,
                    'created <=' => _formatDate('Y-m-d H:i:s', $endDate, true)
                ) ,
                'recursive' => -1
            ));
            //unset($model_datas['Normal']['conditions']['User.is_android_user']);
            //unset($model_datas['Normal']['conditions']['User.is_iphone_user']);
            unset($model_datas['All']);
            unset($model_datas['iPhone']);
            unset($model_datas['Android']);
            //unset($model_datas['OpenID']);
            $_pie_data = $chart_pie_relationship_data = $chart_pie_education_data = $chart_pie_employment_data = $chart_pie_income_data = $chart_pie_gender_data = $chart_pie_age_data = array();
            if (!empty($total_users)) {
                foreach($model_datas as $_period) {
                    $new_conditions = array();
                    $new_conditions = array_merge($_period['conditions'], array(
                        'created >=' => _formatDate('Y-m-d H:i:s', $startDate, true) ,
                        'created <=' => _formatDate('Y-m-d H:i:s', $endDate, true)
                    ));
                    $new_conditions['User.user_type_id'] = $user_type_id;
                    $sub_total = $this->User->find('count', array(
                        'conditions' => $new_conditions,
                        'recursive' => -1
                    ));
                    $_pie_data[$_period['display']] = number_format(($sub_total/$total_users) *100, 2);
                }
                // demographics
                $conditions = array(
                    'User.created >=' => _formatDate('Y-m-d H:i:s', $startDate, true) ,
                    'User.created <=' => _formatDate('Y-m-d H:i:s', $endDate, true) ,
                    'User.user_type_id' => $user_type_id
                );
               // $this->_setDemographics($total_users, $conditions);
            }
            $this->set('chart_pie_data', $_pie_data);
        }
    }
    public function admin_chart_user_logins()
    {
        if (isset($this->request->params['named']['user_type_id'])) {
            $this->request->data['Chart']['user_type_id'] = $this->request->params['named']['user_type_id'];
        }
		if(isset($this->request->data['Chart']['is_ajax_load'])){
			$this->request->params['named']['is_ajax_load'] = $this->request->data['Chart']['is_ajax_load'];
		}
        if (isset($this->request->params['named']['is_ajax_load'])) {
            $this->initChart();
            $this->loadModel('UserLogin');
            if (isset($this->request->params['named']['select_range_id'])) {
                $this->request->data['Chart']['select_range_id'] = $this->request->params['named']['select_range_id'];
            }
            if (isset($this->request->data['Chart']['select_range_id'])) {
                $select_var = $this->request->data['Chart']['select_range_id'];
            } else {
                $select_var = 'lastDays';
            }
            $user_type_id = ConstUserTypes::User;
            $this->request->data['Chart']['select_range_id'] = $select_var;
            $this->request->data['Chart']['user_type_id'] = $user_type_id;
            $model_datas['Normal'] = array(
                'display' => __l('Normal') ,
                'conditions' => array(
                 //   'UserLogin.user_login_type_id' => ConstUserLoginType::Site,
                    'User.is_facebook_register' => 0,
                    'User.is_twitter_register' => 0,
                    'User.is_openid_register' => 0,
                    'User.is_gmail_register' => 0,
                    'User.is_yahoo_register' => 0,
					'User.is_foursquare_register' => 0,
                    //'User.is_iphone_user' => 0,
                    //'User.is_android_user' => 0,
                )
            );
            $model_datas['Twitter'] = array(
                'display' => __l('Twitter') ,
                'conditions' => array(
                    'User.is_twitter_register' => 1,
                ) ,
            );
			 $model_datas['Foursquare'] = array(
                'display' => __l('Foursquare') ,
                'conditions' => array(
                    'User.is_foursquare_register' => 1,
                ) ,
            );
            if (Configure::read('facebook.is_enabled_facebook_connect')) {
                $model_datas['Facebook'] = array(
                    'display' => __l('Facebook') ,
                    'conditions' => array(
                        'User.is_facebook_register' => 1,
                    )
                );
            }
            if (Configure::read('user.is_enable_openid') || Configure::read('user.is_enable_gmail_openid') || Configure::read('user.is_enable_yahoo_openid')) {
                $model_datas['OpenID'] = array(
                    'display' => __l('OpenID') ,
                    'conditions' => array(
                        'User.is_openid_register' => 1,
                    )
                );
            }
            $model_datas['Gmail'] = array(
                'display' => __l('Gmail') ,
                'conditions' => array(
                    'User.is_gmail_register' => 1,
                )
            );
            $model_datas['Yahoo'] = array(
                'display' => __l('Yahoo') ,
                'conditions' => array(
                    'User.is_yahoo_register' => 1,
                )
            );
            /*$model_datas['iPhone'] = array(
                'display' => __l('iPhone') ,
                'conditions' => array(
              //      'UserLogin.user_login_type_id' => ConstUserLoginType::IPhone,
                    //'User.is_iphone_register' => 1,
                )
            );
            $model_datas['Android'] = array(
                'display' => __l('Android') ,
                'conditions' => array(
            //        'UserLogin.user_login_type_id' => ConstUserLoginType::Android,
                    //'User.is_android_register' => 1,
                )
            );*/
            $model_datas['All'] = array(
                'display' => __l('All') ,
                'conditions' => array()
            );
            $common_conditions = array(
                'User.user_type_id' => $user_type_id
            );
            $_data = $this->_setLineData($select_var, $model_datas, 'UserLogin', 'UserLogin', $common_conditions);
            $this->set('chart_data', $_data);
            $this->set('chart_periods', $model_datas);
            $this->set('selectRanges', $this->selectRanges);
            // overall pie chart
            $select_var.= 'StartDate';
            $startDate = $this->$select_var;
            $endDate = date('Y-m-d H:i:s');
            $total_users = $this->UserLogin->find('count', array(
                'conditions' => array(
                    'User.user_type_id' => $user_type_id,
                    'UserLogin.created >=' => _formatDate('Y-m-d H:i:s', $startDate, true) ,
                    'UserLogin.created <=' => _formatDate('Y-m-d H:i:s', $endDate, true) ,
                ) ,
                'recursive' => 0
            ));
            //unset($model_datas['Normal']['conditions']['User.is_android_user']);
            //unset($model_datas['Normal']['conditions']['User.is_iphone_user']);
            unset($model_datas['All']);
//            unset($model_datas['iPhone']);
//            unset($model_datas['Android']);
//            unset($model_datas['OpenID']);
            $_pie_data = array();
            if (!empty($total_users)) {
                foreach($model_datas as $_period) {
                    $new_conditions = array();
                    $new_conditions = array_merge($_period['conditions'], array(
                        'UserLogin.created >=' => _formatDate('Y-m-d H:i:s', $startDate, true) ,
                        'UserLogin.created <=' => _formatDate('Y-m-d H:i:s', $endDate, true)
                    ));
                    $new_conditions['User.user_type_id'] = $user_type_id;
                    $sub_total = $this->UserLogin->find('count', array(
                        'conditions' => $new_conditions,
                        'recursive' => 0
                    ));
                    $_pie_data[$_period['display']] = number_format(($sub_total/$total_users) *100, 2);
                }
            } 
            $this->set('chart_pie_data', $_pie_data);
        }
    }
    protected function _setDealOrders($select_var)
    {
        $this->loadModel('Deal');
        $common_conditions = array();
        if ($this->Auth->user('user_type_id') == ConstUserTypes::Company) {
            $company = $this->Deal->Company->find('first', array(
                'conditions' => array(
                    'Company.user_id' => $this->Auth->user('id')
                ) ,
                'recursive' => -1
            ));
            $deal_id = $this->Deal->find('list', array(
                'conditions' => array(
                    'Deal.company_id' => $company['Company']['id']
                ) ,
                'fields' => array(
                    'Deal.id'
                ) ,
                'recursive' => -1
            ));
            $common_conditions['DealUser.deal_id'] = $deal_id;
        }
        $deal_order_model_datas['Order'] = array(
            'display' => __l('Orders') ,
            'conditions' => array() ,
        );
        $chart_deal_orders_data = $this->_setLineData($select_var, $deal_order_model_datas, array(
            'DealUser'
        ) , 'DealUser', $common_conditions);
        $this->set('chart_deal_orders_data', $chart_deal_orders_data);
    }
    protected function _setDemographics($total_users, $conditions = array())
    {
        $this->loadModel('User');
        $chart_pie_relationship_data = $chart_pie_education_data = $chart_pie_employment_data = $chart_pie_income_data = $chart_pie_gender_data = $chart_pie_age_data = array();
        if (!empty($total_users)) {
            $not_mentioned = array(
                '0' => __l('Not Mentioned')
            );
            //# education
            $user_educations = $this->User->UserProfile->UserEducation->find('list', array(
                'conditions' => array(
                    'UserEducation.is_active' => 1,
                ) ,
                'fields' => array(
                    'id',
                    'education',
                ) ,
                'recursive' => -1
            ));
            $user_educations = array_merge($not_mentioned, $user_educations);
            foreach($user_educations As $edu_key => $user_education) {
                $new_conditions = $conditions;
                if ($edu_key == 0) {
                    $new_conditions['UserProfile.user_education_id'] = NULL;
                } else {
                    $new_conditions['UserProfile.user_education_id'] = $edu_key;
                }
                $education_count = $this->User->UserProfile->find('count', array(
                    'conditions' => $new_conditions,
                    'recursive' => 0
                ));
                $chart_pie_education_data[$user_education] = number_format(($education_count/$total_users) *100, 2);
            }
            //# relationships
            $user_relationships = $this->User->UserProfile->UserRelationship->find('list', array(
                'conditions' => array(
                    'UserRelationship.is_active' => 1,
                ) ,
                'fields' => array(
                    'id',
                    'relationship',
                ) ,
                'recursive' => -1
            ));
            $user_relationships = array_merge($not_mentioned, $user_relationships);
            foreach($user_relationships As $rel_key => $user_relationship) {
                $new_conditions = $conditions;
                if ($rel_key == 0) {
                    $new_conditions['UserProfile.user_relationship_id'] = NULL;
                } else {
                    $new_conditions['UserProfile.user_relationship_id'] = $rel_key;
                }
                $relationship_count = $this->User->UserProfile->find('count', array(
                    'conditions' => $new_conditions,
                    'recursive' => 0
                ));
                $chart_pie_relationship_data[$user_relationship] = number_format(($relationship_count/$total_users) *100, 2);
            }
            //# employments
            $user_employments = $this->User->UserProfile->UserEmployment->find('list', array(
                'conditions' => array(
                    'UserEmployment.is_active' => 1,
                ) ,
                'fields' => array(
                    'id',
                    'employment',
                ) ,
                'recursive' => -1
            ));
            $user_employments = array_merge($not_mentioned, $user_employments);
            foreach($user_employments As $emp_key => $user_employment) {
                $new_conditions = $conditions;
                if ($emp_key == 0) {
                    $new_conditions['UserProfile.user_employment_id'] = NULL;
                } else {
                    $new_conditions['UserProfile.user_employment_id'] = $emp_key;
                }
                $employment_count = $this->User->UserProfile->find('count', array(
                    'conditions' => $new_conditions,
                    'recursive' => 0
                ));
                $chart_pie_employment_data[$user_employment] = number_format(($employment_count/$total_users) *100, 2);
            }
            //# income
            $user_income_ranges = $this->User->UserProfile->UserIncomeRange->find('list', array(
                'conditions' => array(
                    'UserIncomeRange.is_active' => 1,
                ) ,
                'fields' => array(
                    'id',
                    'income',
                ) ,
                'recursive' => -1
            ));
            $user_income_ranges = array_merge($not_mentioned, $user_income_ranges);
            foreach($user_income_ranges As $inc_key => $user_income_range) {
                $new_conditions = $conditions;
                if ($inc_key == 0) {
                    $new_conditions['UserProfile.user_income_range_id'] = NULL;
                } else {
                    $new_conditions['UserProfile.user_income_range_id'] = $inc_key;
                }
                $income_range_count = $this->User->UserProfile->find('count', array(
                    'conditions' => $new_conditions,
                    'recursive' => 0
                ));
                $chart_pie_income_data[$user_income_range] = number_format(($income_range_count/$total_users) *100, 2);
            }
            //# genders
            $genders = $this->User->UserProfile->Gender->find('list');
            $genders = array_merge($not_mentioned, $genders);
            foreach($genders As $gen_key => $gender) {
                $new_conditions = $conditions;
                if ($gen_key == 0) {
                    $new_conditions['UserProfile.gender_id'] = NULL;
                } else {
                    $new_conditions['UserProfile.gender_id'] = $gen_key;
                }
                $gender_count = $this->User->UserProfile->find('count', array(
                    'conditions' => $new_conditions,
                    'recursive' => 0
                ));
                $chart_pie_gender_data[$gender] = number_format(($gender_count/$total_users) *100, 2);
            }
            //# age calculation
            $user_ages = array(
                '1' => __l('18 - 34 Yrs') ,
                '2' => __l('35 - 44 Yrs') ,
                '3' => __l('45 - 54 Yrs') ,
                '4' => __l('55+ Yrs')
            );
            $user_ages = array_merge($not_mentioned, $user_ages);
            foreach($user_ages As $age_key => $user_ages) {
                $new_conditions = $conditions;
                if ($age_key == 1) {
                    $new_conditions['Year(Now()) - Year(UserProfile.dob) >= '] = 18;
                    $new_conditions['Year(Now()) - Year(UserProfile.dob) <= '] = 34;
                } elseif ($age_key == 2) {
                    $new_conditions['Year(Now()) - Year(UserProfile.dob) >= '] = 35;
                    $new_conditions['Year(Now()) - Year(UserProfile.dob) <= '] = 44;
                } elseif ($age_key == 3) {
                    $new_conditions['Year(Now()) - Year(UserProfile.dob) >= '] = 45;
                    $new_conditions['Year(Now()) - Year(UserProfile.dob) <= '] = 54;
                } elseif ($age_key == 4) {
                    $new_conditions['Year(Now()) - Year(UserProfile.dob) >= '] = 55;
                } elseif ($age_key == 0) {
                    $new_conditions['UserProfile.dob'] = NULL;
                }
                $age_count = $this->User->UserProfile->find('count', array(
                    'conditions' => $new_conditions,
                    'recursive' => 0
                ));
                $chart_pie_age_data[$user_ages] = number_format(($age_count/$total_users) *100, 2);
            }
        }
        $this->set('chart_pie_education_data', $chart_pie_education_data);
        $this->set('chart_pie_relationship_data', $chart_pie_relationship_data);
        $this->set('chart_pie_employment_data', $chart_pie_employment_data);
        $this->set('chart_pie_income_data', $chart_pie_income_data);
        $this->set('chart_pie_gender_data', $chart_pie_gender_data);
        $this->set('chart_pie_age_data', $chart_pie_age_data);
    }
    protected function _setLineData($select_var, $model_datas, $models, $model = '', $common_conditions = array())
    {
        if (is_array($models)) {
            foreach($models as $m) {
                $this->loadModel($m);
            }
        } else {
            $this->loadModel($models);
            $model = $models;
        }
        $_data = array();
        foreach($this->$select_var as $val) {
            foreach($model_datas as $model_data) {
                $new_conditions = array();
                foreach($val['conditions'] as $key => $v) {
                    $key = str_replace('#MODEL#', $model, $key);
                    $new_conditions[$key] = $v;
                }
                $new_conditions = array_merge($new_conditions, $model_data['conditions']);
                $new_conditions = array_merge($new_conditions, $common_conditions);
                if (isset($model_data['model'])) {
                    $modelClass = $model_data['model'];
                } else {
                    $modelClass = $model;
                }
                $_data[$val['display']][] = $this->{$modelClass}->find('count', array(
                    'conditions' => $new_conditions,
                    'recursive' => 0
                ));
            }
        }
        return $_data;
    }
    public function admin_chart_stats()
    {
    }
    public function admin_chart_business()
    {
        if (isset($this->request->params['named']['select_range_id'])) {
            $this->request->data['Chart']['select_range_id'] = $this->request->params['named']['select_range_id'];
        }
        if (isset($this->request->data['Chart']['select_range_id'])) {
            $select_var = $this->request->data['Chart']['select_range_id'];
        } else {
            $select_var = 'lastDays';
        }
		$this->request->data['Chart']['select_range_id'] = $select_var;
		if(isset($this->request->data['Chart']['is_ajax_load'])){
			$this->request->params['named']['is_ajax_load'] = $this->request->data['Chart']['is_ajax_load'];
		}
        if (isset($this->request->params['named']['is_ajax_load'])) {
//-----------------------------------------------------------------------------------------------------------------------
			$this->initChart();
			$this->loadModel('BusinessView');
			$this->loadModel('BusinessFollower');
			$this->loadModel('Business');
			$this->loadModel('BusinessUpdate');
			$conditions = array();
			$business_model_datas = array();
			$business_model_datas['Business'] = array(
				'display' => __l('Business') ,
				'model' => 'Business',
				'conditions' => array() ,
			);
			$business_model_datas['Business View'] = array(
				'display' => __l('Business View'),
				'model' => 'BusinessView',
				'conditions' => array() ,
			);
			$business_model_datas['Business Follower'] = array(
				'display' => __l('Business Follower'),
				'model' => 'BusinessFollower',
				'conditions' => array() ,
			);
			$business_model_datas['Business Update'] = array(
				'display' => __l('Business Update'),
				'model' => 'BusinessUpdate',
				'conditions' =>array() ,
			);
			$chart_business_data = array();
			foreach($this->$select_var as $val) {
				foreach($business_model_datas as $model_data) {
					$new_conditions = array();
					if (isset($model_data['model'])) {
						$modelClass = $model_data['model'];
					} else {
						$modelClass = 'Business';
					}
					foreach($val['conditions'] as $key => $v) {
						$key = str_replace('#MODEL#', $modelClass, $key);
						$new_conditions[$key] = $v;
					}
					$new_conditions = array_merge($new_conditions, $model_data['conditions']);
					$value_count = $this->{$modelClass}->find('count', array(
						'conditions' => $new_conditions,
						'recursive' => 0
					));
					$chart_guide_data[$val['display']][] = $value_count;
				}
			}

			//print_r($chart_transactions_data);
			$this->set('chart_business_periods', $business_model_datas);
	//------------------------------------------------------------------------------------------------------------------------
			$common_conditions = array();
			$business_view_model_datas['Business_views'] = array(
				'display' => __l('Business Views') ,
				'conditions' => array() ,
			);
			$chart_business_view_data = $this->_setLineData($select_var, $business_view_model_datas, array(
				'BusinessView'
			) , 'BusinessView', $common_conditions);

			$business_follower_model_datas['Business Follower'] = array(
				'display' => __l('Business Follower') ,
				'conditions' => array() ,
			);
			$chart_business_follower_data = $this->_setLineData($select_var, $business_follower_model_datas, array(
				'BusinessFollower'
			) , 'BusinessFollower', $common_conditions);

			$business_update_model_datas['Business Update'] = array(
				'display' => __l('Business Update') ,
				'conditions' => array() ,
			);
			$chart_business_update_line_data = $this->_setLineData($select_var, $business_update_model_datas, array(
				'BusinessUpdate'
			) , 'BusinessUpdate', $common_conditions);

			$business_model_datas['Business'] = array(
				'display' => __l('Business') ,
				'conditions' => array() ,
			);
			$chart_business_line_data = $this->_setLineData($select_var, $business_model_datas, array(
				'Business'
			) , 'Business', $common_conditions);

			$place_model_datas['Place Claim Request'] = array(
				'display' => __l('Place Claim Request') ,
				'conditions' => array() ,
			);
			$chart_place_line_data = $this->_setLineData($select_var, $place_model_datas, array(
				'PlaceClaimRequest'
			) , 'PlaceClaimRequest', $common_conditions);


			$this->set('chart_business_data', $chart_business_data);
			$this->set('chart_business_view_data', $chart_business_view_data);
			$this->set('chart_business_follower_data', $chart_business_follower_data);
			$this->set('chart_business_line_data', $chart_business_line_data);
			$this->set('chart_business_update_line_data', $chart_business_update_line_data);
			$this->set('chart_place_line_data', $chart_place_line_data);
		}
	    $this->set('selectRanges', $this->selectRanges);
    }
    public function admin_chart_sighting()
    {
        if (isset($this->request->params['named']['select_range_id'])) {
            $this->request->data['Chart']['select_range_id'] = $this->request->params['named']['select_range_id'];
        }
        if (isset($this->request->data['Chart']['select_range_id'])) {
            $select_var = $this->request->data['Chart']['select_range_id'];
        } else {
            $select_var = 'lastDays';
        }
		$this->request->data['Chart']['select_range_id'] = $select_var;
		if(isset($this->request->data['Chart']['is_ajax_load'])){
			$this->request->params['named']['is_ajax_load'] = $this->request->data['Chart']['is_ajax_load'];
		}
		if (isset($this->request->params['named']['is_ajax_load'])) {
//-----------------------------------------------------------------------------------------------------------------------
		$this->initChart();
        $this->loadModel('SightingView');
        $this->loadModel('SightingFlag');
        $this->loadModel('Sighting');
        $conditions = array();
        $sighting_model_datas = array();
        $sighting_model_datas['Sighting'] = array(
            'display' => __l('Sighting') ,
            'model' => 'Sighting',
            'conditions' => array() ,
        );
        $sighting_model_datas['Sighting View'] = array(
            'display' => __l('Sighting View'),
            'model' => 'SightingView',
            'conditions' => array() ,
        );
        $sighting_model_datas['Sighting Flag'] = array(
            'display' => __l('Sighting Flag'),
            'model' => 'SightingFlag',
            'conditions' => array() ,
        );
        $chart_sighting_data = array();
        foreach($this->$select_var as $val) {
            foreach($sighting_model_datas as $model_data) {
                $new_conditions = array();
                if (isset($model_data['model'])) {
                    $modelClass = $model_data['model'];
                } else {
                    $modelClass = 'Sighting';
                }
                foreach($val['conditions'] as $key => $v) {
                    $key = str_replace('#MODEL#', $modelClass, $key);
                    $new_conditions[$key] = $v;
                }
                $new_conditions = array_merge($new_conditions, $model_data['conditions']);
				$value_count = $this->{$modelClass}->find('count', array(
					'conditions' => $new_conditions,
					'recursive' => 0
				));
                $chart_sighting_data[$val['display']][] = $value_count;
            }
        }

		//print_r($chart_transactions_data);
		$this->set('chart_sighting_periods', $sighting_model_datas);
//------------------------------------------------------------------------------------------------------------------------
        $common_conditions = array();
        $sighting_view_model_datas['Sighting_views'] = array(
            'display' => __l('Sighting Views') ,
            'conditions' => array() ,
        );
        $chart_sighting_view_data = $this->_setLineData($select_var, $sighting_view_model_datas, array(
            'SightingView'
        ) , 'SightingView', $common_conditions);

        $sighting_flag_model_datas['Sighting_flag'] = array(
            'display' => __l('Sighting Flag') ,
            'conditions' => array() ,
        );
        $chart_sighting_flag_data = $this->_setLineData($select_var, $sighting_flag_model_datas, array(
            'SightingFlag'
        ) , 'SightingFlag', $common_conditions);

        $sighting_model_datas['Sighting'] = array(
            'display' => __l('Sightings') ,
            'conditions' => array() ,
        );
        $chart_sighting_line_data = $this->_setLineData($select_var, $sighting_model_datas, array(
            'Sighting'
        ) , 'Sighting', $common_conditions);

		$this->set('chart_sighting_data', $chart_sighting_data);
        $this->set('chart_sighting_view_data', $chart_sighting_view_data);
        $this->set('chart_sighting_flag_data', $chart_sighting_flag_data);
        $this->set('chart_sighting_line_data', $chart_sighting_line_data);
        }
        $this->set('selectRanges', $this->selectRanges);

    }
    public function admin_chart_review()
    {
        if (isset($this->request->params['named']['select_range_id'])) {
            $this->request->data['Chart']['select_range_id'] = $this->request->params['named']['select_range_id'];
        }
        if (isset($this->request->data['Chart']['select_range_id'])) {
            $select_var = $this->request->data['Chart']['select_range_id'];
        } else {
            $select_var = 'lastDays';
        }
		$this->request->data['Chart']['select_range_id'] = $select_var;
			//-----------------------------------------------------------------------------------------------------------------------
			$this->initChart();
			$this->loadModel('Review');
			$this->loadModel('ReviewView');
			$this->loadModel('ReviewComment');
			$conditions = array();
			$review_model_datas = array();
			$review_model_datas['Review'] = array(
				'display' => __l('Review') ,
				'model' => 'Review',
				'conditions' => array() ,
			);
			$review_model_datas['Review View'] = array(
				'display' => __l('Review View'),
				'model' => 'ReviewView',
				'conditions' => array() ,
			);
			$review_model_datas['Review Comment'] = array(
				'display' => __l('Review Comment'),
				'model' => 'ReviewComment',
				'conditions' => array() ,
			);
			$chart_review_data = array();
			foreach($this->$select_var as $val) {
				foreach($review_model_datas as $model_data) {
					$new_conditions = array();
					if (isset($model_data['model'])) {
						$modelClass = $model_data['model'];
					} else {
						$modelClass = 'Review';
					}
					foreach($val['conditions'] as $key => $v) {
						$key = str_replace('#MODEL#', $modelClass, $key);
						$new_conditions[$key] = $v;
					}
					$new_conditions = array_merge($new_conditions, $model_data['conditions']);
					$value_count = $this->{$modelClass}->find('count', array(
						'conditions' => $new_conditions,
						'recursive' => 0
					));
					$chart_review_data[$val['display']][] = $value_count;
				}
			}

			//print_r($chart_transactions_data);
			$this->set('chart_review_periods', $review_model_datas);
			//------------------------------------------------------------------------------------------------------------------------
			$common_conditions = array();

			$review_comment_model_datas['Review Comment'] = array(
				'display' => __l('Review Comment') ,
				'conditions' => array() ,
			);
			$chart_review_comment_data = $this->_setLineData($select_var, $review_comment_model_datas, array(
				'ReviewComment'
			) , 'ReviewComment', $common_conditions
            );
			$review_view_model_datas['Review View'] = array(
				'display' => __l('Review View') ,
				'conditions' => array() ,
			);
			$chart_review_view_data = $this->_setLineData($select_var, $review_view_model_datas, array(
				'ReviewView'
			) , 'ReviewView', $common_conditions);

			$review_model_datas['Review'] = array(
				'display' => __l('Review') ,
				'conditions' => array() ,
			);
			$chart_review_line_data = $this->_setLineData($select_var, $review_model_datas, array(
				'Review'
			) , 'Review', $common_conditions);
			$this->set('chart_review_data', $chart_review_data);
			$this->set('chart_review_view_data', $chart_review_view_data);
			$this->set('chart_review_line_data', $chart_review_line_data);
			$this->set('chart_review_comment_data', $chart_review_comment_data);
        $this->set('selectRanges', $this->selectRanges);

    }
    public function admin_chart_item()
    {
        if (isset($this->request->params['named']['select_range_id'])) {
            $this->request->data['Chart']['select_range_id'] = $this->request->params['named']['select_range_id'];
        }
        if (isset($this->request->data['Chart']['select_range_id'])) {
            $select_var = $this->request->data['Chart']['select_range_id'];
        } else {
            $select_var = 'lastDays';
        }
		$this->request->data['Chart']['select_range_id'] = $select_var;
		if(isset($this->request->data['Chart']['is_ajax_load'])){
			$this->request->params['named']['is_ajax_load'] = $this->request->data['Chart']['is_ajax_load'];
		}
		if (isset($this->request->params['named']['is_ajax_load'])) {
	//-----------------------------------------------------------------------------------------------------------------------
			$this->initChart();
			$this->loadModel('Item');
			$this->loadModel('ItemFollower');
			$conditions = array();
			$item_model_datas = array();
			$item_model_datas['Item'] = array(
				'display' => __l('Item') ,
				'model' => 'Item',
				'conditions' => array() ,
			);
			$item_model_datas['Item Follower'] = array(
				'display' => __l('Item Follower'),
				'model' => 'ItemFollower',
				'conditions' => array() ,
			);
			$chart_item_data = array();
			foreach($this->$select_var as $val) {
				foreach($item_model_datas as $model_data) {
					$new_conditions = array();
					if (isset($model_data['model'])) {
						$modelClass = $model_data['model'];
					} else {
						$modelClass = 'Item';
					}
					foreach($val['conditions'] as $key => $v) {
						$key = str_replace('#MODEL#', $modelClass, $key);
						$new_conditions[$key] = $v;
					}
					$new_conditions = array_merge($new_conditions, $model_data['conditions']);
					$value_count = $this->{$modelClass}->find('count', array(
						'conditions' => $new_conditions,
						'recursive' => 0
					));
					$chart_item_data[$val['display']][] = $value_count;
				}
			}

			//print_r($chart_transactions_data);
			$this->set('chart_item_periods', $item_model_datas);
	//------------------------------------------------------------------------------------------------------------------------
			$common_conditions = array();
			$item_view_model_datas['Item Follower'] = array(
				'display' => __l('Item Follower') ,
				'conditions' => array() ,
			);
			$chart_item_view_data = $this->_setLineData($select_var, $item_view_model_datas, array(
				'ItemFollower'
			) , 'ItemFollower', $common_conditions);

			$item_model_datas['Item'] = array(
				'display' => __l('Item') ,
				'conditions' => array() ,
			);
			$chart_item_line_data = $this->_setLineData($select_var, $item_model_datas, array(
				'Item'
			) , 'Item', $common_conditions);

			$this->set('chart_item_data', $chart_item_data);
			$this->set('chart_item_view_data', $chart_item_view_data);
			$this->set('chart_item_line_data', $chart_item_line_data);
		}
        $this->set('selectRanges', $this->selectRanges);

    }
    public function admin_chart_place()
    {
        if (isset($this->request->params['named']['select_range_id'])) {
            $this->request->data['Chart']['select_range_id'] = $this->request->params['named']['select_range_id'];
        }
        if (isset($this->request->data['Chart']['select_range_id'])) {
            $select_var = $this->request->data['Chart']['select_range_id'];
        } else {
            $select_var = 'lastDays';
        }
		$this->request->data['Chart']['select_range_id'] = $select_var;

		if(isset($this->request->data['Chart']['is_ajax_load'])){
			$this->request->params['named']['is_ajax_load'] = $this->request->data['Chart']['is_ajax_load'];
		}
		if (isset($this->request->params['named']['is_ajax_load'])) {
//-----------------------------------------------------------------------------------------------------------------------
			$this->initChart();
			$this->loadModel('PlaceView');
			$this->loadModel('PlaceFollower');
			$this->loadModel('Place');
			$conditions = array();
			$place_model_datas = array();
			$place_model_datas['Place'] = array(
				'display' => __l('Place') ,
				'model' => 'Place',
				'conditions' => array() ,
			);
			$place_model_datas['Place View'] = array(
				'display' => __l('Place View'),
				'model' => 'PlaceView',
				'conditions' => array() ,
			);
			$place_model_datas['Place Follower'] = array(
				'display' => __l('Place Follower'),
				'model' => 'PlaceFollower',
				'conditions' => array() ,
			);
			$chart_place_data = array();
			foreach($this->$select_var as $val) {
				foreach($place_model_datas as $model_data) {
					$new_conditions = array();
					if (isset($model_data['model'])) {
						$modelClass = $model_data['model'];
					} else {
						$modelClass = 'Place';
					}
					foreach($val['conditions'] as $key => $v) {
						$key = str_replace('#MODEL#', $modelClass, $key);
						$new_conditions[$key] = $v;
					}
					$new_conditions = array_merge($new_conditions, $model_data['conditions']);
					$value_count = $this->{$modelClass}->find('count', array(
						'conditions' => $new_conditions,
						'recursive' => 0
					));
					$chart_place_data[$val['display']][] = $value_count;
				}
			}

			//print_r($chart_transactions_data);
			$this->set('chart_place_periods', $place_model_datas);
	//------------------------------------------------------------------------------------------------------------------------
			$common_conditions = array();
			$place_view_model_datas['Place_views'] = array(
				'display' => __l('Place Views') ,
				'conditions' => array() ,
			);
			$chart_place_view_data = $this->_setLineData($select_var, $place_view_model_datas, array(
				'PlaceView'
			) , 'PlaceView', $common_conditions);

			$place_flag_model_datas['Place Follower'] = array(
				'display' => __l('Place Follower') ,
				'conditions' => array() ,
			);
			$chart_place_flag_data = $this->_setLineData($select_var, $place_flag_model_datas, array(
				'PlaceFollower'
			) , 'PlaceFollower', $common_conditions);

			$place_model_datas['Place'] = array(
				'display' => __l('Places') ,
				'conditions' => array() ,
			);
			$chart_place_line_data = $this->_setLineData($select_var, $place_model_datas, array(
				'Place'
			) , 'Place', $common_conditions);

			$this->set('chart_place_data', $chart_place_data);
			$this->set('chart_place_view_data', $chart_place_view_data);
			$this->set('chart_place_flag_data', $chart_place_flag_data);
			$this->set('chart_place_line_data', $chart_place_line_data);
		}
        $this->set('selectRanges', $this->selectRanges);

    }
    public function admin_chart_guide()
    {
        if (isset($this->request->params['named']['select_range_id'])) {
            $this->request->data['Chart']['select_range_id'] = $this->request->params['named']['select_range_id'];
        }
        if (isset($this->request->data['Chart']['select_range_id'])) {
            $select_var = $this->request->data['Chart']['select_range_id'];
        } else {
            $select_var = 'lastDays';
        }
		$this->request->data['Chart']['select_range_id'] = $select_var;
		if(isset($this->request->data['Chart']['is_ajax_load'])){
			$this->request->params['named']['is_ajax_load'] = $this->request->data['Chart']['is_ajax_load'];
		}
		if (isset($this->request->params['named']['is_ajax_load'])) {
//-----------------------------------------------------------------------------------------------------------------------
			$this->initChart();
			$this->loadModel('GuideView');
			$this->loadModel('GuideFollower');
			$this->loadModel('Guide');
			$conditions = array();
			$guide_model_datas = array();
			$guide_model_datas['Guide'] = array(
				'display' => __l('Guide') ,
				'model' => 'Guide',
				'conditions' => array() ,
			);
			$guide_model_datas['Guide View'] = array(
				'display' => __l('Guide View'),
				'model' => 'GuideView',
				'conditions' => array() ,
			);
			$guide_model_datas['Guide Follower'] = array(
				'display' => __l('Guide Follower'),
				'model' => 'GuideFollower',
				'conditions' => array() ,
			);
			$guide_model_datas['Guide Published'] = array(
				'display' => __l('Guide Published'),
				'model' => 'Guide',
				'conditions' => array('Guide.is_published' => 1) ,
			);
			$guide_model_datas['Guide Featured'] = array(
				'display' => __l('Guide Featured'),
				'model' => 'Guide',
				'conditions' => array('Guide.is_featured' => 1) ,
			);
			$chart_guide_data = array();
			foreach($this->$select_var as $val) {
				foreach($guide_model_datas as $model_data) {
					$new_conditions = array();
					if (isset($model_data['model'])) {
						$modelClass = $model_data['model'];
					} else {
						$modelClass = 'Guide';
					}
					foreach($val['conditions'] as $key => $v) {
						$key = str_replace('#MODEL#', $modelClass, $key);
						$new_conditions[$key] = $v;
					}
					$new_conditions = array_merge($new_conditions, $model_data['conditions']);
					$value_count = $this->{$modelClass}->find('count', array(
						'conditions' => $new_conditions,
						'recursive' => 0
					));
					$chart_guide_data[$val['display']][] = $value_count;
				}
			}

			//print_r($chart_transactions_data);
			$this->set('chart_guide_periods', $guide_model_datas);
	//------------------------------------------------------------------------------------------------------------------------
			$common_conditions = array();
			$guide_view_model_datas['Guide_views'] = array(
				'display' => __l('Guide Views') ,
				'conditions' => array() ,
			);
			$chart_guide_view_data = $this->_setLineData($select_var, $guide_view_model_datas, array(
				'GuideView'
			) , 'GuideView', $common_conditions);

			$guide_flag_model_datas['Guide Follower'] = array(
				'display' => __l('Guide Follower') ,
				'conditions' => array() ,
			);
			$chart_guide_flag_data = $this->_setLineData($select_var, $guide_flag_model_datas, array(
				'GuideFollower'
			) , 'GuideFollower', $common_conditions);

			$guide_publisher_model_datas['Guide Publisher'] = array(
				'display' => __l('Guide Publisher') ,
				'conditions' => array('Guide.is_published' => 1) ,
			);
			$chart_guide_publisher_line_data = $this->_setLineData($select_var, $guide_publisher_model_datas, array(
				'Guide'
			) , 'Guide', $common_conditions);

			$guide_featured_model_datas['Guide Featured'] = array(
				'display' => __l('Guide Featured') ,
				'conditions' => array('Guide.is_featured' => 1) ,
			);
			$chart_guide_featured_line_data = $this->_setLineData($select_var, $guide_featured_model_datas, array(
				'Guide'
			) , 'Guide', $common_conditions);


			$guide_model_datas['Guide'] = array(
				'display' => __l('Guides') ,
				'conditions' => array() ,
			);
			$chart_guide_line_data = $this->_setLineData($select_var, $guide_model_datas, array(
				'Guide'
			) , 'Guide', $common_conditions);

			$this->set('chart_guide_data', $chart_guide_data);
			$this->set('chart_guide_view_data', $chart_guide_view_data);
			$this->set('chart_guide_flag_data', $chart_guide_flag_data);
			$this->set('chart_guide_line_data', $chart_guide_line_data);
			$this->set('chart_guide_publisher_line_data', $chart_guide_publisher_line_data);
			$this->set('chart_guide_featured_line_data', $chart_guide_featured_line_data);
		}
        $this->set('selectRanges', $this->selectRanges);

    }
}
?>
