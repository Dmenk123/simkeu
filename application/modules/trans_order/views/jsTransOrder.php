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
	//addrow field inside modal
  $('#btn_add_row').click(function() {
    var ambilNama = $('#form_nama_barang_order').val();
    var ambilIdNama = $('#form_id_barang_order').val();
    var ambilSatuan = $('#form_nama_satuan_order').val();
    var ambilIdSatuan = $('#form_id_satuan_order').val();
    var ambilJumlah = $('#form_jumlah_barang_order').val();
    var ambilKeterangan = $('#form_keterangan_barang_order').val();
      if (ambilNama == "" || ambilSatuan == "" || ambilJumlah == "" || ambilIdNama == "" ) {
        alert('ada field yang tidak diisi, Mohon cek lagi!!');
      }else{
         $('#tabel_order').append('<tr class="tbl_modal_row" id="row'+i+'">'
                                +'<td><input type="text" name="fieldNamaBarangOrder[]" value="'+ambilNama+'" id="field_nama_barang_order" class="form-control" required readonly>'
                                +'<input type="hidden" name="fieldIdBeli[]" value="0" id="field_id_beli" class="form-control">'
                                +'<input type="hidden" name="fieldIdBarangOrder[]" value="'+ambilIdNama+'" id="field_id_barang_order" class="form-control"></td>'
                                +'<td><input type="text" name="fieldNamaSatuanOrder[]" value="'+ambilSatuan+'" id="field_nama_satuan_order" class="form-control" required readonly>'
                                +'<input type="hidden" name="fieldIdSatuanOrder[]" value="'+ambilIdSatuan+'" id="field_id_satuan_order" class="form-control"></td>'
                                +'<td><input type="text" name="fieldJumlahBarangOrder[]" value="'+ambilJumlah+'" id="field_jumlah_barang_order" class="form-control" required readonly></td>'
                                +'<td><input type="text" name="fieldKeteranganBarangOrder[]" value="'+ambilKeterangan+'" id="field_keterangan_barang_order" class="form-control" required readonly></td>'
                                +'<td><button name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td>'
                                +'</tr>');
        i = randString(5);
        //kosongkan field setelah append row
        $('#form_nama_barang_order').val("");
        $('#form_id_barang_order').val("");
        $('#form_nama_satuan_order').val("");
        $('#form_id_satuan_order').val("");
        $('#form_jumlah_barang_order').val("");
        $('#form_keterangan_barang_order').val("");
      } 
  });

  //force integer input in textfield
  $('input.numberinput').bind('keypress', function (e) {
    return (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57) && e.which != 46) ? false : true;
  });

  $(document).on('click', '.btn_remove', function(){
    var button_id = $(this).attr('id');
    $('#row'+button_id+'').remove();
  });

  // select class modal apabila bs.modal hidden
  $("#modal_form_order").on("hidden.bs.modal", function(){
    $('#form')[0].reset(); 
    //clear tr append in modal
    $('tr').remove('.tbl_modal_row'); 
    $('#form_tanggal_order').datepicker('setDate', null); 
    $('.form-group').removeClass('has-error');//clear error class
    $('.help-block').empty(); //clear error string
  });

  //datatables  
  // tabel trans order
	table = $('#tabelTransOrder').DataTable({
		
		"processing": true, 
		"serverSide": true, 
		"order":[[ 2, 'desc' ]], 
		//load data for table content from ajax source
		"ajax": {
			"url": "<?php echo site_url('trans_order/list_trans_order') ?>",
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

  //tabel peramalan
  table2 = $('#tabelforecasting').DataTable({
        
    "processing": true, //feature control the processing indicator
    "serverSide": true, //feature control DataTables server-side processing mode
    "order":[[ 0, 'desc' ]], //index for order, 0 is first column

    //load data for table content from ajax source
    "ajax": {
       "url": "<?php echo site_url('trans_order/list_peramalan') ?>",
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

  //update dt_read after click
  $(document).on('click', '.linkNotif', function(){
    var id = $(this).attr('id');
    $.ajax({
      url : "<?php echo site_url('inbox/update_read/')?>/" + id,
      type: "POST",
      dataType: "JSON",
      success: function(data)
      {
        location.href = "<?php echo site_url('inbox/index')?>";
      },
        error: function (jqXHR, textStatus, errorThrown)
      {
        alert('Error get data from ajax');
      }
    });
  });
//end jquery
});	

setInterval(function(){
    $("#load_row").load('<?=base_url()?>pesan/load_row_notif')
}, 2000); //menggunakan setinterval jumlah notifikasi akan selalu update setiap 2 detik diambil dari controller notifikasi fungsi load_row
 
setInterval(function(){
    $("#load_data").load('<?=base_url()?>pesan/load_data_notif')
}, 2000); //yang ini untuk selalu cek isi data notifikasinya sama setiap 2 detik diambil dari controller notifikasi fungsi load_data

function addTransOrder() 
{
	save_method = 'add';
	$('#form')[0].reset(); //reset form on modals
	$('.form-group').removeClass('has-error');//clear error class
	$('.help-block').empty(); //clear error string
	$('#modal_form_order').modal('show'); //show bootstrap modal
	$('.modal-title').text('Transaksi Pemesanan Barang'); //set title modal
  $.ajax({
        url : "<?php echo site_url('trans_order/ajax_get_header_form/')?>",
        type: "GET",
        dataType: "JSON",
        success: function(data)
          {
            //header
            $('[name="fieldIdOrder"]').val(data.kode_trans_order);
          }
      });
}

function editTransOrder(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form_order').modal('show'); // show bootstrap modal when complete loaded
    $('.modal-title').text('Edit Transaksi Order');
    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('trans_order/edit_trans_order/')?>" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            /*alert(rec); //mkyong*/
            //ambil data ke json->modal
            //header
            $('[name="fieldIdOrder"]').val(data.data_header[0].id_trans_order);
            $('[name="fieldTanggalOrder"]').val(data.data_header[0].tgl_trans_order);
            $('[name="fieldUserOrder"]').val(data.data_header[0].username);
            $('[name="fieldIdUserOrder"]').val(data.data_header[0].id_user);
            //isi
            var i = randString(5);
            var key_isi = 1;
            Object.keys(data.data_isi).forEach(function(){         
              $('#tabel_order').append('<tr class="tbl_modal_row" id="row'+i+'">'
                              +'<td><input type="text" name="fieldNamaBarangOrder[]" value="'+data.data_isi[key_isi-1].nama_barang+'" id="field_nama_barang_order" class="form-control" required readonly>'
                              +'<input type="hidden" name="fieldIdBeli[]" value="'+data.data_isi[key_isi-1].id_trans_beli+'" id="field_id_beli" class="form-control">'
                              +'<input type="hidden" name="fieldIdOrderDetail[]" value="'+data.data_isi[key_isi-1].id_trans_order_detail+'" id="field_id_order_detail" class="form-control">'
                              +'<input type="hidden" name="fieldIdBarangOrder[]" value="'+data.data_isi[key_isi-1].id_barang+'" id="field_id_barang_order" class="form-control"></td>'
                              +'<td><input type="text" name="fieldNamaSatuanOrder[]" value="'+data.data_isi[key_isi-1].nama_satuan+'" id="field_nama_satuan_order" class="form-control" required readonly>'
                              +'<input type="hidden" name="fieldIdSatuanOrder[]" value="'+data.data_isi[key_isi-1].id_satuan+'" id="field_id_satuan_order" class="form-control"></td>'
                              +'<td><input type="text" name="fieldJumlahBarangOrder[]" value="'+data.data_isi[key_isi-1].qty_order+'" id="field_jumlah_barang_order" class="form-control" required></td>'
                              +'<td><input type="text" name="fieldKeteranganBarangOrder[]" value="'+data.data_isi[key_isi-1].keterangan_order+'" id="field_keterangan_barang_order" class="form-control" required></td>'
                              +'<td><button name="remove" id="'+i+'" class="btn btn-danger btn_remove" disabled>X</button></td>'
                              +'</tr>');
              key_isi++;  
              i = randString(5);             
            });

            // select class modal apabila bs.modal hidden
            $("#modal_form_order").on("hidden.bs.modal", function(){
              //reset form value on modals
              $('#form')[0].reset(); 
              $('tr').remove('.tbl_modal_row'); 
              $('.form-group').removeClass('has-error');//clear error class
              $('.help-block').empty(); //clear error string
            });
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
   
}

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

function reload_table2()
{
    table2.ajax.reload(null,false); //reload datatable ajax 
}

function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;
    var tipe_simpan;

    if(save_method == 'add') {
        url = "<?php echo site_url('trans_order/add_trans_order')?>";
        tipe_simpan = 'tambah';
    } else {
        url = "<?php echo site_url('trans_order/update_trans_order')?>";
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

                $('#modal_form_order').modal('hide'); 
                //call function
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
