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
                  <h6 id="category">Loading</h6>
                </li>
                <li>
                  <h4>Segmen</h4>
                  <h6 id="segment">Loading</h6>
                </li>
                <li>
                  <h4>Nomor Modul</h4>
                  <h6 id="module-number">Loading</h6>
                </li>
                <li>
                  <h4>Nomor Bilah</h4>
                  <h6 id="bilah_number">Loading</h6>
                </li>
                <li>
                  <h4>Tanggal Produksi</h4>
                  <h6 id="production-date">Loading</h6>
                </li>
                <li>
                  <h4>Nomor Rak</h4>
                  <h6 id="shelf-number">Loading</h6>
                </li>
                <li>
                  <h4>1/0</h4>
                  <h6 id="">Ya / Tidak</h6>
                </li>
                <li>
                  <h4>Dibaut dan Dimur</h4>
                  <h6 id="nut-bolt">Loading</h6>
                </li>
                <li>
                  <h4>Deskripsi</h4>
                  <h6 id="description">Loading</h6>
                </li>
                <li>
                  <h4>Tanggal Pengiriman</h4>
                  <h6 id="delivery-date">Loading</h6>
                </li>
                <li>
                  <h4>Dibuat Pada</h4>
                  <h6 id="created-at">Loading</h6>
                </li>
                <li>
                  <h4>Dibuat Oleh</h4>
                  <h6 id="created-by">Loading</h6>
                </li>
                <li>
                  <h4>Diupdate Pada</h4>
                  <h6 id="updated-at">Loading</h6>
                </li>
                <li>
                  <h4>Diupdate Oleh</h4>
                  <h6 id="updated-by">Loading</h6>
                </li>
              </ul>
              <ul class="product-bar mt-5">
                <li>
                  <h4>Status</h4>
                  <h6 id="status">Loading</h6>
                </li>
                <li>
                  <h4>Tanggal Status</h4>
                  <h6 id="status-date">Loading</h6>
                </li>
                <li>
                  <h4>Catatan</h4>
                  <h6 id="note">Loading</h6>
                </li>
                <li>
                  <h4>Dibuat Pada</h4>
                  <h6 id="created-at-status">Loading</h6>
                </li>
                <li>
                  <h4>Dibuat Oleh</h4>
                  <h6 id="created-by-status">Loading</h6>
                </li>
                <li>
                  <h4>Diupdate Pada</h4>
                  <h6 id="updated-at-status">Loading</h6>
                </li>
                <li>
                  <h4>Diupdate Oleh</h4>
                  <h6 id="updated-by-status">Loading</h6>
                </li>
              </ul>
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
                <img src="{{ url('assets/img/product/product69.jpg') }}" id="image-process" alt="img">
                <h4>Foto Proses</h4>
              </div>
              <div class="slider-product mt-5 border p-3">
                <img src="{{ url('assets/img/product/product69.jpg') }}" id="image-status" alt="img">
                <h4>Foto Status</h4>
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

      axios.get("{{ url('api/v1/product/detail/' . $id) }}", config)
        .then(res => {
          const product = res.data.data.item

          const formattedCreatedAt = new Date(product.created_at).toISOString().split('T')[0]
          const formattedUpdatedAt = new Date(product.updated_at).toISOString().split('T')[0]
          const formattedCreatedAtStatus = new Date(product.status_log.created_at).toISOString().split('T')[0]
          const formattedUpdatedAtStatus = new Date(product.status_log.updated_at).toISOString().split('T')[0]


          console.log(product)

          JsBarcode("#barcode", product.barcode)

          $('#category').text(product.category)
          $('#segment').text(product.segment_name)
          $('#module-number').text(product.module_number)
          $('#bilah-number').text(product.bilah_number)
          $('#production-date').text(product.production_date)
          $('#shelf-number').text(product.shelf_number)
          $('#nut-bolt').text(product.nut_bolt ? 'Ya' : 'Tidak')
          $('#description').text(product.description)
          $('#delivery-date').text(product.delivery_date)
          $('#created-at').text(formattedCreatedAt)
          $('#created-by').text(product.created_by)
          $('#updated-at').text(formattedUpdatedAt)
          $('#updated-by').text(product.updated_by)
          
          $("#image-process").attr("src", product.process_photo)
          
          $('#status').text(product.status_log.status_name)
          $('#status-date').text(product.status_log.status_date)
          $('#note').text(product.status_log.note)
          $('#created-at-status').text(formattedCreatedAtStatus)
          $('#created-by-status').text(product.status_log.created_by)
          $('#updated-at-status').text(formattedUpdatedAtStatus)
          $('#updated-by-status').text(product.status_log.updated_by)
          
          $("#image-status").attr("src", product.status_photo)

        })
        .catch(err => {
          console.log(err)
        })
    })
  </script>
@endsection
