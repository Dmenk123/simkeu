<!-- Bootstrap modal -->
<!-- modal_rop -->
<div class="modal fade modal_help_des" tabindex="-1" role="dialog" aria-labelledby="modal_help_des" aria-hidden="true" id="modal_help_des">
    <div class="modal-dialog modal-md">
        <div class="modal-content"> 
            <div class="modal-body form">
                <p><strong>Peramalan Metode Pemulusan Eksponensial Orde Dua (Double Exponential Smoothing)</strong>. Metode ini merupakan model linier yang dikemukakan oleh Brown. Metode Double Exponential Smoothing ini biasanya lebih tepat digunakan untuk <strong>meramalkan data yang mengalami trend dan proses pemulusan (Smoothing) dilakukan dua kali</strong>. Pada sistem ini, proses penentuan nilai alpha didapatkan dengan cara memilih alpha dengan hasil terbaik secara otomatis.</p>
            </div> <!-- modal body -->
            <div class="modal-footer">
                <button type="reset" class="btn btn-danger" data-dismiss="modal">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade modal_help_alpha" tabindex="-1" role="dialog" aria-labelledby="modal_help_alpha" aria-hidden="true" id="modal_help_alpha">
    <div class="modal-dialog modal-md">
        <div class="modal-content"> 
            <div class="modal-body form">
                <p><strong>Alpha </strong>adalah nilai yang dibutuhkan dalam peramalan Double Exponential Smoothing. Dalam penentuan peramalan biasanya diadakan pemilihan nilai alpha secara trial-error. Akan tetapi pada sistem ini digunakan alpha yang menghasilkan peramalan terbaik secara otomatis </p>
            </div> <!-- modal body -->
            <div class="modal-footer">
                <button type="reset" class="btn btn-danger" data-dismiss="modal">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade modal_help_real" tabindex="-1" role="dialog" aria-labelledby="modal_help_real" aria-hidden="true" id="modal_help_real">
    <div class="modal-dialog modal-md">
        <div class="modal-content"> 
            <div class="modal-body form">
                <p><strong>Data Real </strong>merupakan data pemakaian <strong>aktual/sesungguhnya yang terjadi pada proses pengambilan barang pada PT. SPB Surabaya</strong>. Data Real disini adalah data <strong>pemakaian total perbulan </strong></p>
            </div> <!-- modal body -->
            <div class="modal-footer">
                <button type="reset" class="btn btn-danger" data-dismiss="modal">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade modal_help_s1t" tabindex="-1" role="dialog" aria-labelledby="modal_help_s1t" aria-hidden="true" id="modal_help_s1t">
    <div class="modal-dialog modal-md">
        <div class="modal-content"> 
            <div class="modal-body form">
                <p><strong>S1t </strong>merupakan data pemulusan pertama. Menentukan nilai ini merupakan termasuk <strong>langkah pertama dalam upaya mengetahui nilai peramalan</strong></p>
            </div> <!-- modal body -->
            <div class="modal-footer">
                <button type="reset" class="btn btn-danger" data-dismiss="modal">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade modal_help_s2t" tabindex="-1" role="dialog" aria-labelledby="modal_help_s2t" aria-hidden="true" id="modal_help_s2t">
    <div class="modal-dialog modal-md">
        <div class="modal-content"> 
            <div class="modal-body form">
                <p><strong>S2t </strong>merupakan data pemulusan kedua. Nilai ini merupakan lanjutan dari pemulusan pertama, dikarenakan sistem menggunakan peramalan <strong>Double Exponential Smoothing (peramalan dengan dua kali pemulusan)</strong></p>
            </div> <!-- modal body -->
            <div class="modal-footer">
                <button type="reset" class="btn btn-danger" data-dismiss="modal">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade modal_help_at" tabindex="-1" role="dialog" aria-labelledby="modal_help_at" aria-hidden="true" id="modal_help_at">
    <div class="modal-dialog modal-md">
        <div class="modal-content"> 
            <div class="modal-body form">
                <p><strong>at </strong>Merupakan nilai konstanta/tetapan (suatu nilai tetap) peramalan. Penting untuk diketahuinya nilai at (konstanta/tetapan) ini, karena termasuk <strong>salah satu langkah yang diperlukan untuk menghitung peramalan</strong></p>
            </div> <!-- modal body -->
            <div class="modal-footer">
                <button type="reset" class="btn btn-danger" data-dismiss="modal">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade modal_help_bt" tabindex="-1" role="dialog" aria-labelledby="modal_help_bt" aria-hidden="true" id="modal_help_bt">
    <div class="modal-dialog modal-md">
        <div class="modal-content"> 
            <div class="modal-body form">
                <p><strong>bt </strong>Merupakan nilai Slope (Kemiringan pada setiap titik yang terletak pada garis lurus) peramalan. Penting untuk diketahuinya nilai bt (slope) ini, karena termasuk <strong>salah satu langkah yang diperlukan untuk menghitung peramalan</strong></p>
            </div> <!-- modal body -->
            <div class="modal-footer">
                <button type="reset" class="btn btn-danger" data-dismiss="modal">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade modal_help_ft" tabindex="-1" role="dialog" aria-labelledby="modal_help_ft" aria-hidden="true" id="modal_help_ft">
    <div class="modal-dialog modal-md">
        <div class="modal-content"> 
            <div class="modal-body form">
                <p><strong>ft </strong>Merupakan nilai forecast (peramalan). nilai peramalan ini pada nantinya <strong>diolah untuk mengetahui nilai kesalahan peramalan dan untuk mengetahui peramalan periode kedepan</strong></p>
            </div> <!-- modal body -->
            <div class="modal-footer">
                <button type="reset" class="btn btn-danger" data-dismiss="modal">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade modal_help_ae" tabindex="-1" role="dialog" aria-labelledby="modal_help_ae" aria-hidden="true" id="modal_help_ae">
    <div class="modal-dialog modal-md">
        <div class="modal-content"> 
            <div class="modal-body form">
                <p><strong>ae </strong>Merupakan nilai kesalahan absolut (Absolute Error). nilai ini didapatkan dari selisih data pemakaian real dikurangi nilai peramalan kemudian hasilnya dijadikan nilai absolut. <strong>Nilai ini diperlukan untuk menghitung MAD (Maean Absolute Deviation)</strong></p>
            </div> <!-- modal body -->
            <div class="modal-footer">
                <button type="reset" class="btn btn-danger" data-dismiss="modal">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade modal_help_sqe" tabindex="-1" role="dialog" aria-labelledby="modal_help_sqe" aria-hidden="true" id="modal_help_sqe">
    <div class="modal-dialog modal-md">
        <div class="modal-content"> 
            <div class="modal-body form">
                <p><strong>sqe </strong>Merupakan nilai kuadrat kesalahan (Square Error). nilai ini didapatkan dari nilai ae yang dikuadratkan. <strong>Nilai ini diperlukan untuk menghitung MSE (Maean Square Error)</strong></p>
            </div> <!-- modal body -->
            <div class="modal-footer">
                <button type="reset" class="btn btn-danger" data-dismiss="modal">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade modal_help_ape" tabindex="-1" role="dialog" aria-labelledby="modal_help_ape" aria-hidden="true" id="modal_help_ape">
    <div class="modal-dialog modal-md">
        <div class="modal-content"> 
            <div class="modal-body form">
                <p><strong>ape </strong>Merupakan nilai kesalahan presentase absolut (Absolute Percentage Error). nilai ini didapatkan dari nilai ae yang kemudian dijadikan satuan persen. <strong>Nilai ini diperlukan untuk menghitung MAPE (Maean Absolute Percentage Error)</strong></p>
            </div> <!-- modal body -->
            <div class="modal-footer">
                <button type="reset" class="btn btn-danger" data-dismiss="modal">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade modal_help_ft1" tabindex="-1" role="dialog" aria-labelledby="modal_help_ft1" aria-hidden="true" id="modal_help_ft1">
    <div class="modal-dialog modal-md">
        <div class="modal-content"> 
            <div class="modal-body form">
                <p><strong>ft+1p </strong>Merupakan nilai Peramalan satu periode kedepan. Nilai ini didapatkan dari ft (peramalan) ditambahkan 1p (satu periode kedepan)</p>
            </div> <!-- modal body -->
            <div class="modal-footer">
                <button type="reset" class="btn btn-danger" data-dismiss="modal">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade modal_help_mad" tabindex="-1" role="dialog" aria-labelledby="modal_help_mad" aria-hidden="true" id="modal_help_mad">
    <div class="modal-dialog modal-md">
        <div class="modal-content"> 
            <div class="modal-body form">
                <p><strong>MAD </strong>Merupakan nilai tengah kesalahan absolut (Mean Absolute Deviation). Nilai ini didapatkan dari total pemakaian dikurangi total ae (absolute error) dibagi dengan jumlah periode</p>
            </div> <!-- modal body -->
            <div class="modal-footer">
                <button type="reset" class="btn btn-danger" data-dismiss="modal">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade modal_help_mse" tabindex="-1" role="dialog" aria-labelledby="modal_help_mse" aria-hidden="true" id="modal_help_mse">
    <div class="modal-dialog modal-md">
        <div class="modal-content"> 
            <div class="modal-body form">
                <p><strong>MSE </strong>Merupakan nilai kuadrat kesalahan (Mean Square Error). Nilai ini didapatkan dari total sqe (Square Error) dibagi dengan jumlah periode</p>
            </div> <!-- modal body -->
            <div class="modal-footer">
                <button type="reset" class="btn btn-danger" data-dismiss="modal">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade modal_help_mape" tabindex="-1" role="dialog" aria-labelledby="modal_help_mape" aria-hidden="true" id="modal_help_mape">
    <div class="modal-dialog modal-md">
        <div class="modal-content"> 
            <div class="modal-body form">
                <p><strong>MAPE </strong>Merupakan nilai tengah persentase kesalahan absolut (Mean Absolute Percentage Error). Nilai ini didapatkan dari total ape (Absolute Percentage Error) dibagi dengan jumlah periode</p>
            </div> <!-- modal body -->
            <div class="modal-footer">
                <button type="reset" class="btn btn-danger" data-dismiss="modal">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
