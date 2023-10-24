@extends('layouts/content')

@section('title')
  <title>Progress Pekerjaan Bilah Workshop Bandung</title>
@endsection

@section('content')
  <div class="content">
    <div class="page-header">
      <div class="page-title">
        <h4>Progress Pekerjaan Bilah Workshop Bandung</h4>
      </div>
    </div>

    <div class="card bg-white">
      <div class="card-body">
        <ul class="nav nav-tabs" id="title-tab">
          {{-- <li class="nav-item"><a class="nav-link active text-black" href="#basictab1" data-bs-toggle="tab">Home</a></li>
          <li class="nav-item"><a class="nav-link text-black" href="#basictab2" data-bs-toggle="tab">Profile</a></li>
          <li class="nav-item"><a class="nav-link text-black" href="#basictab3" data-bs-toggle="tab">Messages</a></li> --}}
        </ul>
        <div class="tab-content" id="content-tab">
          <div class="tab-pane show active" id="basictab1">
            <div class="row py-5 align-items-center gap-3">
              <div class="card border-0 mx-auto" style="width: 18rem;">
                <img src="{{ url('assets/img/product/product69.jpg') }}" class="p-0" style="max-width: 300px" alt="img">
              </div>
              <div class="card border-0 mx-auto" style="width: 18rem;">
                <img src="{{ url('assets/img/product/product69.jpg') }}" class="p-0" style="max-width: 300px" alt="img">
              </div>
              <div class="card border-0 mx-auto" style="width: 18rem;">
                <img src="{{ url('assets/img/product/product69.jpg') }}" class="p-0" style="max-width: 300px" alt="img">
              </div>
              <div class="card border-0 mx-auto" style="width: 18rem;">
                <img src="{{ url('assets/img/product/product69.jpg') }}" class="p-0" style="max-width: 300px" alt="img">
              </div>
              <div class="card border-0 mx-auto" style="width: 18rem;">
                <img src="{{ url('assets/img/product/product69.jpg') }}" class="p-0" style="max-width: 300px" alt="img">
              </div>
              <div class="card border-0 mx-auto" style="width: 18rem;">
                <img src="{{ url('assets/img/product/product69.jpg') }}" class="p-0" style="max-width: 300px" alt="img">
              </div>
              <div class="card border-0 mx-auto" style="width: 18rem;">
                <img src="{{ url('assets/img/product/product69.jpg') }}" class="p-0" style="max-width: 300px" alt="img">
              </div>
              <div class="card border-0 mx-auto" style="width: 18rem;">
                <img src="{{ url('assets/img/product/product69.jpg') }}" class="p-0" style="max-width: 300px" alt="img">
              </div>
              <div class="card border-0 mx-auto" style="width: 18rem;">
                <img src="{{ url('assets/img/product/product69.jpg') }}" class="p-0" style="max-width: 300px" alt="img">
              </div>
              <div class="card border-0 mx-auto" style="width: 18rem;">
                <img src="{{ url('assets/img/product/product69.jpg') }}" class="p-0" style="max-width: 300px" alt="img">
              </div>
            </div>
          </div>
          <div class="tab-pane" id="basictab2">
            Tab content 2
          </div>
          <div class="tab-pane" id="basictab3">
            Tab content 3
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    const currentUser = JSON.parse(localStorage.getItem('current_user'))
    const tokenType = localStorage.getItem('token_type')
    const accessToken = localStorage.getItem('access_token')

    let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    let config = {
      headers: {
        'X-CSRF-TOKEN': token,
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': `${tokenType} ${accessToken}`
      }
    }

    $(document).ready(function() {
      axios.get("{{ url('api/v1/work-progress/index') }}", config)
        .then(res => {
          const datas = res.data.data.items
          console.log(datas)

          datas.map((data, i) => {
            $('#title-tab').append(
              `
                <li class="nav-item"><a class="nav-link text-black" href="#progress-${i}" data-bs-toggle="tab">${data.process_name}</a></li>
              `
            )

            $('#content-tab').append(
              `
                <div class="tab-pane" id="progress-${i}">
                  <div class="row py-5 align-items-center gap-3">
                    <div class="card border-0 mx-auto" style="width: 18rem;">
                      <img src="${data.photo_01 ? data.photo_01 : `https://cdn.idntimes.com/content-images/community/2020/11/cover-96b257c6f9b6e7264a8d4332d286650b_600x400.jpg`}" class="p-0" style="max-width: 300px" alt="img">
                    </div>
                    <div class="card border-0 mx-auto" style="width: 18rem;">
                      <img src="{{ url('assets/img/product/product69.jpg') }}" class="p-0" style="max-width: 300px" alt="img">
                    </div>
                    <div class="card border-0 mx-auto" style="width: 18rem;">
                      <img src="{{ url('assets/img/product/product69.jpg') }}" class="p-0" style="max-width: 300px" alt="img">
                    </div>
                    <div class="card border-0 mx-auto" style="width: 18rem;">
                      <img src="{{ url('assets/img/product/product69.jpg') }}" class="p-0" style="max-width: 300px" alt="img">
                    </div>
                    <div class="card border-0 mx-auto" style="width: 18rem;">
                      <img src="{{ url('assets/img/product/product69.jpg') }}" class="p-0" style="max-width: 300px" alt="img">
                    </div>
                    <div class="card border-0 mx-auto" style="width: 18rem;">
                      <img src="{{ url('assets/img/product/product69.jpg') }}" class="p-0" style="max-width: 300px" alt="img">
                    </div>
                    <div class="card border-0 mx-auto" style="width: 18rem;">
                      <img src="{{ url('assets/img/product/product69.jpg') }}" class="p-0" style="max-width: 300px" alt="img">
                    </div>
                    <div class="card border-0 mx-auto" style="width: 18rem;">
                      <img src="{{ url('assets/img/product/product69.jpg') }}" class="p-0" style="max-width: 300px" alt="img">
                    </div>
                    <div class="card border-0 mx-auto" style="width: 18rem;">
                      <img src="{{ url('assets/img/product/product69.jpg') }}" class="p-0" style="max-width: 300px" alt="img">
                    </div>
                    <div class="card border-0 mx-auto" style="width: 18rem;">
                      <img src="{{ url('assets/img/product/product69.jpg') }}" class="p-0" style="max-width: 300px" alt="img">
                    </div>
                  </div>
                </div>
              `
            )

          })

          $('#title-tab li:first a').addClass( "active" )
          $('#content-tab tab-pane:first').addClass( "show active" )
        })
        .catch(err => {
          console.log(err)
        })

    })
  </script>
@endsection
