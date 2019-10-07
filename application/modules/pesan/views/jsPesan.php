<!-- ambil uri segment 3 -->
<?php $val_url = $this->session->userdata('id_user'); ?>
<!-- DataTables -->
<script src="<?=config_item('assets')?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?=config_item('assets')?>plugins/datatables/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
	var save_method; //for save method string
	var table;
    var idUser = "<?php echo $val_url; ?>";
$(document).ready(function() {
	//datatables
    //tabelFeedback
    table = $('#tabelPesan').DataTable({
        
        "processing": true, //feature control the processing indicator
        "serverSide": true, //feature control DataTables server-side processing mode
        "order":[], //initial no order

        //load data for table content from ajax source
        "ajax": {
            "url": "<?php echo site_url('pesan/pesan_list') ?>/" + idUser,
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
});	

setInterval(function(){
    $("#load_row").load('<?=base_url()?>pesan/load_row_notif')
}, 2000); //menggunakan setinterval jumlah notifikasi akan selalu update setiap 2 detik diambil dari controller notifikasi fungsi load_row
 
setInterval(function(){
    $("#load_data").load('<?=base_url()?>pesan/load_data_notif')
}, 2000); //yang ini untuk selalu cek isi data notifikasinya sama setiap 2 detik diambil dari controller notifikasi fungsi load_data

function addPesan() 
	{
		save_method = 'add'; //Properti untuk menentukan method
        $('[name="sendTo"]').removeAttr('disabled', '');
        $('[name="subjectPesan"]').removeAttr('disabled', '');
        $('[name="isiPesan"]').removeAttr('disabled', '');
        $('#btnSave').removeAttr('disabled', '');
		$('#form')[0].reset(); //reset form on modals
		$('.form-group').removeClass('has-error');//clear error class
		$('.help-block').empty(); //clear error string
		$('#modal_pesan').modal('show'); //show bootstrap modal
		$('.modal-title').text('Tulis Pesan'); //set title modal
	}

function detailPesan(id)
{
    save_method = 'update'; //Properti untuk menentukan method
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('pesan/detail_pesan/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            //ambil data ke json->modal
            $('[name="IdPesan"]').val(data.id_pesan);
            $('[name="idUser"]').val(data.id_user);
            $('[name="sendTo"]').val(data.id_user_target);
            $('[name="sendTo"]').attr('disabled', '');
            $('[name="subjectPesan"]').val(data.subject_pesan);
            $('[name="subjectPesan"]').attr('disabled', '');
            $('[name="isiPesan"]').val(data.isi_pesan);
            $('[name="isiPesan"]').attr('disabled', '');
            $('#btnSave').attr('disabled', '');
            $('#modal_pesan').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Detail Pesan Terkirim'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
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

    if(save_method == 'add') {
        url = "<?php echo site_url('pesan/add_pesan')?>";
        tipe_simpan = 'tambah';
    } else {
        url = "<?php echo site_url('pesan/update_pesan')?>";
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

                $('#modal_pesan').modal('hide');
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

function deletePesan(id)
{
    if(confirm('Yakin Hapus data ini ?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('pesan/delete_pesan')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                //$('#modal_feedback').modal('hide');
                alert(data.pesan);
                reload_table();
                reload_table2();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
}

</script>	