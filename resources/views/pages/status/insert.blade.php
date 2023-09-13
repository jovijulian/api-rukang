@extends('layouts/content')

@section('title')
  <title>Tambah Status</title>
@endsection

@section('content')
  <div class="cardhead">
    <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
        <div class="row">
          <div class="col">
            <h3 class="page-title">Tambah Data Status</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ url('/category') }}">Status</a></li>
              <li class="breadcrumb-item active">Tambah Status</li>
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
              <form id="insert-status-form">
                <div class="form-group row">
                  <label class="col-lg-2 col-form-label">Status</label>
                  <div class="col-lg-10">
                    <input type="text" id="status" class="form-control" placeholder="Masukan status" required>
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


      $('#insert-status-form').on('submit', () => {
        event.preventDefault()
        $('#global-loader').show()

        const data = {
          status: $('#status').val(),
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

        axios.post("{{ url('api/v1/status/create') }}", data, config)
          .then(res => {
            const status = res.data.data.item
            sessionStorage.setItem("success", `${status.status} berhasil ditambahkan`)
            window.location.href = "{{ url('/status') }}"
          })
          .catch(err => {
            $('#global-loader').hide()
            Swal.fire('Status gagal ditambahkan', '', 'error')
            console.log(err)
          })

      })

    })
  </script>
@endsection
