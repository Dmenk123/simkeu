<?php require_once(APPPATH.'views/template/temp_img_cetak_header.php'); ?>
<html><head>
  <title><?php echo $title; ?></title>
  <style type="text/css">
    #outtable{
      padding: 20px;
      border:1px solid #e3e3e3;
      width:600px;
      border-radius: 5px;
    }
    .short{
      width: 50px;
    }
    .normal{
      width: 150px;
    }
    .tbl-outer{
      color:#070707;
      margin-bottom: 10px;
    }

    .text-center{
      text-align:center;
    }

    .text-left{
      text-align:left;
    }

    .text-right{
      text-align:right;
    }

    .tebal {
      font-weight: bold;
    }
    
    .outer-left{
      padding: 2px;
      border: 0px solid white;
      border-color: white;
      margin: 0px;
      background: white;
    }
    .head-left{
      padding-top: 5px;
      padding-bottom: 0px;
      border: 0px solid white;
      border-color: white;
      margin: 0px;
      background: white;
    }
    .tbl-footer{
      width: 100%;
      color:#070707;
      border-top: 0px solid white;
      border-color: white;
      padding-top: 75px;
    }
    .head-right{
       padding-bottom: 0px;
       border: 0px solid white;
       border-color: white;
       margin: 0px;
    }
    .tbl-header{
      width: 100%;
      color:#070707;
      border-color: #070707;
      border-top: 2px solid #070707;
    }
    #tbl_content{
      padding-top: 10px;
      margin-left: -20px;
    } 
    .tbl-footer td{
      border-top: 0px;
      padding: 10px;
    }
    .tbl-footer tr{
      background: white;
    }
    .foot-center{
      padding-left: 70px;
    }
    .inner-head-left{
       padding-top: 20px;
       border: 0px solid white;
       border-color: white;
       margin: 0px;
       background: white;
    }
    .tbl-content-footer{
      width: 100%;
      color:#070707;
      padding-top: 0px;
    }
    table{
      border-collapse: collapse;
      font-family: arial;
      color:black;
      font-size: 12px;
    }
    thead th{
      text-align: center;
      padding: 10px;
      font-style: bold;
    }
    tbody td{
      padding: 10px;
    }
    tbody tr:nth-child(even){
      background: #F6F5FA;
    }
    tbody tr:hover{
      background: #EAE9F5
    }
    .clear{
        clear:both;
    }
  </style>
