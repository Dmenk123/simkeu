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
    }

    .tbl-header{
      width: 100%;
      color:#070707;
      border-color: #070707;
      border-top: 2px solid #070707;
    }

    #tbl_content{
      padding-top: 40px;
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
      <h2 style="text-align: center;"><strong>Form Pemesanan Barang - PT. SPB Surabaya</strong></h2>
      <table class="tbl-header">
        <tr>
          <td align="left">
            <p style="text-align: left;" class="head-left">ID Permintaan : <?php echo $val->id_trans_order; ?></p>
          </td>
          <td align="right">
            <p style="text-align: right;" class="head-right">Tanggal Pesan: <?php echo $val->tgl_trans_order; ?></p>
          </td>
        </tr>
      </table>
    <?php endforeach ?>

    <table id="tbl_content" class="table table-bordered table-hover" cellspacing="0" width="100%" border="1">
        <tr>
          <th style="width: 10px; text-align: left;">No</th>
          <th style="width: 300px; text-align: left;">Nama Barang</th>
          <th style="width: 50px; text-align: left;">Satuan</th>
          <th style="width: 50px; text-align: left;">Qty</th>
          <th style="text-align: left;">Keterangan</th>
        </tr>
      <?php $no = 1; ?>
      <?php foreach ($hasil_data as $val ) : ?>
        <tr>
          <td><?php echo $no++; ?></td>  
          <td><?php echo $val->nama_barang; ?></td>
          <td><?php echo $val->nama_satuan; ?></td>
          <td><?php echo $val->qty_order; ?></td>
          <td><?php echo $val->keterangan_order; ?></td>
        </tr>
      <?php endforeach ?>
    </table>
    <table class="tbl-footer">
        <tr>
          <td align="left">
            <p style="text-align: left;" class="foot-left"><strong>Dibuat Oleh</strong> </p>
          </td>
          <td align="center">
            <p style="text-align: center;" class="foot-right"><strong>SPV Gudang</strong> </p>
          </td>
          <td align="right">
            <p style="text-align: right;" class="foot-right"><strong>Manajer</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
          </td>
        </tr>
        <tr>
          <td align="left">
            <?php foreach ($hasil_header as $val ) : ?> 
              <p style="text-align: left;" class="foot-left">( <?php echo $val->nama_lengkap_user;?> ) </p>
            <?php endforeach ?>      
          </td>
          <td align="center">
            <p style="text-align: center;" class="foot-right">( Ony Cahyono ) </p>
          </td>
          <td align="right">
            <p style="text-align: right;" class="foot-right">( Suryo Putro ) </p>
          </td>
        </tr>
      </table>
  </div>          
</body></html>