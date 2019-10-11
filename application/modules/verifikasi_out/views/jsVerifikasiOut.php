<!--  DataTables --> 
<script src="<?=config_item('assets')?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?=config_item('assets')?>plugins/datatables/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
	var save_method; //for save method string
	var table;
  var table2;

$(document).ready(function() {
  //declare variable for row count
  var i = randString(5);

  //force integer input in textfield
  $('input.numberinput').bind('keypress', function (e) {
    return (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57) && e.which != 46) ? false : true;
  });

  // select class modal apabila bs.modal hidden
  $("#modal_pengeluaran").on("hidden.bs.modal", function(){
    $('#form')[0].reset(); 
    //clear tr append in modal
    $('tr').remove('.tbl_modal_row'); 
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
  });

  //datatables  
  // tabel trans order
	table = $('#tabelVerifikasi').DataTable({
		
		"processing": true, 
		"serverSide": true, 
		"order":[[ 2, 'desc' ]], 
		//load data for table content from ajax source
		"ajax": {
			"url": "<?php echo site_url('verifikasi_out/list_verifikasi') ?>",
			"type": "POST" 
		},

		//set column definition initialisation properties
		"columnDefs": [
			{
				"targets": [-1], //last column
				"orderable": false, //set not orderable
			},
		],
	});

  $('#tabelTransOrderDetail').DataTable({
  });

  //datepicker
	$('#form_tanggal_order').datepicker({
		autoclose: true,
		format: "yyyy-mm-dd",
		todayHighlight: true,
		orientation: "top auto",
		todayBtn: true,
		todayHighlight: true,
	});

  //autocomplete
  $('#form_nama_barang_order').autocomplete({
    minLength: 2,
    delay: 0,
    source: '<?php echo site_url('trans_order/suggest_barang'); ?>',
    select:function(event, ui) {
              $('#form_id_barang_order').val(ui.item.id_barang);
              $('#form_nama_satuan_order').val(ui.item.nama_satuan);
              $('#form_id_satuan_order').val(ui.item.id_satuan);
            }     
  });

  //set input/textarea/select event when change value, remove class error and remove text help block
	$("input").change(function() {
		$(this).parent().parent().removeClass('has-error');
		$(this).next().empty();
	});

//end jquery
});	

function randString(angka) 
{
  var text = "";
  var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

  for (var i = 0; i < angka; i++)
    text += possible.charAt(Math.floor(Math.random() * possible.length));

  return text;
}
function reloadPage() 
{
  location.reload();
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;
    var tipe_simpan;

    if(save_method == 'add') {
        url = "<?php echo site_url('pengeluaran/add_pengeluaran')?>";
        tipe_simpan = 'tambah';
    } else {
        url = "<?php echo site_url('pengeluaran/update_pengeluaran')?>";
        tipe_simpan = 'update';
    }

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {
            if(data.status) //if success close modal and reload ajax table
            {
                if (tipe_simpan == 'tambah') {
                  alert(data.pesan_tambah);
                } 
                else
                {
                  alert(data.pesan_update);
                }

                $('#modal_pengeluaran').modal('hide'); 
                reload_table();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    //parent once if element from form-group, if any div inside form-group, just make another parent
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                    //alert(data.error_string[i]);
                }
            }
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            //$('#modal_form_order').modal('hide');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 
        }
    });
}

function deleteTransOrder(id)
{
    if(confirm('Are you sure delete this data?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('trans_order/delete_trans_order')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_form_order').modal('hide');
                alert(data.pesan);
                //call function
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });
    }
}

function ambilData(id) 
{
  if(confirm('Ambil data peramalan ?'))
    {
        $('#modal_peramalan').modal('hide');      
        var rowIdBrg = $('.row_idBrg_'+id+'').text();
        var rowQty = $('.row_ft_'+id+'').text();

        $('#form_id_barang_order').val(rowIdBrg);
        $('#form_jumlah_barang_order').val(rowQty);
        
        $.ajax({
          url : "<?php echo site_url('trans_order/get_data_barang')?>/"+rowIdBrg,
          type: "GET",
          dataType: "JSON",
          success: function(data)
          {
              $('#form_nama_barang_order').val(data[0].nama_barang);
              $('#form_nama_satuan_order').val(data[0].nama_satuan);
              $('#form_id_satuan_order').val(data[0].id_satuan);
          },
          error: function (jqXHR, textStatus, errorThrown)
          {
              alert('Error get data from ajax');
          }
      });  
    }
}    
</script>	
