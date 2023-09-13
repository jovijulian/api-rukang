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
                  <h4>Tanggal Produksi</h4>
                  <h6 id="production-date"></h6>
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
                  <h4>Tanggal Pengiriman</h4>
                  <h6 id="delivery-date"></h6>
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
                <table class="table mb-0">
                  <thead>
                    <tr>
                      <th>Foto Status</th>
                      <th>Status</th>
                      <th>Tanggal Status</th>
                      <th>Dibuat Pada</th>
                      <th>Dibuat Oleh</th>
                      <th>Diubah Pada</th>
                      <th>Diubah Oleh</th>
                    </tr>
                  </thead>
                  <tbody id="status-table">
                    {{-- <tr>
                      <td>John</td>
                      <td>Doe</td>
                      <td>john@example.com</td>
                    </tr>
                    <tr>
                      <td>Mary</td>
                      <td>Moe</td>
                      <td>mary@example.com</td>
                    </tr>
                    <tr>
                      <td>July</td>
                      <td>Dooley</td>
                      <td>july@example.com</td>
                    </tr> --}}
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
              {{-- <div class="owl-carousel owl-theme product-slide">
                <div class="slider-product">
                  <img src="assets/img/product/product69.jpg" alt="img">
                  <h4>macbookpro.jpg</h4>
                  <h6>581kb</h6>
                </div>
              </div> --}}
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

          console.log(product);


          JsBarcode("#barcode", product.barcode)

          $('#category').text(product.category ? product.category : '')
          $('#segment').text(product.segment.segment_name ? product.segment.segment_name : '')
          $('#module-number').text(product.module.module_number ? product.module.module_number : '')
          $('#bilah-number').text(product.bilah_number ? product.bilah_number : '')
          $('#production-date').text(product.production_date ? product.production_date : '')
          $('#shelf-number').text(product.shelf_number ? product.shelf_number : '')
          $('#quantity').text(product.quantity ? product.quantity : '')
          $('#nut-bolt').text(product.nut_bolt ? 'Ya' : 'Tidak')
          $('#description').text(product.description ? product.description : '')
          $('#delivery-date').text(product.delivery_date ? product.delivery_date : '')
          $('#created-at').text(new Date(product.created_at).toISOString().split('T')[0])
          $('#created-by').text(product.created_by)
          $('#updated-at').text(new Date(product.updated_at).toISOString().split('T')[0])
          $('#updated-by').text(product.updated_by)

          $("#image-status").attr("src", product.status_logs[0].status_photo)
          
          product.status_logs.map((statusLog, i) => {
            $('#status-table').append(
              `
                <tr>
                  <td><img src="${statusLog.status_photo}" width="40" alt="${statusLog.id}"></td>
                  <td>${statusLog.status_name}</td>
                  <td>${statusLog.status_date}</td>
                  <td>${new Date(statusLog.created_at).toISOString().split('T')[0]}</td>
                  <td>${statusLog.created_by}</td>
                  <td>${new Date(statusLog.updated_at).toISOString().split('T')[0]}</td>
                  <td>${statusLog.updated_by ? statusLog.updated_by : ''}</td>
                </tr>
              `
            )
          })

          // $('#status').text(product.status_log.status_name)
          // $('#status-date').text(product.status_log.status_date)
          // $('#note').text(product.status_log.note)
          // $('#created-at-status').text(formattedCreatedAtStatus)
          // $('#created-by-status').text(product.status_log.created_by)
          // $('#updated-at-status').text(formattedUpdatedAtStatus)
          // $('#updated-by-status').text(product.status_log.updated_by)

          // $("#image-status").attr("src", product.status_photo)

        })
        .catch(err => {
          console.log(err)
        })
    })
  </script>
@endsection
