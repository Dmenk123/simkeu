<!--  DataTables --> 
<script src="<?=config_item('assets')?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?=config_item('assets')?>plugins/datatables/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
	var save_method; //for save method string
	var table;

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
       $('#tabel_pembelian').append('<tr class="tbl_modal_row" id="row'+i+'">'
                              +'<td><input type="text" name="fieldNamaBarangOrder[]" value="'+ambilNama+'" id="field_nama_barang_order" class="form-control" required readonly>'
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
    $('#form_id_pembelian').empty(); 
    $('#form_nama_supplier').empty();
    $('#form_tanggal_masuk').datepicker('setDate', null); 
    $('.form-group').removeClass('has-error');//clear error class
    $('.help-block').empty(); //clear error string
  });

  //datatables  
	table = $('#tabelTransMasuk').DataTable({
		
		"processing": true, //feature control the processing indicator
		"serverSide": true, //feature control DataTables server-side processing mode
		"order":[[ 0, 'desc' ]], //index for order, 0 is first column

		//load data for table content from ajax source
		"ajax": {
			"url": "<?php echo site_url('trans_masuk/list_trans_masuk') ?>",
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
  $('#tabelTransMasukDetail').DataTable({
  });

  //datepicker
	$('#form_tanggal_masuk').datepicker({
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
  $( "#form_id_pembelian" ).select2({
    ajax: {
        url: '<?php echo site_url('trans_masuk/suggest_id_pembelian'); ?>',
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
  $('#form_id_pembelian').change(function(){
    var idTransBeli = $('#form_id_pembelian').val();
    //var tblRows = $('#tabel_pembelian tr').length;
    //remove tr before appending
    $('tr').remove('.tbl_modal_row');
      $.ajax({
      url: '<?php echo site_url('trans_masuk/list_pembelian'); ?>',
      type: 'POST',
      dataType: 'json',
      async : false,
      data : { idTransBeli : idTransBeli},
      success : function(data) {
          var i = randString(5);
          var key = 1;
          Object.keys(data.data_list).forEach(function(){
              $('#form_nama_supplier').val(data.data_supplier[0].nama_supplier);
              $('#form_id_supplier').val(data.data_supplier[0].id_supplier);   
              $('#tabel_penerimaan').append('<tr class="tbl_modal_row" id="row'+i+'">'
                              +'<td><input type="text" name="fieldNamaBarangMasuk[]" value="'+data.data_list[key-1].nama_barang+'" id="field_nama_barang_masuk" class="form-control" readonly>'
                              +'<input type="hidden" name="fieldIdTransBeliDetail[]" value="'+data.data_list[key-1].id_trans_beli_detail+'" id="field_id_trans_beli_detail" class="form-control" readonly>'
                              +'<input type="hidden" name="fieldIdBarangMasuk[]" value="'+data.data_list[key-1].id_barang+'" id="field_id_barang_masuk" class="form-control"></td>'
                              +'<td><input type="text" name="fieldNamaSatuanMasuk[]" value="'+data.data_list[key-1].nama_satuan+'" id="field_nama_satuan_masuk" class="form-control" readonly>'
                              +'<input type="hidden" name="fieldIdSatuanMasuk[]" value="'+data.data_list[key-1].id_satuan+'" id="field_id_satuan_masuk" class="form-control"></td>'
                              +'<td><input type="text" name="fieldJumlahBarangMasuk[]" value="'+data.data_list[key-1].qty_beli+'" id="field_jumlah_barang_masuk" class="form-control"></td>'
                              +'<td><input type="text" name="fieldKeteranganBarangMasuk[]" value="'+data.data_list[key-1].keterangan_beli+'" id="field_keterangan_barang_masuk" class="form-control"></td>'
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

  //fix to issue select2 on modal when opening in firefox, thanks to github
  $.fn.modal.Constructor.prototype.enforceFocus = function() {};

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
  
});

setInterval(function(){
    $("#load_row").load('<?=base_url()?>pesan/load_row_notif')
}, 2000); //menggunakan setinterval jumlah notifikasi akan selalu update setiap 2 detik diambil dari controller notifikasi fungsi load_row
 
setInterval(function(){
    $("#load_data").load('<?=base_url()?>pesan/load_data_notif')
}, 2000); //yang ini untuk selalu cek isi data notifikasinya sama setiap 2 detik diambil dari controller notifikasi fungsi load_data

function addTransMasuk() 
{
	save_method = 'add';
  $('[name="fieldTanggalMasuk"]').attr("disabled", false);
  $('#form_id_pembelian').attr("disabled", false);
	$('#form')[0].reset(); //reset form on modals
	$('.form-group').removeClass('has-error');//clear error class
  $('.form-control').removeClass('has-error');//clear error class
	$('.help-block').empty(); //clear error string
	$('#modal_form_barang_masuk').modal('show'); //show bootstrap modal
	$('.modal-title').text('Transaksi Penerimaan Barang'); //set title modal
  $.ajax({
        url : "<?php echo site_url('trans_masuk/ajax_get_header_form/')?>",
        type: "GET",
        dataType: "JSON",
        success: function(data)
          {
            //header
            $('[name="fieldIdMasuk"]').val(data.kode_trans_beli);
          }
  });
}

function editTransMasuk(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form_barang_masuk').modal('show'); // show bootstrap modal when complete loaded
    $('.modal-title').text('Edit Penerimaan Barang');
    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('trans_masuk/edit_trans_masuk')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            /*alert(rec); //mkyong*/
            //ambil data ke json->modal
            //header
            $('[name="fieldIdMasuk"]').val(data.data_header[0].id_trans_masuk);
            $('[name="fieldTanggalMasuk"]').val(data.data_header[0].tgl_trans_masuk);
            $('[name="fieldUserMasuk"]').val(data.data_header[0].username);
            $('[name="fieldIdUserMasuk"]').val(data.data_header[0].id_user);
            $('[name="fieldNamaSupplier"]').val(data.data_header[0].nama_supplier);
            $('[name="fieldIdSupplier"]').val(data.data_header[0].id_supplier);
      
            $selectedIdBeli = $("<option></option>").val(data.data_header[0].id_trans_beli).text(data.data_header[0].id_trans_beli);
            //tanpa trigger event
            $("#form_id_pembelian").append($selectedIdBeli);
    
            //set attribute
            // $('[name="fieldTanggalMasuk"]').attr("disabled", true);
            $('#form_id_pembelian').attr("disabled", true); 

            //isi
            var i = randString(5);
            var key = 1;
            Object.keys(data.data_isi).forEach(function(){         
              $('#tabel_penerimaan').append('<tr class="tbl_modal_row" id="row'+i+'">'
                              +'<td><input type="text" name="fieldNamaBarangMasuk[]" value="'+data.data_isi[key-1].nama_barang+'" id="field_nama_barang_masuk" class="form-control" readonly>'
                              +'<input type="hidden" name="fieldIdBeli[]" value="'+data.data_isi[key-1].id_trans_beli+'" id="field_id_trans_beli" class="form-control" readonly>'
                              +'<input type="hidden" name="fieldIdTransBeliDetail[]" value="'+data.data_isi[key-1].id_trans_beli_detail+'" id="field_id_trans_beli_detail" class="form-control" readonly>'
                              +'<input type="hidden" name="fieldIdBarangMasuk[]" value="'+data.data_isi[key-1].id_barang+'" id="field_id_barang_masuk" class="form-control"></td>'
                              +'<td><input type="text" name="fieldNamaSatuanMasuk[]" value="'+data.data_isi[key-1].nama_satuan+'" id="field_nama_satuan_masuk" class="form-control" readonly>'
                              +'<input type="hidden" name="fieldIdSatuanMasuk[]" value="'+data.data_isi[key-1].id_satuan+'" id="field_id_satuan_masuk" class="form-control"></td>'
                              +'<td><input type="text" name="fieldJumlahBarangMasuk[]" value="'+data.data_isi[key-1].qty_masuk+'" id="field_jumlah_barang_masuk" class="form-control" required></td>'
                              +'<td><input type="text" name="fieldKeteranganBarangMasuk[]" value="'+data.data_isi[key-1].keterangan_masuk+'" id="field_keterangan_barang_masuk" class="form-control"></td>'
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
        url = "<?php echo site_url('trans_masuk/add_trans_masuk')?>";
        tipe_simpan = 'tambah';
    } else {
        url = "<?php echo site_url('trans_masuk/update_trans_masuk')?>";
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

                $('#modal_form_barang_masuk').modal('hide'); 
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

function deleteTransMasuk(id)
{
    if(confirm('Apakah anda yakin hapus data ini?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('trans_masuk/delete_trans_masuk')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_form_barang_masuk').modal('hide');
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