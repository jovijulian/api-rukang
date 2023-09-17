@extends('layouts/content')

@section('title')
  <title>Edit Kelompok</title>
@endsection

@section('content')
  <div class="cardhead">
    <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
        <div class="row">
          <div class="col">
            <h3 class="page-title">Edit Data Kelompok</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ url('/group') }}">Kelompok</a></li>
              <li class="breadcrumb-item active">Edit Kelompok</li>
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
              <form id="update-group-form">
                <div class="form-group row">
                  <label class="col-lg-2 col-form-label">Nama Kelompok</label>
                  <div class="col-lg-10">
                    <input type="text" id="group" class="form-control" placeholder="Masukan nama kelompok" required>
                  </div>
                </div>
                <div class="text-end">
                  <button type="submit" class="btn btn-primary">Ubah Data</button>
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

      $('#update-group-form').on('submit', () => {
        event.preventDefault()
        $('#global-loader').show()

        const data = {
          group_name: $('#group').val(),
        }


        axios.put("{{ url('api/v1/group/update/' . $id) }}", data, config)
          .then(res => {
            const group = res.data.data.item
            sessionStorage.setItem("success", `${group.group_name} berhasil diubah`)
            window.location.href = "{{ url('/group') }}"
          })
          .catch(err => {
            $('#global-loader').hide()
            Swal.fire('Kelompok gagal diubah', '', 'error')
            console.log(err)
          })

      })

      function getData() {
        axios.get("{{ url('api/v1/group/detail/' . $id) }}", config)
          .then(res => {
            const data = res.data.data.item
            $('#group').val(data.group_name)
          })
          .catch(err => {
            console.log(err)
          })
      }

    })
  </script>
@endsection
