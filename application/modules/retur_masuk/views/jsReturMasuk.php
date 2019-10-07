<!--  DataTables --> 
<script src="<?=config_item('assets')?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?=config_item('assets')?>plugins/datatables/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
	var save_method; //for save method string
	var table;

$(document).ready(function() {
  //force integer input in textfield
  $('input.numberinput').bind('keypress', function (e) {
    return (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57) && e.which != 46) ? false : true;
  });

  //remove all row element after click
  $(document).on('click', '.btn_remove', function(){
    var button_id = $(this).attr('id');
    $('#row'+button_id+'').remove();
  });

  // select class modal whenever bs.modal hidden
  $(".modal").on("hidden.bs.modal", function(){
    $('#form')[0].reset(); 
    //clear tr append in modal
    $('tr').remove('.tbl_modal_row');
    $('#form_tanggal_retur_masuk').datepicker('setDate', null); 
    $('#form_id_retur_keluar').empty(); 
    $('#form_nama_supplier').empty(); 
    $('.form-group').removeClass('has-error');//clear error class
    $('.help-block').empty(); //clear error string
  });

  //datatables  
	table = $('#tabelReturMasuk').DataTable({
		
		"processing": true, //feature control the processing indicator
		"serverSide": true, //feature control DataTables server-side processing mode
		"order":[[ 0, 'desc' ]], //index for order, 0 is first column

		//load data for table content from ajax source
		"ajax": {
			"url": "<?php echo site_url('retur_masuk/list_retur_masuk') ?>",
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

  //datatable
  $('#tabelReturKeluarDetail').DataTable({
  });

  //datepicker
	$('#form_tanggal_retur_masuk').datepicker({
		autoclose: true,
		format: "yyyy-mm-dd",
		todayHighlight: true,
		orientation: "top auto",
		todayBtn: true,
		todayHighlight: true,
	});

  //set input/textarea/select event when change value, remove class error and remove text help block
	$("input").change(function() {
		$(this).parent().parent().removeClass('has-error');
		$(this).next().empty();
	});

  //select2
  $( "#form_id_retur_keluar" ).select2({
    ajax: {
        url: '<?php echo site_url('retur_masuk/suggest_id_retur_keluar'); ?>',
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
            // parse the results into the format expected by Select2.
            // since we are using custom formatting functions we do not need to
            // alter the remote JSON data
            return {
                results: data
            };
        },
        cache: true
    }, 
  });

  //autofill
  var fill = true;
  $('#form_id_retur_keluar').change(function(){
    var idReturKeluar = $('#form_id_retur_keluar').val();
    //var tblRows = $('#tabel_pembelian tr').length;
    //remove tr before appending
    $('tr').remove('.tbl_modal_row');
      $.ajax({
      url: '<?php echo site_url('retur_masuk/list_retur_keluar'); ?>',
      type: 'POST',
      dataType: 'json',
      async : false,
      data : { idReturKeluar : idReturKeluar},
      success : function(data) {
          var i = randString(5);
          var key = 1;
          Object.keys(data.data_list).forEach(function(){
              $('#form_nama_supplier').val(data.data_supplier[0].nama_supplier);
              $('#form_id_supplier').val(data.data_supplier[0].id_supplier);   
              $('#tabel_retur_masuk').append('<tr class="tbl_modal_row" id="row'+i+'">'
                              +'<td><input type="text" name="fieldNamaBarangReturMasuk[]" value="'+data.data_list[key-1].nama_barang+'" id="field_nama_barang_retur_masuk" class="form-control" readonly>'
                              +'<input type="hidden" name="fieldIdBarangReturMasuk[]" value="'+data.data_list[key-1].id_barang+'" id="field_id_barang_retur_masuk" class="form-control"></td>'
                              +'<input type="hidden" name="fieldIdReturKeluarDetail[]" value="'+data.data_list[key-1].id_retur_keluar_detail+'" id="field_id_retur_keluar_detail" class="form-control"></td>'
                              +'<td><input type="text" name="fieldNamaSatuanReturMasuk[]" value="'+data.data_list[key-1].nama_satuan+'" id="field_nama_satuan_retur_masuk" class="form-control" readonly>'
                              +'<input type="hidden" name="fieldIdSatuanReturMasuk[]" value="'+data.data_list[key-1].id_satuan+'" id="field_id_satuan_retur_masuk" class="form-control"></td>'
                              +'<td><input type="text" name="fieldJumlahBarangReturMasuk[]" value="'+data.data_list[key-1].qty_retur_keluar+'" id="field_jumlah_barang_retur_masuk" class="form-control"></td>'
                              +'<td><input type="text" name="fieldKeteranganBarangReturMasuk[]" value="'+data.data_list[key-1].keterangan_retur_keluar+'" id="field_keterangan_barang_retur_masuk" class="form-control"></td>'
                              +'<td><button name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td>'
                              +'</tr>');
              key++;  
              i = randString(5);
            });

            // select class modal apabila bs.modal hidden
            $(".modal").on("hidden.bs.modal", function(){
            //reset form value on modals
            $('#form')[0].reset(); 
            $('tr').remove('.tbl_modal_row'); 
            $('.form-control').removeClass('has-error');//clear error class
            $('.form-group').removeClass('has-error');//clear error class
            $('.help-block').empty(); //clear error string
          });
      },
      error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Gagal request data dari server');
        }
    });
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

  //fix to issue select2 on modal when opening in firefox, thanks to github
  $.fn.modal.Constructor.prototype.enforceFocus = function() {};
  
});


setInterval(function(){
    $("#load_row").load('<?=base_url()?>pesan/load_row_notif')
}, 2000); //menggunakan setinterval jumlah notifikasi akan selalu update setiap 2 detik diambil dari controller notifikasi fungsi load_row
 
setInterval(function(){
    $("#load_data").load('<?=base_url()?>pesan/load_data_notif')
}, 2000); //yang ini untuk selalu cek isi data notifikasinya sama setiap 2 detik diambil dari controller notifikasi fungsi load_data

function addReturMasuk() 
{
	save_method = 'add';
  $('[name="fieldTanggalReturMasuk"]').attr("disabled", false);
  $('#form_id_retur_masuk').attr("disabled", false);
	$('#form')[0].reset(); //reset form on modals
	$('.form-group').removeClass('has-error');//clear error class
  $('.form-control').removeClass('has-error');//clear error class
	$('.help-block').empty(); //clear error string
	$('#modal_form_retur_masuk').modal('show'); //show bootstrap modal
	$('.modal-title').text('Tambah Penerimaan Barang Retur'); //set title modal
  $.ajax({
        url : "<?php echo site_url('retur_masuk/ajax_get_header_form/')?>",
        type: "GET",
        dataType: "JSON",
        success: function(data)
          {
            //header
            $('[name="fieldIdReturMasuk"]').val(data.kode_retur_masuk);
          }
  });
}

function editReturMasuk(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form_retur_masuk').modal('show'); // show bootstrap modal when complete loaded
    $('.modal-title').text('Edit Penerimaan Barang Retur');
    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('retur_masuk/edit_retur_masuk')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            /*alert(rec); //mkyong*/
            //ambil data ke json->modal
            //header
            $('[name="fieldIdReturMasuk"]').val(data.data_header[0].id_retur_masuk);
            $('[name="fieldTanggalReturMasuk"]').val(data.data_header[0].tgl_retur_masuk);
            $('[name="fieldUserReturMasuk"]').val(data.data_header[0].username);
            $('[name="fieldIdUserReturMasuk"]').val(data.data_header[0].id_user);
            $('[name="fieldNamaSupplier"]').val(data.data_header[0].nama_supplier);
            $('[name="fieldIdSupplier"]').val(data.data_header[0].id_supplier);
      
            $selectedIdReturKeluar = $("<option></option>").val(data.data_header[0].id_retur_keluar).text(data.data_header[0].id_retur_keluar);
            //tanpa trigger event
            $("#form_id_retur_keluar").append($selectedIdReturKeluar);
    
            //set attribute
            // $('[name="fieldTanggalMasuk"]').attr("disabled", true);
            $('#form_id_retur_keluar').attr("disabled", true); 

            //isi
            var i = randString(5);
            var key = 1;
            Object.keys(data.data_isi).forEach(function(){         
              $('#tabel_retur_masuk').append('<tr class="tbl_modal_row" id="row'+i+'">'
                              +'<td><input type="text" name="fieldNamaBarangReturMasuk[]" value="'+data.data_isi[key-1].nama_barang+'" id="field_nama_barang_retur_masuk" class="form-control" readonly>'
                              +'<input type="hidden" name="fieldIdBarangReturMasuk[]" value="'+data.data_isi[key-1].id_barang+'" id="field_id_barang_retur_masuk" class="form-control"></td>'
                              +'<input type="hidden" name="fieldIdReturOut[]" value="'+data.data_isi[key-1].id_retur_keluar+'" id="field_id_retur_out" class="form-control" readonly>'
                              +'<td><input type="text" name="fieldNamaSatuanReturMasuk[]" value="'+data.data_isi[key-1].nama_satuan+'" id="field_nama_satuan_retur_masuk" class="form-control" readonly>'
                              +'<input type="hidden" name="fieldIdSatuanReturMasuk[]" value="'+data.data_isi[key-1].id_satuan+'" id="field_id_satuan_retur_masuk" class="form-control"></td>'
                              +'<td><input type="text" name="fieldJumlahBarangReturMasuk[]" value="'+data.data_isi[key-1].qty_retur_masuk+'" id="field_jumlah_barang_retur_masuk" class="form-control" required></td>'
                              +'<td><input type="text" name="fieldKeteranganBarangReturMasuk[]" value="'+data.data_isi[key-1].keterangan_retur_masuk+'" id="field_keterangan_barang_retur_masuk" class="form-control"></td>'
                              +'<td><button name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td>'
                              +'</tr>');
              key++;  
              i = randString(5);
            });

            // select class modal apabila bs.modal hidden
            $(".modal").on("hidden.bs.modal", function(){
              //reset form value on modals
              $('#form')[0].reset(); 
              $('tr').remove('.tbl_modal_row');
              $('#form_id_retur_keluar').attr("disabled", false);
              $('.form-group').removeClass('has-error');//clear error class
              $('.help-block').empty(); //clear error string
            });
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Gagal request data dari server');
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

function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;
    var tipe_simpan;

    if(save_method == 'add') {
        url = "<?php echo site_url('retur_masuk/add_retur_masuk')?>";
        tipe_simpan = 'tambah';
    } else {
        url = "<?php echo site_url('retur_masuk/update_retur_masuk')?>";
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

                $('#modal_form_retur_masuk').modal('hide'); 
                //call function
                reload_table();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 
        }
    });
}

function deleteReturMasuk(id)
{
    if(confirm('Apakah anda yakin hapus data ini?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('retur_masuk/delete_retur_masuk')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_form_retur_masuk').modal('hide');
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
</script>	