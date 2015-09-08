 <link href="<?=base_url("assets/css/chosen.css")?>" rel="stylesheet">
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
			<h1 class="box-title"><small><i class="fa fa-user"></i> Data Pengguna</small></h1>
			<ol class="breadcrumb">
				<li><a href="<?=base_url()?>"><i class="fa fa-home"></i> Home</a></li>
				<li >Data Pengguna</li><li class="active">Baru</li>
			</ol>
        </section>
		<!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box box-success">
                <div class="box-header">
                  <i class="fa fa-user"></i>
                  <h3 class="box-title">Data Pengguna</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
					<div data-alerts="alerts" data-fade="5000"></div>
					<div id="form_members" action="<?=base_url('admin/members/save')?>" class="form-horizontal">
						<div class="form-group"><label class="col-sm-3 control-label">Nama User</label>
							<div class="col-sm-6"><input type="text" id="username" name="username" class="form-control input-sm" value="<?=@$data_user->username?>"></div>
						</div>
						<div class="form-group"><label class="col-sm-3 control-label">Password</label>
							<div class="col-sm-6"><input type="password" id="password" name="password" class="form-control input-sm"> 
							</div>
						</div>
						<div class="form-group"><label class="col-sm-3 control-label">Ulangi Password</label>

							<div class="col-sm-6"><input type="password" id="repassword" name="repassword" class="form-control input-sm" name="password"></div>
						</div>
						<div class="form-group"><label class="col-sm-3 control-label">Group</label>
							<div class="col-sm-3">
								<select class="chosen-select" id="groupid" name="groupid" data-placeholder="Select Group" style="width:250px;">
									<?php 
										foreach($list_group as $key=>$val){
									?>
										<option value="<?=$val->id?>"><?=$val->group?></option>
									<?php 
										}
									?>
								</select>
								<!-- input type="text" class="form-control input-sm" -->
							</div>
						</div>
						<div class="form-group"><label class="col-sm-3 control-label">Email</label>
							<div class="col-sm-8"><input type="text" id="email" name="email" class="form-control input-sm" value="<?=@$data_user->email?>"></div>
						</div>
						<div class="form-group"><label class="col-sm-3 control-label">Nama Lengkap</label>
							<div class="col-sm-8"><input type="text" id="fullname" name="fullname" class="form-control input-sm" value="<?=@$data_user->nama?>"></div>
						</div>
						
						<!--  class="form-group"><label class="col-sm-3 control-label">Hak Akses Modul</label>
							<div class="col-sm-5">
								<select data-placeholder="Pilih Modul" id="id_modul" name="id_modul" multiple class="chosen-select" style="width:350px;">
									<option value=""></option>
									<?php 
										/*
										foreach($list_modul as $key=>$val){
											<option value="<?=$val->kode_module?>"><?=$val->nama_module?></option>
									
										}
										*/
									?>
								</select>
								
							</div>
						</div -->
						
						<div class="hr-line-dashed"></div>
						<div class="form-group">
							<div class="col-sm-4 col-sm-offset-3">
								<a class="btn btn-danger btn-sm" href="<?=base_url('manager/members')?>">
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
		
		var xdata = {};
			xdata.username = $("#username").val();
			xdata.password = $("#password").val();
			xdata.groupid  = $("#groupid").val();
			//xdata.group    = $("#group").val();
			xdata.email    = $("#email").val();
			xdata.fullname     = $("#fullname").val();
		<?php 
			if($state_form == "edit"){?>
				var url = "<?=base_url('manager/members/update/'.@$data_user->id)?>";
			<?php }else{?>
				var url = '<?=base_url('manager/members/save')?>';
		<?php }?>
		var loader;
				loader = new ajaxLoader("body", {classOveride: 'blue-loader'});
		$.ajax({
			type: "POST",
			url: url,
			data:xdata,//"data="+JSON.stringify(xdata),
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
		
		$("#fullname").val("<?=@$data_user->fullname?>");
		$("#groupid").val(<?=@$data_user->groupid?>);
		//$("#id_samsat_lokasi").val(<?=@$data_user->id_samsat_lokasi?>);		
		//$("#id_modul").val([<?=@$val_modul?>]);
		//$("#id_wilayah_samsat").val([<?=@$val_wilayah?>]);
	
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
