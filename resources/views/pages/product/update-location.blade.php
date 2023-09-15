@extends('layouts/content')

@section('title')
  <title>Update Lokasi Produk</title>
@endsection

@section('content')
  <div class="cardhead">
    <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
        <div class="row">
          <div class="col">
            <h3 class="page-title">Update Lokasi</h3>
            {{-- <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ url('/module') }}">Modul</a></li>
              <li class="breadcrumb-item active">Edit Modul</li>
            </ul> --}}
          </div>
        </div>
      </div>
      <!-- /Page Header -->

      <div class="row">
        <div class="col-xl-7 d-flex">
          <div class="card flex-fill">
            <div class="card-body p-4">
              <form id="update-location-form">
                <div class="form-group row">
                  <label class="col-lg-2 col-form-label">Lokasi Terkini</label>
                  <div class="col-lg-10">
                    <input type="text" id="location" class="form-control" placeholder="Masukan lokasi terkini" required>
                  </div>
                </div>
                <div class="text-end">
                  <button type="submit" class="btn btn-primary">Update Lokasi</button>
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
      

      $('#update-location-form').on('submit', () => {
        event.preventDefault()
        $('#global-loader').show()

        const data = {
          current_location: $('#location').val(),
        }


        axios.put("{{ url('api/v1/product/edit-location/' . $id) }}", data, config)
          .then(res => {
            // console.log(res.data.data.item.product_id)
            sessionStorage.setItem("success", `Lokasi berhasil diupdate`)
            window.location.href = `{{ url('/product/detail/${res.data.data.item.product_id}') }}`
          })
          .catch(err => {
            $('#global-loader').hide()
            Swal.fire('Lokasi gagal diupdate', '', 'error')
            console.log(err)
          })

      })

    })
  </script>
@endsection
