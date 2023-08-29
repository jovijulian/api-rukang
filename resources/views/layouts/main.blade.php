<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  @yield('title')

  @include('partials.head')

</head>

<body>
  <div id="global-loader">
    <div class="whirly-loader"> </div>
  </div>

  @yield('main')

  @include('partials.footer-script')
</body>

</html>
