<?php 
 		$arrow = "down-arrow";
 		if(isset($this->request->params['named']['is_ajax_load'])){ 
 		$arrow = "up-arrow";
	   }
?>
<div class="js-cache-load-admin-charts {'data_url':'admin/charts/chart_place', 'data_load':'js-cache-load-admin-charts-places'}">
<div class="clearfix js-responses js-cache-load-admin-charts-places">
  <div class="admin-side1-tl ">
		<div class="admin-side1-tr">
		  <div class="admin-side1-tc page-title-info">
			<h2 class="chart-dashboard-title"><?php echo __l('Places'); ?>
			<span class="js-chart-showhide <?php echo $arrow; ?> {'chart_block':'admin-place-overview', 'dataloading':'div.js-cache-load-admin-charts-places',  'dataurl':'admin/charts/chart_place/is_ajax_load:1'}"><?php echo $arrow; ?></span></h2>
		  </div>
		</div>
	</div>
	<?php if(isset($this->request->params['named']['is_ajax_load'])){ ?>   
	<div class="admin-center-block dashboard-center-block clearfix" id="admin-place-overview">
    <div class="clearfix">
	 <?php echo $this->Form->create('Chart' , array('class' => "language-form {'chart_block':'admin-place-overview', 'dataloading':'div.js-cache-load-admin-charts-places'}", 'action' => 'chart_place')); ?>
		<?php
		echo $this->Form->input('is_ajax_load', array('type' => 'hidden', 'value' => 1));
		echo $this->Form->input('select_range_id', array('class' => 'js-chart-autosubmit', 'label' => __l('Select Range'))); ?>
		<div class="hide"> <?php echo $this->Form->submit('Submit');  ?> </div>
	<?php echo $this->Form->end(); ?>
	</div>
    
	<div class="js-load-column-chart chart-half-section {'data_container':'total_place_column_data', 'chart_container':'total_place_column_chart12', 'chart_title':'<?php echo __l('Places') ;?>', 'chart_y_title': '<?php echo __l('Place');?>'}">
     <div class="dashboard-tl">
     <div class="dashboard-tr">
         <div class="dashboard-tc">
             </div>
         </div>
     </div>
     <div class="dashboard-cl">
         <div class="dashboard-cr">
         <div class="dashboard-cc clearfix">
        <div id="total_place_column_chart12" class="admin-dashboard-chart"></div>
		<div class="hide">
			<table id="total_place_column_data" class="list">
			<tbody>
				<?php foreach($chart_place_line_data as $key => $_data): ?>
				<tr>
				   <th><?php echo $key; ?></th>
				   <td><?php echo $_data[0]; ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
			</table>
		</div>
	   </div>
		</div>
		</div>
        <div class="dashboard-bl">
             <div class="dashboard-br">
                 <div class="dashboard-bc">
                 </div>
             </div>
         </div>
	</div>
    <div class="js-load-column-chart chart-half-section {'data_container':'total_place_views_column_data', 'chart_container':'total_place_views_column_chart', 'chart_title':'<?php echo __l('Views') ;?>', 'chart_y_title': '<?php echo __l('Views');?>'}">
     <div class="dashboard-tl">
     <div class="dashboard-tr">
         <div class="dashboard-tc">
             </div>
         </div>
     </div>
     <div class="dashboard-cl">
         <div class="dashboard-cr">
         <div class="dashboard-cc clearfix">
        <div id="total_place_views_column_chart" class="admin-dashboard-chart"></div>
		<div class="hide">
			<table id="total_place_views_column_data" class="list">
			<tbody>
				<?php foreach($chart_place_view_data as $key => $_data): ?>
				<tr>
				   <th><?php echo $key; ?></th>
				   <td><?php echo $_data[0]; ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
			</table>
		</div>
	   </div>
		</div>
		</div>
        <div class="dashboard-bl">
             <div class="dashboard-br">
                 <div class="dashboard-bc">
                 </div>
             </div>
         </div>
	</div>
    <div class="js-load-column-chart chart-half-section {'data_container':'total_place_flag_column_data', 'chart_container':'total_place_flag_column_chart', 'chart_title':'<?php echo __l('Followers ') ;?>', 'chart_y_title': '<?php echo __l('Followers ');?>'}">
     <div class="dashboard-tl">
     <div class="dashboard-tr">
         <div class="dashboard-tc">
             </div>
         </div>
     </div>
     <div class="dashboard-cl">
         <div class="dashboard-cr">
         <div class="dashboard-cc clearfix">
        <div id="total_place_flag_column_chart" class="admin-dashboard-chart"></div>
		<div class="hide">
			<table id="total_place_flag_column_data" class="list">
			<tbody>
				<?php foreach($chart_place_flag_data as $key => $_data): ?>
				<tr>
				   <th><?php echo $key; ?></th>
				   <td><?php echo $_data[0]; ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
			</table>
		</div>
	   </div>
		</div>
		</div>
        <div class="dashboard-bl">
             <div class="dashboard-br">
                 <div class="dashboard-bc">
                 </div>
             </div>
         </div>
	</div>
	
  </div>
  <?php } ?>
</div>
</div>