</head><body>
  <div class="container">   
    <table class="tbl-outer">
      <tr>
        <td align="left" class="outer-left">
          <?php echo $img_laporan; ?>
        </td>
        <td align="right" class="outer-left">
          <p style="text-align: left; font-size: 14px" class="outer-left">
            <strong>SMP. Darul Ulum Surabaya</strong>
          </p>
          <p style="text-align: left; font-size: 12px" class="outer-left">Jl. Raya Manukan Kulon No.98-100 Kota Surabaya, Jawa Timur 60185</p>
        </td>
      </tr>
    </table>
    <h4 style="text-align: center;"><strong>REALISASI PENGGUNAAN DANA TIAP JENIS ANGGARAN (K7)</strong></h4>
    
    <table class="tbl-header">
      <tr>
        <td align="center" class="head-center">
          <p style="text-align: center; font-size: 14px" class="head-left"><strong>Periode <?php echo $periode; ?></strong></p>
        </td>
      </tr> 
    </table>
    <table>
      <tr>
        <td>Nama Sekolah :</td>
        <td>Smp Darul Ulum Surabaya</td>
      </tr>
      <tr>
        <td>Desa/Kecamatan :</td>
        <td>Tandes</td>
      </tr>
      <tr>
        <td>Kabupaten/Kota</td>
        <td>Surabaya</td>
      </tr>
      <tr>
        <td>Provinsi</td>
        <td>Jawa Timur</td>
      </tr>
    </table>
    <table id="tbl_content" class="table table-bordered table-hover" cellspacing="0" width="100%" border="1">
      <thead>
        <tr>
          <th rowspan="3" style="width: 30px; text-align: center;">No. Kode</th>
          <th rowspan="3" style="width: 250px; text-align: center;">Uraian Kegiatan</th>
          <th rowspan="3" style="width: 100px; text-align: center;">Jumlah</th>
          <th colspan="6" style="text-align: center;">Penggunaan dana per sumber dana</th>
        </tr>
        <tr>
          <th rowspan="2" style="text-align: center;">Penggunaan dana per sumber dana</th>
          <th colspan="3" style="text-align: center;">Bantuan Operasional Sekolah</th>
          <th rowspan="2" style="text-align: center;">Bantuan Lain</th>
          <th rowspan="2" style="text-align: center;">Sumber Lain</th>
        </tr>
        <tr>
          <th style="text-align: center;">Pusat</th>
          <th style="text-align: center;">Provinsi</th>
          <th style="text-align: center;">Kota</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($hasil_data as $val ) : ?>
        <tr>
          <?php if (strlen($val['kode']) == 1) { ?>
            <td class="text-center tebal"><?php echo $val['kode'] ?></td>
            <td class="tebal"><?php echo $val['kegiatan'] ?></td>
            <?php if ($val['kode'] == '-'){ ?>
              <td class="tebal">
                <?php if ($val['jumlah'] != 0){ ?>
                  <div>
                    <span style="float: left;">Rp. </span>
                    <span style="float: right;"><?= number_format($val['jumlah'],0,",",".");?></span>
                    <div class="clear"></div>
                  </div>
                <?php }else { ?>
                  
                <?php } ?>
              </td>
            <?php }else{ ?>
              <td class="tebal">
                <?php if ($val['jumlah'] != 0){ ?>
                  <div>
                    <span style="float: left;">Rp. </span>
                    <span style="float: right;"><?= $val['jumlah'];?></span>
                    <div class="clear"></div>
                  </div>
                <?php }else { ?>
                  
                <?php } ?>
              </td>
            <?php } ?>
            <td class="tebal"></td>  
            <?php if ($val['kode'] == '-'){ ?>
              <td class="tebal">
                <?php if ($val['jumlah'] != 0){ ?>
                  <div>
                    <span style="float: left;">Rp. </span>
                    <span style="float: right;"><?= number_format($val['jumlah'],0,",",".");?></span>
                    <div class="clear"></div>
                  </div>
                <?php }else { ?>
                  
                <?php } ?>
              </td>
            <?php }else{ ?>
              <td class="tebal">
                <?php if ($val['jumlah'] != 0){ ?>
                  <div>
                    <span style="float: left;">Rp. </span>
                    <span style="float: right;"><?= $val['jumlah'];?></span>
                    <div class="clear"></div>
                  </div>
                <?php }else { ?>
                  
                <?php } ?>
              </td>
            <?php } ?>
            <td class="tebal"></td>
            <td class="tebal"></td>
            <td class="tebal"></td>
            <td class="tebal"></td>
          <?php }else{ ?>
            <td class="text-center"><?php echo $val['kode'] ?></td>
            <td class=""><?php echo $val['kegiatan'] ?></td>
            <?php if ($val['kode'] == '-'){ ?>
              <td>
                <?php if ($val['jumlah'] != 0){ ?>
                  <div>
                    <span style="float: left;">Rp. </span>
                    <span style="float: right;"><?= number_format($val['jumlah'],0,",",".");?></span>
                    <div class="clear"></div>
                  </div>
                <?php }else { ?>
                  
                <?php } ?>
              </td>
            <?php }else{ ?>
              <td>
                <?php if ($val['jumlah'] != 0){ ?>
                  <div>
                    <span style="float: left;">Rp. </span>
                    <span style="float: right;"><?= $val['jumlah'];?></span>
                    <div class="clear"></div>
                  </div>
                <?php }else { ?>
                  
                <?php } ?>
              </td>
            <?php } ?>
            <td></td>  
            <?php if ($val['kode'] == '-'){ ?>
              <td>
                <?php if ($val['jumlah'] != 0){ ?>
                  <div>
                    <span style="float: left;">Rp. </span>
                    <span style="float: right;"><?= number_format($val['jumlah'],0,",",".");?></span>
                    <div class="clear"></div>
                  </div>
                <?php }else { ?>
                  
                <?php } ?>
              </td>
            <?php }else{ ?>
              <td>
                <?php if ($val['jumlah'] != 0){ ?>
                  <div>
                    <span style="float: left;">Rp. </span>
                    <span style="float: right;"><?= $val['jumlah'];?></span>
                    <div class="clear"></div>
                  </div>
                <?php }else { ?>
                  
                <?php } ?>
              </td>
            <?php } ?>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          <?php } ?>
        </tr>
      <?php endforeach ?>
        <!-- <tr>
          <td class="text-center" colspan="6"><strong>Saldo Akhir Bulan <?php echo $arr_bulan[date('m', strtotime($tanggaltxt))]; ?></strong></td> 
          <td>
            <div>
              <span style="float: left;"><strong>Rp. </strong></span>
              <span style="float: right;"><strong><?= number_format($saldoTot,2,",",".");?></strong></span>
              <div class="clear"></div>
            </div>
          </td>      
        </tr> -->
      </tbody>
    </table>
    <!-- <table class="tbl-content-footer">
      <tr class="content-footer-left"> 
        <td align="left" class="content-footer-left" colspan="5">
          <p style="text-align: left; font-size: 12px" class="content-footer-left">Pada hari ini, <?= $arr_hari[date('w', strtotime(date('Y-m-d')))]; ?> Tanggal <?= date('d'); ?> Bulan <?= date('m'); ?> Tahun <?= date('Y'); ?>, Buku Kas Umum ditutup dengan keadaan sebagai berikut : </p>
          <p style="text-align: left; font-size: 12px" class="content-footer-left">Saldo Buku Kas Umum bulan <?=$arr_bulan[date('m')]?>   2015 Rp. 24.749.800,-   terdiri dari :</p>
        </td>
      </tr>  
    </table> -->
    <table class="tbl-footer">
      <tr>
        <td align="left">
          <p style="text-align: left;" class="foot-left"><strong>Mengetahui</strong> </p>
          <p style="text-align: left;" class="foot-left"><strong>Kepala Sekolah</strong> </p>
        </td>
        <td align="right">
          <p style="text-align: right;" class="foot-left"><strong>Surabaya, <?= date('d').' '.$arr_bulan[date('m')].' '.date('Y');?></strong> </p>
          <p style="text-align: right;" class="foot-right"><strong>Bendahara</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
        </td>
      </tr>
      <tr>
        <td align="left">
          <p style="text-align: left;" class="foot-left">(KHUSNUL KHOTIMAH,S.Pd) </p>
        </td>

        <td align="right">
          <p style="text-align: right;" class="foot-right">(SITI CHOLIFAH)</p>
        </td>
      </tr>
    </table>
  </div>          
</body></html>