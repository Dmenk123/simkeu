    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        User
        <small>Setting</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Profil</a></li>
        <li class="active">User Setting</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
              <form action="<?=base_url()?>profil/update" method="post" enctype="multipart/form-data">
                <div class="col-xs-12">
                <?php foreach ($data_user as $val ) : ?>
                <input type="hidden" class="form-control" id="idUser" name="id_user" value="<?php echo $val->id_user; ?>">  
                  <div class="form-group ">
                    <label for="namaLengkap">Nama Lengkap</label>
                    <input type="text" class="form-control" id="namaLengkap" name="nama_lengkap" placeholder="Masukkan Nama Lengkap Anda" value="<?php echo $val->nama_lengkap_user; ?>">
                  </div>
                  <div class="form-group ">
                    <label for="alamat">Alamat</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Masukkan Alamat Anda"><?php echo $val->alamat_user; ?></textarea>
                  </div>
                  <div class="form-group ">
                    <label for="tanggalLahir">Tanggal Lahir</label>
                    <input type="text" class="form-control" id="tanggalLahir" name="tanggal_lahir" placeholder="Masukkan Tanggal Lahir Anda" value="<?php echo $val->tanggal_lahir_user; ?>">
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3">Jenis Kelamin</label>
                      <select name="jenis_kelamin" id="jenisKelamin" class="form-control">
                        <option value="">--- Pilih Jenis Kelamin ---</option>
                        <?php if ($val->jenis_kelamin_user == null) { ?>
                          <option value="Laki-Laki">Laki-Laki</option>
                          <option value="Perempuan">Perempuan</option>
                        <?php } elseif ($val->jenis_kelamin_user == 'Laki-Laki') { ?>
                          <option value="Laki-Laki" selected>Laki-Laki</option>
                          <option value="Perempuan">Perempuan</option>
                        <?php } elseif ($val->jenis_kelamin_user == 'Perempuan') {?>
                          <option value="Laki-Laki">Laki-Laki</option>
                          <option value="Perempuan" selected>Perempuan</option>
                       <?php } ?>    
                      </select>
                  </div>
                  <div class="form-group ">
                    <label for="cp">Contact Person</label>
                    <input type="text" class="form-control numberInput" id="cpUser" name="cp_user" placeholder="Masukkan Contact Person Anda" value="<?php echo $val->no_telp_user; ?>">
                  </div>
                  <div class="form-group">
                    <label for="fotoUser">Foto User</label>
                    <input type="file" id="fotoUser" name="foto_user" value="<?php echo $val->gambar_user; ?>">
                    <p class="help-block"><strong>Catatan : Apabila tidak ingin merubah foto, Mohon lewati pilihan ini</strong></p>
                  </div>
                  <button type="submit" class="btn btn-success">Submit</button>
                  <button type="button" class="btn btn-danger" onclick="javascript:history.back()">kembali</button>
                </div>
                <!-- /.col xs 8 -->
                <?php endforeach ?>           
              </form>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>    
    </section>
    <!-- /.content -->                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    