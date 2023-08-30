@extends('layouts/main')

@section('title')
  <title>Verifikasi Akun Gagal</title>
@endsection


@section('main')
  <div class="main-wrapper">
    <div class="account-content">
      <div class="login-wrapper">
        <div class="login-content">
          <div class="login-userset">
            <div class="login-logo logo-normal">
              <img src="assets/img/logo.png" alt="img">
            </div>
            <a href="/" class="login-logo logo-white">
              <img src="assets/img/logo-white.png" alt="">
            </a>
            <div class="login-userheading">
              <h3>Verifikasi gagal / Verifikasi tidak berlaku</h3>
              <p>Keterangan: {{$message}}</p>
            </div>
            <div class="form-login">
              <a href="/" class="btn btn-login">Sign In</a>
            </div>
          </div>
        </div>
        <div class="login-img">
          <img src="assets/img/login.jpg" alt="img">
        </div>
      </div>
    </div>
  </div>
@endsection






{{-- <!DOCTYPE html>
<html lang="en-US">
<head>
  <meta charset="utf-8">
  <title>{{config('app.name')}} - Verifikasi</title>
</head>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<body>

<h2>Verifikasi gagal / Verifikasi tidak berlaku</h2>

</body>
<script>
  Swal.fire({
    title: 'Error',
    text: '{{$message}}',
    icon: 'error',
  })
</script> --}}
</html>
