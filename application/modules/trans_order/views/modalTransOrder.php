<!-- Bootstrap modal -->
<!-- modal_form_order -->
<div class="modal fade" id="modal_form_order" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title"></h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-inline">
                     <div class="form-group">
                        <label class="lbl-modal">ID Order : </label>
                        <input type="text" class="form-control" id="form_id_order" name="fieldIdOrder" readonly="">
                     </div>
                     <div class="form-group">
                        <label class="lbl-modal">Tanggal : </label>
                        <input type="text" class="form-control" id="form_tanggal_order" name="fieldTanggalOrder">
                     </div>
                     <br />
                     <div class="form-group">
                        <label class="lbl-modal">User : </label>
                        <input type="text" class="form-control" id="form_user_order" name="fieldUserOrder" value="<?php echo $this->session->userdata('username');?>" readonly>
                        <input type="hidden" class="form-control" id="form_id_user_order" name="fieldIdUserOrder" value="<?php echo $this->session->userdata('id_user');?>" readonly>
                     </div>
                     <div class="form-group">
                        <label class="lbl-modal-help"><a href="#" data-toggle="modal" data-target="#modal_peramalan"><span style="font-size: 12px; text-align: center;">Klik disini untuk lihat data peramalan </span></a></label>
                     </div>
                     <br />
                    <div class="form-group" style="padding-bottom: 20px;">
                       <table id="tabel_order" class="table table-bordered table-hover">
                            <thead>
                              <tr>
                                  <th style="text-align:center">Nama Barang</th>
                                  <th style="text-align:center">Satuan</th>
                                  <th style="text-align:center" width="80px" class="col-xs-2">Jumlah</th>
                                  <th style="text-align:center" width="80px">Keterangan</th>
                                  <th style="text-align:center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                              <tr>
                                 <td>
                                    <div class="form-group">
                                        <input type="text" name="formNamaBarangOrder" class="form-control" id="form_nama_barang_order"  placeholder="Nama Barang"/>
                                        <input type="hidden" name="formIdBarangOrder" class="form-control" id="form_id_barang_order"/>
                                        <input type="hidden" name="formIdOrder" class="form-control" id="form_id_order"/>
                                    </div>
                                 </td>
                                 <td>
                                    <div class="form-group">
                                        <input type="text" name="formNamaSatuanOrder" class="form-control" id="form_nama_satuan_order" placeholder="Satuan" readonly=""/>
                                        <input type="hidden" name="formIdSatuanOrder" class="form-control" id="form_id_satuan_order"/>
                                    </div>    
                                 </td>
                                 <td>
                                    <div class="form-group">
                                        <input type="text" name="formJumlahBarangOrder" class="form-control numberinput" id="form_jumlah_barang_order" placeholder="Jumlah"/>
                                    </div>
                                 </td>
                                 <td>
                                    <div class="form-group">
                                        <input type="text" name="formKeteranganBarangOrder" class="form-control" id="form_keterangan_barang_order" placeholder="Keterangan"/>
                                    </div>    
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
                <button type="reset" id="btn_cancel_order" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Bootstrap modal -->
<!-- modal_form_order -->
<div class="modal fade" id="modal_peramalan" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3>Data Peramalan Barang</h3>
            </div>
            <div class="modal-body form">
                <div class="table-responsive">
                    <table id="tabelforecasting" class="table table-bordered table-hover" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th style="text-align: center;">ID</th>
                          <th style="width: 250px; text-align: center;">Nama Barang</th>
                          <th style="text-align: center;">Periode Ramal</th>
                          <th style="text-align: center;">Alpha</th>
                          <th style="text-align: center;">hasil Ramal</th>
                          <th style="text-align: center;">MAPE</th>
                          <th style="text-align: center;">Action</th>
                        </tr>
                      </thead>
                      <tbody>

                      </tbody>
                    </table>
              </div>
            </div> <!-- modal body -->
            <div class="modal-footer">
                <button class="btn btn-default" onclick="reload_table2()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
                <button type="reset" id="btn_cancel_order" class="btn btn-danger" data-dismiss="modal">Kembali</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->