<script src="<?=base_url("assets/js/jquery.bsAlerts.min.js")?>"></script>
<content>
	<div class="container  col-xs-6">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-disk"></i> Database Configuration</h3>
			</div>
			<div class="panel-body">
				<p class="login-box-msg">
					<div data-alerts="alerts" data-fade="5000"></div>
				</p>
				<div id="form-config-db" class="form form-horizontal">
					<div class="form-group">
						<label class="col-xs-3 control-label" for="old_password" style="font:12px tahoma">DB Host</label>
						<div class="input-group col-xs-8">
							<input type="text" id="hostname" name="hostname"  class="form-control input-sm" value="<?=@$dbconfig['hostname']?>">																				
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-3 control-label" for="old_password" style="font:12px tahoma">DB User</label>
						<div class="input-group col-xs-8">
							<input type="text" id="username" name="username"  class="form-control input-sm" value="<?=@$dbconfig['username']?>">																				
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-3 control-label" for="old_password" style="font:12px tahoma">DB Pass</label>
						<div class="input-group col-xs-8">
							<input type="text" id="password" name="password"  class="form-control input-sm" value="<?=@$dbconfig['password']?>">																				
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-3 control-label" for="old_password" style="font:12px tahoma">DB Name</label>
						<div class="input-group col-xs-8">
							<input type="text" id="database" name="database"  class="form-control input-sm" value="<?=@$dbconfig['database']?>">																				
						</div>
					</div>
					<div class="form-group">
					<div class="col-xs-8">    
					  <div class="checkbox icheck">
						<label>
						  <input type="checkbox" name="create_db"  id="create_db" value='1'> Create DB
						</label>
					  </div>                        
					</div><!-- /.col -->
					</div>
					<div class="form-group">
						<div class="col-xs-6">
							<button onclick="testConnectDb()" class="btn btn-warning btn-block btn-flat" style="clear: left; width: 100%; height: 32px; font-size: 13px;">Test Connection</button >
						</div><!-- /.col -->
						<div class="col-xs-6">
							<button onclick="saveConfiguration()" class="btn btn-primary btn-block btn-flat" style="clear: left; width: 100%; height: 32px; font-size: 13px;">Save Configuration</button >
						</div><!-- /.col -->
					</div>
				  <p></p>
				</div>
			</div>
		</div>
	</div>
</content>

<script type="text/javascript">
	function testConnectDb()
	{
		var arr_field = $(":input","#form-config-db").not(":button");
		var obj_data = {};
		
		$.each(arr_field,function(index,item){
			obj_data[item.name] = item.value;
		});
		if($("#create_db").is(':checked')){
			obj_data.create_db = 1;
		}else{
			obj_data.create_db = 0;
		}
				
		
		$.ajax({
			type: "POST",url: '<?=base_url("install/test_db_connect")?>',
			data:obj_data,
			dataType:"json",//"text",//
			cache: false,
			success: function(result) {
				//alert(result);
				if(result.success == true){
					$(document).trigger("add-alerts", [
						{
						  'message': "<li>Status : <b>Success</b><li>"+result.message+"</li>",
						  'priority': 'success'
						}
					  ]);					 
				}else{
					$(document).trigger("add-alerts", [
						{
						  'message': "<li>Status : <b>Failed</b></li><li>"+result.errors.reason+"</li>",
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
	
	function saveConfiguration()
	{
		var arr_field = $(":input","#form-config-db").not(":button");
		var obj_data = {};
		
		$.each(arr_field,function(index,item){
			obj_data[item.name] = item.value;
		});
		if($("#create_db").is(':checked')){
			obj_data.create_db = 1;
		}else{
			obj_data.create_db = 0;
		}
				
		
		$.ajax({
			type: "POST",url: '<?=base_url("install/save_db_config")?>',
			data:obj_data,
			dataType:"json",//"text",//
			cache: false,
			success: function(result) {
				//alert(result);
				if(result.success == true){
					$(document).trigger("add-alerts", [
						{
						  'message': "<li>Status : <b>Success</b><li>"+result.message+"</li>",
						  'priority': 'success'
						}
					  ]);
					 setTimeout(function(){
							window.location.href='<?=base_url("install")?>';
					  },2000);
				}else{
					$(document).trigger("add-alerts", [
						{
						  'message': "<li>Status : <b>Failed</b></li><li>"+result.errors.reason+"</li>",
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
</script>