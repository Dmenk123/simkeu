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
    $('#tgl_grafik').MonthPicker({ 
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
    $( "#field_barang_grafik" ).select2({        
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

    $( "#field_jenis_grafik" ).select2({
    });

    $( "#field_periode_grafik" ).select2({
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


function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

</script>	

<script type="text/javascript">
      $(document).ready(function() {
        var ctx = document.getElementById("canvas").getContext("2d");
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo $md_grafik; ?>,
                datasets: [{
                    label:  "<?php echo $nama_barang;?>",
                    data: <?php echo $qty_grafik; ?>,
                    backgroundColor:  "rgba(93, 65, 205, 0.5)",
                    borderColor: "rgba(45, 7, 163, 1)",
                    pointBorderColor : "rgba(45, 7, 163, 1)",
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
        /*var data = {
            labels: <?php echo $bulan_out; ?>,
            datasets: [
                {
                    label:  "Pemakaian : " + "<?php echo $nama_barang;?>",
                    fillColor:  "rgba(220,220,220,0.2)",
                    strokeColor: "rgba(220,220,220,1)",
                    pointColor:  "rgba(220,220,220,1)",
                    pointStrokeColor: "#FFF",
                    pointHighlightFill: "#FFF",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: <?php echo $qty_out; ?>
                }
              ,{
                    label: "Pemakaian: "+ <?php echo $bulan_out;?>,
                    fillColor:  "rgba(220,220,220,0.2)",
                    strokeColor: "rgba(220,220,220,1)",
                    pointColor:  "rgba(220,220,220,1)",
                    pointStrokeColor: "#FFF",
                    pointHighlightFill: "#FFF",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: <?php echo $qty_out; ?>
                }
            ]
        };*/
        
        document.getElementById("legenda").innerHTML = chart.generateLegend();
      });
    </script>
