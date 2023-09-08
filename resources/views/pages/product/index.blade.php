@extends('layouts/content')

@section('title')
  <title>Produk</title>
@endsection


@section('content')
  <div class="content">
    <div class="page-header">
      <div class="page-title">
        <h4>Produk</h4>
        <h6>Manajemen data produk</h6>
      </div>
      <div class="page-btn">
        <a href="/product/insert" class="btn btn-added"><img src="assets/img/icons/plus.svg" alt="img"
            class="me-1">Tambah produk baru</a>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <div class="table-top">
          <div class="search-set">
            <div class="search-path">
              <a class="btn btn-filter" id="filter_search">
                <img src="assets/img/icons/filter.svg" alt="img">
                <span><img src="assets/img/icons/closes.svg" alt="img"></span>
              </a>
            </div>
            <div class="search-input">
              <a class="btn btn-searchset"><img src="assets/img/icons/search-white.svg" alt="img"></a>
            </div>
          </div>
          <div class="wordset">
            <ul>
              <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img src="assets/img/icons/pdf.svg"
                    alt="img"></a>
              </li>
              <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img src="assets/img/icons/excel.svg"
                    alt="img"></a>
              </li>
              <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img src="assets/img/icons/printer.svg"
                    alt="img"></a>
              </li>
            </ul>
          </div>
        </div>
        <!-- /Filter -->
        <div class="card mb-0" id="filter_inputs">
          <div class="card-body pb-0">
            <div class="row">
              <div class="col-lg-12 col-sm-12">
                <div class="row">
                  <div class="col-lg col-sm-6 col-12">
                    <div class="form-group">
                      <select class="select">
                        <option>Choose Product</option>
                        <option>Macbook pro</option>
                        <option>Orange</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg col-sm-6 col-12">
                    <div class="form-group">
                      <select class="select">
                        <option>Choose Category</option>
                        <option>Computers</option>
                        <option>Fruits</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg col-sm-6 col-12">
                    <div class="form-group">
                      <select class="select">
                        <option>Choose Sub Category</option>
                        <option>Computer</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg col-sm-6 col-12">
                    <div class="form-group">
                      <select class="select">
                        <option>Brand</option>
                        <option>N/D</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg col-sm-6 col-12 ">
                    <div class="form-group">
                      <select class="select">
                        <option>Price</option>
                        <option>150.00</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-1 col-sm-6 col-12">
                    <div class="form-group">
                      <a class="btn btn-filters ms-auto"><img src="assets/img/icons/search-whites.svg" alt="img"></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /Filter -->
        <div class="table-responsive">
          <table id="product-table" class="table datanew">
            <thead>
              <tr>
                <th>No</th>
                <th>Action</th>
                <th>Kategori</th>
                <th>Segmen</th>
                <th>Nomor Module</th>
                <th>Nomor Bilah</th>
                <th>Nomor Rak</th>
                <th>Tanggal Produksi</th>
                <th>1/0</th>
                <th>Dibaut dan dimur</th>
                <th>Deskripsi</th>
                <th>Tanggal Pengiriman</th>
                <th>Status</th>
                <th>Dibuat</th>
                <th>Dibuat Oleh</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
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
      if (!currentUser.isAdmin) {
        window.location.href = "{{ url('/dashboard') }}"
      }

      // GET DATA
      const table = $('#product-table').DataTable()

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

      // GET PRODUCT
      function getData() {
        axios.get("{{ url('api/v1/product/index') }}", config)
          .then(function(res) {
            const produts = res.data.data.items

            produts.map((product, i) => {
              const formattedCreatedAt = new Date(product.created_at).toISOString().split('T')[0];
              const formattedUpdatedAt = new Date(product.updated_at).toISOString().split('T')[0];

              table.row.add([
                i + 1,
                `
                  <a class="me-3" href="product/detail/${product.id}">
                    <img src="assets/img/icons/eye.svg" alt="img">
                  </a>
                  <a class="me-3" href="/product/edit/${product.id}">
                    <img src="assets/img/icons/edit.svg" alt="img">
                  </a>
                `,
                product.category,
                product.segment_name,
                product.module_number,
                product.bilah_number,
                product.shelf_number,
                product.production_date,
                // product.1/0,
                'tes',
                product.nut_bolt ? 'Ya' : 'Tidak',
                product.description,
                product.delivery_date,
                product.status_log.status_name,
                formattedCreatedAt,
                product.created_by,
              ]).draw(false)
            })
          })
          .catch(function(err) {
            console.log(err)
          })
      }
    })
  </script>
@endsection
