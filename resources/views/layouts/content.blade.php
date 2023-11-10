@extends('layouts/main')

@section('main')
  <div class="main-wrapper">
    @include('partials.navigation')

    <div class="page-wrapper">
      @yield('content')
    </div>
  </div>

  @if (Request::is('dashboard'))
    @include('partials.dashboard-chart')
  @endif

  <script>
    $(document).ready(function () {
      const token = localStorage.getItem('access_token')
      const expirationTime = localStorage.getItem('expires_at')

      if (!(token && expirationTime && Date.now() < parseInt(expirationTime))) {
        window.location.href = '{{url('/')}}'
      }

      if (!token) {
        window.location.href = '{{url('/')}}'
      }
    })
  </script>
@endsection
