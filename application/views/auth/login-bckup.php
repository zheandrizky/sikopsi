<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="<?php echo base_url('assets/auth/fonts'); ?>/icomoon/style.css">

  <link rel="stylesheet" href="<?php echo base_url('assets/auth/css'); ?>/owl.carousel.min.css">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="<?php echo base_url('assets/auth/css'); ?>/bootstrap.min.css">

  <!-- Style -->
  <link rel="stylesheet" href="<?php echo base_url('assets/auth/css'); ?>/style.css">

  <title>Login #2</title>
</head>

<body>

  <div class="d-lg-flex half">
    <div class="bg order-1 order-md-2" style="background-image: url('<?php echo base_url('assets/auth/images'); ?>/bg_1.jpg');"></div>
    <div class="contents order-2 order-md-1">

      <div class="container">
        <div class="row align-items-center justify-content-center">
          <div class="col-md-7">
            <h3>SIKOPSI</h3>
            <p class="mb-4">Selamat datang, silahkan masuk ke akun Anda</p>
            <?php if (isset($error)) { ?>
              <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
              </div>
            <?php } ?>
            <?php echo form_open('auth/login'); ?>
            <div class="form-group first">
              <label for="username">NIK</label>
              <input type="number" name="nik" class="form-control" placeholder="Masukkan NIK" id="username">
            </div>
            <div class="form-group last mb-3">
              <label for="password">Password</label>
              <input type="password" name="password" class="form-control" placeholder="Masukkan Password" id="password">
            </div>

            <!-- <div class="d-flex mb-5 align-items-center"> -->
              <!-- <label class="control control--checkbox mb-0"><span class="caption">Remember me</span>
                <input type="checkbox" checked="checked" />
                <div class="control__indicator"></div>
              </label> -->
              <!-- <span class="ml-auto"><a href="#" class="forgot-pass">Forgot Password</a></span> -->
            <!-- </div> -->

            <input type="submit" value="Log In" class="btn btn-block btn-primary">
            <?php echo form_close(); ?>
            
            <div class="text-center mt-3">
              <p>Don't have any account? <a href="<?php echo site_url('auth/register'); ?>">Sign up</a></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="<?php echo base_url('assets/auth/js'); ?>/jquery-3.3.1.min.js"></script>
  <script src="<?php echo base_url('assets/auth/js'); ?>/popper.min.js"></script>
  <script src="<?php echo base_url('assets/auth/js'); ?>/bootstrap.min.js"></script>
  <script src="<?php echo base_url('assets/auth/js'); ?>/main.js"></script>
</body>

</html>
