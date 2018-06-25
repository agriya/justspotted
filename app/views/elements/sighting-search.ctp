<div class="search-map-block-outer">
 <?php echo $this->Form->create('Sighting', array('type' => 'get', 'class' => 'map-search clearfix clearfix js-search-map', 'action'=>'index', 'id' => 'sightingsearch')); ?>
<div class=" js-search-map-block-outer">
<div class="map-search-block container_24">
<div class="location-block">
 <div class="location">
 <?php  $value = "Anywhere";
 		if(!empty($geo_lat) && !empty($geo_lan) && (empty($this->request->params['named']['location']) || (!empty($this->request->params['named']['location']) && $this->request->params['named']['location'] != 'Anywhere')))
		{
			$value = "Map Area";
			$lat = $geo_lat;
			$lng = $geo_lan;
			$zoom = 8;
		} else {
			$lat = $lng = 0;
			$zoom = 2;
		}
		if(!empty($this->request->params['named']['latitude'])){
			$lat = $this->request->params['named']['latitude'];
		}
		if(!empty($this->request->params['named']['longitude'])){
			$lng = $this->request->params['named']['longitude'];
		}
		if(!empty($this->request->params['named']['zoom'])){
			$zoom = 8;
		}
		if(!empty($this->request->params['named']['sw_latitude'])){
			$sw_lat = $this->request->params['named']['sw_latitude'];
		}
		if(!empty($this->request->params['named']['sw_longitude'])){
			$sw_lng = $this->request->params['named']['sw_longitude'];
		}
		if(!empty($this->request->params['named']['ne_latitude'])){
			$ne_lat = $this->request->params['named']['ne_latitude'];
		}
		if(!empty($this->request->params['named']['ne_longitude'])){
			$ne_lng = $this->request->params['named']['ne_longitude'];
		}
		if(!empty($this->request->params['named']['cen_latitude'])){
			$cen_lat = $lat = $this->request->params['named']['cen_latitude'];
		}
		if(!empty($this->request->params['named']['cen_longitude'])){
			$cen_lng = $lng = $this->request->params['named']['cen_longitude'];
		}
 		if(!empty($this->request->params['named']['location'])){
			 $value = $this->request->params['named']['location'];
		}
		if(!empty($this->request->params['named']['zoom_level'])){
			$zoom = $this->request->params['named']['zoom_level'];
		}
 ?>
 <?php echo $this->Form->input('location', array('label' => __l('Search Location:'), 'id' => 'address', 'AUTOCOMPLETE' => 'off', 'value' =>$value));?>
</div>
     	<ul class="hide location-search-value js-location-search-value">
			<li class="js-location-search {'meta_value':'Current Location'}">
			<span class="current-location">
            <?php echo __l('Current Location'); ?>
            </span>
            </li>
            <li class="js-location-search {'meta_value':'Anywhere'}">
            <span class="anywhere">
            <?php echo __l('Anywhere'); ?>
            </span>
            </li>
		</ul>
	</div>

 <div class="mapblock-info searchby js-overlabel">	
<?php        
		echo $this->Form->autocomplete('q', array('label' => __l('Search by') . ': <span class="map-q">' . __l('food, place or person') . '</span>', 'id' => 'Qsearch',   'acFieldKey' => array('Item' => 'Item.id', 'Place' => 'Place.id', 'User' => 'User.id'), 'acFields' => array('Item' => 'Item.name', 'Place' => array('Place.id', 'Place.name'), 'User' => 'User.username'), 'acSearchFieldNames' => array('Item' => 'Item.name', 'Place' => 'Place.name',   'User' => 'User.username'), 'maxlength' => '255', 'acMultiple' => 'js-multi-autocomplete', 'accontrollers' => 'Item:Place:User'));		
?>
		<div class="autocompleteblock">            
        </div>
</div> 

 <?php  echo $this->Form->submit(__l('search'), array('id' =>'js-sighting-search-submit'));?>


  <div class="search-map-block">
	 <?php
	?>
	 <div class="extra-search-block clearfix">
	
	 <div class=" clearfix">
		<?php
			echo $this->Form->input('item', array('type' => 'hidden', 'id' =>'sighting_item'));
			echo $this->Form->input('page', array('type' => 'hidden', 'id' =>'sighting_page'));
		
			echo $this->Form->input('sw_latitude', array('type' => 'hidden', 'id' =>'sw_latitude', 'value' => $sw_lat));
			echo $this->Form->input('sw_longitude', array('type' => 'hidden', 'id' =>'sw_longitude', 'value' => $sw_lng));
			echo $this->Form->input('ne_latitude', array('type' => 'hidden', 'id' =>'ne_latitude', 'value' => $ne_lat));
			echo $this->Form->input('ne_longitude', array('type' => 'hidden', 'id' =>'ne_longitude', 'value' => $ne_lng));
			
			echo $this->Form->input('latitude', array('type' => 'hidden', 'id' =>'sighting_latitude', 'value' => $lat));
			echo $this->Form->input('longitude', array('type' => 'hidden', 'id' =>'sighting_longitude', 'value' => $lng));
			echo $this->Form->input('zoom_level', array('type' => 'hidden', 'id' =>'sighting_zoom_level', 'value' => 8));
			echo $this->Form->input('sighting_search', array('type' => 'hidden', 'value' =>'1'));
			echo $this->Form->input('r', array('type' => 'hidden', 'value' =>$this->params['controller']));
		  ?>
	 </div>
	</div>
</div>

</div>

 </div>
<?php  echo $this->Form->end();  ?>
<div class="map-inner-block">
 <div class="map-block" id ="js-map-search-container" style="height:412px;"></div>
 </div>
 <?php echo $this->element('search_menu');?>
</div>
<script type="text/javascript">
function loadScript(){
	var script = document.createElement('script');
	var google_map_key = 'http://maps.googleapis.com/maps/api/js?key='+ <?php echo "'" .Configure::read('google.gmap_app_id') ."'"; ?> +'&sensor=false&callback=loadMap';
	//var google_map_key = 'http://maps.google.com/maps/api/js?sensor=false&callback=loadMap';
	script.setAttribute('src', google_map_key);
	script.setAttribute('type', 'text/javascript');
	document.documentElement.firstChild.appendChild(script);		
}
    window.onload = function() {
        loadScript();
    };
</script>
