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
    $('#form_id_penerimaan').empty(); 
    $('#form_tanggal_retur_keluar').datepicker('setDate', null); 
    $('#form_nama_supplier').empty(); 
    $('.form-group').removeClass('has-error');//clear error class
    $('.help-block').empty(); //clear error string
  });

  //datatables  
	table = $('#tabelReturKeluar').DataTable({
		
		"processing": true, //feature control the processing indicator
		"serverSide": true, //feature control DataTables server-side processing mode
		"order":[[ 0, 'desc' ]], //index for order, 0 is first column

		//load data for table content from ajax source
		"ajax": {
			"url": "<?php echo site_url('retur_keluar/list_retur_keluar') ?>",
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
	$('#form_tanggal_retur_keluar').datepicker({
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
  $( "#form_id_penerimaan" ).select2({
    ajax: {
        url: '<?php echo site_url('retur_keluar/suggest_id_penerimaan'); ?>',
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
  $('#form_id_penerimaan').change(function(){
    var idTransMasuk = $('#form_id_penerimaan').val();
    //var tblRows = $('#tabel_pembelian tr').length;
    //remove tr before appending
    $('tr').remove('.tbl_modal_row');
      $.ajax({
      url: '<?php echo site_url('retur_keluar/list_penerimaan'); ?>',
      type: 'POST',
      dataType: 'json',
      async : false,
      data : { idTransMasuk : idTransMasuk},
      success : function(data) {
          var i = randString(5);
          var key = 1;
          Object.keys(data.data_list).forEach(function(){
              $('#form_nama_supplier').val(data.data_supplier[0].nama_supplier);
              $('#form_id_supplier').val(data.data_supplier[0].id_supplier);   
              $('#tabel_retur_keluar').append('<tr class="tbl_modal_row" id="row'+i+'">'
                              +'<td><input type="text" name="fieldNamaBarangReturKeluar[]" value="'+data.data_list[key-1].nama_barang+'" id="field_nama_barang_retur_keluar" class="form-control" readonly>'
                              +'<input type="hidden" name="fieldIdBarangReturKeluar[]" value="'+data.data_list[key-1].id_barang+'" id="field_id_barang_retur_keluar" class="form-control"></td>'
                              +'<td><input type="text" name="fieldNamaSatuanReturKeluar[]" value="'+data.data_list[key-1].nama_satuan+'" id="field_nama_satuan_retur_keluar" class="form-control" readonly>'
                              +'<input type="hidden" name="fieldIdSatuanReturKeluar[]" value="'+data.data_list[key-1].id_satuan+'" id="field_id_satuan_retur_keluar" class="form-control"></td>'
                              +'<td><input type="text" name="fieldJumlahBarangReturKeluar[]" value="'+data.data_list[key-1].qty_masuk+'" id="field_jumlah_barang_retur_keluar" class="form-control"></td>'
                              +'<td><input type="text" name="fieldKeteranganBarangReturKeluar[]" value="'+data.data_list[key-1].keterangan_masuk+'" id="field_keterangan_barang_retur_keluar" class="form-control"></td>'
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

function addReturKeluar() 
{
	save_method = 'add';
  $('[name="fieldTanggalReturKeluar"]').attr("disabled", false);
  $('#form_id_retur_keluar').attr("disabled", false);
	$('#form')[0].reset(); //reset form on modals
	$('.form-group').removeClass('has-error');//clear error class
  $('.form-control').removeClass('has-error');//clear error class
	$('.help-block').empty(); //clear error string
	$('#modal_form_retur_keluar').modal('show'); //show bootstrap modal
	$('.modal-title').text('Tambah Pengeluaran Barang Retur'); //set title modal
  $.ajax({
        url : "<?php echo site_url('retur_keluar/ajax_get_header_form/')?>",
        type: "GET",
        dataType: "JSON",
        success: function(data)
          {
            //header
            $('[name="fieldIdReturKeluar"]').val(data.kode_retur_keluar);
          }
  });
}

function editReturKeluar(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form_retur_keluar').modal('show'); // show bootstrap modal when complete loaded
    $('.modal-title').text('Edit Pengeluaran Barang Retur');
    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('retur_keluar/edit_retur_keluar')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            /*alert(rec); //mkyong*/
            //ambil data ke json->modal
            //header
            $('[name="fieldIdReturKeluar"]').val(data.data_header[0].id_retur_keluar);
            $('[name="fieldTanggalReturKeluar"]').val(data.data_header[0].tgl_retur_keluar);
            $('[name="fieldUserReturKeluar"]').val(data.data_header[0].username);
            $('[name="fieldIdUserReturKeluar"]').val(data.data_header[0].id_user);
            $('[name="fieldNamaSupplier"]').val(data.data_header[0].nama_supplier);
            $('[name="fieldIdSupplier"]').val(data.data_header[0].id_supplier);
      
            $selectedIdMasuk = $("<option></option>").val(data.data_header[0].id_trans_masuk).text(data.data_header[0].id_trans_masuk);
            //tanpa trigger event
            $("#form_id_penerimaan").append($selectedIdMasuk);
    
            //set attribute
            // $('[name="fieldTanggalMasuk"]').attr("disabled", true);
            $('#form_id_penerimaan').attr("disabled", true); 

            //isi
            var i = randString(5);
            var key = 1;
            Object.keys(data.data_isi).forEach(function(){         
              $('#tabel_retur_keluar').append('<tr class="tbl_modal_row" id="row'+i+'">'
                              +'<td><input type="text" name="fieldNamaBarangReturKeluar[]" value="'+data.data_isi[key-1].nama_barang+'" id="field_nama_barang_retur_keluar" class="form-control" readonly>'
                              +'<input type="hidden" name="fieldIdBarangReturKeluar[]" value="'+data.data_isi[key-1].id_barang+'" id="field_id_barang_retur_keluar" class="form-control"></td>'
                              +'<input type="hidden" name="fieldIdMasuk[]" value="'+data.data_isi[key-1].id_trans_masuk+'" id="field_id_trans_masuk" class="form-control" readonly>'
                              +'<td><input type="text" name="fieldNamaSatuanReturKeluar[]" value="'+data.data_isi[key-1].nama_satuan+'" id="field_nama_satuan_retur_keluar" class="form-control" readonly>'
                              +'<input type="hidden" name="fieldIdSatuanReturKeluar[]" value="'+data.data_isi[key-1].id_satuan+'" id="field_id_satuan_retur_keluar" class="form-control"></td>'
                              +'<td><input type="text" name="fieldJumlahBarangReturKeluar[]" value="'+data.data_isi[key-1].qty_retur_keluar+'" id="field_jumlah_barang_retur_keluar" class="form-control" required></td>'
                              +'<td><input type="text" name="fieldKeteranganBarangReturKeluar[]" value="'+data.data_isi[key-1].keterangan_retur_keluar+'" id="field_keterangan_barang_retur_keluar" class="form-control"></td>'
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
              $('#form_id_penerimaan').attr("disabled", false);
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
        url = "<?php echo site_url('retur_keluar/add_retur_keluar')?>";
        tipe_simpan = 'tambah';
    } else {
        url = "<?php echo site_url('retur_keluar/update_retur_keluar')?>";
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

                $('#modal_form_retur_keluar').modal('hide'); 
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

function deleteReturKeluar(id)
{
    if(confirm('Apakah anda yakin hapus data ini?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('retur_keluar/delete_retur_keluar')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_form_retur_keluar').modal('hide');
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