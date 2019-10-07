<!-- Bootstrap modal -->
<!-- modal_form_beli -->
<div class="modal fade" id="modal_form_barang_keluar" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content"> 
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title"></h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-inline">
                    <div class="form-group">
                        <label class="lbl-modal">ID : </label>
                        <input type="text" class="form-control" id="form_id_keluar" name="fieldIdKeluar" readonly="">
                    </div>
                    <div class="form-group">
                        <label class="lbl-modal">Tanggal : </label>
                        <input type="text" class="form-control" id="form_tanggal_keluar" name="fieldTanggalKeluar" required>
                        <!-- <span class="help-block"></span> -->
                    </div>
                     <br />
                    <div class="form-group">
                        <label class="lbl-modal">Petugas : </label>
                        <input type="text" class="form-control" id="form_user_keluar" name="fieldUserKeluar" value="<?php echo $this->session->userdata('username');?>" readonly>
                        <input type="hidden" class="form-control" id="form_id_user_keluar" name="fieldIdUserKeluar" value="<?php echo $this->session->userdata('id_user');?>" readonly>
                    </div>
                    <div class="form-group">
                       <label class="lbl-modal">Borongan : </label>
                        <select class="form-control" style="width:200px;" id="form_id_borongan" name="fieldIdBorongan" required></select>
                    </div>
                    <div class="form-group">
                        <label class="lbl-modal">Nama : </label>
                          <select class="form-control" style="width:535px;" id="form_nama_personil" name="fieldNamaPersonil" required></select>
                    </div>
                    <br />
                    <div class="form-group" style="padding-bottom: 20px;">
                       <table id="tabel_pengeluaran" class="table table-bordered table-hover">
                            <thead>
                              <tr>
                                  <th class="thBarang" style="text-align:center">Nama Barang</th>
                                  <th class="thSatuan" style="text-align:center">Satuan</th>
                                  <th class="thQty" style="text-align:center" >Qty</th>
                                  <th class="thKeterangan" style="text-align:center" >Keterangan</th>
                                  <th class="thAksi" style="text-align:center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                              <tr>
                                 <td>
                                    <input type="text" name="formNamaBarangkeluar[]" class="form-control" id="form_nama_barang_keluar"  placeholder="Nama Barang"/>
                                    <input type="hidden" name="formIdBarangKeluar[]" class="form-control" id="form_id_barang_keluar"/>
                                 </td>
                                 <td>
                                    <input type="text" name="formNamaSatuanKeluar[]" class="form-control" id="form_nama_satuan_keluar" placeholder="Satuan" readonly=""/>
                                    <input type="hidden" name="formIdSatuanKeluar[]" class="form-control" id="form_id_satuan_keluar"/>
                                 </td>
                                 <td>
                                    <input type="text" name="formJumlahBarangKeluar[]" class="form-control numberinput" id="form_jumlah_barang_keluar" placeholder="Jumlah"/>
                                 </td>
                                 <td>
                                    <input type="text" name="formKeteranganBarangKeluar[]" class="form-control" id="form_keterangan_barang_keluar" placeholder="Keterangan"/>
                                 </td>
                                 <td>
                                   <button type="button" name="btnAddRow" id="btn_add_row" class="btn btn-small btn-default">+</button>           
                                 </td>
                              </tr>
                           </tbody> <!-- tbody -->
                        </table> <!-- table --> 
                    </div> <!-- form group -->
                </form> <!-- form -->
            </div> <!-- modal body -->
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                <button type="reset" id="btn_cancel_keluar" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
