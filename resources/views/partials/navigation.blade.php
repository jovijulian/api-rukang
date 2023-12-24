<!-- Header -->
<div class="header">

    <!-- Logo -->
    <div class="header-left active">
        <a href="{{ url('/') }}" class="logo logo-normal">
            RUKANG ADMIN
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
            {{-- <div class="top-nav-search">

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
            </div> --}}
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
        {{-- <li class="nav-item nav-item-box ">
            <a><i data-feather="settings"></i></a>
        </li> --}}
        <li class="nav-item dropdown has-arrow main-drop">
            <a href="javascript:void(0);" class="dropdown-toggle nav-link userset" data-bs-toggle="dropdown">
                <span class="user-info">
                    <span class="user-letter">
                        <img src="{{ url('assets/img/profiles/avator1.jpg') }}" alt="" class="img-fluid">
                    </span>
                    <span class="user-detail">
                        <span class="user-name fullname">ADMIN</span>
                        <span class="user-role role">Admin</span>
                    </span>
                </span>
            </a>
            <div class="dropdown-menu menu-drop-user">
                <div class="profilename">
                    <div class="profileset">
                        <span class="user-img"><img src="{{ url('assets/img/profiles/avator1.jpg') }}" alt="">
                            <span class="status online"></span></span>
                        <div class="profilesets">
                            <h6 class="fullname">ADMIN</h6>
                            <h5 class="role">Admin</h5>
                        </div>
                    </div>
                    <hr class="m-0">
                    {{-- <a class="dropdown-item" href="profile.html"> <i class="me-2" data-feather="user"></i> My
                        Profile</a>
                    <a class="dropdown-item" href="generalsettings.html"><i class="me-2"
                            data-feather="settings"></i>Settings</a> --}}
                    <hr class="m-0">
                    <button class="dropdown-item logout pb-0 logout-account"><img
                            src="{{ url('assets/img/icons/log-out.svg') }}" class="me-2"
                            alt="img">Logout</button>
                </div>
            </div>
        </li>
    </ul>
    <!-- /Header Menu -->

    <!-- Mobile Menu -->
    <div class="dropdown mobile-user-menu">
        <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
            aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
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
                <li class="submenu-open manajemen-asset">
                    <h6 class="submenu-hdr">Manajemen Talent</h6>
                    <ul>
                        <li><a href="/talent" class="{{ request()->is('talent*') ? '' : 'text-white' }}">Talent</a></li>
                    </ul>
                </li>



                <li class="submenu-open">
                    {{-- <h6 class="submenu-hdr">Settings</h6> --}}
                    <ul>
                        {{-- <li class="submenu">
                            <a href="javascript:void(0);"><i data-feather="settings"></i><span>Settings</span><span
                                    class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="generalsettings.html">General Settings</a></li>
                                <li><a href="emailsettings.html">Email Settings</a></li>
                                <li><a href="paymentsettings.html">Payment Settings</a></li>
                                <li><a href="currencysettings.html">Currency Settings</a></li>
                                <li><a href="grouppermissions.html">Group Permissions</a></li>
                                <li><a href="taxrates.html">Tax Rates</a></li>
                            </ul>
                        </li> --}}
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
</script>
<!-- /Sidebar -->
