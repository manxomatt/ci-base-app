<link href="<?=base_url("assets/css/chosen.css")?>" rel="stylesheet">
<script src="<?=base_url("assets/js/chosen.jquery.js")?>"></script>
<script src="<?=base_url("assets/js/jquery.bsAlerts.min.js")?>"></script>

<div id="wrapper">
	<nav class="navbar-default navbar-static-side" role="navigation">
		<div class="sidebar-collapse">
			<ul class="nav metismenu" id="side-menu">
				<li >
					<a href="<?=base_url('dashboard')?>"><i class="fa fa-th-large"></i> 
						<span class="nav-label">Dashboard</span></a>
				</li>
				<?php 
					//echo("<pre>");
					foreach($list_menu as $key=>$val){
						if($val["nama_module"] == "setting_modul"){
							$is_active='class="active"';
						}else{
							$is_active='';
						}
						//print_r($val);
				?>
					<li <?=$is_active?>>
						<?php 
							if(isset($val["is_parent"])){?>
							<a href="#"><i class="fa fa-<?=$val["icon"]?>"></i> 
								<span class="nav-label"><?=$this->libcms->format_module_name($val["nama_module"])?></span>
								<span class="fa arrow"></span></a>
							<ul class="nav nav-second-level collapse">
								<?php
									 foreach($val["child_item"] as $xkey=>$xval){
								?>
								<li><a href="<?=base_url($xval["url_module"])?>">
									<!-- i class="fa fa-<?=$xval["icon"]?>"></i -->
									<?=$this->libcms->format_module_name($xval["nama_module"])?>
									</a></li>
								<?php } ?>
							</ul>
						<?php	}else{?>
							<a href="<?=base_url($val["url_module"])?>"><i class="fa fa-<?=$val["icon"]?>"></i> <span class="nav-label"><?=$this->libcms->format_module_name($val["nama_module"])?></span></a>
						<?php }?>
					</li>
				<?php } ?>
				<li>
					<a href="<?=base_url('auth/logout')?>"><i class="fa fa-sign-out"></i> <span class="nav-label">Log Out</span></a>
				</li>
			</ul>
		</div>
	</nav>
	<div id="page-wrapper" class="gray-bg">
		<div class="row wrapper border-bottom white-bg" >
			<div class="col-sm-8" style="padding-top:3px" style="border:1px solid red">
				<ol class="breadcrumb" >
					<li >
						<a href="<?=base_url()?>"><i class="fa fa-home"></i> Home</a>
					</li>
					<?=$breadcrumbs?>
				</ol>
			</div>			
		</div>
		<div class="wrapper wrapper-content">
			<div class="row">
				<div class="col-lg-3">
					<div class="ibox float-e-margins">						
						<div class="ibox-title">
							<h5><i class="fa fa-user"></i> Pengaturan Modul Member</h5>
						</div>						
                        <div class="ibox-content">
							<div data-alerts="alerts" data-fade="10000"></div>
							<div id="form_stu" class="form-horizontal">
								<div class="form-group form-inline">
									<label class="col-xs-2 control-label" style="font:12px tahoma" >Nama Modul :</label>
									<!-- div class="col-xs-2" style="padding-left:15px"-->
									<label class="col-xs-4 control-label"style="text-align:left" >
										<?=$this->libcms->format_module_name($module)?>
									</label>
									<!-- /div -->
								</div>
								<div class="form-group form-inline">
									<label class="col-xs-2 control-label" style="font:12px tahoma" >Hak Akses Level :</label>
									<div class="col-xs-8" style="padding-left:15px">
										<select data-placeholder="Pilih Level" style="width:300px" name="id_level" id="id_level" class="chosen-select" multiple data-style="btn-warning">
										<?php 
											//print_r($lgroup);
											foreach($lroles as $key=>$val){?>
												<option value="<?=$val->id?>"><?=$val->role?></option>
											<?php }
											?>
									</select>
									</div>
								</div>
								<div class="form-group form-inline">
									<label class="col-xs-2 control-label" style="font:12px tahoma" >Hak Akses Group :</label>
									<div class="col-xs-8" style="padding-left:15px">
										<select data-placeholder="Pilih Group" style="width:300px" name="id_group" id="id_group" class="chosen-select" multiple data-style="btn-warning">
										<?php 
											//print_r($lgroup);
											foreach($lgroup as $key=>$val){?>
												<option value="<?=$val->id?>"><?=$val->group?></option>
											<?php }
											?>
									</select>
									</div>
								</div>
								<div class="hr-line-dashed"></div>
								<div class="form-group">
                                    <div class="col-sm-4 col-sm-offset-2">
                                        <a class="btn btn-danger btn-sm" href="<?=base_url('setting_modul')?>">Batal</a>
                                        <button class="btn btn-primary btn-sm" onClick="saveData()">Simpan</button>
                                    </div>
                                </div> 
							</div>
						</div>
					</div>
				</div>
		</div>
	</div>
</div>
<?php

$config = unserialize($sysmodule->config);
$val_level ="";
$val_group ="";

foreach((array)$config["id_level"] as $key=>$val)
{
	$val_level .= $val.",";
}

foreach((array)$config["id_group"] as $key=>$val)
{
	$val_group .= $val.",";
}

?>
<script type="text/javascript">
	function saveData()
	{	
		var input_arr =  $(':input','#form_stu').not(':button, :submit, :text');
		var data_arr = {};
		//alert(input_arr.name);
		$.each(input_arr,function(item,value){
			data_arr[value.name] = $("#"+value.id+"").val();
		});
		var json_data = JSON.stringify(data_arr);
		
		save_url = "<?=base_url('admin/members/save')?>";
		$.ajax({
			type: "GET",
			url: save_url,
			data:"data="+json_data,dataType:"json",
			cache: false,contentType: false,
			processData: false,
			success: function(data) {
				if(data.success == true){
					$(document).trigger("add-alerts", [
						{
						  'message': "<li>Status : <b>BERHASIL</b><li>"+data.msg+"</li>",
						  'priority': 'success'
						}
					  ]);
				}else{
					$(document).trigger("add-alerts", [
						{
						  'message': "<li>Status : <b>GAGAL</b></li><li>"+data.errors.reason+"</li>",
						  'priority': 'danger'
						}
					  ]);
				}
			}, 
			error: function(jqXHR, textStatus, errorMessage) {
			   console.log(errorMessage); // Optional
			},
			
		});
	}
	
	$(document).ready(function() {
		$("#id_level").val([<?=@$val_level?>]);
		$("#id_group").val([<?=@$val_group?>]);
		
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