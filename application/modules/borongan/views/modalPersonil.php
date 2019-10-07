<!-- Bootstrap modal -->
<div class="modal fade" id="modal_personil" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Master Personil Borongan</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <?php $id = $this->uri->segment(3); ?>
                    <input type="hidden" value="" name="idPersonilDetail"/>
                    <input type="hidden" value="<?php echo $id; ?>" name="idPersonil"/>  
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Nama Personil</label>
                            <div class="col-md-9">
                                <input name="namaPersonil" placeholder="Nama Personil Borongan" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Alamat</label>
                            <div class="col-md-9">
                               <!--  <input name="alamatSupplier" placeholder="Alamat Supplier" class="form-control" type="text"> -->
                                <textarea name="alamatPersonil" placeholder="Alamat Personil Borongan" class="form-control" rows="5"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Telp Personil</label>
                            <div class="col-md-9">
                                <input name="telpPersonil" placeholder="Telp Personil Borongan" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Status Personil</label>
                            <div class="col-md-9">
                                <select name="statusPersonil" class="form-control">
                                    <option value="aktif">Aktif</option>
                                    <option value="nonaktif">Non-Aktif</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save_personil()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->