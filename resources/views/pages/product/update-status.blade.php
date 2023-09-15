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
              <li class="breadcrumb-item"><a href="{{ url('/product') }}">Produk</a></li>
              <li class="breadcrumb-item active">Update Status Produk</li>
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
                  <label class="col-lg-3 col-form-label">Lokasi Terkini</label>
                  <div class="col-lg-9">
                    <input type="text" id="current-location" class="form-control" placeholder="Masukan lokasi terkini" disabled>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-lg-3 col-form-label">Catatan</label>
                  <div class="col-lg-9">
                    <textarea rows="3" cols="5" id="note" class="form-control" placeholder="Masukan catatan"></textarea>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-lg-3 col-form-label">Upload Foto Status</label>
                  <div class="col-lg-9">
                    <input class="form-control" type="file" id="image-status" accept="image/*">
                    <div id="image-preview" class="mt-2"></div>
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

      $('#image-status').change(function(){
        const file = this.files[0]
        if (file){
          let reader = new FileReader()
          reader.onload = function(event){
            $('#image-preview img').remove()
            $('#image-preview').append(`<img src="${event.target.result}" alt="" class="mx-auto" alt="" style="height: 150px; width: auto">`)
          }
          reader.readAsDataURL(file)
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
            url: "{{ url('api/v1/status/index') }}",
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

          console.log(needExpedition)
          
          if (needExpedition) {
            $('#shipping').removeAttr('disabled')

            $('#current-location').removeAttr('disabled')
          } else {
            $('#shipping').select2("enable", false)
            $("#shipping").val(null).trigger("change")
            
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


      $('#update-status-form').on('submit', () => {
        event.preventDefault()
        $('#global-loader').show()

        const data = {
          status_id: $('#status-product').val() ? $('#status-product').val() : '',
          status_name: $('#status-product').val() ? $('#status-product').find("option:selected").text() : '',
          note: $('#note').val(),
          status_photo: $('#image-status')[0].files[0] ? $('#image-status')[0].files[0] : '',
          shipping_id: $('#shipping').val() ? $('#shipping').val() : '',
          shipping_name: $('#shipping').val() ? $('#shipping').find("option:selected").text() : '',
          current_location: $('#current-location').prop('disabled') ? '' : $('#current-location').val()
        }

        // console.log(data)
        // return

        axios.post("{{ url('api/v1/product/update-status/' . $id) }}", data, config)
          .then(res => {
            const produk = res.data.data.item
            sessionStorage.setItem("success", `Produk berhasil diedit`)
            window.location.href = "{{ url('/product') }}"
          })
          .catch(err => {
            $('#global-loader').hide()
            Swal.fire('Produk gagal diedit', '', 'error')
            console.log(err)
          })

      })

    })
  </script>
@endsection
