@extends('layouts/content')

@section('title')
  <title>Edit Kelengkapan Modul</title>
@endsection

@section('content')
  <div class="cardhead">
    <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
        <div class="row">
          <div class="col">
            <h3 class="page-title">Edit Data Kelengkapan Modul</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ url('/module-complete') }}">Kelengkapan Modul</a></li>
              <li class="breadcrumb-item active">Edit Kelengkapan Modul</li>
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
              <form id="update-module-complete-form">
                <div class="form-group row">
                  <label class="col-lg-2 col-form-label">Segmen *</label>
                  <div class="col-lg-10">
                    <select id="segment" class="form-control select" required>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-lg-2 col-form-label">Nomor Modul *</label>
                  <div class="col-lg-10">
                    <select id="module" class="form-control select" required>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-lg-2 col-form-label">Modul Lengkap?</label>
                  <div class="col-lg-10 pt-2">
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="module-complete" id="modul-yes" value="1" checked>
                      <label class="form-check-label" for="modul-yes">
                      Ya
                      </label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="module-complete" id="module-no" value="0">
                      <label class="form-check-label" for="module-no">
                      Tidak
                      </label>
                    </div>
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
      
      getData()

      getSegment()
      getModule()

      function getSegment() {
        $('#segment').select2({
          placeholder: "Pilih segmen",
          ajax: {
            url: "{{ url('api/v1/segment/index') }}",
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
              segments = data.data.items
              

              return {
                results: $.map(data.data.items, function(item) {
                  return {
                    text: item.segment_name,
                    id: item.id,
                  }
                }),
                pagination: {
                    more: data.page_info.last_page != params.page
                }
              }
            },
            cache: true,
          }
        })
      }

      function getModule() {
        $('#module').select2({
          placeholder: "Pilih modul",
          ajax: {
            url: "{{ url('api/v1/module/index') }}",
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
                    text: item.module_number,
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

      $('#update-module-complete-form').on('submit', () => {
        event.preventDefault()
        $('#global-loader').show()

        const data = {
          segment_id: $('#segment').val() ? $('#segment').val() : '',
          segment: $('#segment').val() ? $('#segment').find("option:selected").text() : '',
          module_id: $('#module').val() ? $('#module').val() : '',
          module: $('#module').val() ? $('#module').find("option:selected").text() : '',
          completeness: $("input[name='module-complete']:checked").val() ? $("input[name='module-complete']:checked").val() : ''
        }


        axios.put("{{ url('api/v1/module-completeness/update/' . $id) }}", data, config)
          .then(res => {
            sessionStorage.setItem("success", `Kelengkapan Modul berhasil diubah`)
            window.location.href = "{{ url('/module-complete') }}"
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
              title: 'Kelengkapan Modul gagal ditambahkan',
              text: errorMessage
            })
          })

      })

      function getData() {
        axios.get("{{ url('api/v1/module-completeness/detail/' . $id) }}", config)
          .then(res => {
            const data = res.data.data.item
            console.log(data);

            $("#segment").append(`<option value=${data.segment_id} selected>${data.segment}</option>`)
            $("#module").append(`<option value=${data.module_id} selected>${data.module}</option>`)

            data.completeness ? $("input[name='module-complete'][value='1']").prop("checked", true) : $("input[name='module-complete'][value='0']").prop("checked", true)

            // $('#shelf').val(data.shelf_name)
          })
          .catch(err => {
            console.log(err)
          })
      }

    })
  </script>
@endsection
