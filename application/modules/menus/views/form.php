 <link href="<?=base_url("assets/css/chosen.css")?>" rel="stylesheet">
 <link href="<?=base_url("assets/css/fontawesome-iconpicker.min.css")?>" rel="stylesheet">
 <script src="<?=base_url("assets/js/fontawesome-iconpicker.min.js")?>"></script>
 <script src="<?=base_url("assets/js/chosen.jquery.js")?>"></script>

<script type="text/javascript">
	var tanggal = '<?=$tanggal?>';
</script>
<style type="text/css">
.blue-loader .ajax_loader {background: url("<?=base_url('assets/images/ajax-loader_blue.gif')?>") no-repeat center center transparent; width:100%;height:100%;}

			
</style>
<script type="text/javascript" src="<?=base_url('assets/js/ajax_spinner.js')?>"></script>
<script src="<?=base_url("assets/js/jquery.bsAlerts.min.js")?>"></script>

<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
			<h1 class="box-title"><small><i class="fa fa-th-list"></i> Data Menu</small></h1>
			<ol class="breadcrumb">
				<li><a href="<?=base_url()?>"><i class="fa fa-home"></i> Home</a></li>
				<li >Data Menu</li><li class="active">Baru</li>
			</ol>
        </section>
		<!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box box-success">
                <div class="box-header">
                  <i class="fa fa-th-list"></i>
                  <h3 class="box-title">Data Menu</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
					<div data-alerts="alerts" data-fade="5000"></div>
					<div id="form_menus" class="form-horizontal">
						<div class="form-group"><label class="col-sm-3 control-label">Title</label>
							<div class="col-sm-6"><input type="text" id="title" name="title" class="form-control input-sm" value="<?=@$data_menu->title?>"></div>
						</div>
						<div class="form-group"><label class="col-sm-3 control-label">Parent</label>
							<div class="col-sm-6">
								<select class="chosen-select" id="parent_id" name="parent_id" data-placeholder="Select Parent" style="width:250px;">
									<option value=0>TOP LEVEL</option>
									<?php 
										foreach($parent as $key=>$val){
									?>
										<option value="<?=$val['id'] ?>" ><?=$val["title"]?></option>
									<?php } ?>
								</select>								
							</div>
						</div>
						<div class="form-group"><label class="col-sm-3 control-label">URL</label>

							<div class="col-sm-6">
								<input type="text" id="url" name="url" class="form-control input-sm" value="<?=@$data_menu->url?>" />
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Icon</label>
							<div class="input-group col-sm-3" style="padding-left:15px">
								<input data-placement="bottomRight" name="icon" id="icon" class="form-control icp icp-auto" type="text" value="<?=@$data_menu->icon?>">
								<span class="input-group-addon"></span>
                            </div>
						</div>
						<div class="form-group"><label class="col-sm-3 control-label">Description</label>
							<div class="col-sm-8"><input type="text" id="description" name="description" class="form-control input-sm" value="<?=@$data_menu->description?>"></div>
						</div>
						
						<div class="hr-line-dashed"></div>
						<div class="form-group">
							<div class="col-sm-4 col-sm-offset-3">
								<a class="btn btn-danger btn-sm" href="<?=base_url('manager/menus')?>">
									<i class="fa fa-remove"></i>
										Batal</a>
								<a href="#" class="btn btn-primary btn-sm" onClick="saveData()">
									<i class="fa fa-save"></i>  	Simpan
								</a>
							</div>
						</div>                                
					</div>							
				</div>
			</div>
		</div>		
	</div>
</div>
<script>

	function saveData()
	{
		
		var arr_field = $(":input","#form_menus").not(":button");
		var obj_data = {};
		$.each(arr_field,function(index,item){
			//alert(item.name+"-"+item.value+"-"+item.type);			
			obj_data[item.name] = item.value;
		});
			/*
			xdata.title 	= $("#title").val();
			xdata.parent_id = $("#parent_id").val();
			xdata.url  		= $("#url").val();
			xdata.icon    	= $("#icon").val();
			xdata.description = $("#description").val();
			*/
		<?php 
			if($state_form == "edit"){?>
				var url = "<?=base_url('manager/menus/update/'.@$data_menu->id)?>";
			<?php }else{?>
				var url = '<?=base_url('manager/menus/save')?>';
		<?php }?>
		var loader;
				loader = new ajaxLoader("body", {classOveride: 'blue-loader'});
		$.ajax({
			type: "POST",
			url: url,
			data:obj_data,//"data="+JSON.stringify(xdata),
			dataType:"json",//"text",//
			success: function(result) {
				//alert(result);
				loader.remove();
				if(result.success == true){
					//window.location.reload(true);
					//alert(result.success);
					$(document).trigger("add-alerts", [
					{
					  'message': "<li>Status : <b>Berhasil</b></li><li>"+result.message+"</li>",
					  'priority': 'success'
					}
					]);
				}else{
					
					$(document).trigger("add-alerts", [
					{
					  'message': "<li>Status : <b>GAGAL</b></li><li>"+result.error.message+"</li>",
					  'priority': 'danger'
					}
					]);
					
				}
				// */
				//window.location.reload(true);				
			}, 
			error: function(jqXHR, textStatus, errorMessage) {
			   
			   console.log(errorMessage); // Optional
			},
			
		});
		// */
	}
	$(document).ready(function() {
		
		$("#parent_id").val("<?=@$data_menu->parent_id?>");;
		$('.icp').iconpicker();
		var config = {
                '.chosen-select'           : {},
                '.chosen-select-deselect'  : {allow_single_deselect:true},
                '.chosen-select-no-single' : {disable_search_threshold:10},
                '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
                '.chosen-select-width'     : {width:"95%"}
                }
        for (var selector in config) {
            $(selector).chosen(config[selector]);
        }
	});
	
</script>
