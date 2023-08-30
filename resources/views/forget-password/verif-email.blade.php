@extends('layouts/blankLayout')

@section('title', 'MEET UP BOXER')

@section('page-style')
  <!-- Page -->
  <link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}">
@endsection
<style>
  body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
  }

  .container-xxl {
    background-color: #000000;
  }

  .container {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #000000;
  }

  h1 {
    font-size: 3rem;
    margin-bottom: 1rem;
    color: #fff !important;
  }

  p {
    font-size: 1.2rem;
    margin-bottom: 2rem;
    max-width: 80%;
    text-align: center;
    color: #fff !important;
  }

  .btn {
    padding: 1rem 2rem;
    background-color: #007BFF;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    font-size: 1.2rem;
    transition: background-color 0.2s;
  }

  .btn:hover {
    background-color: #0056b3;
  }

</style>
@section('content')
  <div class="container-xxl">

    <div class="container">
      <h1>Reset Password</h1>
      <p>Silakan periksa kotak masuk Anda untuk mengatur ulang kata sandi.</p>
      <p class="text-center" style="font-size: 16px;margin-top: 30px;">
        <span>Sudah melakukan reset password?</span>
        <a href="{{url('/users/login')}}">
          <span style="color: #0D7CC4">Login disini</span>
        </a>
      </p>
    </div>
  </div>
@endsection
