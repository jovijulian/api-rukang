<!-- Header -->
<div class="header">

  <!-- Logo -->
  <div class="header-left active">
    <a href="index.html" class="logo logo-normal">
      <img src="{{ url('assets/img/logo.png') }}" alt="">
    </a>
    <a href="index.html" class="logo logo-white">
      <img src="{{ url('assets/img/logo-white.png') }}" alt="">
    </a>
    <a href="index.html" class="logo-small">
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

    <!-- Flag -->
    <li class="nav-item dropdown has-arrow flag-nav nav-item-box">
      <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="javascript:void(0);" role="button">
        <i data-feather="globe"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-right">
        <a href="javascript:void(0);" class="dropdown-item active">
          <img src="{{ url('assets/img/flags/us.png') }}" alt="" height="16"> English
        </a>
        <a href="javascript:void(0);" class="dropdown-item">
          <img src="{{ url('assets/img/flags/fr.png') }}" alt="" height="16"> French
        </a>
        <a href="javascript:void(0);" class="dropdown-item">
          <img src="{{ url('assets/img/flags/es.png') }}" alt="" height="16"> Spanish
        </a>
        <a href="javascript:void(0);" class="dropdown-item">
          <img src="{{ url('assets/img/flags/de.png') }}" alt="" height="16"> German
        </a>
      </div>
    </li>
    <!-- /Flag -->

    <li class="nav-item nav-item-box">
      <a href="javascript:void(0);" id="btnFullscreen">
        <i data-feather="maximize"></i>
      </a>
    </li>
    <li class="nav-item nav-item-box">
      <a href="email.html">
        <i data-feather="mail"></i>
        <span class="badge rounded-pill">1</span>
      </a>
    </li>
    <!-- Notifications -->
    {{-- <li class="nav-item dropdown nav-item-box">
      <a href="javascript:void(0);" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
        <i data-feather="bell"></i><span class="badge rounded-pill">2</span>
      </a>
      <div class="dropdown-menu notifications">
        <div class="topnav-dropdown-header">
          <span class="notification-title">Notifications</span>
          <a href="javascript:void(0)" class="clear-noti"> Clear All </a>
        </div>
        <div class="noti-content">
          <ul class="notification-list">
            <li class="notification-message">
              <a href="activities.html">
                <div class="media d-flex">
                  <span class="avatar flex-shrink-0">
                    <img alt="" src="assets/img/profiles/avatar-02.jpg">
                  </span>
                  <div class="media-body flex-grow-1">
                    <p class="noti-details"><span class="noti-title">John Doe</span> added new task <span
                        class="noti-title">Patient appointment booking</span></p>
                    <p class="noti-time"><span class="notification-time">4 mins ago</span></p>
                  </div>
                </div>
              </a>
            </li>
            <li class="notification-message">
              <a href="activities.html">
                <div class="media d-flex">
                  <span class="avatar flex-shrink-0">
                    <img alt="" src="assets/img/profiles/avatar-03.jpg">
                  </span>
                  <div class="media-body flex-grow-1">
                    <p class="noti-details"><span class="noti-title">Tarah Shropshire</span> changed the task name
                      <span class="noti-title">Appointment booking with payment gateway</span>
                    </p>
                    <p class="noti-time"><span class="notification-time">6 mins ago</span></p>
                  </div>
                </div>
              </a>
            </li>
            <li class="notification-message">
              <a href="activities.html">
                <div class="media d-flex">
                  <span class="avatar flex-shrink-0">
                    <img alt="" src="assets/img/profiles/avatar-06.jpg">
                  </span>
                  <div class="media-body flex-grow-1">
                    <p class="noti-details"><span class="noti-title">Misty Tison</span> added <span
                        class="noti-title">Domenic Houston</span> and <span class="noti-title">Claire Mapes</span> to
                      project <span class="noti-title">Doctor available module</span></p>
                    <p class="noti-time"><span class="notification-time">8 mins ago</span></p>
                  </div>
                </div>
              </a>
            </li>
            <li class="notification-message">
              <a href="activities.html">
                <div class="media d-flex">
                  <span class="avatar flex-shrink-0">
                    <img alt="" src="assets/img/profiles/avatar-17.jpg">
                  </span>
                  <div class="media-body flex-grow-1">
                    <p class="noti-details"><span class="noti-title">Rolland Webber</span> completed task <span
                        class="noti-title">Patient and Doctor video conferencing</span></p>
                    <p class="noti-time"><span class="notification-time">12 mins ago</span></p>
                  </div>
                </div>
              </a>
            </li>
            <li class="notification-message">
              <a href="activities.html">
                <div class="media d-flex">
                  <span class="avatar flex-shrink-0">
                    <img alt="" src="assets/img/profiles/avatar-13.jpg">
                  </span>
                  <div class="media-body flex-grow-1">
                    <p class="noti-details"><span class="noti-title">Bernardo Galaviz</span> added new task <span
                        class="noti-title">Private chat module</span></p>
                    <p class="noti-time"><span class="notification-time">2 days ago</span></p>
                  </div>
                </div>
              </a>
            </li>
          </ul>
        </div>
        <div class="topnav-dropdown-footer">
          <a href="activities.html">View all Notifications</a>
        </div>
      </div>
    </li> --}}
    <!-- /Notifications -->

    <li class="nav-item nav-item-box">
      <a href="generalsettings.html"><i data-feather="settings"></i></a>
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
          <a class="dropdown-item" href="generalsettings.html"><i class="me-2"
              data-feather="settings"></i>Settings</a>
          <hr class="m-0">
          <button class="dropdown-item logout pb-0 logout-account"><img
              src="{{ url('assets/img/icons/log-out.svg') }}" class="me-2" alt="img">Logout</button>
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
            <li class="submenu">
              <a href="javascript:void(0);"><i data-feather="smartphone"></i><span>Application</span><span
                  class="menu-arrow"></span></a>
              <ul>
                <li><a href="chat.html">Chat</a></li>
                <li><a href="calendar.html">Calendar</a></li>
                <li><a href="email.html">Email</a></li>
              </ul>
            </li>
          </ul>
        </li>
        <li class="submenu-open">
          <h6 class="submenu-hdr">Manajemen Master</h6>
          <ul>
            <li><a href="/segment" class="{{ request()->is('segment*') ? 'active' : '' }}"><i data-feather="pie-chart"></i><span>Segmen</span></a></li>
            <li><a href="/description" class="{{ request()->is('description*') ? 'active' : '' }}"><i data-feather="file-text"></i><span>Deskripsi</span></a></li>
            <li><a href="/"><i data-feather="codepen"></i><span>Kategori</span></a></li>
            <li><a href="/"><i data-feather="package"></i><span>Status</span></a></li>
          </ul>
        </li>
        <li class="submenu-open">
          <h6 class="submenu-hdr">Products</h6>
          <ul>
            <li><a href="/product"><i data-feather="box"></i><span>Products</span></a></li>
            <li><a href="addproduct.html"><i data-feather="plus-square"></i><span>Create Product</span></a></li>
            <li><a href="categorylist.html"><i data-feather="codepen"></i><span>Category</span></a></li>
            <li><a href="brandlist.html"><i data-feather="tag"></i><span>Brands</span></a></li>
            <li><a href="subcategorylist.html"><i data-feather="speaker"></i><span>Sub Category</span></a></li>
            <li><a href="barcode.html"><i data-feather="align-justify"></i><span>Print Barcode</span></a></li>
            <li><a href="importproduct.html"><i data-feather="minimize-2"></i><span>Import Products</span></a></li>
          </ul>
        </li>
        <li class="submenu-open is-admin">
          <h6 class="submenu-hdr">Manajemen User</h6>
          <ul>
            <li><a href="user/inactive-user" class="{{ request()->is('user/inactive-user') ? 'active' : '' }}"><i data-feather="users"></i><span>Verifikasi User</span></a></li>
            {{-- <li class="submenu">
              <a href="javascript:void(0);" class="{{ request()->is('user*') ? 'active subdrop' : '' }}"><i
                  data-feather="users"></i><span>User</span><span class="menu-arrow"></span></a>
              <ul>
                <li><a href="user/inactive-user"
                    class="{{ request()->is('user/inactive-user') ? 'active' : '' }}">Verifikasi User</a></li>
                <li><a href="userlists.html">Users List</a></li>
              </ul>
            </li> --}}
          </ul>
        </li>
        <li class="submenu-open">
          <h6 class="submenu-hdr">Sales</h6>
          <ul>
            <li><a href="saleslist.html"><i data-feather="shopping-cart"></i><span>Sales</span></a></li>
            <li><a href="invoicereport.html"><i data-feather="file-text"></i><span>Invoices</span></a></li>
            <li><a href="salesreturnlists.html"><i data-feather="copy"></i><span>Sales Return</span></a></li>
            <li><a href="quotationList.html"><i data-feather="save"></i><span>Quotation</span></a></li>
            <li><a href="pos.html"><i data-feather="hard-drive"></i><span>POS</a></li>
            <li class="submenu">
              <a href="javascript:void(0);"><i data-feather="shuffle"></i><span>Transfer</span><span
                  class="menu-arrow"></span></a>
              <ul>
                <li><a href="transferlist.html">Transfer List</a></li>
                <li><a href="importtransfer.html">Import Transfer </a></li>
              </ul>
            </li>
            <li class="submenu">
              <a href="javascript:void(0);"><i data-feather="corner-up-left"></i><span>Return</span><span
                  class="menu-arrow"></span></a>
              <ul>
                <li><a href="salesreturnlist.html">Sales Return</a></li>
                <li><a href="purchasereturnlist.html">Purchase Return</a></li>
              </ul>
            </li>
          </ul>
        </li>
        <li class="submenu-open">
          <h6 class="submenu-hdr">Purchases</h6>
          <ul>
            <li><a href="purchaselist.html"><i data-feather="shopping-bag"></i><span>Purchases</span></a></li>
            <li><a href="importpurchase.html"><i data-feather="minimize-2"></i><span>Import Purchases</span></a></li>
            <li><a href="purchaseorderreport.html"><i data-feather="file-minus"></i><span>Purchase Order</span></a>
            </li>
            <li><a href="purchasereturnlist.html"><i data-feather="refresh-cw"></i><span>Purchase Return</span></a>
            </li>
          </ul>
        </li>
        <li class="submenu-open">
          <h6 class="submenu-hdr">Finance & Accounts</h6>
          <ul>
            <li class="submenu">
              <a href="javascript:void(0);"><i data-feather="file-text"></i><span>Expense</span><span
                  class="menu-arrow"></span></a>
              <ul>
                <li><a href="expenselist.html">Expenses</a></li>
                <li><a href="expensecategory.html">Expense Category</a></li>
              </ul>
            </li>

          </ul>
        </li>
        <li class="submenu-open">
          <h6 class="submenu-hdr">Peoples</h6>
          <ul>
            <li><a href="customerlist.html"><i data-feather="user"></i><span>Customers</span></a></li>
            <li><a href="supplierlist.html"><i data-feather="users"></i><span>Suppliers</span></a></li>
            <li><a href="userlist.html"><i data-feather="user-check"></i><span>Users</span></a></li>
            <li><a href="storelist.html"><i data-feather="home"></i><span>Stores</span></a></li>
          </ul>
        </li>
        <li class="submenu-open">
          <h6 class="submenu-hdr">Reports</h6>
          <ul>
            <li><a href="salesreport.html"><i data-feather="bar-chart-2"></i><span>Sales Report</span></a></li>
            <li><a href="purchaseorderreport.html"><i data-feather="pie-chart"></i><span>Purchase report</span></a>
            </li>
            <li><a href="inventoryreport.html"><i data-feather="credit-card"></i><span>Inventory Report</span></a>
            </li>
            <li><a href="invoicereport.html"><i data-feather="file"></i><span>Invoice Report</span></a></li>
            <li><a href="purchasereport.html"><i data-feather="bar-chart"></i><span>Purchase Report</span></a></li>
            <li><a href="supplierreport.html"><i data-feather="database"></i><span>Supplier Report</span></a></li>
            <li><a href="customerreport.html"><i data-feather="pie-chart"></i><span>Customer Report</span></a></li>
          </ul>
        </li>
        <li class="submenu-open">
          <h6 class="submenu-hdr">Pages</h6>
          <ul>
            <li class="submenu">
              <a href="javascript:void(0);"><i data-feather="shield"></i><span>Authentication</span><span
                  class="menu-arrow"></span></a>
              <ul>
                <li><a href="signin.html">Log in</a></li>
                <li><a href="signup.html">Register</a></li>
                <li><a href="forgetpassword.html">Forgot Password</a></li>
                <li><a href="resetpassword.html">Reset Password</a></li>
              </ul>
            </li>
            <li class="submenu">
              <a href="javascript:void(0);"><i data-feather="file-minus"></i><span>Error Pages</span><span
                  class="menu-arrow"></span></a>
              <ul>
                <li><a href="error-404.html">404 Error </a></li>
                <li><a href="error-500.html">500 Error </a></li>
              </ul>
            </li>
            <li class="submenu">
              <a href="javascript:void(0);"><i data-feather="map"></i><span>Places</span><span
                  class="menu-arrow"></span></a>
              <ul>
                <li><a href="countrieslist.html">Countries</a></li>
                <li><a href="statelist.html">States</a></li>
              </ul>
            </li>
            <li>
              <a href="blankpage.html"><i data-feather="file"></i><span>Blank Page</span> </a>
            </li>
            <li>
              <a href="components.html"><i data-feather="pen-tool"></i><span>Components</span> </a>
            </li>
          </ul>
        </li>
        <li class="submenu-open">
          <h6 class="submenu-hdr">UI Interface</h6>
          <ul>
            <li class="submenu">
              <a href="javascript:void(0);"><i data-feather="layers"></i><span>Elements</span><span
                  class="menu-arrow"></span></a>
              <ul>
                <li><a href="sweetalerts.html">Sweet Alerts</a></li>
                <li><a href="tooltip.html">Tooltip</a></li>
                <li><a href="popover.html">Popover</a></li>
                <li><a href="ribbon.html">Ribbon</a></li>
                <li><a href="clipboard.html">Clipboard</a></li>
                <li><a href="drag-drop.html">Drag & Drop</a></li>
                <li><a href="rangeslider.html">Range Slider</a></li>
                <li><a href="rating.html">Rating</a></li>
                <li><a href="toastr.html">Toastr</a></li>
                <li><a href="text-editor.html">Text Editor</a></li>
                <li><a href="counter.html">Counter</a></li>
                <li><a href="scrollbar.html">Scrollbar</a></li>
                <li><a href="spinner.html">Spinner</a></li>
                <li><a href="notification.html">Notification</a></li>
                <li><a href="lightbox.html">Lightbox</a></li>
                <li><a href="stickynote.html">Sticky Note</a></li>
                <li><a href="timeline.html">Timeline</a></li>
                <li><a href="form-wizard.html">Form Wizard</a></li>
              </ul>
            </li>
            <li class="submenu">
              <a href="javascript:void(0);"><i data-feather="bar-chart-2"></i><span>Charts</span><span
                  class="menu-arrow"></span></a>
              <ul>
                <li><a href="chart-apex.html">Apex Charts</a></li>
                <li><a href="chart-js.html">Chart Js</a></li>
                <li><a href="chart-morris.html">Morris Charts</a></li>
                <li><a href="chart-flot.html">Flot Charts</a></li>
                <li><a href="chart-peity.html">Peity Charts</a></li>
              </ul>
            </li>
            <li class="submenu">
              <a href="javascript:void(0);"><i data-feather="database"></i><span>Icons</span><span
                  class="menu-arrow"></span></a>
              <ul>
                <li><a href="icon-fontawesome.html">Fontawesome Icons</a></li>
                <li><a href="icon-feather.html">Feather Icons</a></li>
                <li><a href="icon-ionic.html">Ionic Icons</a></li>
                <li><a href="icon-material.html">Material Icons</a></li>
                <li><a href="icon-pe7.html">Pe7 Icons</a></li>
                <li><a href="icon-simpleline.html">Simpleline Icons</a></li>
                <li><a href="icon-themify.html">Themify Icons</a></li>
                <li><a href="icon-weather.html">Weather Icons</a></li>
                <li><a href="icon-typicon.html">Typicon Icons</a></li>
                <li><a href="icon-flag.html">Flag Icons</a></li>
              </ul>
            </li>
            <li class="submenu">
              <a href="javascript:void(0);"><i data-feather="edit"></i><span>Forms</span><span
                  class="menu-arrow"></span></a>
              <ul>
                <li><a href="form-basic-inputs.html">Basic Inputs </a></li>
                <li><a href="form-input-groups.html">Input Groups </a></li>
                <li><a href="form-horizontal.html">Horizontal Form </a></li>
                <li><a href="form-vertical.html"> Vertical Form </a></li>
                <li><a href="form-mask.html">Form Mask </a></li>
                <li><a href="form-validation.html">Form Validation </a></li>
                <li><a href="form-select2.html">Form Select2 </a></li>
                <li><a href="form-fileupload.html">File Upload </a></li>
              </ul>
            </li>
            <li class="submenu">
              <a href="javascript:void(0);"><i data-feather="columns"></i><span>Tables</span><span
                  class="menu-arrow"></span></a>
              <ul>
                <li><a href="tables-basic.html">Basic Tables </a></li>
                <li><a href="data-tables.html">Data Table </a></li>
              </ul>
            </li>
          </ul>
        </li>
        <li class="submenu-open">
          <h6 class="submenu-hdr">Settings</h6>
          <ul>
            <li class="submenu">
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

    !currentUser.isAdmin && $('.is-admin').hide()

    $('.fullname').text(currentUser.fullname)
    $('.role').text(currentUser.isAdmin ? 'Admin' : 'Pegawai')
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
