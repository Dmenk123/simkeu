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
            <div class="box-body">
              <label class="control-label">Pilih bulan pada field dibawah ini : (periode yang digunakan adalah 12 bulan yang lalu sampai bulan terpilih)</label>
              <form method="post" action="<?php echo site_url('grafik_barang/grafik_step_2') ?>">
              <div class="form-row">
                <div class="form-group">
                  <input type="text" class="form-control" id="tgl_grafik" name="tanggalGrafik" placeholder="Pilih bulan" required="">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group">
                <label class="control-label">Pilih Jenis Transaksi : </label>
                  <select name="JenisGrafik" class="form-control col-md-4 select2" required="" id="field_jenis_grafik">
                    <option value="">-- Pilih Jenis Transaksi --</option>
                    <option value="trans_masuk">Penerimaan</option>
                    <option value="trans_keluar">Pengeluaran</option>
                  </select>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group">
                <label class="control-label">Pilih Nama Barang : </label>
                  <select name="barangGrafik" class="form-control col-md-4" required="" id="field_barang_grafik">
                    
                  </select>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group">
                <label class="control-label">Pilih Periode Tampilan : </label>
                  <select name="periodeGrafik" class="form-control col-md-4" required="" id="field_periode_grafik">
                    <option value="">Pilih Periode</option>
                    <option value="3">3 Bulan</option>
                    <option value="6">6 Bulan</option>
                    <option value="9">9 Bulan</option>
                    <option value="12">12 Bulan</option>
                  </select>
                </div>
              </div>
            <!-- /.box-body -->
            </div> 
            <div class="box-footer">
               <div class="form-row">
                  <div class="form-group">
                    <button type="submit" class="btn btn-info">Cari</button>
                    <button type="reset" class="btn btn-default">reset</button>
                    <a class="btn btn-danger" title="Kembali" onclick="javascript:history.back()"><i class="glyphicon glyphicon-menu-left"></i> Kembali</a>
                  </div>
                </div>
            <!-- /.box-footer -->
            </form> 
            </div>
          <!-- /.box -->  
          </div>
        </div>
      </div>    
    </section>
    <!-- /.content -->
