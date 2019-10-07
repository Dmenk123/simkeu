    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Peramalan (Double Exponential Smoothing) <a href="#" data-toggle="modal" data-target="#modal_help_des"><span style="font-size: 12px">apa itu Peramalan DES ?</span></a>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Data Statistik</a></li>
        <li class="active">Peramalan</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <div class="form-row">
                  <form id="form_alpha" action="#">
                    <div class="form-group">
                      <input type="hidden" name="fieldAlpha" id="field_alpha" value="<?php echo $alpha; ?>">
                      <input type="hidden" name="blnIndex" id="bln_index" value="<?php echo $bulan_index; ?>">
                      <input type="hidden" name="idBarang" id="id_barang" value="<?php echo $id_barang; ?>">
                      <input type="hidden" name="idSatuan" id="id_satuan" value="<?php foreach ($hasil_barang as $val ): echo $val->id_satuan; endforeach ?>">
                      <input type="hidden" name="periodeForecast" id="periode_forecast" value="<?php echo $periodeForecast; ?>">
                      <input type="hidden" name="tglRamal" class="fieldTglRamal" id="tgl_ramal" value="<?php echo $bulan; ?>">
                    </div>
                </div>
                <div class="col-xs-12">
                  <h4 style="text-align: center;"><strong>Perhitungan Peramalan Barang : <span id="field_nama_barang"><?php foreach ($hasil_barang as $val ): echo $val->nama_barang; endforeach ?></span></strong></h4>
                </div>             
                  <table class="table table-bordered table-hover" cellspacing="0" width="100%" border="2" id="tbl_peramalan">
                    <tbody>
                     <?php if (count($hasil_data) != 0): ?>
                      <tr>
                        <th></th>
                        <?php foreach ($hasil_data as $val ) : ?>
                        <th style="text-align: center"><?php echo $val->month;?>/<?php echo $val->tahun; ?></th>
                        <?php endforeach ?>
                      </tr>
                      <tr>
                        <th><a href="#" data-toggle="modal" data-target="#modal_help_real"><span style="font-size: 14px">Real</span></a></th>
                        <?php foreach ($hasil_data as $val ) : ?>
                        <td align="center"><?php echo $val->total; ?></td>
                        <?php endforeach ?>
                      </tr>
                    <?php endif ?>     
                    </tbody>
                  </table>
                <div class="col-xs-12 grafik">
                  <!-- <canvas id="canvas" width="1000" height="250"></canvas> -->
                </div>
              </div>  
            </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="form-row">
                  <div class="form-group">
                    <button type="button" class="btn btn-info" id="btnSave" onclick="save_peramalan()" disabled>Simpan</button>
                    <a class="btn btn-danger" title="Kembali" href="<?php echo site_url('forecasting'); ?>"><i class="glyphicon glyphicon-menu-left"></i> Kembali</a>
                  </div>
                </div>
              </form>     
              </div><!-- /.box-footer -->        
          </div> <!-- /.box -->
        </div>
      </div>    
    </section>
    <!-- /.content -->                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    