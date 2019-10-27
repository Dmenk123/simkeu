<!-- DataTables -->
<script src="<?=config_item('assets')?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?=config_item('assets')?>plugins/datatables/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
	var save_method; //for save method string
	var table;

$(document).ready(function() {
	//datatables
	table = $('#tabelGuru').DataTable({
		
		"processing": true, //feature control the processing indicator
		"serverSide": true, //feature control DataTables server-side processing mode
		"order":[], //initial no order

		//load data for table content from ajax source
		"ajax": {
			"url": "<?php echo site_url('master_guru/list_guru') ?>",
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
    $( ".jabatan" ).select2({ 
        ajax: {
          url: '<?php echo site_url('master_guru/suggest_jabatan'); ?>/',
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

    $.dobPicker({
        // Selectopr IDs
        daySelector: '#dobday',
        monthSelector: '#dobmonth',
        yearSelector: '#dobyear',

        // Default option values
        dayDefault: 'Hari',
        monthDefault: 'Bulan',
        yearDefault: 'Tahun',

        // Minimum age
        minimumAge: 12,

        // Maximum age
        maximumAge: 80
    });

    $(".gambar").change(function() {
      //console.log(this);
      var id = this.id;
      readURL(this, id);
    });

	//set input/textarea/select event when change value, remove class error and remove text help block
	$("input").change(function() {
		$(this).parent().parent().removeClass('has-error');
		$(this).next().empty();
	});

});	


function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function save(save_method)
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('master_guru/add_data')?>";
    } else {
        url = "<?php echo site_url('master_guru/update_data')?>";
    }

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form_input').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                window.location.replace("<?=base_url('/master_guru');?>");
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

function readURL(input, id) {
  var idImg = id +'-img';
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    console.log(reader);
    reader.onload = function(e) {
      $('#'+ idImg).attr('src', e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]);
  }
}

</script>	