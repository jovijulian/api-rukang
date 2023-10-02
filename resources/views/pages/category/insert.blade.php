@extends('layouts/content')

@section('title')
  <title>Tambah Kategori</title>
@endsection

@section('content')
  <div class="cardhead">
    <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
        <div class="row">
          <div class="col">
            <h3 class="page-title">Tambah Data Kategori</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ url('/category') }}">Kategori</a></li>
              <li class="breadcrumb-item active">Tambah Kategori</li>
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
              <form id="insert-category-form">
                <div class="form-group row">
                  <label class="col-lg-2 col-form-label">Nama Kategori *</label>
                  <div class="col-lg-10">
                    <input type="text" id="category" class="form-control" placeholder="Masukan nama kategori">
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
      // if (!currentUser.isAdmin) {
      //   window.location.href = "{{ url('/dashboard') }}"
      // }


      $('#insert-category-form').on('submit', () => {
        event.preventDefault()
        $('#global-loader').show()

        const data = {
          category: $('#category').val(),
        }

        let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        let config = {
          headers: {
            'X-CSRF-TOKEN': token,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': `${tokenType} ${accessToken}`
          }
        }

        axios.post("{{ url('api/v1/category/create') }}", data, config)
          .then(res => {
            const category = res.data.data.item
            sessionStorage.setItem("success", `${category.category} berhasil ditambahkan`)
            window.location.href = "{{ url('/category') }}"
          })
          .catch(err => {
            $('#global-loader').hide()

            let errorMessage = ''

            if (err.response.status == 422) {
              const errors = err.response.data.errors[0]
              for (const key in errors) {
                errorMessage += `${errors[key]} \n`
              }
            } else if(err.response.status == 500) {
              errorMessage = 'Internal server error'
            }

            Swal.fire({
              icon: 'error',
              title: 'Kategori gagal ditambahkan',
              text: errorMessage
            })
          })

      })

    })
  </script>
@endsection
