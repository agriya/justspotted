<?php
/**
 * XAjax - Extended Ajax
 *
 * @author      rajesh_04ag02 // 2008-12-01
 * Note: Original version from http://cakeforge.org/snippet/download.php?type=snippet&id=286 (AutocompleteComponent)
 *      But, heavily modified it to work with Router::parseExetensions() and make it automatic as much as possible
 */
class XAjaxComponent extends Component
{
    var $enabled = true;
    var $autocompleteLimit = 250;
    function startup($controller)
    {
        $this->Controller = $controller;
    }
	function autocomplete($param_encode = null, $param_hash = null, $conditions = false)
    {

        $controller = $this->Controller;
        if (is_null($param_encode) || is_null($param_hash)) {
           // $controller->cakeError('error404');
        }
        $exp_param_hash = substr(md5(Configure::read('Security.salt') . $param_encode) , 5, 7);
        if (strcmp($exp_param_hash, $param_hash) !== 0) {
          //  $controller->cakeError('error404');
        }	
        $params = unserialize(gzinflate(base64_url_decode($param_encode)));
		if(isset($params['accontrollers'])){
	        $this->autocomplete3(@$params['accontrollers'], @$params['acFieldKey'], @$params['acFields'], @$params['acSearchFieldNames'],$conditions);
		}
		else{
	        $this->autocomplete2(@$params['acFieldKey'], @$params['acFields'], @$params['acSearchFieldNames'], $conditions);
		}	
    }
    function autocomplete3($models, $acfieldKey = null, $acfieldNames = null, $acautocompleteSearchFieldNames = null, $condition_array = false)
    {
		$controller = $this->Controller;
		$controller->view = 'Json';
		$model_list = explode(':', $models);
		$conditions = false;
		foreach($model_list as $key => $value){
			$fieldKey = $fieldNames = $autocompleteSearchFieldNames = null;
			if(isset($acfieldKey[$value])){
				$fieldKey = $acfieldKey[$value];
			}
			if(isset($acfieldNames[$value])){
				$fieldNames = $acfieldNames[$value];
			}
			if(isset($acautocompleteSearchFieldNames[$value])){
				$autocompleteSearchFieldNames = $acautocompleteSearchFieldNames[$value];
			}
	        App::import('Model', $value);
        	$this->{$value} = new $value();		
			if(!empty($condition_array)) {
				foreach($condition_array as $k => $condition_value){
					if($k == $value) {
						$conditions = $condition_value;
					}	
				}
			}		
			$findOptions = array(
				'recursive' => - 1
			);
			if (is_null($fieldKey)) {
				$fieldKey = $value.'.'.'id';
			}
			if (is_null($fieldNames)) {
				$findOptions['fields'] = array(
					$fieldKey,
				   $value.'.'. $this->{$value}->displayField
				);
			} else {
				$findOptions['fields'] = $fieldNames;
			}
			if ($conditions) {
				$findOptions['conditions'] = $conditions;
			}
			$findOptions['limit'] = $this->autocompleteLimit;
			if (isset($controller->request->query['term'])) {
				if (is_null($autocompleteSearchFieldNames)) {
					$autocompleteSearchFieldNames = $value.'.'. $this->{$value}->displayField;
				} else { // array
					//@todo handle array
					$autocompleteSearchFieldNames = $autocompleteSearchFieldNames;
				}
				$findOptions['conditions'][$autocompleteSearchFieldNames . ' LIKE '] = '%' . $controller->request->query['term'] . '%';
			}
			if($value != 'Place'){
				$data[$value] = $this->{$value}->find('list', $findOptions);
			}
			else{
				$data[$value] = $this->{$value}->find('all', $findOptions);
			}
				
		}
		$controller->set('json', $data);
		
    }
	
	
	
