@extends('layouts/content')

@section('title')
    <title>Tambah Talent</title>
@endsection

@section('content')
    <div class="cardhead">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <h3 class="page-title">Tambah Data Talent</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/talent') }}">Talent</a></li>
                            <li class="breadcrumb-item active">Tambah Talent</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-xl-11 d-flex">
                    <div class="card flex-fill">
                        {{-- <div class="card-header">
              <h5 class="card-title">Basic Form</h5>
            </div> --}}
                        <div class="card-body p-4">
                            <form id="insert-talent-form">
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Nama Talent *</label>
                                    <div class="col-lg-10">
                                        <input type="text" id="fullname" class="form-control"
                                            placeholder="Masukan nama lengkap talent" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Email</label>
                                    <div class="col-lg-10">
                                        <input type="email" id="email" class="form-control"
                                            placeholder="Masukan email talent">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">No. Handphone *</label>
                                    <div class="col-lg-10">
                                        <input type="number" id="phone_number" class="form-control"
                                            placeholder="Masukan nomor handphone talent" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Tentang tukang </label>
                                    <div class="col-lg-10">

                                        <textarea rows="3" cols="5" id="about_me" class="form-control"
                                            placeholder="Masukan tentang / biodata / slogan tukang"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Alamat tukang </label>
                                    <div class="col-lg-10">
                                        <textarea rows="3" cols="5" id="address" class="form-control" placeholder="Masukan alamat tukang"
                                            required></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Kategory / Servis yang ditawarkan </label>
                                    <div class="col-lg-10">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="category_id1">
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Atap
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="category_id2">
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Cat
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="category_id3">
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Keramik
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="category_id4">
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Pipa
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Upload Foto Tukang (Maks 4 Foto)</label>
                                    <div class="col-lg-10">
                                        <input class="form-control mb-1" type="file" id="image-profile" accept="image/*"
                                            multiple>
                                        <div id="image-preview" class="mt-2 row"></div>
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

            const tokenType = localStorage.getItem('token_type')
            const accessToken = localStorage.getItem('access_token')

            // REDIRECT IF NOT ADMIN
            // if (!currentUser.isAdmin) {
            //   window.location.href = "{{ url('/dashboard') }}"
            // }

            let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            let config = {
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Content-Type': 'multipart/form-data',
                    'Accept': 'application/json',
                    'Authorization': `${tokenType} ${accessToken}`
                }
            }

            $('#image-profile').change(function(event) {
                if (this.files) {
                    let fileAmount = this.files.length
                    $('#image-preview img').remove()

                    if (fileAmount > 10) {
                        console.log('salah');
                        Swal.fire('Maksimal upload 4 foto', '', 'error')
                        $('#image-profile').val('')
                        return
                    }

                    for (let i = 0; i < fileAmount; i++) {
                        let reader = new FileReader()
                        reader.onload = function() {
                            $('#image-preview').append(
                                `<img src="${this.result}" alt="" class="mx-auto col-2 m-1" alt="" style="height: 70px; width: auto">`
                            )
                        }
                        reader.readAsDataURL(this.files[i])
                    }
                }
            })

            var category1 = '';
            var category2 = '';
            var category3 = '';
            var category4 = '';

            $("#category_id1").change(function() {
                var isChecked = $(this).is(":checked");
                category1 = isChecked ? 2 : '';
            });

            $("#category_id2").change(function() {
                var isChecked = $(this).is(":checked");
                category2 = isChecked ? 3 : '';
            });

            $("#category_id3").change(function() {
                var isChecked = $(this).is(":checked");
                category3 = isChecked ? 4 : '';
            });

            $("#category_id4").change(function() {
                var isChecked = $(this).is(":checked");
                category4 = isChecked ? 5 : '';
            });


            $('#insert-talent-form').on('submit', () => {
                event.preventDefault()
                $('#global-loader').show()

                const data = {
                    fullname: $('#fullname').val(),
                    email: $('#email').val(),
                    phone_number: $('#phone_number').val(),
                    about_me: $('#about_me').val(),
                    address: $('#address').val(),
                    category_id1: category1,
                    category_name1: category1 ? 'Atap' : '',
                    category_id2: category2,
                    category_name2: category2 ? 'Cat' : '',
                    category_id3: category3,
                    category_name3: category3 ? 'Keramik' : '',
                    category_id4: category4,
                    category_name4: category4 ? 'Pipa' : '',
                    image_profile: $('#image-profile')[0].files[0] ? $('#image-profile')[0]
                        .files[0] : '',
                    image_profile2: $('#image-profile')[0].files[2] ? $('#image-profile')[0]
                        .files[2] : '',
                    image_profile3: $('#image-profile')[0].files[1] ? $('#image-profile')[0]
                        .files[1] : '',
                    image_profile4: $('#image-profile')[0].files[3] ? $('#image-profile')[0]
                        .files[3] : '',
                }

                // console.log(data);
                // return


                axios.post("{{ url('api/v1/talent/create') }}", data, config)
                    .then(res => {
                        // const shelf = res.data.data.item
                        sessionStorage.setItem("success", `Talent berhasil ditambahkan`)
                        window.location.href = "{{ url('/talent') }}"
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
                            errorMessage = 'Internal server error'
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Talent gagal ditambahkan',
                            text: errorMessage
                        })
                    })

            })

        })
    </script>
@endsection
