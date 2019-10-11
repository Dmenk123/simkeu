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
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <table id="tabel_form_proses" class="table table-bordered table-hover" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th>ID Pengeluaran</th>
                      <th>Tanggal</th>
                      <th>Nama User</th>
                      <th>Pemohon</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <?php 
                        echo "<td>$data_form->id</td>";
                        echo "<td>".date('d-m-Y', strtotime($data_form->tanggal))."</td>";
                        echo "<td>$data_form->user_id</td>";
                        echo "<td>$data_form->pemohon</td>";
                      ?>
                    </tr>
                  </tbody>
                </table>
              </div>  
              <?php 
              foreach ($data_detail as $key => $value) {
              echo ' 
                <div class="form-group col-md-3">
                  <label>Keterangan : </label>
                  <input type="text" class="form-control" id="form_username" name="fieldUsername" value="">
                </div>

                <div class="form-group col-md-1">
                  <label>Satuan : </label>
                  <input type="text" class="form-control" id="form_username" name="fieldUsername" value="">
                </div>

                <div class="form-group col-md-1">
                  <label>Qty : </label>
                  <input type="text" class="form-control" id="form_username" name="fieldUsername" value="">
                </div>

                <div class="form-group col-md-2">
                  <label>harga Satuan : </label>
                  <input type="text" class="form-control" id="form_username" name="fieldUsername" value="">
                </div>

                <div class="form-group col-md-2">
                  <label>Harga Total : </label>
                  <input type="text" class="form-control" id="form_username" name="fieldUsername" value="">
                </div>

                <div class="form-group col-md-1">
                  <label>Bukti : </label>
                  <input type="file" id="imgInp" />
                </div>

                <div class="form-group col-md-1">
                  <img id="blah" src="#" alt="your image" height="75" width="75" class="pull-right"/>
                </div>

                <div class="form-group col-md-1">
                <label></label>
                  <div class="checkbox">
                      <label>
                        <input type="checkbox"> Setuju
                      </label>
                    </div>
                </div>';
              }
              ?>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>    
    </section>
    <!-- /.content -->