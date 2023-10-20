<!-- Header -->
<div class="header">

  <!-- Logo -->
  <div class="header-left active">
    <a href="{{ url('/') }}" class="logo logo-normal">
      <img src="{{ url('assets/img/logo.png') }}" alt="">
    </a>
    <a href="{{ url('/') }}" class="logo logo-white">
      <img src="{{ url('assets/img/logo-white.png') }}" alt="">
    </a>
    <a href="{{ url('/') }}" class="logo-small">
      <img src="{{ url('assets/img/logo-small.png') }}" alt="">
    </a>
    <a id="toggle_btn" href="javascript:void(0);">
      <i data-feather="chevrons-left" class="feather-16"></i>
    </a>
  </div>
  <!-- /Logo -->

  <a id="mobile_btn" class="mobile_btn" href="#sidebar">
    <span class="bar-icon">
      <span></span>
      <span></span>
      <span></span>
    </span>
  </a>

  <!-- Header Menu -->
  <ul class="nav user-menu">

    <!-- Search -->
    <li class="nav-item nav-searchinputs">
      <div class="top-nav-search">

        <a href="javascript:void(0);" class="responsive-search">
          <i class="fa fa-search"></i>
        </a>
        <form action="#">
          <div class="searchinputs">
            <input type="text" placeholder="Search">
            <div class="search-addon">
              <span><i data-feather="search" class="feather-14"></i></span>
            </div>
          </div>
          <!-- <a class="btn"  id="searchdiv"><img src="assets/img/icons/search.svg" alt="img"></a> -->
        </form>
      </div>
    </li>
    <!-- /Search -->

    <li class="nav-item nav-item-box">
      <a href="javascript:void(0);" id="btnFullscreen">
        <i data-feather="maximize"></i>
      </a>
    </li>
    <li class="nav-item nav-item-box " id="dark-mode-toggle">
      {{-- <a class="dark-mode"><i data-feather="moon"></i></a> --}}
      <a class="light-mode"><i data-feather="sun"></i></a>
    </li>
    <li class="nav-item nav-item-box ">
      <a><i data-feather="settings"></i></a>
    </li>
    <li class="nav-item dropdown has-arrow main-drop">
      <a href="javascript:void(0);" class="dropdown-toggle nav-link userset" data-bs-toggle="dropdown">
        <span class="user-info">
          <span class="user-letter">
            <img src="{{ url('assets/img/profiles/avator1.jpg') }}" alt="" class="img-fluid">
          </span>
          <span class="user-detail">
            <span class="user-name fullname">John Smilga</span>
            <span class="user-role role">Super Admin</span>
          </span>
        </span>
      </a>
      <div class="dropdown-menu menu-drop-user">
        <div class="profilename">
          <div class="profileset">
            <span class="user-img"><img src="{{ url('assets/img/profiles/avator1.jpg') }}" alt="">
              <span class="status online"></span></span>
            <div class="profilesets">
              <h6 class="fullname">John Smilga</h6>
              <h5 class="role">Super Admin</h5>
            </div>
          </div>
          <hr class="m-0">
          <a class="dropdown-item" href="profile.html"> <i class="me-2" data-feather="user"></i> My Profile</a>
          <a class="dropdown-item" href="generalsettings.html"><i class="me-2" data-feather="settings"></i>Settings</a>
          <hr class="m-0">
          <button class="dropdown-item logout pb-0 logout-account"><img src="{{ url('assets/img/icons/log-out.svg') }}" class="me-2" alt="img">Logout</button>
        </div>
      </div>
    </li>
  </ul>
  <!-- /Header Menu -->

  <!-- Mobile Menu -->
  <div class="dropdown mobile-user-menu">
    <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
    <div class="dropdown-menu dropdown-menu-right">
      <a class="dropdown-item" href="profile.html">My Profile</a>
      <a class="dropdown-item" href="generalsettings.html">Settings</a>
      <a class="dropdown-item logout-account">Logout</a>
    </div>
  </div>
  <!-- /Mobile Menu -->