    //@todo the search fields array to be handled for proper condition formation
    function autocomplete2($fieldKey = null, $fieldNames = null, $autocompleteSearchFieldNames = null, $conditions = false)
    {
        $controller = $this->Controller;
        $modelClass = Inflector::singularize($controller->name);
        if (!$this->enabled || !$controller->RequestHandler->isAjax() || !$controller->RequestHandler->prefers('json')) {
            //            $controller->cakeError('error404');
        }
        $controller->view = 'Json';
        $findOptions = array(
            'recursive' => - 1
        );
        if (is_null($fieldKey)) {
            $fieldKey = 'id';
        }
        if (is_null($fieldNames)) {
            $findOptions['fields'] = array(
                $fieldKey,
                $controller->{$modelClass}->displayField
            );
        } else {
            $findOptions['fields'] = $fieldNames;
        }
        if ($conditions) {
            $findOptions['conditions'] = $conditions;
        }
        $findOptions['limit'] = $this->autocompleteLimit;
        if (isset($controller->request->query['term'])) {
            if (is_null($autocompleteSearchFieldNames)) {
                $autocompleteSearchFieldNames = $controller->{$modelClass}->displayField;
            } else { // array
                //@todo handle array
                $autocompleteSearchFieldNames = $autocompleteSearchFieldNames[0];
            }
            $findOptions['conditions'][$autocompleteSearchFieldNames . ' LIKE '] = '%' . $controller->request->query['term'] . '%';
        }
        $data = $controller->{$modelClass}->find('list', $findOptions);
        $controller->set('json', $data);
    }
    function flashuploadset($data)
    {
        Configure::write('debug', 0);
        $controller = $this->Controller;
        $_SESSION['flashupload_data'][$controller->name] = $data;
        echo 'flashupload';
        exit;
    }
    function flashupload($multiple = false)
    {
        $controller = $this->Controller;
        $modelClass = Inflector::singularize($controller->name);
        if (isset($_FILES['Filedata']['name']) and !empty($_SESSION['flashupload_data'][$controller->name])) {
            $_FILES['Filedata']['type'] = get_mime($_FILES['Filedata']['tmp_name']);
            $this->data = $_SESSION['flashupload_data'][$controller->name];
            if ($multiple) {
                // update the title field with the file name
                $t_filename = $_FILES['Filedata']['name'];
                $this->data[$modelClass]['title'] = Inflector::humanize(str_replace(array(
                    '_',
                    '-'
                ) , ' ', basename($t_filename, substr($t_filename, strrpos($t_filename, '.')))));
                $controller->{$modelClass}->create();
                if ($controller->{$modelClass}->save($this->data, false)) {
                    $attachments = array();
                    $attachments['Attachment']['filename'] = $_FILES['Filedata'];
                    $attachments['Attachment']['class'] = $modelClass;
                    $attachments['Attachment']['foreign_id'] = $controller->{$modelClass}->getLastInsertId();
                    $controller->{$modelClass}->Attachment->create();
                    $controller->{$modelClass}->Attachment->save($attachments);
                    // save in session to retrieve the last inserted id in controller
                    $_SESSION['flash_uploaded']['data'][] = $controller->{$modelClass}->getLastInsertId();
                }
            } else {
                $attachments = array();
                $attachments['Attachment']['filename'] = $_FILES['Filedata'];
                $attachments['Attachment']['class'] = $modelClass;
                $attachments['Attachment']['foreign_id'] = $this->data['Attachment']['foreign_id'];
                $controller->{$modelClass}->Attachment->create();
                $controller->{$modelClass}->Attachment->save($attachments);
            }
            echo ' '; // Prevent bug in Mac OS 8 flash player
            session_write_close(); // Write session variables!
            exit();
        }
    }
    function normalupload($data, $multiple = false)
    {
        $controller = $this->Controller;
        $modelClass = Inflector::singularize($controller->name);
        if ($multiple) {
            foreach($data['Attachment'] as $attachment) {
                $controller->{$modelClass}->Attachment->Behaviors->attach('ImageUpload');
                if (!empty($attachment['filename']['name'])) {
                    // update the title field with the file name
                    $t_filename = $attachment['filename']['name'];
                    $data[$modelClass]['title'] = Inflector::humanize(str_replace(array(
                        '_',
                        '-'
                    ) , ' ', basename($t_filename, substr($t_filename, strrpos($t_filename, '.')))));
                }
                $controller->{$modelClass}->create();
                if (!empty($attachment['filename']['name']) && $controller->{$modelClass}->save($data, false)) {
                    $attachments = array();
                    $attachments['Attachment']['filename'] = $attachment['filename'];
                    $attachments['Attachment']['class'] = $modelClass;
                    $attachments['Attachment']['foreign_id'] = $controller->{$modelClass}->getLastInsertId();
                    $controller->{$modelClass}->Attachment->create();
                    $controller->{$modelClass}->Attachment->save($attachments);
                    // save in session to retrieve the last inserted id in controller
                    $_SESSION['flash_uploaded']['data'][] = $controller->{$modelClass}->getLastInsertId();
                }
                $controller->{$modelClass}->Attachment->Behaviors->detach('ImageUpload');
            }
        } else {
            foreach($data['Attachment'] as $attachment) {
                $attachments = array();
                if (!empty($attachment['filename']['name'])) {
                    $attachments['Attachment']['filename'] = $attachment['filename'];
                    $attachments['Attachment']['class'] = $modelClass;
                    $attachments['Attachment']['foreign_id'] = $data['foreign_id'];
                    $controller->{$modelClass}->Attachment->create();
                    $controller->Attachment->save($attachments);
                }
            }
        }
    }
}
?>
