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
                <h4 style="text-align: center;"><strong>Hasil Analisa Barang : <span id="val_nama_brg"><?php foreach ($hasil_barang as $val ): echo $val->nama_barang; ?></span> (<span id="val_id_brg"><?php echo $val->id_barang; endforeach ?></span>) </strong></h4>
                <h5 style="text-align: center;"><strong>Pemakaian total tiap bulan</strong></h5>
              </div>             
              <table class="table table-bordered table-hover" cellspacing="0" width="100%" border="2" >
                <tbody>
                 <?php if (count($hasil_data) != 0): ?>
                  <tr>
                    <th></th>
                    <?php foreach ($hasil_data as $val ) : ?>
                    <th><?php echo $val->month;?> - <?php echo $val->tahun; ?></th>
                    <?php endforeach ?>
                  </tr>
                  <tr>
                    <th>Total</th>
                    <?php foreach ($hasil_data as $val ) : ?>
                    <td align="center"><?php echo $val->total; ?></td>
                    <?php endforeach ?>
                  </tr>
                <?php endif ?>
                  <tr>
                    <th colspan="3">Rata-Rata Pemakaian (<span style="font-style: italic;">m</span>)</th>
                    <td colspan="11"> <?php echo $rata2; ?></td>
                  </tr>
                  <tr>
                    <th colspan="3">Standar Deviasi (σ)&nbsp;
                      <a href="#" data-toggle="modal" data-target="#modal_help_sd">
                        <span style="font-size: 10px; font-style: italic;">Apa itu Standar Deviasi ?</span>
                      </a>
                    </th>
                    <td colspan="11"> <?php echo $stdev; ?></td>
                  </tr>
                  <tr>
                    <th colspan="3">Lead Time (<span style="font-style: italic;">Lt</span>)&nbsp;
                      <a href="#" data-toggle="modal" data-target="#modal_help_lt2">
                        <span style="font-size: 10px; font-style: italic;">Apa itu Lead Time ?</span>
                      </a>
                    </th>
                    <td colspan="11"> <?php echo $lt; ?> Bulan</td>
                  </tr>
                  <tr>
                    <th colspan="3">Safety Factor (<span style="font-style: italic;">Z</span>)&nbsp;
                      <a href="#" data-toggle="modal" data-target="#modal_help_sf">
                        <span style="font-size: 10px; font-style: italic;">Apa itu Safety Factor ?</span>
                      </a>
                    </th>
                    <td colspan="11"> <?php echo $z; ?></td>
                  </tr>
                  <tr>
                    <th colspan="3">Safety Stock (<span style="font-style: italic;">SS</span>)&nbsp;
                      <a href="#" data-toggle="modal" data-target="#modal_help_ss">
                        <span style="font-size: 10px; font-style: italic;">Apa itu Safety Stock ?</span>
                      </a>
                    </th>
                    <td colspan="8"> <span id="val_ss"><?php echo $ss; ?></span> 
                      <?php $id = ''; ?>
                      <?php foreach ($hasil_barang as $val ):  
                        echo $val->nama_satuan;
                        $id = $val->id_barang;
                      endforeach ?>
                    </td>
                    <td colspan="3" align="center"><a href="#" class="btn-sm btn-success" 
                    <?php echo 'onclick = "update_ss('."'".$id."'".')"'?>>Update di Master</a></td>
                  </tr>
                  <tr>
                    <th colspan="3">Reorder Point (<span style="font-style: italic;">ROP</span>)&nbsp;
                      <a href="#" data-toggle="modal" data-target="#modal_help_rop">
                        <span style="font-size: 10px; font-style: italic;">Apa itu ROP ?</span>
                      </a>
                    </th>
                    <td colspan="8"> <span id="val_rop"><?php echo $rop; ?></span> <?php foreach ($hasil_barang as $val ): echo $val->nama_satuan; endforeach ?></td>
                    <td colspan="3" align="center"><a href="#" class="btn-sm btn-success"
                    <?php echo 'onclick = "update_rop('."'".$id."'".')"'?>>Update di Master</a></td>
                  </tr>   
                </tbody>
              </table>
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="form-row">
                  <div class="form-group">
                    <a class="btn btn-danger" title="Kembali" href="<?php echo site_url('rop'); ?>"><i class="glyphicon glyphicon-menu-left"></i> Kembali</a>
                  </div>
                </div>
              </form>     
              </div><!-- /.box-footer -->        
          </div> <!-- /.box -->
        </div>
      </div>    
    </section>
    <!-- /.content -->                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    