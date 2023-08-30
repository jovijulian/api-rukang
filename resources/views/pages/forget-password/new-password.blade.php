@extends('layouts/main')

@section('title')
  <title>Signin</title>
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
              <h3>Reset Password</h3>
              <h4>Silahkan masukan password baru anda</h4>
            </div>
            <form id="reset-password-form">
              <div class="form-login">
                <label for="reset-password">Password</label>
                <div class="pass-group">
                  <input type="password" id="reset-password" class="pass-input " placeholder="Masukan password anda"
                    required>
                  <span class="fas toggle-password fa-eye-slash"></span>
                </div>
              </div>
              <div class="form-login">
                <label for="reset-password-confirm">Konfirmasi Password</label>
                <div class="pass-group">
                  <input type="password" id="reset-password-confirm" class="pass-input "
                    placeholder="Masukan konfirmasi password" required>
                  <span class="fas toggle-password fa-eye-slash"></span>
                </div>
              </div>
              <div class="form-login">
                <button type="submit" class="btn btn-login">Reset Password</button>
              </div>
            </form>
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
      const urlParams = new URLSearchParams(window.location.search)
      const getToken = urlParams.get('token')
      const getEmail = urlParams.get('email')

      $('#reset-password-form').on('submit', function() {
        event.preventDefault()

        $('#global-loader').show()

        if ($('#reset-password-confirm').val() != $('#reset-password').val()) {
          Swal.fire({
            icon: 'warning',
            title: 'Oops...',
            text: 'Konfirmasi password salah'
          })
          return
        }

        const data = {
          email: getEmail,
          token: getToken,
          password: $('#reset-password').val()
        }

        let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        let config = {
          headers: {
            'X-CSRF-TOKEN': token,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          }
        }

        axios.post("{{ url('/api/v1/user/reset-password') }}", data, config)
          .then(function(res) {
            window.location.href = "{{ url('/change-password-success') }}"
          })
          .catch(function(err) {
            $('#global-loader').hide()

            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Token salah atau tidak berlaku'
            })
          })

      })
    })
  </script>
@endsection


{{-- <script>
        $(document).ready(function() {
            const urlParams = new URLSearchParams(window.location.search);
            const getToken = urlParams.get('token');
            const getEmail = urlParams.get('email');

            $('#formAuthentication').on('submit', function(event) {
                const getPassword = $("#password").val()
                const getConfirmPassword = $("#confirm_password").val()
                event.preventDefault();
                $('#loader').show();

                if (!getPassword || !getConfirmPassword) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Password dan konfirmasi password harus diisi.',
                        icon: 'error',
                    })
                    $('#loader').hide();
                    return;
                }

                if (getPassword !== getConfirmPassword) {
                    $('#loader').hide();
                    Swal.fire({
                        title: 'Error',
                        text: 'Password dan konfirmasi password tidak sesuai.',
                        icon: 'error',
                    })
                    return;
                }
                let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                let postData = {
                    email: getEmail,
                    token: getToken,
                    password: getPassword,
                    password_confirmation: getConfirmPassword,
                };
                let config = {
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                };
                axios.post('{{ url('/api/v1/user/reset-password') }}', postData, config)
                    .then(function(response) {
                        $('#loader').hide();
                        Swal.fire({
                            title: 'Sukses',
                            text: 'Password berhasil direset.',
                            icon: 'success',
                        })
                        window.location.href = '{{ url('/users/login') }}';
                    })
                    .catch(function(error) {
                        $('#loader').hide();
                        Swal.fire({
                            title: 'Error',
                            text: 'Internal Server Error',
                            icon: 'error',
                        })

                        event.preventDefault();
                    });
            })
        })
    </script> --}}
