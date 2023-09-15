@extends('layouts/content')

@section('title')
  <title>Detail Produk</title>
@endsection

@section('content')
  <div class="content">
    <div class="page-header">
      <div class="page-title">
        <h4>Detail Produk</h4>
        <h6>Informasi detail produk</h6>
      </div>
      <div class="page-btn">
        <a href="/product/update-status" class="btn btn-added update-status">Update Status Produk</a>
      </div>
    </div>
    <!-- /add -->
    <div class="row">
      <div class="col-lg-8 col-sm-12">
        <div class="card">
          <div class="card-body">
            <div class="productdetails">
              <ul class="product-bar">
                <li>
                  <h4>Kategori</h4>
                  <h6 id="category"></h6>
                </li>
                <li>
                  <h4>Segmen</h4>
                  <h6 id="segment"></h6>
                </li>
                <li>
                  <h4>Nomor Modul</h4>
                  <h6 id="module-number"></h6>
                </li>
                <li>
                  <h4>Nomor Bilah</h4>
                  <h6 id="bilah-number"></h6>
                </li>
                <li>
                  <h4>Nomor Rak</h4>
                  <h6 id="shelf-number"></h6>
                </li>
                <li>
                  <h4>1/0</h4>
                  <h6 id="quantity"></h6>
                </li>
                <li>
                  <h4>Dibaut dan Dimur</h4>
                  <h6 id="nut-bolt"></h6>
                </li>
                <li>
                  <h4>Deskripsi</h4>
                  <h6 id="description"></h6>
                </li>
                <li>
                  <h4>Tanggal Produksi</h4>
                  <h6 id="production-date"></h6>
                </li>
                <li>
                  <h4>Tanggal Pengiriman</h4>
                  <h6 id="delivery-date"></h6>
                </li>
                <li>
                  <h4>Catatan</h4>
                  <h6 id="product-note"></h6>
                </li>
                <li>
                  <h4>Dibuat Pada</h4>
                  <h6 id="created-at"></h6>
                </li>
                <li>
                  <h4>Dibuat Oleh</h4>
                  <h6 id="created-by"></h6>
                </li>
                <li>
                  <h4>Diubah Pada</h4>
                  <h6 id="updated-at"></h6>
                </li>
                <li>
                  <h4>Diubah Oleh</h4>
                  <h6 id="updated-by"></h6>
                </li>
              </ul>
              <div class="table-responsive mt-5">
                <h4 class="mb-3">Log Status</h4>
                <table class="table mb-0">
                  <thead>
                    <tr>
                      <th>Foto Status</th>
                      <th>Status</th>
                      <th>Catatan</th>
                      <th>Dibuat Pada</th>
                      <th>Dibuat Oleh</th>
                      <th>Diubah Pada</th>
                      <th>Diubah Oleh</th>
                    </tr>
                  </thead>
                  <tbody id="status-table">
                  </tbody>
                </table>
              </div>
              <div class="table-responsive mt-5">
                <h4 class="mb-3">Log Lokasi</h4>
                <table class="table mb-0">
                  <thead>
                    <tr>
                      <th>Action</th>
                      <th>Lokasi Terkini</th>
                      <th>Status</th>
                      <th>Dibuat Pada</th>
                      <th>Dibuat Oleh</th>
                      <th>Diubah Pada</th>
                      <th>Diubah Oleh</th>
                    </tr>
                  </thead>
                  <tbody id="location-table">
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-sm-12">
        <div class="card">
          <div class="card-body">
            <div class="slider-product-details">
              <div class="w-100 border mx-auto p-5">
                <svg id="barcode" class="mx-auto w-100 h-16"></svg>
              </div>
              <div class="slider-product mt-5 border p-3">
                <img src="{{ url('assets/img/product/product69.jpg') }}" id="image-status" alt="img">
                <h4>Foto Status Terakhir</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- /add -->
  </div>

  <script>
    $(document).ready(function() {
      const currentUser = JSON.parse(localStorage.getItem('current_user'))
      const tokenType = localStorage.getItem('token_type')
      const accessToken = localStorage.getItem('access_token')

      // NOTIF VERIFY USER
      const success = sessionStorage.getItem("success")
      if (success) {
        Swal.fire(success, '', 'success')
        sessionStorage.removeItem("success")
      }

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

      axios.get("{{ url('api/v1/product/detail/' . $id) }}", config)
        .then(res => {
          const product = res.data.data.item

          $('.update-status').attr('href', '/product/update-status/' + product.id)

          JsBarcode("#barcode", product.barcode)

          $('#category').text(product.category ? product.category : '')
          $('#segment').text(product.segment ? product.segment : '')
          $('#module-number').text(product.module ? product.module : '')
          $('#bilah-number').text(product.bilah_number ? product.bilah_number : '')
          $('#shelf-number').text(product.shelf_number ? product.shelf_number : '')
          $('#quantity').text(product.quantity ? product.quantity : '')
          $('#nut-bolt').text(product.nut_bolt ? 'Ya' : 'Tidak')
          $('#description').text(product.description ? product.description : '')
          $('#production-date').text(product.production_date ? new Date(product.production_date).toISOString().split('T')[0].split('-').reverse().join('-') : '')
          $('#delivery-date').text(product.delivery_date ? new Date(product.delivery_date).toISOString().split('T')[0].split('-').reverse().join('-') : '')
          $('#product-note').text(product.note ? product.note : '')
          $('#created-at').text(new Date(product.created_at).toISOString().split('T')[0].split('-').reverse().join('-'))
          $('#created-by').text(product.created_by)
          $('#updated-at').text(new Date(product.updated_at).toISOString().split('T')[0].split('-').reverse().join('-'))
          $('#updated-by').text(product.updated_by)

          product.status_logs[0].status_photo && $("#image-status").attr("src", product.status_logs[0].status_photo)
          
          product.status_logs.map((statusLog, i) => {
            $('#status-table').append(
              `
                <tr>
                  <td><img class="cursor-pointer" src="${statusLog.status_photo ? statusLog.status_photo : "{{ url('assets/img/product/product1.jpg') }}"}" onclick="previewPhoto('${statusLog.status_photo ? statusLog.status_photo : "{{ url('assets/img/product/product1.jpg') }}"}')" width="40" alt="${statusLog.id + statusLog.status_date}"></td>
                  <td>${statusLog.status_name ? statusLog.status_name : ''}</td>
                  <td>${statusLog.note ? statusLog.note : ''}</td>
                  <td>${new Date(statusLog.created_at).toISOString().split('T')[0].split('-').reverse().join('-')}</td>
                  <td>${statusLog.created_by ? statusLog.created_by : ''}</td>
                  <td>${new Date(statusLog.updated_at).toISOString().split('T')[0].split('-').reverse().join('-')}</td>
                  <td>${statusLog.updated_by ? statusLog.updated_by : ''}</td>
                </tr>
              `
            )
          })

          product.location_logs.map((locationLog, i) => {
            const link = `<a href="{{ url('product/edit-location/` + locationLog.status_log_id + `') }}" class='p-2 btn btn-submit text-white'>Update Lokasi</a>`

            $('#location-table').append(
              `
                <tr>
                  <td>${locationLog.current_location ? link : ''}</td>
                  <td>${locationLog.current_location ? locationLog.current_location : ''}</td>
                  <td>${locationLog.status.status_name}</td>
                  <td>${new Date(locationLog.created_at).toISOString().split('T')[0].split('-').reverse().join('-')}</td>
                  <td>${locationLog.created_by ? locationLog.created_by : ''}</td>
                  <td>${new Date(locationLog.updated_at).toISOString().split('T')[0].split('-').reverse().join('-')}</td>
                  <td>${locationLog.updated_by ? locationLog.updated_by : ''}</td>
                </tr>
              `
            )
          })

        })
        .catch(err => {
          console.log(err)
        })
    })

    function previewPhoto(url) {
      Swal.fire({
        showConfirmButton: false,
        imageUrl: url,
        imageWidth: 700,
        width: 700
      })
    }
  </script>
@endsection
