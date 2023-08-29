@extends('layouts/main')

@section('main')
  <div class="main-wrapper">
    @include('partials.navigation')

    <div class="page-wrapper">
      @yield('content')
    </div>
  </div>
@endsection
