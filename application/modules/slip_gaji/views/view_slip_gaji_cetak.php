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
    <!-- <table class="tbl-outer">
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
    </table> -->
    <h2 style="text-align: center;"><strong>Slip Gaji SMP Darul Ulum Surabaya</strong></h2>
    
    <table class="tbl-header">
      <tr>
        <td align="center" class="head-center">
          Nama : 
        </td>
        <td>
          <?= $hasil_data['nama_guru'];?>
        </td>
      </tr>
      <tr>
        <td align="center" class="head-center">
          Jumlah Jam : 
        </td>
        <td>
          <?= $hasil_data['jumlah_jam_kerja'];?>
        </td>
      </tr> 
    </table>
    <br>
    <br>
     <table class="tbl-header">
      <tr>
        <td align="center" class="head-center">
          <p style="text-align: center; font-size: 14px" class="head-left"><strong>Periode <?php echo $periode; ?></strong></p>
        </td>
      </tr> 
    </table>
    <table id="tbl_content" class="table table-bordered table-hover" cellspacing="0" width="100%" border="1">
      <thead>
        <tr>
          <th style="width: 10px; text-align: center;">No</th>
          <th style="width: 100px; text-align: center;">Uraian</th>
          <th style="width: 50px; text-align: center;">Jumlah</th>
        </tr>
      </thead>
      <tbody>
      <?php $no = 0; ?> 
        <tr>
          <td colspan="3"><strong>Penerimaan</strong></td>
        </tr> 
        <tr>
          <td class="text-center"><?= 1; ?></td>
          <td class="text-left">Gaji Pokok</td> 
          <td>
            <div>
              <span style="float: left;">Rp. </span>
              <?php if ($hasil_data['is_guru'] == '1') { ?>
                <?php $gapok = (int)$hasil_data['gaji_perjam'] * (int)$hasil_data['jumlah_jam_kerja'];?>
                <span style="float: right;"><?= $gapok;?></span>
              <?php }else{ ?>
                <span style="float: right;"><?= $hasil_data['gaji_pokok'];?></span>
              <?php } ?>
              <div class="clear"></div>
            </div>
          </td>
        </tr>
        <tr>
          <td class="text-center"><?= 2; ?></td>
          <td class="text-left">Tunjangan Jabatan</td> 
          <td>
            <div>
              <span style="float: left;">Rp. </span>
              <span style="float: right;"><?= $hasil_data['gaji_tunjangan_jabatan'];?></span>
              <div class="clear"></div>
            </div>
          </td>
        </tr>

        <tr>
          <td class="text-center"><?= 3; ?></td>
          <td class="text-left">Tunjangan Lainnya</td> 
          <td>
            <div>
              <span style="float: left;">Rp. </span>
              <span style="float: right;"><?= $hasil_data['gaji_tunjangan_lain'];?></span>
              <div class="clear"></div>
            </div>
          </td>
        </tr>
        
        <tr>
          <td colspan="3"><strong>Pengeluaran</strong></td>
        </tr>

        <tr>
          <td class="text-center"><?= 1; ?></td>
          <td class="text-left">Potongan Lainnya</td> 
          <td>
            <div>
              <span style="float: left;">Rp. </span>
              <span style="float: right;"><?= $hasil_data['potongan_lain'];?></span>
              <div class="clear"></div>
            </div>
          </td>
        </tr>
        <tr>
          <td colspan="2"><strong>Jumlah</strong></td> 
          <td>
            <div>
              <span style="float: left;"><strong>Rp. </strong></span>
              <span style="float: right;"><strong><?= $hasil_data['total_take_home_pay'];?></strong></span>
              <div class="clear"></div>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
    <!-- <table class="tbl-footer">
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
    </table> -->
  </div>          
</body></html>