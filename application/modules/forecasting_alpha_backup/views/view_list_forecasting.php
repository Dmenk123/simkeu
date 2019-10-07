    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        List Peramalan (Double Exponential Smoothing) <a href="#" data-toggle="modal" data-target="#modal_help_des"><span style="font-size: 12px">apa itu Peramalan DES ?</span></a>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Data Statistik</a></li>
        <li class="active">Peramalan</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-s12">
          <div class="box">
            <div class="box-header">
              <a class="btn btn-success" title="Tambah" href="<?php echo site_url('forecasting/forecasting_step_1'); ?>"><i class="glyphicon glyphicon-plus"></i> Tambah Peramalan</a>
              <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <table id="tabelforecasting" class="table table-bordered table-hover" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Nama Barang</th>
                      <th>Tgl Ramal</th>
                      <th>Alpha</th>
                      <th>hasil Ramal</th>
                      <th>MAD</th>
                      <th>MAE</th>
                      <th>MAPE</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>

                  </tbody>
                </table>
              </div>  
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>    
    </section>
    <!-- /.content -->