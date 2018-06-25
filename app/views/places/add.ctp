<?php /* SVN: $Id: $ */ ?>
<div class="places form location-add-block js-responses js-response">
<?php if ($this->request->params['prefix'] != 'admin') { ?>
	<h2><?php echo $this->pageTitle; ?></h2>
	<?php 
}
if (empty($this->request->params['isAjax'])) { 
$class = '';
} else {
$class = ' js-ajax-place-add-form';
}
?>
<?php echo $this->Form->create('Place', array('class' => 'normal'.$class));
echo $this->Form->input('type',array('type' => 'hidden'));
?>
	<fieldset>
		<?php if(!$this->Auth->user('user_type_id') == ConstUserTypes::Admin): ?>
			<h2><?php echo __l('Add Place');?></h2>
		<?php endif; ?>
		<div>
	  <div class="clearfix">
		<?php echo $this->Form->input('place_type_id',array('empty' =>  __l('Please Select'))); ?>
       </div>
      <div class="clearfix">
     	<?php echo $this->Form->input('name',array('id'=>'placename_add'));	?>
     </div>
        	<div class="padd-center clearfix">
                 <div class="mapblock-info1">
                    <div class="clearfix">
                    <?php
                        echo $this->Form->input('address2', array('label' => __l('Address'), 'id' => 'PropertyAddressSearch','info'=>'Address suggestion will be listed when you enter location.<br/>
                        (Note: If address entered is not exact/incomplete, you will be prompted to fill the missing address fields.)'));
                    ?>
                    </div>
                    <div id="js-geo-fail-address-fill-block">
                    <div class="clearfix">
                    <div class="map-address-left-block clearfix">
                        <div class="grid_left colorbox-left-block">
                        <?php
                            echo $this->Form->input('latitude', array('id' => 'latitude', 'type' => 'hidden'));
                            echo $this->Form->input('longitude', array('id' => 'longitude', 'type' => 'hidden'));
    						echo $this->Form->input('address1',array('id'=>'js-street_id','type' => 'text', 'label' => 'Address'));
                            echo $this->Form->input('City.name', array('type' => 'text', 'label' => 'City'));
                            echo $this->Form->input('State.name', array('type' => 'text', 'label' => 'State'));
                            echo $this->Form->input('country_id',array('id'=>'js-country_id', 'empty' => __l('Please Select')));
                        ?>
                        </div>
                      <div class="map-info-block colorbox-right-block colorbox-map-info-block grid_left">
                        	<h3><?php echo __l('Point Your Location');?></h3>
    						<div class="js-side-map">
    							<div id="js-map-container" class="map-container"></div>
    							<span ><?php echo __l('Point the exact location in map by dragging marker');?></span>
    						</div>
						</div>
					 </div>
                    </div>
					</div>
                    <div id="mapblock">
                        <div id="mapframe">
                            <div id="mapwindow"></div>
                        </div>
                    </div>
                    </div>
					<?php
                        echo $this->Form->input('zip_code',array('label' => __l('Zip'), 'id' => 'PropertyPostalCode'));					
                    ?>

            </div>   
				<?php
                $map_zoom_level = 9;//Configure::read('GoogleMap.static_map_zoom_level');
                echo $this->Form->input('zoom_level',array('type' => 'hidden','id'=>'zoomlevel', 'value' => $map_zoom_level));
                ?>
		</div>
	</fieldset>
	<div class="submit-block clearfix">
<?php echo $this->Form->Submit(__l('Add'));?>
</div>
<?php echo $this->Form->end();?>
</div>