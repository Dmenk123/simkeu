<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Simkeu - SMP darul ulum surabaya</title>
  <link rel="shortcut icon" href="<?php echo config_item('assets'); ?>img/logo.png" />
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- font google -->
  <link href='https://fonts.googleapis.com/css?family=Carter One' rel='stylesheet'>
  <link href='https://fonts.googleapis.com/css?family=Charm' rel='stylesheet'>

  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo config_item('assets'); ?>bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo config_item('assets'); ?>adminlte/css/AdminLTE.min.css">

  <style>
    p.login-box-text {
        font-family: 'Charm';
        font-size: 20px;
        text-align: center;
    }

     p.login-box-text-header {
        font-family: 'Carter One';
        font-size: 20px;
        text-align: center;
    }
  </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->
  <p class="login-box-text-header">SISTEM INFORMASI KEUANGAN</p>
  <div class="login-box-body">  
      <div class="login-logo">
        <span class="logo-lg"><img src="<?php echo base_url('assets/img/logo-login-page.png');?>"></span> 
        <strong><p class="login-box-text">SMP DARUL ULUM SURABAYA</p></strong>
      </div>
    <form action="<?php echo base_url('login/proc'); ?>" method="post">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="username" name="username" required>
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Password" name="password" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Login</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

  </div>
  <!-- /.login-box-body -->
  <?php if ($this->session->flashdata('message')): ?>
    <div class="alert alert-danger">
      <a href="#" class="close" data-dismiss="alert">&times;</a>
      <strong><p align="center"><?php echo $this->session->flashdata('message'); ?></p></strong>
    </div>
  <?php endif ?>  
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="<?php echo config_item('assets'); ?>plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo config_item('assets'); ?>bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
