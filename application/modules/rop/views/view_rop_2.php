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
            <!-- /.box-header -->
            <div class="box-body"> 
              <div class="col-xs-12">
                <h4 style="text-align: center;"><strong>Pemakaian Perbulan Barang : <?php foreach ($hasil_barang as $val ): echo $val->nama_barang; endforeach ?> </strong></h4>
              </div>             
              <table class="table table-bordered table-hover" cellspacing="0" width="100%" border="2" >
                <tbody>
                 <?php if (count($hasil_data) != 0): ?>
                  <tr>
                    <th></th>
                    <?php foreach ($hasil_data as $val ) : ?>
                    <th style="text-align: center;"><?php echo $val->month;?>/<?php echo $val->tahun; ?></th>
                    <?php endforeach ?>
                  </tr>
                  <tr>
                    <th>Total</th>
                    <?php foreach ($hasil_data as $val ) : ?>
                    <td align="center"><?php echo $val->total; ?></td>
                    <?php endforeach ?>
                  </tr>
                <?php endif ?>     
                </tbody>
              </table>
                <div class="form-row" style="padding-top: 20px;">
                <?php $link = site_url("rop/rop_step_3/".$bulan."/".$period.""); ?>
                <form method="post" action="<?php echo $link; ?>">
                  <div class="form-group">
                    <label class="control-label" style="text-align: left;">Lead Time (L) <a href="#" data-toggle="modal" data-target="#modal_help_lt"><span style="font-size: 10px; font-style: italic;">Apa itu Lead time ?</span></a></label>
                    <!-- <input name="leadTime" placeholder="Isi Lama Hari" class="form-control numberinput" type="text"> -->
                    <select name="leadTime" class="form-control col-md-4" required="" id="lead_time" style="width:100%;">
                      <option value=""></option>
                      <?php for ($i=1; $i <= 31  ; $i++) { ?>
                        <option value="<?php echo $i; ?>"><?php echo $i." Hari"; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group">
                    <label class="control-label" style="text-align: left;">Service Level (Sl) <a href="#" data-toggle="modal" data-target="#modal_help_sl"><span style="font-size: 10px; font-style: italic;">Apa itu Service level ?</span></a></label>
                    <!-- <input name="serviceLevel" placeholder="Isi Besarnya Presentase" class="form-control numberinput" type="text"> -->
                    <select name="serviceLevel" class="form-control col-md-4" required="" id="service_level" style="width:100%;">
                      <option value=""></option>
                      <?php for ($i=99; $i >= 51  ; $i--) { ?>
                        <option value="<?php echo $i; ?>"><?php echo $i." %"; ?></option>
                      <?php } ?>
                    </select>
                    <input name="idBarang" type="hidden" value="<?php foreach ($hasil_barang as $val ): echo $val->id_barang; endforeach ?>">
                  </div>
                </div>
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="form-row">
                  <div class="form-group">
                    <button type="submit" class="btn btn-info">Next</button>
                    <button type="reset" class="btn btn-default" id="btn_reset_rop2">reset</button>
                    <a class="btn btn-danger" title="Kembali" onclick="javascript:history.back()"><i class="glyphicon glyphicon-menu-left"></i> Kembali</a>
                  </div>
                </div>
              </form>     
              </div><!-- /.box-footer -->        
          </div> <!-- /.box -->
        </div>
      </div>    
    </section>
    <!-- /.content -->                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    