<!-- place edit starts-->
 <?php /* SVN: $Id: $ */ ?>
<div class="places form">
<div class="side1 grid_16 alpha omega">
<?php echo $this->Form->create('Place', array('class' => 'normal'));?>
	<fieldset>
		<?php if(!$this->Auth->user('user_type_id') == ConstUserTypes::Admin): ?>
			<legend> <?php echo __l('Edit Place');?></legend>
		<?php endif; ?>
		<div>
		<?php
			echo $this->Form->input('id');			
			echo $this->Form->input('place_type_id');
			echo $this->Form->input('name',array('id'=>'placename_add', 'value' => $this->data['Place']['name']));
		?>		
                  	<div class="padd-center clearfix">
            
                 <div class="mapblock-info1">
                    <div class="clearfix">
                    <?php
                        echo $this->Form->input('address2', array('label' => __l('Address'), 'id' => 'PropertyAddressSearch','info'=>'Address suggestion will be listed when you enter location.<br/>
(Note: If address entered is not exact/incomplete, you will be prompted to fill the missing address fields.)'));
                    ?>
                    </div>
                    <?php 
						$class = '';
						if(empty($this->request->data['Place']['address2']) || ( !empty($this->request->data['Place']['address1']) && !empty($this->request->data['City']['name']) &&  !empty($this->request->data['Place']['country_id']))){
							$class = 'hide';
						}
					?>
                    <div id="js-geo-fail-address-fill-block" class="<?php echo $class;?>">
                    <div class="clearfix">
                    <div class="clearfix">
                        <?php
                            echo $this->Form->input('latitude', array('id' => 'latitude', 'type' => 'hidden'));
                            echo $this->Form->input('longitude', array('id' => 'longitude', 'type' => 'hidden'));
    						echo $this->Form->input('address1',array('id'=>'js-street_id','type' => 'text', 'label' => 'Address'));
                            echo $this->Form->input('City.name', array('type' => 'text', 'label' => 'City'));
                            echo $this->Form->input('State.name', array('type' => 'text', 'label' => 'State'));
                            echo $this->Form->input('country_id',array('id'=>'js-country_id', 'empty' => __l('Please Select')));
                        ?>
                        <div class="map-info-block">
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
                //echo $this->Form->input('zip_code',array('label' => __l('Zip Code'), 'id' => 'PropertyPostalCode'));
                $map_zoom_level = 9;//Configure::read('GoogleMap.static_map_zoom_level');
                echo $this->Form->input('zoom_level',array('type' => 'hidden','id'=>'zoomlevel', 'value' => $map_zoom_level));
                ?>
		</div>
	</fieldset>
	<div class="submit-block clearfix">
<?php echo $this->Form->Submit(__l('Update'));?>
</div>
<?php echo $this->Form->end();?>
</div>
<?php if($this->Auth->user('user_type_id') != ConstUserTypes::Admin) { ?>
<div class="side2 grid_8 alpha omega">
<h3><?php echo __l('Other Places'); ?></h3>
<?php echo $this->element('places-index', array('type' => 'own', 'from' => $this->request->data['Place']['id'],'config' => 'site_element_cache_2_min')); ?>
</div>
<?php } ?>
</div>
<!-- place edit ends-->
