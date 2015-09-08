<style type="text/css">
.blue-loader .ajax_loader {background: url("<?=base_url('assets/img/ajax-loader_blue.gif')?>") no-repeat center center transparent; width:100%;height:100%;}
.login-container {
  margin: 0 auto;
  width: 350px; /* Whatever exact width you are looking for (not bound by preset bootstrap widths) */
}

#form-login{
	 padding: 20px;
}
</style>
<script type="text/javascript" src="<?=base_url('assets/js/ajax_spinner.js')?>"></script>
<script src="<?=base_url("assets/js/jquery.bsAlerts.min.js")?>"></script>
<div class="login-container">
	<div class="panel panel-warning">
		<div class="panel-heading">
			<h4 class="panel-title">Login System</h4>
		</div>
		<div class="panel-body">
			<div data-alerts="alerts" data-fade="5000"></div>
			<div id="form-login" class="form form-horizontal">
				  <div class="form-group has-feedback">
					<input type="text" id="login" name="login" class="form-control" placeholder="Login"/>
					<span class="glyphicon glyphicon-user form-control-feedback"></span>
				  </div>

				  <div class="form-group has-feedback">
					<input type="password" id="password" name="password" class="form-control" placeholder="Password"/>
					<span class="glyphicon glyphicon-lock form-control-feedback"></span>
				  </div>
				  
					<!-- div class="form-group">
						<div class="col-xs-8">    
						  <div class="checkbox icheck">
							<label>
							  <input type="checkbox" id="remember" name="remember"> Selalu Login 
							</label>
						  </div>                        
						</div>
					</div -->
					<div class="form-group">
						<div class="col-xs-12">
						  <button onclick="loginProcess()" class="btn btn-primary btn-block btn-flat" style="clear: left; width: 100%; height: 32px; font-size: 13px;">Login</button >
						  <?php // echo form_submit('submit', lang('login_submit_btn'),'class="btn btn-primary btn-block btn-flat" style="clear: left; width: 100%; height: 32px; font-size: 13px;"');?>
						</div><!-- /.col -->
					</div>
				  <p></p>   
				</div>
			
			<p><a href="forgot_password"><?=('Lupa Password ?')?></a></p>
		</div><!-- /.panel-body -->		
	</div> <!-- end panel -->
</div><!-- end container -->
<script type="text/javascript">

	
	function loginProcess()
		{
			
			var loader;
				loader = new ajaxLoader("body", {classOveride: 'blue-loader'});
			var xdata = {action: "login"}
				xdata.login = $("#login").val();
				xdata.password = $("#password").val();
				xdata.remember = $("#remember").val();
				
			$.ajax({
				type: 'POST',
				url: "<?=base_url('auth/login') ?>",
				data: xdata,// myKeyVals,
				dataType:'json',//'text',//
				success: function(result) { 
					loader.remove();
					//alert(result);//.action);
					if(result.success == true){
						window.location.reload(true);
						//alert(result.success);
					}else{
						
						$(document).trigger("add-alerts", [
						{
						  'message': "<li>Status : <b>GAGAL</b></li><li>"+result.error.message+"</li>",
						  'priority': 'danger'
						}
						]);
						
					}
					//alert("Save Complete") 
					//*/
				},
				error: function (request, status, error) {
					alert("error :: "+request.responseText);
				}
			});
		}
</script>