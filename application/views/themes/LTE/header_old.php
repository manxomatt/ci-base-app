<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?=$title_page ?></title>
	<link href="<?=base_url('assets/css/bootstrap.min.css')?>" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="<?=base_url('assets/css/font-awesome.min.css')?>" rel="stylesheet" type="text/css" />
	 <!-- Ionicons 2.0.0 -->
    <link href="<?=base_url('assets/css/ionicons.min.css') ?>" rel="stylesheet" type="text/css" />    
    <!-- Theme style -->
    <link href="<?=base_url('application/views/themes/LTE/css/AdminLTE.min.css')?>" rel="stylesheet" type="text/css" />
	<!-- AdminLTE Skins. Choose a skin from the css/skins 
         folder instead of downloading all of them to reduce the load. -->
    <link href="<?=base_url('application/views/themes/LTE/css/skins/_all-skins.min.css')?>" rel="stylesheet" type="text/css" />
	  <!-- Date Picker -->
    <link href="<?=base_url('assets/css/datepicker3.css')?>" rel="stylesheet" type="text/css" />
    <!-- Daterange picker -->
    <link href="<?=base_url('assets/css/daterangepicker-bs3.css')?>" rel="stylesheet" type="text/css" />
	<!-- iCheck -->
    <link href="<?=base_url('assets/js/iCheck/square/blue.css')?>" rel="stylesheet" type="text/css" />	
	
	<!-- jQuery 2.1.4 -->
    <script src="<?=base_url('assets/js/jQuery-2.1.4.min.js') ?>" type="text/javascript"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="<?=base_url('assets/js/bootstrap.min.js') ?>" type="text/javascript"></script>
	
	<?=$script_load ?>
	<!-- AdminLTE App -->
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="<?=base_url('assets/LTE/js/app.min.js')?>" type="text/javascript"></script>   
    <!-- AdminLTE for demo purposes -->
    <script src="<?=base_url('assets/LTE/js/demo.js')?>" type="text/javascript"></script>
		
