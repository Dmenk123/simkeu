    <!-- Content Header (Page header) -->
    <section class="content-header">
    <?php foreach ($hasil_header as $val ) : ?>
      <h1>
        Daftar Personil Borongan <?php echo $val->nama_borongan; ?> | ID : <?php echo $val->id_borongan; ?> 
      </h1>
    <?php endforeach ?>  
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Data Master</a></li>
        <li class="active">Borongan</li>
        <li class="active">Personil Borongan</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <button class="btn btn-success" onclick="addPersonil()"><i class="glyphicon glyphicon-plus"></i> Tambah Personil</button>
              <button class="btn btn-default" onclick="reload_table2()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <table id="tabelPersonil" class="table table-bordered table-hover" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th style="width: 40px; text-align: center;">No</th>
                      <th style="width: 170px; text-align: center;">Nama Personil</th>
                      <th style="text-align: center;">Alamat</th>
                      <th style="width: 70px; text-align: center;">Telp</th>
                      <th style="width: 70px; text-align: center;">Status</th>
                      <th style="width: 115px; text-align: center;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  
                  </tbody>
                </table>
                <div style="padding-top: 30px; padding-bottom: 10px;">
                  <a class="btn btn-sm btn-danger" title="Kembali" onclick="javascript:history.back()"><i class="glyphicon glyphicon-menu-left"></i> Kembali</a>
                </div>
              </div>  
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- col -->
      </div>
      <!-- row -->    
    </section>
    <!-- /.content -->
