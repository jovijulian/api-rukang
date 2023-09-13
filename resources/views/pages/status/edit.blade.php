@extends('layouts/content')

@section('title')
  <title>Edit Status</title>
@endsection

@section('content')
  <div class="cardhead">
    <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
        <div class="row">
          <div class="col">
            <h3 class="page-title">Edit Data Status</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ url('/status') }}">Status</a></li>
              <li class="breadcrumb-item active">Edit Status</li>
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
              <form id="update-status-form">
                <div class="form-group row">
                  <label class="col-lg-2 col-form-label">Status</label>
                  <div class="col-lg-10">
                    <input type="text" id="status" class="form-control" placeholder="Masukan status" required>
                  </div>
                </div>
                <div class="text-end">
                  <button type="submit" class="btn btn-primary">Edit Data</button>
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

      let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      let config = {
        headers: {
          'X-CSRF-TOKEN': token,
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'Authorization': `${tokenType} ${accessToken}`
        }
      }
      
      getData()

      $('#update-status-form').on('submit', () => {
        event.preventDefault()
        $('#global-loader').show()

        const data = {
          status: $('#status').val(),
        }


        axios.put("{{ url('api/v1/status/update/' . $id) }}", data, config)
          .then(res => {
            const status = res.data.data.item
            sessionStorage.setItem("success", `${status.status} berhasil diedit`)
            window.location.href = "{{ url('/status') }}"
          })
          .catch(err => {
            $('#global-loader').hide()
            Swal.fire('Kategori gagal diedit', '', 'error')
            console.log(err)
          })

      })

      function getData() {
        axios.get("{{ url('api/v1/status/detail/' . $id) }}", config)
          .then(res => {
            const data = res.data.data.item
            $('#status').val(data.status)
          })
          .catch(err => {
            console.log(err)
          })
      }

    })
  </script>
@endsection