<div class="js-cache-load js-cache-load-admin-charts {'data_url':'admin/charts/chart_review', 'data_load':'js-cache-load-admin-charts-reviews'}">
<div class="clearfix js-responses js-cache-load-admin-charts-reviews">
  <div class="admin-side1-tl ">
		<div class="admin-side1-tr">
		  <div class="admin-side1-tc page-title-info">
			<h2 class="chart-dashboard-title"><?php echo __l('Reviews'); ?>
			<span class="js-chart-showhide up-arrow {'chart_block':'admin-review-overview'}">arrow</span></h2>
		  </div>
		</div>
	</div>
	<div class="admin-center-block dashboard-center-block clearfix" id="admin-review-overview">
    <div class="clearfix">
	 <?php echo $this->Form->create('Chart' , array('class' => "language-form", 'action' => 'chart_review')); ?>
		<?php
		echo $this->Form->input('select_range_id', array('class' => 'js-chart-autosubmit', 'label' => __l('Select Range'))); ?>
		<div class="hide"> <?php echo $this->Form->submit('Submit');  ?> </div>
	<?php echo $this->Form->end(); ?>
	</div>
	<div class="js-load-column-chart chart-half-section {'data_container':'total_review_column_data', 'chart_container':'total_review_column_chart', 'chart_title':'<?php echo __l('Reviews') ;?>', 'chart_y_title': '<?php echo __l('Reviews');?>'}">
     <div class="dashboard-tl">
     <div class="dashboard-tr">
         <div class="dashboard-tc">
             </div>
         </div>
     </div>
     <div class="dashboard-cl">
         <div class="dashboard-cr">
         <div class="dashboard-cc clearfix">
        <div id="total_review_column_chart" class="admin-dashboard-chart"></div>
		<div class="hide">
			<table id="total_review_column_data" class="list">
			<tbody>
				<?php foreach($chart_review_line_data as $key => $_data): ?>
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
    <div class="js-load-column-chart chart-half-section {'data_container':'total_review_views_column_data', 'chart_container':'total_review_views_column_chart', 'chart_title':'<?php echo __l('Views') ;?>', 'chart_y_title': '<?php echo __l('Views');?>'}">
     <div class="dashboard-tl">
     <div class="dashboard-tr">
         <div class="dashboard-tc">
             </div>
         </div>
     </div>
     <div class="dashboard-cl">
         <div class="dashboard-cr">
         <div class="dashboard-cc clearfix">
        <div id="total_review_views_column_chart" class="admin-dashboard-chart"></div>
		<div class="hide">
			<table id="total_review_views_column_data" class="list">
			<tbody>
				<?php foreach($chart_review_view_data as $key => $_data): ?>
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
	    <div class="js-load-column-chart chart-half-section {'data_container':'total_review_comment_column_data', 'chart_container':'total_review_comment_column_chart', 'chart_title':'<?php echo __l('Comments') ;?>', 'chart_y_title': '<?php echo __l('Comments');?>'}">
     <div class="dashboard-tl">
     <div class="dashboard-tr">
         <div class="dashboard-tc">
             </div>
         </div>
     </div>
     <div class="dashboard-cl">
         <div class="dashboard-cr">
         <div class="dashboard-cc clearfix">
        <div id="total_review_comment_column_chart" class="admin-dashboard-chart"></div>
		<div class="hide">
			<table id="total_review_comment_column_data" class="list">
			<tbody>
				<?php foreach($chart_review_comment_data as $key => $_data): ?>
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
</div>
</div>