@extends('layouts/content')

@section('title')
  <title>Tambah Rak</title>
@endsection

@section('content')
  <div class="cardhead">
    <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
        <div class="row">
          <div class="col">
            <h3 class="page-title">Tambah Data Rak</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ url('/shelf') }}">Rak</a></li>
              <li class="breadcrumb-item active">Tambah Rak</li>
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
              <form id="insert-shelf-form">
                <div class="form-group row">
                  <label class="col-lg-2 col-form-label">Nama Rak</label>
                  <div class="col-lg-10">
                    <input type="text" id="shelf" class="form-control" placeholder="Masukan nama rak" required>
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


      $('#insert-shelf-form').on('submit', () => {
        event.preventDefault()
        $('#global-loader').show()

        const data = {
          shelf_name: $('#shelf').val(),
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

        axios.post("{{ url('api/v1/shelf/create') }}", data, config)
          .then(res => {
            const shelf = res.data.data.item
            sessionStorage.setItem("success", `${shelf.shelf_name} berhasil ditambahkan`)
            window.location.href = "{{ url('/shelf') }}"
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
              title: 'Rak gagal ditambahkan',
              text: errorMessage
            })
          })

      })

    })
  </script>
@endsection
