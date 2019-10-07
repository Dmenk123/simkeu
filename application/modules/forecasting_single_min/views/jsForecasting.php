<!-- DataTables -->
<script src="<?=config_item('assets')?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?=config_item('assets')?>plugins/datatables/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    //datatables
    table = $('#tabelforecasting').DataTable({
        
        "processing": true, //feature control the processing indicator
        "serverSide": true, //feature control DataTables server-side processing mode
        "order":[[ 0, 'desc' ]], //index for order, 0 is first column

        //load data for table content from ajax source
        "ajax": {
            "url": "<?php echo site_url('forecasting/list_peramalan') ?>",
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

    //monthpicker
    $('#bln_ramal').MonthPicker({ 
        Button: false,
        //MaxMonth: -1
        MaxMonth: +1,
        MonthFormat: "yy-mm",
    });
    
    //force integer input in textfield
    $('input.numberinput').bind('keypress', function (e) {
        return (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57) && e.which != 46) ? false : true;
    });

    //select2
    $( "#field_barang_ramal" ).select2({
        placeholder: "Pilih Nama Barang",
        allowClear: true,
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

    $( "#field_periode_forecast" ).select2({
        placeholder: "Pilih periode",
        allowClear: true,
    });

    //reset select2
    $('#btn_reset_f1').click(function(event) {
        $("#field_barang_ramal").val('').trigger('change');
        $("#field_periode_forecast").val('').trigger('change');
    });

    //autofill
    var fill = true;
    var alpha = $('#field_alpha').val();
    if (alpha != null) {
        var alpha = $('#field_alpha').val();
        var blnIndex = $('#bln_index').val();
        var idBarang = $('#id_barang').val();
        var periodeForecast = $('#periode_forecast').val();
        var totalColSpan = parseInt(periodeForecast) + 1;
        //set button to disable
        $('#btnSave').attr('disabled',false);
        //remove tr before appending
        $('tr').remove('#row_peramalan');
        //remove id canvas before appending
        $('#canvas').remove();
        //append canvas in div.grafik
        $('.grafik').append('<canvas id="canvas" width="1000" height="180"></canvas>');
        $.ajax({
            url: '<?php echo site_url('forecasting/peramalan'); ?>',
            type: 'POST',
            dataType: 'json',
            async : false,
            data : { alpha : alpha, blnIndex : blnIndex, idBarang : idBarang, periodeForecast : periodeForecast },
            success : function(data) {
                var key = 1;
                //append tabel
                $('#tbl_peramalan').append('<tr class="tbl_s1t_row" id="row_peramalan">'
                                        +'<th>'
                                            +'<a href="#" data-toggle="modal" data-target="#modal_help_s1t"><span style="font-size: 14px">s1t</span></a>'
                                        +'</th></tr>');
                $('#tbl_peramalan').append('<tr class="tbl_s2t_row" id="row_peramalan">'
                                        +'<th>'
                                            +'<a href="#" data-toggle="modal" data-target="#modal_help_s2t"><span style="font-size: 14px">s2t</span></a>'
                                        +'</th></tr>');
                $('#tbl_peramalan').append('<tr class="tbl_at_row" id="row_peramalan">'
                                        +'<th>'
                                            +'<a href="#" data-toggle="modal" data-target="#modal_help_at"><span style="font-size: 14px">at</span></a>'
                                        +'</th></tr>');
                $('#tbl_peramalan').append('<tr class="tbl_bt_row" id="row_peramalan">'
                                        +'<th>'
                                            +'<a href="#" data-toggle="modal" data-target="#modal_help_bt"><span style="font-size: 14px">bt</span></a>'
                                        +'</th></tr>');
                $('#tbl_peramalan').append('<tr class="tbl_ft_row" id="row_peramalan">'
                                        +'<th>'
                                            +'<a href="#" data-toggle="modal" data-target="#modal_help_ft"><span style="font-size: 14px">ft</span></a>'
                                        +'</th></tr>');
                $('#tbl_peramalan').append('<tr class="tbl_ae_row" id="row_peramalan">'
                                        +'<th>'
                                            +'<a href="#" data-toggle="modal" data-target="#modal_help_ae"><span style="font-size: 14px">ae</span></a>'
                                        +'</th></tr>');
                $('#tbl_peramalan').append('<tr class="tbl_sqe_row" id="row_peramalan">'
                                        +'<th>'
                                            +'<a href="#" data-toggle="modal" data-target="#modal_help_sqe"><span style="font-size: 14px">sqe</span></a>'
                                        +'</th></tr>');
                $('#tbl_peramalan').append('<tr class="tbl_ape_row" id="row_peramalan">'
                                        +'<th>'
                                            +'<a href="#" data-toggle="modal" data-target="#modal_help_ape"><span style="font-size: 14px">ape</span></a>'
                                        +'</th></tr>');
                //append hasil
                $('#tbl_peramalan').append('<tr class="tbl_alpha_row" id="row_peramalan">'
                                        +'<td colspan="'+totalColSpan+'"><strong>'
                                            +'<a href="#" data-toggle="modal" data-target="#modal_help_alpha"><span style="font-size: 14px">Nilai Alpha (α) yang digunakan</span></a>'
                                                +' = <span class="hasil_alpha_min">'+data.alpha_min+'</span>'
                                            +'</strong></td>');
                $('#tbl_peramalan').append('<tr class="tbl_ftp1p_row" id="row_peramalan">'
                                        +'<td colspan="'+totalColSpan+'"><strong>'
                                            +'<a href="#" data-toggle="modal" data-target="#modal_help_ft1"><span style="font-size: 14px">Hasil Peramalan Barang (Ft+1p)</span></a>'
                                                +' = <span class="hasil_peramalan">'+data.peramalan+'</span>'
                                            +'</strong></td>');
                $('#tbl_peramalan').append('<tr class="tbl_mad_row" id="row_peramalan">'
                                        +'<td colspan="'+totalColSpan+'"><strong>'
                                            +'<a href="#" data-toggle="modal" data-target="#modal_help_mad"><span style="font-size: 14px">Mean Absolute Deviation (MAD)</span></a>'
                                                +' = <span class="hasil_mad">'+data.mad+'</span>'
                                            +'</strong></td>');
                $('#tbl_peramalan').append('<tr class="tbl_mad_row" id="row_peramalan">'
                                        +'<td colspan="'+totalColSpan+'"><strong>'
                                            +'<a href="#" data-toggle="modal" data-target="#modal_help_mse"><span style="font-size: 14px">Mean Square Error (MSE)</span></a>'
                                                +' = <span class="hasil_mse">'+data.mse+'</span>'
                                            +'</strong></td>');
                $('#tbl_peramalan').append('<tr class="tbl_mad_row" id="row_peramalan">'
                                        +'<td colspan="'+totalColSpan+'"><strong>'
                                            +'<a href="#" data-toggle="modal" data-target="#modal_help_mape"><span style="font-size: 14px">Mean Absolute Percentage Error (MAPE)</span></a>'
                                                +' = <span class="hasil_mape">'+data.mape+' %</span>'
                                            +'</strong></td>');
                $('#tbl_peramalan').append('<tr class="tbl_note_row" id="row_peramalan">'
                                        +'<td colspan="'+totalColSpan+'"><strong>Catatan :</strong> Dalam penentuan nilai alpha pada program ini, dicari alpha yang menghasilkan nilai MAPE terkecil secara otomatis </td></tr>');
                //chart.js
                var ctx = document.getElementById("canvas").getContext("2d");
                var chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.md_grafik,
                        datasets: [{
                            label:  "real",
                            data: data.qty_grafik,
                            backgroundColor:  "rgba(93, 65, 205, 0.5)",
                            borderColor: "rgba(45, 7, 163, 1)",
                            pointBorderColor : "rgba(45, 7, 163, 1)",
                            borderWidth: 1
                        },
                        {
                            label:  "forecast",
                            data: data.ft_grafik,
                            backgroundColor:  "rgba(255, 23, 0, 0.8)",
                            borderColor: "rgba(255, 23, 0, 0.8)",
                            pointBorderColor : "rgba(255, 23, 0, 0.8)",
                            borderWidth: 1
                        }],
                    },
                    options: {
                        legend: {
                            labels: {
                            // This more specific font property overrides the global property
                            fontColor: 'black'
                            }
                        },  
                        scales: {
                            yAxes: [{
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Qty total',
                                    fontColor: "black"
                                },
                                ticks: {
                                    beginAtZero:true
                                }
                            }],
                            xAxes: [{
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Bulan-tahun',
                                    fontColor: "black"
                                },
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });

          //ambil key tiap array, berhubung count length tiap array sama, jadi pake slah satu array buat index key
          Object.keys(data.s1t).forEach(function(){
              $('.tbl_s1t_row').append('<td align="center">'+data.s1t[key-1]+'</td>');
              $('.tbl_s2t_row').append('<td align="center">'+data.s2t[key-1]+'</td>');
              $('.tbl_at_row').append('<td align="center">'+data.at[key-1]+'</td>');
              $('.tbl_bt_row').append('<td align="center">'+data.bt[key-1]+'</td>');
              $('.tbl_ft_row').append('<td align="center">'+data.ft[key-1]+'</td>');
              $('.tbl_ae_row').append('<td align="center">'+data.ae[key-1]+'</td>');
              $('.tbl_sqe_row').append('<td align="center">'+data.sqe[key-1]+'</td>');
              $('.tbl_ape_row').append('<td align="center">'+data.ape[key-1]+' %</td>');  
              key++;
            });
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Gagal request data dari server');
        }

        });
    }//end if
    
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

    //set interval load notif
    setInterval(function(){
        $("#load_row").load('<?=base_url()?>pesan/load_row_notif')
    }, 2000); //menggunakan setinterval jumlah notifikasi akan selalu update setiap 2 detik diambil dari controller notifikasi fungsi load_row
     
    //set interval load isi notif 
    setInterval(function(){
        $("#load_data").load('<?=base_url()?>pesan/load_data_notif')
    }, 2000); //yang ini untuk selalu cek isi data notifikasinya sama setiap 2 detik diambil dari controller notifikasi fungsi load_data

});//end .ready

function save_peramalan()
{
    if(confirm('Apakah anda yakin Menyimpan Data Peramalan ?')) {
        $('#btnSave').text('saving...'); //change button text
        $('#btnSave').attr('disabled',true); //set button disable 
        var alpha = $('.hasil_alpha_min').text();
        var idBarang = $('#id_barang').val();
        var idSatuan = $('#id_satuan').val();
        var tglRamal = $('#tgl_ramal').val();
        var hasilRamal = $('.hasil_peramalan').text();
        var hasilMad = $('.hasil_mad').text();
        var hasilMse = $('.hasil_mse').text();
        var hasilMape = $('.hasil_mape').text();
        var url = "<?php echo site_url('forecasting/simpan_data')?>";
      /*  alert(alpha);
        alert(idBarang);
        alert(hasilRamal);*/
        // ajax adding data to database
        $.ajax({
            url : url,
            type: "POST",
            data: { alpha : alpha, idBarang : idBarang, idSatuan : idSatuan, tglRamal : tglRamal, hasilRamal : hasilRamal, hasilMad : hasilMad, hasilMse : hasilMse, hasilMape : hasilMape},
            dataType: "JSON",
            success: function(data)
            {

                if(data.status) //if success close modal and reload ajax table
                {
                    alert(data.pesan_simpan);
                }
                else
                {
                    for (var i = 0; i < data.inputerror.length; i++) 
                    {
                        $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                        $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                    }
                    alert("asu");
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
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function deletePeramalan(id)
{
    if(confirm('Apakah anda yakin hapus data Peramalan ini?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('forecasting/delete_peramalan')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
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