</div>
<!-- Header -->
<!-- Sidebar -->
<div class="sidebar" id="sidebar">
  <div class="sidebar-inner slimscroll">
    <div id="sidebar-menu" class="sidebar-menu">
      <ul>
        <li class="submenu-open">
          <h6 class="submenu-hdr">Main</h6>
          <ul>
            <li class="{{ request()->is('dashboard') ? 'active' : '' }}">
              <a href="/dashboard"><i data-feather="grid"></i><span>Dashboard</span></a>
            </li>
          </ul>
        </li>
        <li class="submenu-open aset-data">
          <h6 class="submenu-hdr">Manajemen Aset</h6>
          <ul>
            <li class="{{ request()->is('tool*') ? 'active' : '' }}"><a href="/tool"><i data-feather="tool"></i><span>Alat</span></a></li>
            <li class="{{ request()->is('material*') ? 'active' : '' }}"><a href="/material"><i data-feather="layers"></i><span>Bahan</span></a></li>
            {{-- <li><a href="/product" class="{{ request()->is('product*') && !request()->is('product/insert') ? 'active' : '' }}"><i data-feather="box"></i><span>Produk</span></a></li>
            <li><a href="/product/insert" class="insert-product {{ request()->is('product/insert') ? 'active' : '' }}"><i data-feather="plus-square"></i><span>Tambah Produk</span></a></li> --}}
            <li class="{{ request()->is('product*') && !request()->is('product-by-status*') ? 'active submenu' : 'submenu' }}">
              <a href="javascript:void(0);" class="{{ request()->is('product*') && !request()->is('product-by-status*') ? 'active subdrop' : '' }}"><i data-feather="box"></i><span>Produk</span><span class="menu-arrow"></span></a>
              <ul>
                <li><a href="/product/insert" class="{{ request()->is('product/insert') && !request()->is('product-by-status*')  ? '' : 'text-white' }}">Tambah Produk</a></li>
                <li><a href="/product" class="{{ request()->is('product*') && !request()->is('product/insert') && !request()->is('product-by-status*') ? '' : 'text-white' }}">Semua Produk</a></li>
              </ul>
            </li>
          </ul>
        </li>
        <li class="submenu-open aset-data">
          <h6 class="submenu-hdr">Manajemen Status Aset</h6>
          <ul>
            <li class="{{ request()->is('product-by-status*') ? 'active submenu' : 'submenu' }}">
              <a href="javascript:void(0);" class="{{ request()->is('product-by-status*') ? 'active subdrop' : '' }}"><i data-feather="box"></i><span>Produk By Status</span><span class="menu-arrow"></span></a>
              <ul>
                {{-- <li><a href="/product-by-status/insert" class="insert-product {{ request()->is('product/insert') ? '' : 'text-white' }}">Tambah Produk</a></li> --}}
                <li><a href="/product-by-status/16" class="insert-product {{ request()->is('product-by-status/16')  ? '' : 'text-white' }}">01. Dalam Proses Produksi</a></li>
                <li><a href="/product-by-status/17" class="insert-product {{ request()->is('product-by-status/17')  ? '' : 'text-white' }}">02. Selesai Produksi</a></li>
                <li><a href="/product-by-status/18" class="insert-product {{ request()->is('product-by-status/18')  ? '' : 'text-white' }}">03. Pengecekan</a></li>
                <li><a href="/product-by-status/19" class="insert-product {{ request()->is('product-by-status/19')  ? '' : 'text-white' }}">04. Siap Kirim</a></li>
                <li><a href="/product-by-status/32" class="insert-product {{ request()->is('product-by-status/32')  ? '' : 'text-white' }}">05. Persiapan Surat Jalan</a></li>
                <li><a href="/product-by-status/20" class="insert-product {{ request()->is('product-by-status/20')  ? '' : 'text-white' }}">06. Pengiriman</a></li>
                <li><a href="/product-by-status/21" class="insert-product {{ request()->is('product-by-status/21')  ? '' : 'text-white' }}">07. Diterima</a></li>
              </ul>
            </li>
          </ul>
        </li>
        <li class="submenu-open master-data">
          <h6 class="submenu-hdr">Manajemen Referensi</h6>
          <ul>
            <li class="{{ request()->is('category*') || request()->is('segment*') || request()->is('module*') && !request()->is('module-complete*') || request()->is('shelf*') || request()->is('status-product*') || request()->is('status-tool-material*') || request()->is('shipping*') || request()->is('group*') ? 'active submenu' : 'submenu' }}">
              <a href="javascript:void(0);" class="{{ request()->is('category*') || request()->is('segment*') || request()->is('module*') && !request()->is('module-complete*') || request()->is('shelf*') || request()->is('status-product*') || request()->is('status-tool-material*') || request()->is('shipping*') || request()->is('group*') ? 'active subdrop' : '' }}"><i data-feather="box"></i><span>Referensi Aset</span><span class="menu-arrow"></span></a>
              <ul>
                <li><a href="/category" class="{{ request()->is('category*') ? '' : 'text-white' }}">Kategori</a></li>
                <li><a href="/segment" class="{{ request()->is('segment*') ? '' : 'text-white' }}">Segmen</a></li>
                <li><a href="/module" class="{{ request()->is('module*') && !request()->is('module-complete*')  ? '' : 'text-white' }}">Modul</a></li>
                <li><a href="/shelf" class="{{ request()->is('shelf*') ? '' : 'text-white' }}">Rak</a></li>
                <li><a href="/status-product" class="{{ request()->is('status-product*') ? '' : 'text-white' }}">Status Produk</a></li>
                <li><a href="/status-tool-material" class="{{ request()->is('status-tool-material*') ? '' : 'text-white' }}">Status Alat & Bahan</a></li>
                <li><a href="/shipping" class="{{ request()->is('shipping*') ? '' : 'text-white' }}">Ekspedisi</a></li>
                <li><a href="/group" class="{{ request()->is('group*') ? '' : 'text-white' }}">Grup</a></li>
              </ul>
            </li>

            <li class="{{ request()->is('module-complete*') || request()->is('work-progress*') ? 'active submenu' : 'submenu' }}">
              <a href="javascript:void(0);" class="{{ request()->is('module-complete*') || request()->is('work-progress*') ? 'active subdrop' : '' }}"><i data-feather="box"></i><span>Referensi Dashboard</span><span class="menu-arrow"></span></a>
              <ul>
                <li><a href="/module-complete" class="{{ request()->is('module-complete*') ? '' : 'text-white' }}">Kelengkapan Modul</a></li>
                <li><a href="/work-progress" class="{{ request()->is('work-progress*') ? '' : 'text-white' }}">Progress Pekerjaan</a></li>
              </ul>
            </li>

            {{-- <li class="{{ request()->is('category*') ? 'active' : '' }}"><a href="/category"><i data-feather="codepen"></i><span>Kategori</span></a></li> --}}
            {{-- <li class="{{ request()->is('segment*') ? 'active' : '' }}"><a href="/segment"><i data-feather="pie-chart"></i><span>Segmen</span></a></li>
            <li class="{{ request()->is('module*') ? 'active' : '' }}"><a href="/module"><i data-feather="clipboard"></i><span>Modul</span></a></li>
            <li class="{{ request()->is('shelf*') ? 'active' : '' }}"><a href="/shelf"><i data-feather="server"></i><span>Rak</span></a></li>
            <li class="{{ request()->is('status-product*') ? 'active' : '' }}"><a href="/status-product"><i data-feather="package"></i><span>Status Produk</span></a></li>
            <li class="{{ request()->is('status-tool-material*') ? 'active' : '' }}"><a href="/status-tool-material"><i data-feather="package"></i><span>Status Alat & Bahan</span></a></li>
            <li class="{{ request()->is('shipping*') ? 'active' : '' }}"><a href="/shipping"><i data-feather="truck"></i><span>Ekspedisi</span></a></li>
            <li class="{{ request()->is('group*') ? 'active group' : 'group' }}"><a href="/group"><i data-feather="users"></i><span>Kelompok</span></a></li> --}}
          </ul>
        </li>
        <li class="submenu-open user-data">
          <h6 class="submenu-hdr">Manajemen User</h6>
          <ul>
            <li class="{{ request()->is('user*') && !request()->is('user/inactive-user') ? 'active' : '' }}"><a href="/user"><i data-feather="users"></i><span>User</span></a></li>
            <li class="{{ request()->is('user/inactive-user') ? 'active verif-role' : 'verif-role' }}"><a href="/user/inactive-user"><i data-feather="user-x"></i><span>Verifikasi User</span></a></li>
          </ul>
        </li>
        <li class="submenu-open">
          <h6 class="submenu-hdr">Settings</h6>
          <ul>
            <li class="submenu">
              <a href="javascript:void(0);"><i data-feather="settings"></i><span>Settings</span><span class="menu-arrow"></span></a>
              <ul>
                <li><a href="generalsettings.html">General Settings</a></li>
                <li><a href="emailsettings.html">Email Settings</a></li>
                <li><a href="paymentsettings.html">Payment Settings</a></li>
                <li><a href="currencysettings.html">Currency Settings</a></li>
                <li><a href="grouppermissions.html">Group Permissions</a></li>
                <li><a href="taxrates.html">Tax Rates</a></li>
              </ul>
            </li>
            <li>
              <a class="logout-account"><i data-feather="log-out"></i><span>Logout</span> </a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    const currentUser = JSON.parse(localStorage.getItem('current_user'))
    const tokenType = localStorage.getItem('token_type')
    const accessToken = localStorage.getItem('access_token')

    $('.fullname').text(currentUser.fullname)
    
    $('.role').text(currentUser.isAdmin ? 'Admin' : 'Pegawai')
    switch(currentUser.isAdmin) {
      case 1:
        $('.role').text('Admin')
        break
      case 2:
        $('.role').text('Admin Produksi')
        break
      case 3:
        $('.role').text('Officer Produksi')
        break
      case 4:
        $('.role').text('Officer Monitoring')
        break
      case 5:
        $('.role').text('Owner')
        break
      default:
        // code block
    }

    // Untuk semua user kecuali admin dan owner
    currentUser.isAdmin !== 1 && currentUser.isAdmin !== 5 && $('.user-data').remove()

    // officer produksi
    currentUser.isAdmin == 3 && $('.master-data').remove()

    // officer monitoring
    currentUser.isAdmin == 4 && $('.master-data').remove()
    currentUser.isAdmin == 4 && $('.insert-product').remove()

    // owner
    currentUser.isAdmin == 5 && $('.insert-product').remove()
    currentUser.isAdmin == 5 && $('.verif-role').remove()


    $('#btnFullscreen').on('click', () => {
      toggleFullScreen()
    })

    $('.logout-account').on('click', function() {
      logout()
    })


    function logout() {
      $('#global-loader').show()

      let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      let config = {
        headers: {
          'X-CSRF-TOKEN': token,
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'Authorization': `${tokenType} ${accessToken}`
        }
      }

      axios.delete("{{ url('api/v1/auth/logout') }}", config)
        .then(function(res) {

          // Swal.fire({
          //   position: 'center',
          //   icon: 'success',
          //   title: res.data.meta.message,
          //   showConfirmButton: false,
          //   timer: 3000
          // })

          localStorage.clear()

          window.location.href = "{{ url('/') }}"
        })
        .catch(function(err) {
          console.log(err)
        })
    }

    function toggleFullScreen() {
      if (!document.fullscreenElement && // alternative standard method
        !document.mozFullScreenElement && !document.webkitFullscreenElement) { // current working methods
        if (document.documentElement.requestFullscreen) {
          document.documentElement.requestFullscreen();
        } else if (document.documentElement.mozRequestFullScreen) {
          document.documentElement.mozRequestFullScreen();
        } else if (document.documentElement.webkitRequestFullscreen) {
          document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
        }
      } else {
        if (document.cancelFullScreen) {
          document.cancelFullScreen();
        } else if (document.mozCancelFullScreen) {
          document.mozCancelFullScreen();
        } else if (document.webkitCancelFullScreen) {
          document.webkitCancelFullScreen();
        }
      }
    }

  })
</script>
<!-- /Sidebar -->
