@extends('layouts/content')

@section('title')
  <title>Deskripsi</title>
@endsection

@section('content')
  <div class="content">
    <div class="page-header">
      <div class="page-title">
        <h4>Deskripsi</h4>
        <h6>Manajemen Data Deskripsi</h6>
      </div>
      <div class="page-btn">
        <a href="/description/insert" class="btn btn-added"><img src="{{ url('assets/img/icons/plus.svg') }}" alt="img" class="me-1">Tambah Deskripsi Baru</a>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <div class="table-top">
          <div class="search-set">
            <div class="search-path">
              <a class="btn btn-filter" id="filter_search">
                <img src="{{ url('assets/img/icons/filter.svg') }}" alt="img">
                <span><img src="{{ url('assets/img/icons/closes.svg') }}" alt="img"></span>
              </a>
            </div>
            <div class="search-input">
              <a class="btn btn-searchset"><img src="{{ url('assets/img/icons/search-white.svg') }}" alt="img"></a>
            </div>
          </div>
          <div class="wordset">
            <ul>
              <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img
                    src="{{ url('assets/img/icons/pdf.svg') }}" alt="img"></a>
              </li>
              <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img
                    src="{{ url('assets/img/icons/excel.svg') }}" alt="img"></a>
              </li>
              <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img
                    src="{{ url('assets/img/icons/printer.svg') }}" alt="img"></a>
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
                      <a class="btn btn-filters ms-auto"><img src="{{ url('assets/img/icons/search-whites.svg') }}" alt="img"></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /Filter -->
        <div class="table-responsive">
          <table id="data-user-inactive" class="table datanew">
            <thead>
              <tr>
                <th>No</th>
                <th>Deskripsi</th>
                <th>Dibuat</th>
                <th>Diubah</th>
                <th>Dibuat Oleh</th>
                <th>Diubah Oleh</th>
                <th>Action</th>
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
      if(!currentUser.isAdmin) {
        window.location.href = "{{ url('/dashboard') }}"
      }

      // GET DATA
      const table = $('#data-user-inactive').DataTable()

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

      // GET SEGMENT
      function getData() {
        axios.get("{{ url('api/v1/description/index') }}", config)
          .then(function(res) {
            const descriptions = res.data.data.items

            descriptions.map((description, i) => {
              const formattedCreatedAt = new Date(description.created_at).toISOString().split('T')[0];
              const formattedUpdatedAt = new Date(description.updated_at).toISOString().split('T')[0];

              table.row.add([
                i + 1,
                description.description,
                formattedCreatedAt,
                formattedUpdatedAt,
                description.created_by,
                description.updated_by,
                `
                  <a class="me-3" href="/description/edit/${description.id}">
                    <img src="assets/img/icons/edit.svg" alt="img">
                  </a>
                `,
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
