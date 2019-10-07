    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Daftar
        <small>Borongan</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
        <li class="active">Borongan</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <button class="btn btn-success" onclick="addBorongan()"><i class="glyphicon glyphicon-plus"></i> Tambah Borongan</button>
              <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <table id="tabelBorongan" class="table table-bordered table-hover" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th style="width: 70px; text-align: center;">ID</th>
                      <th style="width: 70px; text-align: center;">Nama Borongan</th>
                      <th style="text-align: center;">Alamat</th>
                      <th style="text-align: center;">Keterangan</th>
                      <th style="width: 70px; text-align: center;">Telp</th>
                      <th style="width: 70px; text-align: center;">Status</th>
                      <th style="width: 175px; text-align: center;">Action</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>  
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>    
    </section>
    <!-- /.content -->
