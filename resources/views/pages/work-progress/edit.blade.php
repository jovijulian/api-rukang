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
                  <label class="col-lg-2 col-form-label">Nama Proses *</label>
                  <div class="col-lg-10">
                    <input type="text" id="process-name" class="form-control" placeholder="Masukan nama proses" required>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-lg-2 col-form-label">Upload Foto Proses (Maks 10 Foto)</label>
                  <div class="col-lg-10">
                    <input class="form-control mb-1" type="file" id="image-process" accept="image/*" multiple>
                    <div id="image-preview" class="mt-2 row"></div>
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

      $('#image-process').change(function(event) {
        if (this.files) {
          let fileAmount = this.files.length
          $('#image-preview img').remove()

          if (fileAmount > 10) {
            console.log('salah');
            Swal.fire('Maksimal upload 10 foto', '', 'error')
            $('#image-process').val('')
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
      
      getData()

      $('#update-module-complete-form').on('submit', () => {
        event.preventDefault()
        $('#global-loader').show()

        const data = {
          process_name: $('#process-name').val() ? $('#process-name').val() : '',
          photo_01: $('#image-process')[0].files[0] ? $('#image-process')[0].files[0] : '',
          photo_03: $('#image-process')[0].files[2] ? $('#image-process')[0].files[2] : '',
          photo_02: $('#image-process')[0].files[1] ? $('#image-process')[0].files[1] : '',
          photo_04: $('#image-process')[0].files[3] ? $('#image-process')[0].files[3] : '',
          photo_05: $('#image-process')[0].files[4] ? $('#image-process')[0].files[4] : '',
          photo_06: $('#image-process')[0].files[5] ? $('#image-process')[0].files[5] : '',
          photo_07: $('#image-process')[0].files[6] ? $('#image-process')[0].files[6] : '',
          photo_08: $('#image-process')[0].files[7] ? $('#image-process')[0].files[7] : '',
          photo_09: $('#image-process')[0].files[8] ? $('#image-process')[0].files[8] : '',
          photo_10: $('#image-process')[0].files[9] ? $('#image-process')[0].files[9] : '',
        }


        axios.put("{{ url('api/v1/work-progress/update/' . $id) }}", data, config)
          .then(res => {
            sessionStorage.setItem("success", `Progress Pekerjaan berhasil diubah`)
            window.location.href = "{{ url('/work-progress') }}"
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
              title: 'Progress Pekerjaan gagal ditambahkan',
              text: errorMessage
            })
          })

      })

      function getData() {
        axios.get("{{ url('api/v1/work-progress/detail/' . $id) }}", config)
          .then(res => {
            const data = res.data.data.item
            console.log(data);

            $('#process-name').val(data.process_name)
          })
          .catch(err => {
            console.log(err)
          })
      }

    })
  </script>
@endsection
