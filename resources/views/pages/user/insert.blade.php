@extends('layouts/content')

@section('title')
    <title>Tambah User</title>
@endsection

@section('content')
    <div class="cardhead">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <h3 class="page-title">Tambah Data User</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/user') }}">User</a></li>
                            <li class="breadcrumb-item active">Tambah User</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-xl-7 d-flex">
                    <div class="card flex-fill">
                        {{-- <div class="card-header">
              <h5 class="card-title">Basic Form</h5>
            </div> --}}
                        <div class="card-body p-4">
                            <form id="insert-user-form">
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Nama Lengkap *</label>
                                    <div class="col-lg-10">
                                        <input type="text" id="fullname" class="form-control"
                                            placeholder="Masukan nama lengkap" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Role *</label>
                                    <div class="col-lg-10">
                                        <select id="role" class="form-control select" required>
                                            <option selected="selected" disabled>Pilih role </option>
                                            <option value="1">Admin</option>
                                            <option value="2">Admin Produksi</option>
                                            <option value="3">Admin Pengiriman</option>
                                            <option value="4">Admin Onsite</option>
                                            <option value="5">Owner</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Email *</label>
                                    <div class="col-lg-10">
                                        <input type="email" id="email" class="form-control"
                                            placeholder="Masukan email" required>
                                    </div>
                                </div>
                                {{-- <div class="form-group row">
                  <label class="col-lg-2 col-form-label">No HP *</label>
                  <div class="col-lg-10">
                    <input type="text" id="phone" class="form-control" placeholder="Masukan no hp" required>
                  </div>
                </div> --}}
                                {{-- <div class="form-group row">
                  <label class="col-lg-2 col-form-label">Tanggal Lahir *</label>
                  <div class="col-lg-10">
                    <input type="date" id="birthdate" class="form-control" placeholder="Masukan tanggal lahir" required>
                  </div>
                </div> --}}
                                {{-- <div class="form-group row">
                  <label class="col-lg-2 col-form-label">Alamat *</label>
                  <div class="col-lg-10">
                    <textarea rows="3" cols="5" id="address" class="form-control" placeholder="Masukan alamat anda" required></textarea>
                  </div>
                </div> --}}
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Password *</label>
                                    <div class="col-lg-10">
                                        <div class="pass-group">
                                            <input type="password" id="password" class="pass-input "
                                                placeholder="Masukan password" required>
                                            <span class="fas toggle-password fa-eye-slash"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Konfirmasi Password *</label>
                                    <div class="col-lg-10">
                                        <div class="pass-group">
                                            <input type="password" id="password-confirm" class="pass-input"
                                                placeholder="Masukan konfirmasi password" required>
                                            <span class="fas toggle-password fa-eye-slash"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">Tambah Data</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const currentUser = JSON.parse(localStorage.getItem('current_user'))
            const tokenType = localStorage.getItem('token_type')
            const accessToken = localStorage.getItem('access_token')

            // REDIRECT IF NOT ADMIN
            if (!currentUser.isAdmin) {
                window.location.href = "{{ url('/dashboard') }}"
            }

            // PHONE NUMBER HANYA ANGKA
            $("#phone").keypress(function(e) {
                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    return false;
                }
            })

            let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            let config = {
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `${tokenType} ${accessToken}`
                }
            }

            $('#insert-user-form').on('submit', () => {
                event.preventDefault()
                $('#global-loader').show()

                const data = {
                    fullname: $('#fullname').val(),
                    email: $('#email').val(),
                    birthdate: '',
                    phone_number: '',
                    address: '',
                    password: $('#password').val(),
                    isAdmin: $('#role').val(),
                }

                // VALIDASI GROUP SELECT
                // if (!data.group_id) {
                //   $('#global-loader').hide()
                //   Swal.fire({
                //     icon: 'warning',
                //     title: 'Oops...',
                //     text: 'Tolong pilih kelompok anda'
                //   })
                //   return
                // }

                // VALIDASI PASSWORD
                if ($('#password-confirm').val() != data.password) {
                    $('#global-loader').hide()
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Konfirmasi password salah'
                    })
                    return
                }

                // console.log(data);
                // return

                axios.post("{{ url('api/v1/user/create') }}", data, config)
                    .then(res => {
                        const user = res.data.data.item
                        sessionStorage.setItem("success", `${user.fullname} berhasil ditambahkan`)
                        window.location.href = "{{ url('/user') }}"
                    })
                    .catch(err => {
                        $('#global-loader').hide()

                        let errorMessage = ''

                        if (err.response.status == 422) {
                            const errors = err.response.data.errors[0]
                            for (const key in errors) {
                                errorMessage += `${errors[key]} \n`
                            }
                        } else if (err.response.status == 500) {
                            errorMessage = 'Interbal server error'
                        }


                        Swal.fire({
                            icon: 'error',
                            title: 'User gagal ditambahkan',
                            text: errorMessage
                        })
                    })

            })

        })
    </script>
@endsection
