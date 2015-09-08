<!-- FooTable -->
	<link href="<?=base_url('assets/css/footable.core.css')?>" rel="stylesheet">
<!-- FooTable -->
    <script src="<?=base_url('assets/js/footable.all.min.js')?>"></script>	
	<script src="<?=base_url("assets/js/bootbox.min.js")?>"></script>
	<script src="<?=base_url("assets/js/jquery.bsAlerts.min.js")?>"></script>
<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
			<h1 class="box-title"><small><i class="fa fa-th-list"></i> Application Menu</small></h1>
			<ol class="breadcrumb">
				<li><a href="<?=base_url()?>"><i class="fa fa-home"></i> Home</a></li>
				<li class="active">Application Menu</li>
			</ol>
        </section>
		
		<!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box box-success">
                <div class="box-header">
                  <i class="fa fa-th-list"></i>
                  <h3 class="box-title">Application Menu</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
					
					<a class="btn btn-primary btn-xs" href="<?=base_url('manager/menus/add')?>">
                                    <i class="fa fa-plus"></i>  Data Menu Baru	
                                </a>
					<div data-alerts="alerts" data-fade="5000"></div>
					<table class="footable table table-stripped" data-page-size="10" data-filter=#filter>
						<thead>
						<tr>
							<th>#</th>
							<th>Title</th>
							<th>URL</th>
							<th>Order</th>								
							<th>Icon</th>
							<th>Description</th>
						</tr>
						</thead>
						<tbody>
						<?php 
							foreach($menus as $key=>$val){		
//?user_id=							
						?>
						<tr>
							<td><?=$val["id"]?></td>
							<td><?=$val["title"]?></td>
							<td><?=$val["url"]?></td>
							<td><?=$val["menu_order"]?></td>
							<td><?=$val["icon"]?></td>
							<td><?=$val["description"]?></td>
							<td><a href="<?=base_url("manager/menus/edit/".$val["id"])?>"><i class="fa fa-edit"></i></a> 
								<a href="#" onClick="deleteData(<?=$val["id"]?>)"><i class="fa fa-trash"></i></a></td>
						</tr>
						<?php }
						?>
						</tbody>
						<tfoot>
						<tr>
							<td colspan="5">
								<ul class="pagination pull-right"></ul>
							</td>
						</tr>
						</tfoot>
					</table>							
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</div>
</div>
<script>
	function deleteData(id)
	{
		var url="<?=base_url('manager/menus/delete/"+id+"')?>";
		bootbox.dialog({
		  message: "Anda akan menghapus data menu aplikasi ?<br>",
		  title: "Hapus data menu aplikasi",closable:false,
		  buttons: {
			success: {
			  label: "YA",
			  className: "btn-success",
			  callback: function() {//pembayaran/cetak_skpd/print_skpd/
				$.ajax({
					type: "GET",
					url: url,//"<?=base_url('pembayaran/print_skpd/"+no_polisi+"')?>",
 					data:"",dataType:"json",
					cache: false,contentType: false,
					processData: false,
					success: function(data) {
						if(data.success == true){
							window.location.reload(true);	
						}else{
							$(document).trigger("add-alerts", [
								{
								  'message': 'Penghapusan data gagal.',
								  'priority': 'danger'
								}
							]);
						}
					}, 
					error: function(jqXHR, textStatus, errorMessage) {
					   console.log(errorMessage); // Optional
					},
				});
				//Example.show("great success");
			  }
			},
			danger: {
			  label: "Tidak",
			  className: "btn-danger",
			  callback: function() {
				//Example.show("uh oh, look out!");
			  }
			}
		  }
		});	
		
	}
	$(document).ready(function() {

		$('.footable').footable();
	});

</script>