    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Daftar
        <small>Barang</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
        <li class="active">Barang</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <button class="btn btn-success" onclick="add_barang()"><i class="glyphicon glyphicon-plus"></i> Add Barang</button>
              <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive"> 
                <table id="tabelBarang" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>ID Barang</th>
                      <th>Nama Barang</th>
                      <th>Satuan</th>
                      <th>Stok</th>
                      <th>SS</th>
                      <th>ROP</th>
                      <th>Kategori</th>
                      <th>Status</th>
                      <th style="width: 50px;">Action</th>
                    </tr>
                  </thead>
                  <tbody>

                  </tbody>
                </table>
              </div>
              <!-- table-responsive -->  
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col --> 
      </div>  
      <!-- row -->
    </section>
    <!-- /.content -->
