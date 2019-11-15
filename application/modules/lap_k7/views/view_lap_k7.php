    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        LAPORAN K7
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Laporan</a></li>
        <li class="active">LAPORAN K7</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-body">
              <label class="control-label">Pilih periode Laporan pada field dibawah ini</label>
              <form class="form-inline" method="get" action="<?php echo site_url('lap_k7/laporan_k7_detail') ?>">
                <div class="form-group">
                  <select name="triwulan" class="form-control" id="triwulan" required="">
                    <option value="">Pilih Triwulan</option>
                    <option value="1-3-I">Triwulan I (Januari-Maret)</option>
                    <option value="4-6-II">Triwulan II (April-Juni)</option>
                    <option value="7-9-III">Triwulan III (Juli-September)</option>
                    <option value="10-12-IV">Triwulan IV (Oktober-Desember)</option>
                  </select>
                </div>
                <div class="form-group">
                  <select name="tahun" class="form-control" id="tahun" required="">
                    <option value="">Pilih Tahun</option>
                    <?php
                      $thn = date('Y'); 
                      for ($z=$thn; $z <= (int)$thn + 3; $z++) { 
                      echo "<option value='$z'>$z</option>";
                    } ?>
                  </select>
                </div>
                <button type="submit" class="btn btn-info">Cari</button>
                <button type="reset" class="btn btn-default">reset</button>
              </form>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <a class="btn btn-sm btn-danger" title="Kembali" onclick="javascript:history.back()"><i class="glyphicon glyphicon-menu-left"></i> Kembali</a>
            </div>
          </div>
          <!-- /.box -->
        </div>
      </div>    
    </section>
    <!-- /.content -->