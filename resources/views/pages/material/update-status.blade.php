@extends('layouts/content')

@section('title')
  <title>Update Status</title>
@endsection

@section('content')
  <div class="cardhead">
    <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
        <div class="row">
          <div class="col">
            <h3 class="page-title">Update Status</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ url('/material') }}">Bahan</a></li>
              <li class="breadcrumb-item active">Update Status Bahan</li>
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
              <form id="update-status-form">
                <div class="form-group row">
                  <label class="col-lg-3 col-form-label">Status</label>
                  <div class="col-lg-9">
                    <select id="status-product" class="form-control select">
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
                    <input type="text" id="current-location" class="form-control" placeholder="Masukan lokasi terkini">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-lg-3 col-form-label">Catatan</label>
                  <div class="col-lg-9">
                    <textarea rows="3" cols="5" id="note" class="form-control" placeholder="Masukan catatan"></textarea>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-lg-3 col-form-label">Upload Foto Status (Maks 10 Foto)</label>
                  <div class="col-lg-9">
                    <input class="form-control mb-1" type="file" id="image-status" accept="image/*" multiple>
                    <div id="image-preview" class="mt-2 row"></div>
                  </div>
                </div>
                <div class="text-end">
                  <button type="submit" class="btn btn-primary">Update Status</button>
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

      $('#image-status').change(function(event) {
        if (this.files) {
          let fileAmount = this.files.length
          $('#image-preview img').remove()

          if (fileAmount > 10) {
            // console.log('salah');
            Swal.fire('Maksimal upload 10 foto', '', 'error')
            $('#image-status').val('')
            return
          }
          
          for (let i = 0; i < fileAmount; i++) {
            let reader = new FileReader()
            reader.onload = function(){
              $('#image-preview').append(`<img src="${this.result}" alt="" class="mx-auto col-2 m-1" alt="" style="height: 70px; width: auto">`)
            }
            reader.readAsDataURL(this.files[i])
          }
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
      
      getStatus()
      getShipping()
            
      function getStatus() {
        $('#status-product').select2({
          placeholder: 'Pilih status',
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
            }
          },
          cache: true
        })
        
        $('#status-product').on('change', function(e) {
          const needExpedition = $(this).select2('data')[0].location

          // console.log(needExpedition)
          
          if (needExpedition) {
            $('#shipping').removeAttr('disabled')

            $('#number-plate').removeAttr('disabled')
          } else {
            $('#shipping').select2("enable", false)
            $("#shipping").val(null).trigger("change")
            
            $('#number-plate').attr('disabled', 'disabled')
            $('#number-plate').val('')
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


      $('#update-status-form').on('submit', () => {
        event.preventDefault()
        $('#global-loader').show()

        const data = {
          status_id: $('#status-product').val() ? $('#status-product').val() : '',
          status_name: $('#status-product').val() ? $('#status-product').find("option:selected").text() : '',
          status_photo: $('#image-status')[0].files[0] ? $('#image-status')[0].files[0] : '',
          status_photo2: $('#image-status')[0].files[1] ? $('#image-status')[0].files[1] : '',
          status_photo3: $('#image-status')[0].files[2] ? $('#image-status')[0].files[2] : '',
          status_photo4: $('#image-status')[0].files[3] ? $('#image-status')[0].files[3] : '',
          status_photo5: $('#image-status')[0].files[4] ? $('#image-status')[0].files[4] : '',
          status_photo6: $('#image-status')[0].files[5] ? $('#image-status')[0].files[5] : '',
          status_photo7: $('#image-status')[0].files[6] ? $('#image-status')[0].files[6] : '',
          status_photo8: $('#image-status')[0].files[7] ? $('#image-status')[0].files[7] : '',
          status_photo9: $('#image-status')[0].files[8] ? $('#image-status')[0].files[8] : '',
          status_photo10: $('#image-status')[0].files[9] ? $('#image-status')[0].files[9] : '',
          note: $('#note').val() ? $('#note').val() : '',
          shipping_id: $('#shipping').val() ? $('#shipping').val() : '',
          shipping_name: $('#shipping').val() ? $('#shipping').find("option:selected").text() : '',
          number_plate: $('#number-plate').val() ? $('#number-plate').val() : '',
          current_location: $('#current-location').prop('disabled') ? '' : $('#current-location').val()
        }

        // console.log(data)
        // return

        axios.post("{{ url('api/v1/material/update-status/' . $id) }}", data, config)
          .then(res => {
            const produk = res.data.data.item
            sessionStorage.setItem("success", `Status bahan berhasil diupdate`)
            window.location.href = `{{ url('/material/detail/${res.data.data.item.material_id}') }}`
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
              title: 'Status bahan gagal diupdate',
              text: errorMessage
            })
          })

      })

    })
  </script>
@endsection
