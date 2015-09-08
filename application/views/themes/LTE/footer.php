
 <?php 
 
if($this->user->logged_in){?>
	<footer class="main-footer" >
        <div class="pull-right hidden-xs">
          <b>Version</b> 1.0
        </div>
        <strong>&copy; 2015 <a href="#">JR</a>.</strong> All rights reserved.
     </footer>
	  <!-- Control Sidebar -->      
		<div class='control-sidebar-bg'></div>
    </div><!-- ./wrapper -->
	</body>
<?php }else{?>
	
	<footer class="xmain-footer" >
        <div class="pull-right hidden-xs">
          <b>Version</b> 1.0
        </div>
        <strong>&copy; 2015 <a href="#">JR</a>.</strong> All rights reserved.
     </footer>
   </body>
<?php } ?>
</html>