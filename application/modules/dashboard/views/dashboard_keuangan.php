<div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-red">
        <div class="inner">
            <h3><?= $data_dashboard['jumlah_belum_verifikasi'] ?></h3>

            <p>Jumlah Transaksi Belum Verifikasi</p>
        </div>
        <div class="icon">
            <i class="fa fa-exclamation-triangle "></i>
        </div>
    </div>
</div>

<div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-green">
        <div class="inner">
            <h3><?= $data_dashboard['jumlah_sudah_verifikasi'] ?></h3>

            <p>Jumlah Pengeluaran Bulan : <?= $data_dashboard['bulan_indo'] ?></p>
        </div>
        <div class="icon">
            <i class="fa fa-users"></i>
        </div>
    </div>
</div>

<div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-green">
        <div class="inner">
            <h3><?= $data_dashboard['jumlah_penerimaan'] ?></h3>

            <p>Jumlah Penerimaan Bulan : <?= $data_dashboard['bulan_indo'] ?></p>
        </div>
        <div class="icon">
            <i class="fa fa-users"></i>
        </div>
    </div>
</div>