<!-- DataTables -->
<script src="<?=config_item('assets')?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?=config_item('assets')?>plugins/datatables/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {

	//set input/textarea/select event when change value, remove class error and remove text help block
	$("input").change(function() {
		$(this).parent().parent().removeClass('has-error');
		$(this).next().empty();
	});


    //datatable
    $('#tblLaporanMutasiDetail').DataTable({
        "pageLength": 50,
         "order": [],
    });
});	

setInterval(function(){
    $("#load_row").load('<?=base_url()?>pesan/load_row_notif')
}, 2000); //menggunakan setinterval jumlah notifikasi akan selalu update setiap 2 detik diambil dari controller notifikasi fungsi load_row
 
setInterval(function(){
    $("#load_data").load('<?=base_url()?>pesan/load_data_notif')
}, 2000); //yang ini untuk selalu cek isi data notifikasinya sama setiap 2 detik diambil dari controller notifikasi fungsi load_data

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

</script>	