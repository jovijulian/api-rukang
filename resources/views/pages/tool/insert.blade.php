@extends('layouts/content')

@section('title')
  <title>Tambah Alat</title>
@endsection

@section('content')
  <div class="cardhead">
    <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
        <div class="row">
          <div class="col">
            <h3 class="page-title">Tambah Data Alat</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ url('/tool') }}">Alat</a></li>
              <li class="breadcrumb-item active">Tambah Alat</li>
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
              <form id="insert-tool-form">
                <h5 class="card-title mb-4">Kategori</h5>
                <div class="row mb-4 gx-lg-5">
                  <div class="col-xl-6">
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Kategori</label>
                      <div class="col-lg-9">
                        <select id="category" class="form-control select">
                          <option value="pilih kategori" selected="selected" disabled>Pilih kategori</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-6">
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

                <h5 class="card-title mb-4">Status</h5>
                <div class="row mb-4 gx-lg-5">
                  <div class="col-xl-6">
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Status</label>
                      <div class="col-lg-9">
                        <select id="status" class="form-control select">
                          <option value="pilih status" selected="selected" disabled>Pilih status</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Ekspedisi</label>
                      <div class="col-lg-9">
                        <select id="shipping" class="form-control select" disabled>
                          <option value="pilih ekspedisi" selected="selected" disabled>Pilih ekspedisi</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Plat Nomor</label>
                      <div class="col-lg-9">
                        <input type="text" id="number-plate" class="form-control" placeholder="Masukan plat nomor" disabled>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Lokasi Terkini</label>
                      <div class="col-lg-9">
                        <input type="text" id="current-location" class="form-control" placeholder="Masukan lokasi terkini" disabled>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Catatan Status</label>
                      <div class="col-lg-9">
                        <textarea rows="3" cols="5" id="status-note" class="form-control" placeholder="Masukan catatan"></textarea>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Upload Foto Status</label>
                      <div class="col-lg-9">
                        <input class="form-control" type="file" id="image-status" accept="image/*">
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-6">
                    <div class="form-group row mt-4" id="image-preview">
                    </div>
                  </div>
                </div>

                <div class="text-end">
                  <button type="submit" class="btn btn-primary px-5 py-3">Tambah Alat</button>
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
          'Content-Type': 'multipart/form-data',
          'Accept': 'application/json',
          'Authorization': `${tokenType} ${accessToken}`
        }
      }

      getCategory()
      getStatus()
      getShipping()


      $('#image-status').change(function() {
        const file = this.files[0]
        if (file) {
          let reader = new FileReader()
          reader.onload = function(event) {
            $('#image-preview img').remove()
            $('#image-preview').append(
              `<img src="${event.target.result}" alt="" class="mx-auto" alt="" style="height: 300px; width: auto">`
            )
          }
          reader.readAsDataURL(file)
        }
      })

      function getCategory() {
        $('#category').select2({
          minimumResultsForSearch: -1,
          ajax: {
            url: "{{ url('api/v1/category/index?search=Alat') }}",
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

      function getStatus() {
        $('#status').select2({
          ajax: {
            url: "{{ url('api/v1/status-tool-material/index') }}",
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
              // console.log(data);
              return {
                results: $.map(data.data.items, function(item) {
                  return {
                    text: item.status,
                    id: item.id,
                    location: item.need_expedition
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
        
        $('#status').on('change', function(e) {
          const needExpedition = $(this).select2('data')[0].location
          if (needExpedition) {
            $('#shipping').removeAttr('disabled')

            $('#number-plate').removeAttr('disabled')

            $('#current-location').removeAttr('disabled')
          } else {
            $('#shipping').select2("enable", false)
            $("#shipping").val(null).trigger("change")
            
            $('#number-plate').attr('disabled', 'disabled')
            $('#number-plate').val('')

            $('#current-location').attr('disabled', 'disabled')
            $('#current-location').val('')
          }
        })
      }

      function getShipping() {
        $('#shipping').select2({
          placeholder: "Pilih ekspedisi",
          ajax: {
            url: "{{ url('api/v1/shipping/index') }}",
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
                    text: item.shipping_name,
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

          
      $('#insert-tool-form').on('submit', () => {
        event.preventDefault()
        $('#global-loader').show()

        insertData()
      })


      function insertData() {
        const data = {
          category_id: $('#category').val() ? $('#category').val() : '',
          category: $('#category').val() ? $('#category').find("option:selected").text() : '',
          type: $('#type').val() ? $('#type').val() : "",
          tool_name: $('#tool-name').val() ? $('#tool-name').val() : "",
          amount: $('#amount').val() ? $('#amount').val() : "",
          serial_number: $('#serial-number').val() ? $('#serial-number').val() : "",
          note: $('#note').val() ? $('#note').val() : "",
          status_id: $('#status').val() ? $('#status').val() : '',
          status: $('#status').val() ? $('#status').find("option:selected").text() : '',
          status_photo: $('#image-status')[0].files[0] ? $('#image-status')[0].files[0] : '',
          status_note: $('#status-note').val() ? $('#status-note').val() : '',
          shipping_id: $('#shipping').val() ? $('#shipping').val() : '',
          shipping_name: $('#shipping').val() ? $('#shipping').find("option:selected").text() : '',
          number_plate: $('#number-plate').val() ? $('#number-plate').val() : '',
          current_location: $('#current-location').prop('disabled') ? '' : $('#current-location').val()
        }

        // console.log(data)
        // return


        axios.post("{{ url('api/v1/tool/create') }}", data, config)
          .then(res => {
            const product = res.data.data.item
            sessionStorage.setItem("success", `Alat berhasil ditambahkan`)
            window.location.href = "{{ url('/tool') }}"
          })
          .catch(err => {
            $('#global-loader').hide()
            Swal.fire('Alat gagal ditambahkan', '', 'error')
            console.log(err)
          })
      }

    })
  </script>
@endsection
