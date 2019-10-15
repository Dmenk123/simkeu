    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Transaksi
        <small>Verifikasi Pengeluaran</small>
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
              <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <!-- flashdata -->
              <?php if ($this->session->flashdata('feedback_success')) { ?>
              <div class="alert alert-success alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <h4><i class="icon fa fa-check"></i> Berhasil!</h4>
              <?= $this->session->flashdata('feedback_success') ?>
              </div>

              <?php } elseif ($this->session->flashdata('feedback_failed')) { ?>
              <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <h4><i class="icon fa fa-remove"></i> Gagal!</h4>
              <?= $this->session->flashdata('feedback_failed') ?>
              </div>
              <?php } ?>
              <!-- end flashdata -->
              <div class="table-responsive">
                <table id="tabelVerifikasi" class="table table-bordered table-hover" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th>ID Pencatatan</th>
                      <th>Tanggal</th>
                      <th>Nama User</th>
                      <th>Pemohon</th>
                      <th style="width: 10%;">Aksi</th>
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