@extends('layouts/content')

@section('title')
  <title>Edit Alat</title>
@endsection

@section('content')
  <div class="cardhead">
    <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
        <div class="row">
          <div class="col">
            <h3 class="page-title">Edit Data Alat</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ url('/tool') }}">Alat</a></li>
              <li class="breadcrumb-item active">Edit Alat</li>
            </ul>
          </div>
        </div>
      </div>
      <!-- /Page Header -->


      <div class="row">
        <div class="col-md-12">
          <div class="card">
            {{-- <div class="card-header">
              <h5 class="card-title">Two Column Horizontal Form</h5>
            </div> --}}
            <div class="card-body">
              <form id="edit-product-form">
                <h5 class="card-title mb-4">Kategori</h5>
                <div class="row mb-4 gx-lg-5">
                  <div class="col-xl-6">
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Kategori</label>
                      <div class="col-lg-9">
                        <select id="category" class="form-control select" disabled>
                          <option value="pilih kategori" selected="selected" disabled>Pilih kategori</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-6">
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Kelompok</label>
                      <div class="col-lg-9">
                        <select id="group" class="form-control select">
                          <option value="Pilih kelompok" selected="selected" disabled>Pilih kelompok</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>

                <h5 class="card-title mb-4">Alat</h5>
                <div class="row mb-4 gx-lg-5">
                  <div class="col-xl-6">
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Tipe</label>
                      <div class="col-lg-9">
                        <input type="text" id="type" class="form-control" placeholder="Masukan tipe" required>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Nama Alat</label>
                      <div class="col-lg-9">
                        <input type="text" id="tool-name" class="form-control" placeholder="Masukan nama alat" required>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Jumlah</label>
                      <div class="col-lg-9">
                        <input type="text" id="amount" class="form-control" placeholder="Masukan jumlah" required>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-6">
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Nomor Seri</label>
                      <div class="col-lg-9">
                        <input type="text" id="serial-number" class="form-control" placeholder="Masukan nomor seri" required>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Catatan</label>
                      <div class="col-lg-9">
                        <textarea rows="3" cols="5" id="note" class="form-control" placeholder="Masukan catatan" required></textarea>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="text-end">
                  <button type="submit" class="btn btn-primary px-5 py-3">Ubah Alat</button>
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

      $("#amount").keypress(function(e) {
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
          return false;
        }
      })


      let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      let config = {
        headers: {
          'X-CSRF-TOKEN': token,
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'Authorization': `${tokenType} ${accessToken}`
        }
      }

      getCategory()
      getGroup()
      getDetailProduct()


      $('#edit-product-form').on('submit', () => {
        event.preventDefault()
        $('#global-loader').show()

        updateData()
      })


      function getCategory() {
        $('#category').select2({
          ajax: {
            url: "{{ url('api/v1/category/index') }}",
            headers: config.headers,
            dataType: 'json',
            type: "GET",
            data: function(params) {
              var query = {
                search: params.term,
                page: params.page || 1
              }
              return query
            },
            processResults: function(data, params) {
              params.page = params.page || 1

              return {
                results: $.map(data.data.items, function(item) {
                  return {
                    text: item.category,
                    id: item.id,
                  }
                }),
                pagination: {
                    more: data.page_info.last_page != params.page
                }
              }
            },
            cache: true
          }
        })
      }

      function getGroup() {
        $('#group').select2({
          ajax: {
            url: "{{ url('api/v1/group/index') }}",
            headers: config.headers,
            dataType: 'json',
            type: "GET",
            data: function(params) {
              var query = {
                search: params.term,
                page: params.page || 1
              }
              return query
            },
            processResults: function(data, params) {
              params.page = params.page || 1

              return {
                results: $.map(data.data.items, function(item) {
                  return {
                    text: item.group_name,
                    id: item.id,
                  }
                }),
                pagination: {
                    more: data.page_info.last_page != params.page
                }
              }
            },
            cache: true
          }
        })
      }

     

      function getDetailProduct() {
        axios.get("{{ url('api/v1/tool/detail/' . $id) }}", config)
          .then(res => {
            const data = res.data.data.item

            // console.log(data)

            $("#category").append(`<option value=${data.category_id} selected>${data.category}</option>`)
            $("#group").append(`<option value=${data.group_id} selected>${data.group_name}</option>`)
            $('#type').val(data.type)
            $('#tool-name').val(data.tool_name)
            $('#amount').val(data.amount)
            $('#serial-number').val(data.serial_number)
            $('#note').val(data.note)
          })
          .catch(err => {
            console.log(err)
          })
      }

      function updateData() {
        const data = {
          category_id: $('#category').val() ? $('#category').val() : '',
          category: $('#category').val() ? $('#category').find("option:selected").text() : '',
          group_id: $('#group').val() ? $('#group').val() : '',
          group_name: $('#group').val() ? $('#group').find("option:selected").text() : '',
          type: $('#type').val() ? $('#type').val() : "",
          tool_name: $('#tool-name').val() ? $('#tool-name').val() : "",
          amount: $('#amount').val() ? $('#amount').val() : "",
          serial_number: $('#serial-number').val() ? $('#serial-number').val() : "",
          note: $('#note').val() ? $('#note').val() : "",
        }

        // console.log(data)
        // return


        axios.put("{{ url('api/v1/tool/update/' . $id) }}", data, config)
          .then(res => {
            const product = res.data.data.item

            sessionStorage.setItem("success", `Alat berhasil diedit`)
            window.location.href = "{{ url('/tool') }}"
          })
          .catch(err => {
            $('#global-loader').hide()
            
            let errorMessage = ''

            if (err.response.status == 422) {
              const errors = err.response.data.errors[0]
              for (const key in errors) {
                errorMessage += `${errors[key]} \n`
              }
            } else if(err.response.status == 500) {
              errorMessage = 'Internal server error'
            }

            Swal.fire({
              icon: 'error',
              title: 'Alat gagal diubah',
              text: errorMessage
            })
          })
      }

    })
  </script>
@endsection
