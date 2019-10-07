    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Re-Order Point (ROP) <a href="#" data-toggle="modal" data-target="#modal_help_rop"><span style="font-size: 12px">apa itu ROP ?</span></a>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Data Statistik</a></li>
        <li class="active">Reorder Point</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-body">
              <label class="control-label">Pilih bulan untuk perhitungan ROP</label>
              <form method="post" action="<?php echo site_url('rop/rop_step_2') ?>">
              <div class="form-row">
                <div class="form-group">
                  <input type="text" class="form-control" id="tgl_rop" name="tanggalRop" placeholder="Pilih bulan" required="">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group">
                <label class="control-label">Pilih Periode Perhitungan Pemakaian : </label>
                  <select name="periodeRop" class="form-control col-md-4" required="" id="field_periode_rop" style="width:100%;">
                    <option value=""></option>
                    <option value="3">3 Bulan</option>
                    <option value="6">6 Bulan</option>
                    <option value="9">9 Bulan</option>
                    <option value="12">12 Bulan</option>
                  </select>
                </div>
              </div>  
              <div class="form-row">
                <div class="form-group">
                <label class="control-label">Pilih Nama Barang : </label>
                  <select name="barangRop" class="form-control col-md-4" required="" id="field_barang_rop" style="width:100%;">
                    <option value=""></option>
                  </select>
                </div>
              </div>
            <!-- /.box-body -->
            </div> 
            <div class="box-footer">
               <div class="form-row">
                  <div class="form-group">
                    <button type="submit" class="btn btn-info">Cari</button>
                    <button type="reset" class="btn btn-default" id="btn_reset_rop">reset</button>
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
