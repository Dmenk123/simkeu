    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Grafik Barang
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Data Statistik</a></li>
        <li class="active">Grafik Barang</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <!-- /.box-header -->
            <div class="box-body"> 
              <div class="col-xs-12">
                <h4 style="text-align: center;"><strong>Grafik <?php echo $transaksi; ?> Bulanan Periode : <?php echo $bulan_awal.' s/d '.$bulan_akhir; ?> </strong></h4>
              </div>             
                <div class="col-xs-12">
                  <canvas id="canvas" width="1000" height="380"></canvas>
                </div>
                <div class="col-xs-12">
                  <div id="legenda"> </div>
                </div>
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="form-row">
                  <div class="form-group">
                    <a class="btn btn-danger" title="Kembali" onclick="javascript:history.back()"><i class="glyphicon glyphicon-menu-left"></i> Kembali</a>
                  </div>
                </div>     
              </div><!-- /.box-footer -->        
          </div> <!-- /.box -->
        </div>
      </div>    
    </section>
    <!-- /.content -->                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            