<!DOCTYPE html>
<html lang="en-US">
<head>
  <meta charset="utf-8">
  <title>{{config('app.name')}} - Reset Password</title>
</head>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<body>

<h2>Token Expire / Email Tidak Ditemukan</h2>

</body>
<script>
  Swal.fire({
    title: 'Error',
    text: '{{$message}}',
    icon: 'error',
  })
</script>
</html>
