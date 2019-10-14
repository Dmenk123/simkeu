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
              $urut = $key+1;
              echo '<button class="accordion button-acr">Transaksi Detail no. '.$urut.' | Keterangan : '.$value->keterangan.'</button>';
                echo '<div class="panel">';
                  echo ' 
                    <div class="form-group col-md-12">
                      <label>Keterangan : </label>
                      <input type="text" class="form-control" id="i_keterangan" name="i_keterangan[]" value="'.$value->keterangan.'" readonly>
                    </div>

                    <div class="form-group col-md-6">
                      <label>Satuan : </label>
                      <input type="text" class="form-control" id="i_satuan" name="i_satuan[]" value="'.$value->nama.'" readonly>
                      <input type="hidden" class="form-control" id="id_satuan" name="id_satuan[]" value="'.$value->satuan.'" readonly>
                      <input type="hidden" class="form-control" id="id_detail" name="id_detail[]" value="'.$value->id.'" readonly>
                      <input type="hidden" class="form-control" id="id_header" name="id_header[]" value="'.$value->id_trans_keluar.'" readonly>
                    </div>

                    <div class="form-group col-md-6">
                      <label>Qty : </label>
                      <input type="text" class="form-control" id="i_qty" name="i_qty[]" value="'.$value->qty.'" readonly>
                    </div>

                    <div class="form-group col-md-6">
                      <label>harga Satuan : </label>
                      <input type="text" class="form-control" id="i_harga" name="i_harga[]" value="">
                    </div>

                    <div class="form-group col-md-6">
                      <label>Harga Total : </label>
                      <input type="text" class="form-control" id="i_harga_total" name="i_harga_total[]" value="">
                    </div>

                    <div class="form-group col-md-12">
                      <label>Kode Akun : </label>
                        <select class="form-control i_akun" id="i_akun" name="i_akun" required></select>
                    </div>

                    <div class="form-group col-md-9">
                      <label>Bukti : </label>
                      <input type="file" id="i_gambar-'.$key.'" class="i_gambar" name="i_gambar['.$key.']";/>
                    </div>

                    <div class="form-group col-md-3">
                      <img id="i_gambar-'.$key.'-img" src="#" alt="Preview Gambar" height="75" width="75" class="pull-right"/>
                    </div>

                    <div class="form-group col-md-12">
                    <label><strong>Ceklist Pilihan Setuju Apabila Data Sudah di Verifikasi</strong></label>
                      <div class="checkbox">
                          <label>
                            <input type="checkbox"> Setuju, Saya Sudah Memastikan data Telah Benar
                          </label>
                        </div>
                    </div>';
                echo '</div>';
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