{{-- @extends('layouts/blankLayout') --}}

@section('title', 'Reset Password')

@section('page-style')
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}">
@endsection
<style>
    .loader-container {
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
    }

    .loader {
        border: 16px solid #000;
        border-radius: 50%;
        border-top: 16px solid #3498db;
        width: 120px;
        height: 120px;
        -webkit-animation: spin 2s linear infinite;
        animation: spin 2s linear infinite;
        background-color: #e3e3e3;
        padding: 20px;
        position: absolute;
        z-index: 9
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    /* Media Query untuk perangkat mobile dengan lebar maksimum 768px */
    @media (max-width: 768px) {
        .loader-container {
            flex-direction: column;
        }
    }
</style>
@section('content')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Register -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <span class="app-brand-text text-body fw-bolder" style="text-decoration: none">MEET UP
                                BOXER</span>
                            {{--              <a href="{{url('/')}}" class="app-brand-link gap-2"> --}}
                            {{--                <span class="app-brand-logo demo">@include('_partials.macros',["width"=>25,"withbg"=>'#696cff'])</span> --}}
                            {{--               --}}
                            {{--              </a> --}}
                        </div>
                        <!-- /Logo -->
                        <p class="mb-4">Silahkan tambahkan password baru Anda</p>

                        <form id="formAuthentication" class="mb-3">
                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">Password</label>
                                </div>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>
                            <div class="loader-container">
                                <div class="loader" id="loader" style="display: none"></div>
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">Konfirmasi Password</label>
                                </div>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="confirm_password" class="form-control"
                                        name="confirm_password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <button class="btn d-grid w-100" type="submit"
                                    style="background: #0D7CC4; color: #fff">Ganti Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /Register -->
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.0.slim.js"
        integrity="sha256-7GO+jepT9gJe9LB4XFf8snVOjX3iYNb0FHYr5LI1N5c=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
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
    </script>
@endsection
