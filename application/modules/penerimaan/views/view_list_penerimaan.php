    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Transaksi
        <small>Pengeluaran Harian</small>
      </h1>

      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Data Transaksi</a></li>
        <li class="active">Pengeluaran Harian</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-s12">
          <div class="box">
            <div class="box-header">
              <a class="btn btn-success" href="<?= base_url('penerimaan/add_penerimaan/')?>"><i class="glyphicon glyphicon-plus"></i> Tambah Data</a>
              <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <table id="tabelPenerimaan" class="table table-bordered" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th>ID Pencatatan</th>
                      <th>Tanggal</th>
                      <th>Nama User</th>
                      <th>Status</th>
                      <th style="width: 13%;">Aksi</th>
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