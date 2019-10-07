<!-- Bootstrap modal -->
<div class="modal fade" id="modal_reply_inbox" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title"></h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_balas" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Subject Pesan</label>
                            <div class="col-md-9">
                                <input type="hidden" name="idInbox" value="">
                                <input name="idUserInbox" class="form-control" type="hidden" value="<?php echo $this->session->userdata('id_user');?>">
                                <input name="subjectPesanInbox" placeholder="Subject Pesan" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Kirim Kepada</label>
                            <div class="col-md-9">
                                <input name="sendToInbox" class="form-control" type="hidden">
                                <input name="sendToInboxTxt" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Isi Pesan</label>
                            <div class="col-md-9">
                               <!--  <input name="alamatSupplier" placeholder="Alamat Supplier" class="form-control" type="text"> -->
                                <textarea name="isiPesanInbox" placeholder="Isi Pesan" class="form-control" rows="6"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->


<!-- Bootstrap modal -->
<!-- modal detail inbox -->
<div class="modal fade" id="modal_detail_inbox" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title"></h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Subject Pesan</label>
                            <div class="col-md-9">
                                <input type="hidden" name="idPesan" value="">
                                <input name="subjectInbox" placeholder="Subject Pesan" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Pengirim</label>
                            <div class="col-md-9">
                                <select name="pengirim" class="form-control">
                                    <?php $id = $this->session->userdata('id_user'); ?>
                                    <option value="">--Kirim Pesan Kepada--</option> 
                                    <?php $query = $this->db->get('tbl_user_detail')->result();
                                        foreach ($query as $key) { 
                                            //jika key == variabel then lewati
                                            if ($key->id_user == $id) continue;?>
                                                <option value="<?php echo $key->id_user ?>">
                                                    <?php echo $key->nama_lengkap_user ?>
                                                </option>
                                        <?php } ?>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Isi Pesan</label>
                            <div class="col-md-9">
                               <!--  <input name="alamatSupplier" placeholder="Alamat Supplier" class="form-control" type="text"> -->
                                <textarea name="isiInbox" placeholder="Isi Pesan" class="form-control" rows="6"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnReply" onclick="replyInbox()" class="btn btn-primary">Reply</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal