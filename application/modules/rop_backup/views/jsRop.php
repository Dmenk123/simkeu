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

    //monthpicker
    $('#tgl_rop').MonthPicker({ 
        Button: false,
        //MaxMonth: -1
        MaxMonth: 0,
        MonthFormat: "yy-mm",
    });
    
    //force integer input in textfield
    $('input.numberinput').bind('keypress', function (e) {
        return (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57) && e.which != 46) ? false : true;
    });

   //select2
    $( "#field_barang_rop" ).select2({        
        ajax: {
        url: '<?php echo site_url('rop/suggest_barang'); ?>',
        dataType: 'json',
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

    
    // event onchange to modify select2 nama_personil content
    $('#field_index_tampil').change(function(){
        $('#field_tampil_data').empty(); 
        var jenis= $('#field_index_tampil').val();
        $( "#field_tampil_data" ).select2({ 
          ajax: {
                url: '<?php echo site_url('Laporan_history_beli/suggest_tampil_data'); ?>/'+ jenis,
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
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
    
});

setInterval(function(){
    $("#load_row").load('<?=base_url()?>pesan/load_row_notif')
}, 2000); //menggunakan setinterval jumlah notifikasi akan selalu update setiap 2 detik diambil dari controller notifikasi fungsi load_row
 
setInterval(function(){
    $("#load_data").load('<?=base_url()?>pesan/load_data_notif')
}, 2000); //yang ini untuk selalu cek isi data notifikasinya sama setiap 2 detik diambil dari controller notifikasi fungsi load_data

function update_ss() 
{
   /* $('#form')[0].reset(); //reset form on modals
    $('.form-group').removeClass('has-error');//clear error class
    $('.help-block').empty(); //clear error string*/
    $('#modal_update_ss').modal('show'); //show bootstrap modal

    //modal ss
    var nama = $('#val_nama_brg').text();
    var id_brg = $('#val_id_brg').text();
    var ss = $('#val_ss').text();
    $('#nama_barang').val(nama);
    $('#id_barang').val(id_brg);
    $('#ss_barang').val(ss);
    $('#nama_barang').attr('readonly', '');
    $('#id_barang').attr('readonly', '');
    $('#ss_barang').attr('readonly', '');
}

function update_rop() 
{
    /*$('#form')[0].reset(); //reset form on modals
    $('.form-group').removeClass('has-error');//clear error class
    $('.help-block').empty(); //clear error string*/
    $('#modal_update_rop').modal('show'); //show bootstrap modal

    //modal ss
    var nama = $('#val_nama_brg').text();
    var id_brg = $('#val_id_brg').text();
    var rop = $('#val_rop').text();
    $('#nama_barang_rop').val(nama);
    $('#id_barang_rop').val(id_brg);
    $('#rop_barang').val(rop);
    $('#nama_barang_rop').attr('readonly', '');
    $('#id_barang_rop').attr('readonly', '');
    $('#rop_barang').attr('readonly', '');
}

function save_ss()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    
    url = "<?php echo site_url('rop/ss_update')?>";
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
                alert(data.pesan_update);
                $('#modal_update_ss').modal('hide');
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
            alert('Error update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 

        }
    });
}

function save_rop()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    
    url = "<?php echo site_url('rop/rop_update')?>";
    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#formRop').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                alert(data.pesan_update);
                $('#modal_update_rop').modal('hide');
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
            alert('Error update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 

        }
    });
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

</script>	