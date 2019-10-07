    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Detail
        <small>Pembelian Barang</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Data Transaksi</a></li>
        <li class="active">Pembelian Barang</li>
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
                <?php foreach ($hasil_header as $val ) : ?>
                <div class="col-xs-12">
                  <h2 style="text-align: center;"><strong>Detail Pembelian Barang - PT. SPB</strong></h2>
                </div>
                <div class="col-xs-4">
                  <h4 style="text-align: left;">Nama Petugas : <?php echo $val->nama_lengkap_user; ?></h4>
                </div>
                <div class="col-xs-4">
                  <h4 style="text-align: center;">Tanggal Pembelian: <?php echo $val->tgl_trans_beli; ?></h4>
                </div>
                <div class="col-xs-4">
                  <h4 style="text-align: right;">Supplier: <?php echo $val->nama_supplier; ?></h4>
                </div>
                <?php endforeach ?>
                  <table id="tabelTransBeliDetail" class="table table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th style="width: 10px; text-align: center;">No</th>
                        <th style="width: 20px; text-align: center;">ID. Po</th>
                        <th style="width: 300px; text-align: center;">Nama Barang</th>
                        <th style="width: 60px; text-align: center;">Satuan</th>
                        <th style="width: 50px; text-align: center;">Jumlah</th>
                        <th style="text-align: center;">Keterangan</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php $no = 1; ?>
                        <?php foreach ($hasil_data as $val ) : ?>
                          <tr>
                        <td><?php echo $no++; ?></td>  
                        <td><?php echo $val->id_trans_beli; ?></td>
                        <td><?php echo $val->nama_barang; ?></td>
                        <td><?php echo $val->nama_satuan; ?></td>
                        <td><?php echo $val->qty_beli; ?></td>
                        <td><?php echo $val->keterangan_beli; ?></td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                  </table>
                  <div style="padding-top: 30px; padding-bottom: 10px;">
                    <a class="btn btn-sm btn-danger" title="Kembali" onclick="javascript:history.back()"><i class="glyphicon glyphicon-menu-left"></i> Kembali</a>

                    <?php $id = $this->uri->segment(3); ?>
                    <?php $link_print = site_url('trans_beli/cetak_report_trans_beli_detail/').$id; ?>
                    <?php echo '<a class="btn btn-sm btn-success" href="'.$link_print.'" title="Print Order Pembelian" id="btn_print_beli_detail" target="_blank"><i class="glyphicon glyphicon-print"></i> Cetak</a>';?>
                  </div>
              </div>  
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>    
    </section>
    <!-- /.content -->