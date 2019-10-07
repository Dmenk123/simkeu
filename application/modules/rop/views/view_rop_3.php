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
                     <?php $hitung_array = count($hasil_data); ?>
                     <tr>
                       <th></th>
                       <?php foreach ($hasil_data as $val ) : ?>
                       <th style="text-align: center;"><?php echo $val->month;?> - <?php echo $val->tahun; ?></th>
                       <?php endforeach ?>
                     </tr>
                     <tr>
                       <th width="180px;" style="text-align: center;">Total</th>
                       <?php foreach ($hasil_data as $val ) : ?>
                       <td align="center"><?php echo $val->total; ?></td>
                       <?php endforeach ?>
                     </tr>
                  <?php endif ?>
                     <tr>
                       <th>Rata-Rata Pemakaian (<span style="font-style: italic;">m</span>)</th>
                       <td colspan="<?php echo $hitung_array; ?>"> <?php echo $rata2; ?></td>
                     </tr>
                     <tr>
                       <th>
                         <a href="#" data-toggle="modal" data-target="#modal_help_sd">
                           <span>Standar Deviasi (σ)</span>
                         </a>
                       </th>
                       <td colspan="<?php echo $hitung_array; ?>"> <?php echo $stdev; ?></td>
                     </tr>
                     <tr>
                       <th>
                         <a href="#" data-toggle="modal" data-target="#modal_help_lt2">
                           <span>Lead Time (<span style="font-style: italic;">Lt</span>)</span>
                         </a>
                       </th>
                       <td ccolspan="<?php echo $hitung_array; ?>"> <?php echo $lt; ?> Bulan</td>
                     </tr>
                     <tr>
                       <th>
                         <a href="#" data-toggle="modal" data-target="#modal_help_sf">
                           <span>Safety Factor (<span style="font-style: italic;">Z</span>)</span>
                         </a>
                       </th>
                       <td colspan="<?php echo $hitung_array; ?>"> <?php echo $z; ?></td>
                     </tr>
                     <tr>
                        <th>
                           <a href="#" data-toggle="modal" data-target="#modal_help_ss">
                              <span>Safety Stock (<span style="font-style: italic;">SS</span>)</span>
                           </a>   
                        </th>
                        <td colspan="<?php echo $hitung_array; ?>"> <span id="val_ss"><?php echo $ss; ?></span> 
                        <?php $id = ''; ?>
                        <?php foreach ($hasil_barang as $val ):  
                           echo $val->nama_satuan;
                           $id = $val->id_barang;
                        endforeach ?>
                        </td>
                     </tr>
                     <tr>
                       <th>
                           <a href="#" data-toggle="modal" data-target="#modal_help_rop">
                              <span>Reorder Point (<span style="font-style: italic;">ROP</span>)</span>
                           </a>
                       </th>
                        <td colspan="<?php echo $hitung_array; ?>"><span id="val_rop"><?php echo $rop; ?></span> 
                           <?php foreach ($hasil_barang as $val ): echo $val->nama_satuan; endforeach ?>
                        </td>
                     </tr>   
                </tbody>
              </table>
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="form-row">
                  <div class="form-group">
                    <a class="btn btn-danger" title="Kembali" href="<?php echo site_url('rop'); ?>"><i class="glyphicon glyphicon-menu-left"></i> Kembali</a>
                    <a href="#" class="btn btn-success"<?php echo 'onclick = "update_ss('."'".$id."'".')"'?>>Update SS</a>
                    <a href="#" class="btn btn-primary"<?php echo 'onclick = "update_rop('."'".$id."'".')"'?>>Update ROP</a>
                  </div>
                </div>
              </form>     
              </div><!-- /.box-footer -->        
          </div> <!-- /.box -->
        </div>
      </div>    
    </section>
    <!-- /.content -->                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    