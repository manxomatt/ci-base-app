<style type="text/css">
.blue-loader .ajax_loader {
			background: url("<?=base_url('assets/images/ajax-loader_blue.gif')?>") no-repeat center center transparent; width:100%;height:100%;
		}
/*
span #db_state{
	background: url("<?=base_url('assets/images/ajax-loader_blue.gif')?>") no-repeat center center transparent; width:100%;height:100%;
}
*/
</style>
<script type="text/javascript" src="<?=base_url('assets/js/ajax_spinner.js')?>"></script>
<div class="container">
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title"><i class="fa fa-disk"></i>System Status</h3>
		</div>
		<div class="panel-body">
			<table class="table table-striped">
				<tr>
					<td width="200px">PHP Version </td><td> : <span class="label label-success"><?=phpversion()?></span></td>
				</tr>
				<tr>
					<td>Mod Rewrite </td><td> : <?php $isEnabled = in_array('mod_rewrite', apache_get_modules());
														echo ($isEnabled) ? '<span class="label label-success">Enabled</span>' : 
														'<span class="label label-danger">Not enabled</span>'; ?></td>
				</tr>
				<tr>
					<td>config/database.php </td><td> : <?php $isWritable= is_really_writable(APPPATH.'config/database.php'); 
														echo ($isWritable) ? '<span class="label label-success">Writable</span>' : 
														'<span class="label label-danger">Not Writable</span>'; ?></td>
				</tr>
				<tr>
					<td>Database Connection </td><td> : <span id="db_state">   </span></td>
				</tr>
			</table>
		</div>
		<div class="panel-footer">
			<button id="btn_setup_db" disabled onClick= "setupDb()" class="btn btn-success btn-sm">Setup Database Connection</button> 
			<button id="btn_next_step" onClick="generateTable()" class="btn btn-primary btn-sm">Next</button>
		</div>
	</div>	
</div>

<script type="text/javascript">
function setupDb()
{
	window.location.href='<?=base_url("install/setup_db")?>';
}

function generateTable()
{
	window.location.href='<?=base_url("install/generate_table")?>';
}
	
$(document).ready(function () {
	var loader = new ajaxLoader($("#db_state"), {classOveride: 'blue-loader'});	
	$.ajax({
		type: "POST",url: '<?=base_url("install/get_db_state")?>',
		data:'',
		dataType:"json",//"text",//
		cache: false,
		success: function(result) {
			//loader.remove();
			//alert(result.success);
			if(result.success == false){
				$("#db_state").html('<span class="label label-danger">'+result.errors.reason+'</span>');
				$('#btn_setup_db').prop('disabled',false);
				$("#btn_next_step").prop('disabled',true);
			}else{
				$("#db_state").html('<span class="label label-success">'+result.message+'</span>');
				$("#btn_setup_db").prop("disabled",true);
				$("#btn_next_step").prop('disabled',false);
				
			}
		}, 
		error: function(jqXHR, textStatus, errorMessage) {
		   console.log(errorMessage); // Optional
		},
	});
});
</script>