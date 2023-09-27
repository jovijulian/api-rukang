@extends('layouts/content')

@section('title')
  <title>Ubah Status</title>
@endsection

@section('content')
  <div class="cardhead">
    <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
        <div class="row">
          <div class="col">
            <h3 class="page-title">Ubah Foto Status</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ url('/tool') }}">Alat</a></li>
              <li class="breadcrumb-item active">Ubah Foto Status Alat</li>
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

      let image = []


      $('#image-status').change(function(event) {
        if (this.files) {
          let fileAmount = this.files.length
          $('#image-preview img').remove()

          if (fileAmount > 10) {
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
      
      getProduct()

      function getProduct() {
        axios.get("{{ url('api/v1/tool/detail/' . $idProduct) }}", config)
          .then(res => {
            const product = res.data.data.item
            const status = product.status_tool_logs.find(stat => stat.id === '{{ $idStatus }}')
            const location = product.location_tool_logs.find(loc => loc.status_tool_log_id === '{{ $idStatus }}')

            // console.log(location);
          })
          .catch(err => {
            console.log(err)
          })
      }


      $('#update-status-form').on('submit', () => {
        event.preventDefault()
        $('#global-loader').show()

        const data = {
          status_photo: $('#image-status')[0].files[0] ? $('#image-status')[0].files[0] : '',
          status_photo2: $('#image-status')[0].files[1] ? $('#image-status')[0].files[1] : '',
          status_photo3: $('#image-status')[0].files[2] ? $('#image-status')[0].files[2] : '',
          status_photo4: $('#image-status')[0].files[3] ? $('#image-status')[0].files[3] : '',
          status_photo5: $('#image-status')[0].files[4] ? $('#image-status')[0].files[4] : '',
          status_photo6: $('#image-status')[0].files[5] ? $('#image-status')[0].files[5] : '',
          status_photo7: $('#image-status')[0].files[6] ? $('#image-status')[0].files[6] : '',
          status_photo8: $('#image-status')[0].files[7] ? $('#image-status')[0].files[7] : '',
          status_photo9: $('#image-status')[0].files[8] ? $('#image-status')[0].files[8] : '',
        }

        // console.log(data)
        // return

        axios.post("{{ url('api/v1/tool/multiple-image-status/' . $idStatus) }}", data, config)
          .then(res => {
            const produk = res.data.data.item
            sessionStorage.setItem("success", `Status alat berhasil diubah`)
            window.location.href = `{{ url('/tool/detail/${res.data.data.item.tool_id}') }}`
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
              title: 'Status alat gagal diubah',
              text: errorMessage
            })
          })

      })

    })
  </script>
@endsection
