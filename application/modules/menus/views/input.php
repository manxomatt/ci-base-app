<div id="form-test">
<input type='text' name="test_input"> <br>
 <select name="test_select">
     <option value='1'>satu</option>
     <option value='2'>dua</option>
     <option value='3'>tiga</option>
    </select>
</div>
<button onClick="cekInput()">cek</button>

<script type="text/javascript">

function cekInput()
{
    alert('ss');
        var arr_field = $(":input","#form-test").not(":button");
		var obj_data = {};
    $.each(arr_field,function(index,item){
        	alert(item.name+"="+item.value);
			//obj_data[item.name] = item.value;
		});
     // */
}

</script>	