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


                        <form id="formAuthentication" class="mb-3">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Masukan email" autofocus>
                            </div>
                            <div class="loader-container">
                                <div class="loader" id="loader" style="display: none"></div>
                            </div>
                            <div class="mb-3">
                                <button class="btn d-grid w-100" type="submit"
                                    style="background: #0D7CC4; color: #fff">Reset Password
                                </button>
                            </div>
                        </form>

                        <p class="text-center">
                            <span>Belum mempunyai akun?</span>
                            <a href="{{ url('/users/register') }}">
                                <span style="color: #0D7CC4">Daftar disini</span>
                            </a>
                        </p>
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
    </script>
@endsection
