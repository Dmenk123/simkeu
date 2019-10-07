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
    table = $('#tabelInbox').DataTable({
        
        "processing": true, //feature control the processing indicator
        "serverSide": true, //feature control DataTables server-side processing mode
        "order":[], //initial no order

        //load data for table content from ajax source
        "ajax": {
            "url": "<?php echo site_url('inbox/inbox_list') ?>/" + idUser,
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

function detailInbox(id)
{
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('inbox/detail_inbox/')?>" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            //ambil data ke json->modal
            var idPesan = "replyInbox("+(data.id_pesan)+")";
            $('[name="idPesan"]').val(data.id_pesan);
            $('[name="pengirim"]').val(data.id_user);
            $('[name="pengirim"]').attr('disabled', '');
            $('[name="subjectInbox"]').val(data.subject_pesan);
            $('[name="subjectInbox"]').attr('disabled', '');
            $('[name="isiInbox"]').val(data.isi_pesan);
            $('[name="isiInbox"]').attr('disabled', '');
            $('#btnReply').attr('onclick', idPesan);
            $('#modal_detail_inbox').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Detail Inbox Pesan'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function replyInbox(id)
{
    $('#modal_detail_inbox').modal('hide');
    $('#form')[0].reset(); //reset form on modals
    $('.form-group').removeClass('has-error');//clear error class
    $('.help-block').empty(); //clear error string
    var re = "Reply from :";
    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('inbox/detail_inbox/')?>" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            //ambil data ke json->modal
            $('[name="idInbox"]').val(data.id_pesan);
            $('[name="sendToInboxTxt"]').val(data.nama_lengkap_user);
            $('[name="sendToInboxTxt"]').attr('readonly', true);
            $('[name="sendToInbox"]').val(data.id_user);
            $('[name="sendToInbox"]').attr('readonly', true);

            //buat variabel str u/ menyimpan data subject
            var str = data.subject_pesan;
            // cek string di variabel str, lalu lakukan aksi condisional
            // bernilai -1 apbila tidak ditemukan string, note CASE SENSITIVE string
            // This works because indexOf() returns -1 if the string wasn't found at all.
            if (str.indexOf("Reply from :") == -1) {
                 $('[name="subjectPesanInbox"]').val(re+" "+ data.subject_pesan);
             }else{
                 $('[name="subjectPesanInbox"]').val(data.subject_pesan);
             }

            $('[name="subjectPesanInbox"]').attr('readonly', true);
            $('#modal_reply_inbox').modal('show');
            $('.modal-title').text('Balas Pesan');

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
    url = "<?php echo site_url('inbox/reply_inbox')?>";
    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form_balas').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                alert(data.pesan_tambah); 
                $('#modal_reply_inbox').modal('hide');
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

</script>	