<!-- DataTables -->
<script src="<?=config_item('assets')?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?=config_item('assets')?>plugins/datatables/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
	var save_method; //for save method string
	var table;

$(document).ready(function() {
	//datatables
	table = $('#tabelSetGaji').DataTable({
		
		"processing": true, //feature control the processing indicator
		"serverSide": true, //feature control DataTables server-side processing mode
		"order":[], //initial no order

		//load data for table content from ajax source
		"ajax": {
			"url": "<?php echo site_url('set_gaji_guru/list_data') ?>",
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

    //select2
    $("#jabatan").select2({
        ajax: {
            url: '<?php echo site_url('set_gaji_guru/suggest_jabatan'); ?>',
            dataType: 'json',
            type: "GET",
            data: function (params) {
                var queryParameters = {
                    term: params.term
                }
                return queryParameters;
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.text,
                            id: item.id
                        }
                    })
                };
            },
            cache: true
        }, 
    });

    $('#jabatan').on('change', function() {
        var data = $("#jabatan option:selected").val();
        //Ajax Load data from ajax
        $.ajax({
            url : "<?php echo site_url('set_gaji_guru/get_tunjangan')?>/" + data,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
                $('#tunjangan').maskMoney('mask', parseInt(data.tunjangan));
                $("#tunjangan_raw").val(data.tunjangan);

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });

    });

    //mask money
    $('.mask-currency').maskMoney();

	//set input/textarea/select event when change value, remove class error and remove text help block
	$("input").change(function() {
		$(this).parent().parent().removeClass('has-error');
		$(this).next().empty();
	});

});	

function add_data() 
{
    save_method = 'add';
    $('#form')[0].reset(); //reset form on modals
    $('.form-group').removeClass('has-error');//clear error class
    $('.help-block').empty(); //clear error string
    $('#modal_form').modal('show'); //show bootstrap modal
    $('.modal-title').text('Add Setting Gaji'); //set title modal
}

function edit_jabatan(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('master_jabatan/edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            //ambil data ke json->modal
            $('[name="id"]').val(data.id);
            $('[name="nama"]').val(data.nama);
            $('[name="tunjangan"]').maskMoney('mask', parseInt(data.tunjangan));
            $('[name="tunjangan_raw"]').val(data.tunjangan);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Master Jabatan'); // Set title to Bootstrap modal title

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
        url = "<?php echo site_url('set_gaji_guru/add_data')?>";
        tipe_simpan = 'tambah';
    } else {
        url = "<?php echo site_url('set_gaji_guru/update_data')?>";
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
                alert(data.pesan);
                $('#modal_form').modal('hide');
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

function delete_jabatan(id)
{
    if(confirm('Yakin Hapus Data Ini ?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('master_jabatan/delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_form').modal('hide');
                alert(data.pesan);
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
}


function setTunjanganRaw() {  
  var harga = $('#tunjangan').maskMoney('unmasked')[0];
  //set harga raw
  $('#tunjangan_raw').val(harga);
} 

function setGapokRaw() {
    var harga = $('#gapok').maskMoney('unmasked')[0];
    //set harga raw
    $('#gapok_raw').val(harga);
}

function setGaperjamRaw() {
    var harga = $('#gaperjam').maskMoney('unmasked')[0];
    //set harga raw
    $('#gaperjam_raw').val(harga);
}
</script>	