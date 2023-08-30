@extends('layouts/main')

@section('title')
  <title>Lupa Password</title>
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
              <h3>Lupa Password</h3>
              <h4>Silahkan masukan email anda untuk mengubah password</h4>
            </div>
            <form id="forgot-password-form">
              <div class="form-login">
                <label for="email-forgot-password">Email</label>
                <div class="form-addons">
                  <input type="email" id="email-forgot-password" placeholder="Masukan email anda">
                  <img src="assets/img/icons/mail.svg" alt="img">
                </div>
              </div>
              <div class="form-login">
                <button type="submit" class="btn btn-login">Reset Password</button>
              </div>
            </form>
            <div class="signinform text-center">
              <h4>Tidak punya akun? <a href="/signup" class="hover-a">Daftar</a></h4>
            </div>
          </div>
        </div>
        <div class="login-img">
          <img src="assets/img/login.jpg" alt="img">
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      $('#forgot-password-form').on('submit', function() {
        event.preventDefault()
        $('#global-loader').show()

        const data = {
          email: $('#email-forgot-password').val()
        }

        let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        let config = {
          headers: {
            'X-CSRF-TOKEN': token,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          }
        }

        axios.post("{{ url('api/v1/auth/forgot-password') }}", data, config)
          .then(function(res) {
            $('#global-loader').hide()
            Swal.fire({
              icon: 'success',
              title: 'Berhasil',
              text: 'Silahkan cek email anda untuk mengubah password'
            })
            // window.location.href = "{{ url('/forgot-password/verif-email') }}"
          })
          .catch(function(err) {
            $('#global-loader').hide()

            if (err.response.data.meta.code === 400) {
              const errorMessage = err.response.data.meta.errors[0]
              if (errorMessage.errors) {
                Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: 'Akun tidak ditemukan'
                })
              }
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Internal server error'
              })
            }
          })

      })
    })
  </script>
@endsection


{{-- <script>
    $(document).ready(function() {
        $('#formAuthentication').on('submit', function(event) {
            event.preventDefault();
            $('#loader').show();
            let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            let postData = {
                email: $("#email").val(),
            };
            let config = {
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            };
            axios.post('{{ url('/api/v1/auth/forgot-password') }}', postData, config)
                .then(function(response) {
                    $('#loader').hide();
                    window.location.href = '{{ url('/users/send-email') }}';
                })
                .catch(function(error) {
                    $('#loader').hide();
                    if (error.response.data.meta.code === 422) {
                        let errorMessageText = '';
                        for (let field in error.response.data.errors[0]) {
                            errorMessageText += `${error.response.data.errors[0][field][0]}\n`;
                        }
                        Swal.fire({
                            title: 'Error',
                            text: errorMessageText,
                            icon: 'error',
                        })
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Internal Server Error',
                            icon: 'error',
                        })
                    }
                    event.preventDefault();
                });
        })
    })
</script> --}}
