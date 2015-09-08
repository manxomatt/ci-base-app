<script src="<?=base_url("assets/js/jquery.bsAlerts.min.js")?>"></script>
<content>
	<div class="container  col-xs-6">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-disk"></i> SETTING ADMINISTRATOR</h3>
			</div>
			<div class="panel-body">
				<p class="login-box-msg">
					<div data-alerts="alerts" data-fade="5000"></div>
				</p>
				<div id="form-config-admin" class="form form-horizontal">
					<div class="form-group">
						<label class="col-xs-3 control-label" for="old_password" style="font:12px tahoma">Username </label>
						<div class="input-group col-xs-8">
							<input type="text" id="username" name="username"  class="form-control input-sm" >																				
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-3 control-label" for="password" style="font:12px tahoma">Password</label>
						<div class="input-group col-xs-8">
							<input type="text" id="password" name="password"  class="form-control input-sm" >																				
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-3 control-label" for="repassword" style="font:12px tahoma">re-Password</label>
						<div class="input-group col-xs-8">
							<input type="text" id="repassword" name="repassword"  class="form-control input-sm" >																				
						</div>
					</div>
					
					
					<div class="form-group">
						<div class="col-xs-3">
						</div>
						<div class="col-xs-6">
							<button onclick="saveAdmin()" class="btn btn-primary btn-block btn-flat" style="clear: left; width: 100%; height: 32px; font-size: 13px;">Save Configuration</button >
						</div><!-- /.col -->
					</div>
				  <p></p>
				</div>
			</div>
		</div>
	</div>
</content>

<script type="text/javascript">
	function saveAdmin()
	{
		var arr_field = $(":input","#form-config-admin").not(":button");
		var obj_data = {};
		
		$.each(arr_field,function(index,item){
			obj_data[item.name] = item.value;
		});
				
		
		$.ajax({
			type: "POST",url: '<?=base_url("install/save_add_admin")?>',
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
	
</script>