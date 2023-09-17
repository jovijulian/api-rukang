@extends('layouts/content')

@section('title')
  <title>Edit Ekspedisi</title>
@endsection

@section('content')
  <div class="cardhead">
    <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
        <div class="row">
          <div class="col">
            <h3 class="page-title">Edit Data Ekspedisi</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ url('/shipping') }}">Ekspedisi</a></li>
              <li class="breadcrumb-item active">Edit Ekspedisi</li>
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
              <form id="update-shipping-form">
                <div class="form-group row">
                  <label class="col-lg-2 col-form-label">Nama Ekspedisi</label>
                  <div class="col-lg-10">
                    <input type="text" id="shipping" class="form-control" placeholder="Masukan nama ekspedisi" required>
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

      $('#update-shipping-form').on('submit', () => {
        event.preventDefault()
        $('#global-loader').show()

        const data = {
          shipping_name: $('#shipping').val(),
        }


        axios.put("{{ url('api/v1/shipping/update/' . $id) }}", data, config)
          .then(res => {
            const shipping = res.data.data.item
            sessionStorage.setItem("success", `${shipping.shipping_name} berhasil diubah`)
            window.location.href = "{{ url('/shipping') }}"
          })
          .catch(err => {
            $('#global-loader').hide()
            Swal.fire('Ekspedisi gagal diubah', '', 'error')
            console.log(err)
          })

      })

      function getData() {
        axios.get("{{ url('api/v1/shipping/detail/' . $id) }}", config)
          .then(res => {
            const data = res.data.data.item
            $('#shipping').val(data.shipping_name)
          })
          .catch(err => {
            console.log(err)
          })
      }

    })
  </script>
@endsection
