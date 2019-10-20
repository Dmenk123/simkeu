    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Transaksi
        <small>Pencatatan Penerimaan</small>
      </h1>

      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Data Transaksi</a></li>
        <li class="active">Pencatatan Penerimaan</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-s12">
          <div class="box">
            <div class="nav-tabs-custom">
              <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_progress" data-toggle="tab" aria-expanded="true">On Progress</a></li>
                <li class=""><a href="#tab_finish" data-toggle="tab" aria-expanded="false">Verifikasi Selesai</a></li>
              </ul>
              <div class="tab-content">
                <div class="tab-pane active" id="tab_progress">
                  <div class="box-header">
                    <a class="btn btn-success" href="<?= base_url('penerimaan/add_penerimaan/')?>"><i class="glyphicon glyphicon-plus"></i> Tambah Data</a>
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

                <!-- tab finish -->
                <div class="tab-pane" id="tab_finish">
                  <div class="box-header">
                    <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
                  </div>
                  <!-- /.box-header -->
                  <div class="box-body">
                    <!-- flashdata -->
                    <?php if ($this->session->flashdata('feedback_success2')) { ?>
                    <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-check"></i> Berhasil!</h4>
                    <?= $this->session->flashdata('feedback_success') ?>
                    </div>

                    <?php } elseif ($this->session->flashdata('feedback_failed2')) { ?>
                    <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-remove"></i> Gagal!</h4>
                    <?= $this->session->flashdata('feedback_failed') ?>
                    </div>
                    <?php } ?>
                    <!-- end flashdata -->
                    <div class="table-responsive">
                      <table id="tabelVerifikasiFinish" class="table table-bordered" cellspacing="0" width="100%">
                        <thead>
                          <tr>
                            <th style="width: 15%;">ID Pencatatan</th>
                            <th style="width: 15%;">Tanggal</th>
                            <th style="width: 20%;">Nama User</th>
                            <th style="width: 20%;">Pemohon</th>
                            <th style="width: 15%;">Total Biaya</th>
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
              </div>
            </div>
          </div>
          <!-- /.box -->
        </div>
      </div>    
    </section>
    <!-- /.content -->