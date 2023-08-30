@extends('layouts/main')

@section('title')
  <title>Signup</title>
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
              <h3>Buat Akun Anda</h3>
            </div>
            <form id="signup-form">
              <div class="form-login">
                <label for="fullname">Nama Lengkap</label>
                <div class="form-addons">
                  <input type="text" id="fullname" placeholder="Masukan nama lengkap anda" required>
                  <img src="assets/img/icons/users1.svg" alt="img">
                </div>
              </div>
              <div class="form-login">
                <label for="email">Email</label>
                <div class="form-addons">
                  <input type="email" id="email" placeholder="Masukan email anda" required>
                  <img src="assets/img/icons/mail.svg" alt="img">
                </div>
              </div>
              <div class="form-login">
                <label for="birthdate">Tanggal Lahir</label>
                <div class="form-addons">
                  <input type="date" id="birthdate" required>
                  {{-- <img src="assets/img/icons/mail.svg" alt="img"> --}}
                </div>
              </div>
              <div class="form-login">
                <label for="phone">Nomor Telepon</label>
                <div class="form-addons">
                  <input type="text" id="phone" placeholder="Masukan nomor telepon anda" required>
                  <img src="assets/img/icons/phone.svg" alt="img">
                </div>
              </div>
              <div class="form-login">
                <label for="address">Alamat</label>
                <div class="form-addons">
                  {{-- <input type="text" id="address" placeholder="Masukan alamat anda" required> --}}
                  <textarea rows="3" cols="5" id="address" class="form-control" placeholder="Masukan alamat anda" required></textarea>
                  <img src="assets/img/icons/map.svg" alt="img">
                </div>
              </div>
              <div class="form-login">
                <label for="password">Password</label>
                <div class="pass-group">
                  <input type="password" id="password" class="pass-input " placeholder="Masukan password anda" required>
                  <span class="fas toggle-password fa-eye-slash"></span>
                </div>
              </div>
              <div class="form-login">
                <label for="password-confirm">Konfirmasi Password</label>
                <div class="pass-group">
                  <input type="password" id="password-confirm" class="pass-input " placeholder="Masukan konfirmasi password"
                    required>
                  <span class="fas toggle-password fa-eye-slash"></span>
                </div>
              </div>
              <div class="form-login">
                <button type="submit" class="btn btn-login">Daftar</button>
              </div>
            </form>
            <div class="signinform text-center">
              <h4>Sudah punya akun? <a href="/" class="hover-a">Masuk sekarang</a></h4>
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

      const token = localStorage.getItem('access_token')
      const expirationTime = localStorage.getItem('expires_at')

      if (token && expirationTime && Date.now() < parseInt(expirationTime)) {
        window.location.href = "{{ url('/dashboard') }}"
      }

      $("#phone").keypress(function(e) {
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
          return false;
        }
      })

      $('#signup-form').on('submit', function() {
        event.preventDefault()
        $('#global-loader').show()


        if ($('#password-confirm').val() != $('#password').val()) {
          $('#global-loader').hide()
          Swal.fire({
            icon: 'warning',
            title: 'Oops...',
            text: 'Konfirmasi password salah'
          })
          return
        }

        const data = {
          fullname: $('#fullname').val(),
          email: $('#email').val(),
          birthdate: $('#birthdate').val(),
          phone_number: $('#phone').val(),
          address: $('#address').val(),
          password: $('#password').val()
        }

        let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        let config = {
          headers: {
            'X-CSRF-TOKEN': token,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          }
        }

        axios.post("{{ url('api/v1/auth/register') }}", data, config)
          .then(function(res) {
            // $('#global-loader').hide()
            window.location.href = "{{ url('/verification') }}"
          })
          .catch(function(err) {
            $('#global-loader').hide()

            if (err.response.data.meta.code === 422) {
              const errMessage = err.response.data.errors[0]

              if (errMessage.email) {
                Swal.fire({
                  icon: 'warning',
                  title: 'Oops...',
                  text: 'Email Sudah digunakan'
                })
                return
              }

              if (errMessage.phone_number) {
                Swal.fire({
                  icon: 'warning',
                  title: 'Oops...',
                  text: 'Nomor Sudah digunakan'
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
