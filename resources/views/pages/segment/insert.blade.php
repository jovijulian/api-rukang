@extends('layouts/content')

@section('title')
  <title>Tambah Segmen</title>
@endsection

@section('content')
  <div class="cardhead">
    <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
        <div class="row">
          <div class="col">
            <h3 class="page-title">Tambah Data Segmen</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ url('/segment') }}">Segmen</a></li>
              <li class="breadcrumb-item active">Tambah Segmen</li>
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
              <form id="insert-segment-form">
                <div class="form-group row">
                  <label class="col-lg-2 col-form-label">Nama</label>
                  <div class="col-lg-10">
                    <input type="text" id="segment-name" class="form-control" placeholder="Masukan nama segmen" required>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-lg-2 col-form-label">Tempat</label>
                  <div class="col-lg-10">
                    <input type="text" id="segment-place" class="form-control" placeholder="Masukan tempat segmen" required>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-lg-2 col-form-label">Warna Barcode</label>
                  <div class="col-lg-10">
                    <input type="text" id="barcode-color" class="form-control" placeholder="Masukan warna barcode segmen">
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


      $('#insert-segment-form').on('submit', () => {
        event.preventDefault()
        $('#global-loader').show()

        const data = {
          segment_name: $('#segment-name').val(),
          segment_place: $('#segment-place').val(),
          barcode_color: $('#barcode-color').val(),
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

        axios.post("{{ url('api/v1/segment/create') }}", data, config)
          .then(res => {
            const segmen = res.data.data.item
            sessionStorage.setItem("success", `Segmen ${segmen.segment_name} berhasil ditambahkan`)
            window.location.href = "{{ url('/segment') }}"
          })
          .catch(err => {
            $('#global-loader').hide()
            Swal.fire('Segmen gagal ditambahkan', '', 'error')
            console.log(err)
          })

      })

    })
  </script>
@endsection