</head>
<?php 
if(@$noheader == true){
?>
	<body class="skin-blue-light sidebar-mini">
<?php }else{?>
<body class="skin-blue-light  sidebar-mini  sidebar-collapse">
    <div class="wrapper">
      
      <header class="main-header">
        <!-- Logo -->
        <a href="#" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>JR</b></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>JR</b></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
			  <?php 
			  
			  if($this->user->logged_in){				
			  ?>
				 <!-- User Account: style can be found in dropdown.less -->
			  <li class="dropdown user user-menu">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
				  <img src="<?=base_url("assets/img/user2-160x160.png")?>" class="user-image" alt="User Image"/>
				  <span class="hidden-xs"><?=$this->session->userdata("username");?></span>
				</a>
				<ul class="dropdown-menu">
				  <!-- User image -->
				  <li class="user-header">
					<img src="<?=base_url("assets/img/user2-160x160.png")?>" class="img-circle" alt="User Image" />
					<p>
					  <?php //$this->ion_auth->user()->row()->first_name." ".$this->ion_auth->user()->row()->last_name;?>
					</p>
				  </li>
				  <!-- Menu Body -->
				  <li class="user-body">
					<div class="col-xs-8 text-center">
					  <a href="<?=base_url("auth/change_password")?>">Ubah Password</a>
					</div>
				  </li>
				  <!-- Menu Footer-->
				  <li class="user-footer">
					<div class="pull-right">
					  <a href="<?=base_url('auth/logout')?>" class="btn btn-danger btn-xs btn-flat">Logout</a>
					</div>
				  </li>
				</ul>
			  </li>
			  
			  <li class="dropdown user user-menu">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-gears"></i>
				</a>
				<ul class="dropdown-menu">
				  <!-- Menu Body -->
				  <li class="user-body">
					<div class="col-xs-8 text-left"><i class="fa fa-gear"></i>
					  <a href="<?=base_url("system_setting")?>">Setting</a>
					</div>
				  </li>
				  <li class="user-body">
					<div class="col-xs-8 text-left"><i class="fa fa-user"></i>
					  <a href="<?=base_url("auth")?>">Pengguna</a>
					</div>
				  </li>
				  <li class="user-body">
					<div class="col-xs-8 text-left"><i class="fa fa-cube"></i>
					  <a href="<?=base_url("module_setting")?>">Modul</a>
					</div>
				  </li>
				  
				</ul>
			  </li>
			
			  <?php }else{?>
			  <li >
				<div class=" navbar-form pull-right form-inline">
					<div class="input-group">
						<input type="text" id="login" name="login" class="form-control input-sm" placeholder="Login"/>
						<span class="glyphicon glyphicon-user form-control-feedback"></span>
					</div>
					<div class="input-group">
						<input type="password" id="password" name="password" class="form-control input-sm" placeholder="Password"/>
						<span class="glyphicon glyphicon-lock form-control-feedback"></span>
					</div>
					<div class="input-group">
					 <button onclick="loginProcess()" class="btn btn-sm btn-success btn-block btn-flat" style="clear: left; width: 100%; height: 32px; font-size: 13px;"> Login </button >
					</div>	 
				</div>				
			  </li>
			  <?php } ?>
            </ul>
          </div>
        </nav>
      </header>
	     <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          
          <ul class="sidebar-menu">
            <?php 
			
			if($this->user->logged_in){?>
				<li class="header">NAVIGASI</li>
				<!-- li class="active treeview" -->
				 <li><a href="<?=base_url("dashboard")?>">
					<i class="fa fa-dashboard"></i> <span>Dashboard</span> 
					<!-- i class="fa fa-angle-left pull-right"></i -->
				  </a></li>
				  <li class="treeview">
					<a href="#"><i class="fa fa-gears"></i> <span>Sistem</span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li><a href="<?=base_url('system_setting');?>"><i class="fa fa-gear"></i><span>Setting</span></a></li>
						<li><a href="<?=base_url("members")?>"><i class="fa fa-user"></i><span>Pengguna</span></a></li>	
						<li><a href="<?=base_url("module_setting")?>"><i class="fa fa-cube"></i><span>Modul</span></a></li>	
					</ul>
				  </li>
				  <!-- ul class="treeview-menu" -->
					
					<li><a href="<?=base_url("splitzing")?>"><i class="fa fa-file-text"></i><span>Splitzing</span></a></li>
				  <!-- /ul -->
				<!-- /li-->
           <?php }?>
		   
            <!-- li class="header">WILAYAH</li -->
			<li><a href="<?=base_url('chart')?>"><i class="fa fa-bar-chart-o text-blue"></i> <span>Provinsi Lampung</span></a></li>
            <li class="treeview">
				<a href="#">
                <i class="fa fa-bar-chart-o text-red"></i> <span>Wilayah B. Lampung</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
				<ul class="treeview-menu">
					<li><a href="<?=base_url('chart/wilayah/1')?>"><i class="fa fa-bar-chart-o text-red"></i> 
						<span>Kabupaten</span></a></li>
					<li><a href="<?=base_url('chart/lokasi/1')?>"><i class="fa fa-bar-chart-o text-red"></i> 
						<span>Samsat</span></a></li>
						
				</ul>
			</li>
			
			<li class="treeview">
				<a href="#">
                <i class="fa fa-bar-chart-o text-yellow"></i> <span>Wilayah Metro</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
				<ul class="treeview-menu">
					<li><a href="<?=base_url('chart/wilayah/2')?>"><i class="fa fa-bar-chart-o text-yellow"></i> 
						<span>Kabupaten</span></a></li>
					<li><a href="<?=base_url('chart/lokasi/2')?>"><i class="fa fa-bar-chart-o text-yellow"></i> 
						<span>Samsat</span></a></li>
						
				</ul>
			</li>
			<li class="treeview">
				<a href="#">
                <i class="fa fa-bar-chart-o text-aqua"></i> <span>Wilayah Kota Bumi</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
				<ul class="treeview-menu">
					<li><a href="<?=base_url('chart/wilayah/3')?>"><i class="fa fa-bar-chart-o text-aqua"></i> 
						<span>Kabupaten</span></a></li>
					<li><a href="<?=base_url('chart/lokasi/3')?>"><i class="fa fa-bar-chart-o text-aqua"></i> 
						<span>Samsat</span></a></li>
						
				</ul>
			</li>
			<!-- li><a href="<?=base_url('frontend/chart/2')?>"><i class="fa fa-bar-chart-o text-yellow"></i> <span>Metro</span></a></li>
            <li><a href="<?=base_url('frontend/chart/3')?>"><i class="fa fa-bar-chart-o text-aqua"></i> <span>Kota Bumi</span></a></li -->
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>

	  <?php } ?>