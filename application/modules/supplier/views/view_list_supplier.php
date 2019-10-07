    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Daftar
        <small>Supplier</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
        <li class="active">Supplier</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <button class="btn btn-success" onclick="addSupplier()"><i class="glyphicon glyphicon-plus"></i> Tambah Supplier</button>
              <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <table id="tabelSupplier" class="table table-bordered table-hover" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th>ID Supplier</th>
                      <th>Nama Supplier</th>
                      <th>Alamat</th>
                      <th>Telp. Supplier</th>
                      <th>Status</th>
                      <th style="width: 125px;">Action</th>
                    </tr>
                  </thead>
                  <tbody>

                  </tbody>
                </table>
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
