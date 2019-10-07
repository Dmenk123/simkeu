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
      margin-bottom: 35px;
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
      background: white;
      margin: 0px;
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
       background: white;
    }

    .inner-head-left{
       padding-top: 20px;
       border: 0px solid white;
       border-color: white;
       margin: 0px;
       background: white;
    }

    .content-footer-left{
       padding-top: 10px;
       border: 0px solid white;
       border-color: white;
       margin: 0px;
       background: white;
    }

    .tbl-header{
      width: 100%;
      color:#070707;
      border-color: #070707;
      border-top: 2px solid #070707;
    }

    #tbl_content{
      padding-top: 10px;
    }

    .tbl-content-footer{
      width: 100%;
      color:#070707;
      padding-top: 0px;
    }

    .tbl-footer td{
      border-top: 0px;
      padding: 10px;
    }

    .tbl-footer tr{
      background: white;
    }

    table{
      border-collapse: collapse;
      font-family: arial;
      color:black;
      font-size: 12px;
    }

    thead th{
      text-align: left;
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
  </style>
</head><body>
  <!-- Main content -->
  <div class="container">
    <?php foreach ($hasil_header as $val ) : ?>
      <table class="tbl-outer">
        <tr>
          <td align="left" class="outer-left">
            <p style="text-align: left; font-size: 14px" class="outer-left"><strong>PT. Surya Putra Barutama</strong></p>
          </td>
        </tr>
        <tr>
          <td align="left" class="outer-left">
            <p style="text-align: left; font-size: 12px" class="outer-left">Jl. Raya Mastrip Kedurus No.23, Kota Surabaya - Jawa Timur</p>
          </td>
        </tr>
      </table>     
      <h2 style="text-align: center;"><strong>Tanda Terima Barang</strong></h2>
      <table class="tbl-header">
        <tr>
          <td align="left" class="head-left">
            <p style="text-align: left; font-size: 14px" class="head-left"><strong>Surabaya, <?php echo $val->tgl_trans_masuk; ?></strong></p>
          </td>
          <td align="right" class="head-left">
            <p style="text-align: right; font-size: 14px" class="head-right">No Penerimaan : <strong><?php echo $val->id_trans_masuk; ?></strong></p>
          </td>
        </tr> 
        <tr> 
          <td align="left" class="head-left" colspan="2">
            <p style="text-align: left; font-size: 12px" class="head-left">Diterima barang dari : <strong><?php echo $val->nama_supplier; ?></strong></p>
          </td>
        </tr>
        <tr class="head-left"> 
          <td align="left" class="head-left" colspan="2">
            <p style="text-align: left; font-size: 12px" class="head-left"><?php echo $val->alamat_supplier; ?></p>
          </td>
        </tr>  
       <tr class="inner-head-left"> 
          <td align="left" class="inner-head-left" colspan="2">
            <p style="text-align: left; font-size: 12px" class="inner-head-left">Telah diterima barang dengan kondisi baik dari <strong><?php echo $val->nama_supplier; ?></strong>, berikut adalah barang-barang yang telah diterima :</p>
          </td>
        </tr>    
      </table>
    <?php endforeach ?>

    <table id="tbl_content" class="table table-bordered table-hover" cellspacing="0" width="100%" border="1">
      <thead>
        <tr>
          <th style="width: 10px; text-align: left;">No</th>
          <th style="width: 300px; text-align: left;">Nama Barang</th>
          <th style="width: 50px; text-align: left;">Satuan</th>
          <th style="width: 50px; text-align: left;">Qty</th>
          <th style="text-align: left;">Keterangan</th>
        </tr>
      </thead>
      <tbody>
      <?php $no = 1; ?>
      <?php foreach ($hasil_data as $val ) : ?>
        <tr>
          <td><?php echo $no++; ?></td>  
          <td><?php echo $val->nama_barang; ?></td>
          <td><?php echo $val->nama_satuan; ?></td>
          <td><?php echo $val->qty_masuk; ?></td>
          <td><?php echo $val->keterangan_masuk; ?></td>
        </tr>
      <?php endforeach ?>
      </tbody>
      </table>
      <table class="tbl-content-footer">
        <tr class="content-footer-left"> 
          <td align="left" class="content-footer-left" colspan="5">
            <p style="text-align: left; font-size: 12px" class="content-footer-left">Dimohon agar disertakan surat tanda terima ini pada saat melakukan <strong>penagihan</strong> pada kasir kami, Terima kasih.</p>
          </td>
        </tr>  
      </table>
    <table class="tbl-footer">
        <tr>
          <td align="left">
            <p style="text-align: left;" class="foot-left"><strong>Adm Gudang</strong> </p>
          </td>
          <td align="center">
            <p style="text-align: center;" class="foot-right"><strong>SPV gudang</strong> </p>
          </td>
          <td align="right">
            <p style="text-align: right;" class="foot-right"><strong>Pengirim Barang</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
          </td>
        </tr>
        <tr>
          <td align="left">
            <?php foreach ($hasil_header as $val ) : ?> 
            <p style="text-align: left;" class="foot-left">( <?php echo $val->nama_lengkap_user;?> ) </p>
                 
          </td>
          <td align="center">
            <p style="text-align: center;" class="foot-right">( Ony Cahyono ) </p>
          </td>
          <td align="right">
            <p style="text-align: right;" class="foot-right">( <?php echo $val->nama_supplier;?> ) </p>
            <?php endforeach ?> 
          </td>
        </tr>
      </table>
  </div>          
</body></html>