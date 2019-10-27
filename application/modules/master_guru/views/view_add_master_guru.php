    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Daftar
        <small>Guru</small>
      </h1>

      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Daftar</a></li>
        <li class="active">Master Guru</li>
      </ol>
    </section>
    
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-s12">
          <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
              <form method="post" enctype="multipart/form-data" action="#" id="form_input">
                <div class="form-group col-md-12">
                  <label>NIP : </label>
                  <input type="text" class="form-control" id="nip" name="nip" value="">
                  <input type="hidden" class="form-control" id="id" name="id" value="">
                  <span class="help-block"></span>
                </div>

                <div class="form-group col-md-12">
                  <label>Nama : </label>
                  <input type="text" class="form-control" id="nama" name="nama" value="">
                  <span class="help-block"></span>
                </div>
                
                <div class="form-group col-md-12">
                  <label>Jabatan : </label>
                    <select class="form-control jabatan" id="jabatan" name="jabatan"></select>
                    <span class="help-block"></span>
                </div>

                <div class="form-group col-md-12">
                  <label>Tempat Lahir : </label>
                  <input type="text" class="form-control" id="tempatlahir" name="tempatlahir" value="">
                  <span class="help-block"></span>
                </div>

                <div class="form-group col-md-12">
                  <label>Tanggal Lahir : </label>  
                  <div class="row">
                    <div class="col-md-4">
                      <select class="form-control" id="dobday" name="hari"></select>
                      <span class="help-block"></span> 
                    </div>
                    <div class="col-md-4">
                      <select class="form-control" id="dobmonth" name="bulan"></select>
                      <span class="help-block"></span>
                    </div>
                    <div class="col-md-4">
                      <select class="form-control" id="dobyear" name="tahun"></select>
                      <span class="help-block"></span>
                    </div>
                  </div>
                </div>

                <div class="form-group col-md-12">
                  <label>Alamat : </label>
                  <textarea class="form-control" rows="3" placeholder="Alamat ..." id="alamat" name="alamat" ></textarea>
                  <span class="help-block"></span>
                </div>

                <div class="form-group col-md-12">
                  <label>Jenis Kelamin : </label>
                    <select class="form-control select2" id="jenkel" name="jenkel">
                      <option value="">Pilih Jenis Kelamin</option>
                      <option value="L">Laki-Laki</option>
                      <option value="P">Perempuan</option>
                    </select>
                    <span class="help-block"></span>
                </div>

                <div class="form-group col-md-9">
                  <label>Foto : </label>
                  <input type="file" id="gambar" class="gambar" name="gambar";/>
                </div>

                <div class="form-group col-md-3">
                  <img id="gambar-img" src="#" alt="Preview Gambar" height="75" width="75" class="pull-right"/>
                </div>

                <div class="col-md-2 pull-right">
                  <div class="form-group" style="text-align:center; margin:10%">
                    <button type="button" class="btn btn-primary" onclick="save('add')"><i class="fa fa-save"></i> Simpan</button>
                  </div>
                </div>
              </form>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>    
    </section>
    <!-- /.content -->