@extends('layouts/content')

@section('title')
  <title>Verifikasi User</title>
@endsection

@section('content')
    <div class="content">
    <div class="page-header">
      <div class="page-title">
        <h4>User</h4>
        <h6>Verifikasi User</h6>
      </div>
      {{-- <div class="page-btn">
        <a href="addproduct.html" class="btn btn-added"><img src="{{ url('assets/img/icons/plus.svg') }}" alt="img" class="me-1">Add
          New Product</a>
      </div> --}}
    </div>

    <div class="card">
      <div class="card-body">
        <div class="table-top">
          <div class="search-set">
            {{-- <div class="search-path">
              <a class="btn btn-filter" id="filter_search">
                <img src="{{ url('assets/img/icons/filter.svg') }}" alt="img">
                <span><img src="{{ url('assets/img/icons/closes.svg') }}" alt="img"></span>
              </a>
            </div> --}}
            <div class="search-input">
              <a class="btn btn-searchset"><img src="{{ url('assets/img/icons/search-white.svg') }}" alt="img"></a>
            </div>
          </div>
          <div class="wordset">
            <ul>
              <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img src="{{ url('assets/img/icons/pdf.svg') }}"
                    alt="img"></a>
              </li>
              <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img src="{{ url('assets/img/icons/excel.svg') }}"
                    alt="img"></a>
              </li>
              <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img src="{{ url('assets/img/icons/printer.svg') }}"
                    alt="img"></a>
              </li>
            </ul>
          </div>
        </div>
        <!-- /Filter -->
        {{-- <div class="card mb-0" id="filter_inputs">
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
        </div> --}}
        <!-- /Filter -->
        <div class="table-responsive">
          <table id="data-user-inactive" class="table datanew">
            <thead>
              <tr>
                <th>Nama Lengkap</th>
                <th>No HP</th>
                <th>Email </th>
                <th>Tanggal Lahir</th>
                <th>Kelompok</th>
                <th>Alamat</th>
                <th>Dibuat</th>
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

      // GET USER NOT VERIFY
      axios.get("{{ url('api/v1/user/user-inactive') }}", config)
        .then(function(res) {
          const users = res.data.data.items

          users.map((user, i) => {
            const formattedCreatedAt = new Date(user.created_at).toISOString().split('T')[0];

            table.row.add([
              user.fullname,
              user.phone_number,
              user.email,
              user.birthdate,
              user.group_name,
              user.address,
              formattedCreatedAt,
              '<button class="p-2 btn btn-submit">Verifikasi Akun Sekarang</button>',
            ]).draw(false);


            // const row = `
            //   <tr>
            //     <td>${user.fullname}</td>
            //     <td>${user.phone_number}</td>
            //     <td>${user.email}</td>
            //     <td>${user.birthdate}</td>
            //     <td>${user.group_name}</td>
            //     <td>${user.address}</td>
            //     <td>${user.created_at}</td>
            //     <td>
            //       <button class="p-2 btn btn-submit">
            //         Verifikasi Akun Sekarang
            //       </button>
            //     </td>
            //   </tr>
            // `
            // $('#data-user-inactive').append(row)
          })
        })
        .catch(function(err) {
          console.log(err)
        })
    })
  </script>
@endsection
