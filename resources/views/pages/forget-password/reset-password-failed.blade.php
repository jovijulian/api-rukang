@extends('layouts/main')

@section('title')
  <title>Reset Password Gagal</title>
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
              <h3>Reset Password Gagal</h3>
              <p>Keterangan: {{$message}}</p>
            </div>
            {{-- <div class="form-login">
              <a href="/" class="btn btn-login">Sign In</a>
            </div> --}}
          </div>
        </div>
        <div class="login-img">
          <img src="assets/img/login.jpg" alt="img">
        </div>
      </div>
    </div>
  </div>
@endsection
