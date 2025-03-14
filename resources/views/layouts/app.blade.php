<!DOCTYPE html>
<html lang="en">

<head>
    @stack('scripts')

    @livewireStyles

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="  {{ asset('admin/assets/img/icon.png') }}" rel="icon">
    <title>{{ env('APP_NAME') }}</title>
    <link href="   {{ asset('admin/assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet"
        type="text/css">
    <link href=" {{ asset('admin/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/ruang-admin.min.css') }} " rel="stylesheet">
   

    {{-- search --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css">
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <style>
    .nav-item .nav-link {
    color: rgb(255, 255, 255); /* Warna default */
    transition: 0.3s;
}

.nav-item .nav-link:hover,
.nav-item .nav-link:focus {
    background-color: #b99044;
    color: white;
    border-radius: 5px;
}

.nav-item.active .nav-link {
    background-color: #b99044;
    color: white;
    /* border-radius: 5px; */
}

.nav-item.active .nav-link span
{
    color: white;
 
}

    </style>

<style>
    .modal-body {
        max-height: 400px; /* Atur sesuai kebutuhan */
        overflow-y: auto; /* Pastikan scroll muncul */
    }

    /* Kustomisasi scroll bar */
    .modal-body::-webkit-scrollbar {
        width: 8px;
    }
    .modal-body::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    .modal-body::-webkit-scrollbar-thumb {
        background: #0d6efd; /* Warna biru Bootstrap */
        border-radius: 4px;
    }
    .modal-body::-webkit-scrollbar-thumb:hover {
        background: #0b5ed7;
    }
</style>
</head>

<body id="page-top">
   @include('sweetalert::alert')



    <div id="wrapper">


        <!-- Sidebar -->
        <ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon">
                    {{-- <img src="img/logo/logo2.png"> --}}
                </div>
                <div class="sidebar-brand-text mx-3">SIMKLINIK</div>
            </a>
            <hr class="sidebar-divider my-0">
           <div>
            <li class="nav-item ">
                <a class="nav-link" href="">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>


            <hr class="sidebar-divider">
            <div class="sidebar-heading">
              Data Master
            </div>
            <li class="nav-item {{ request()->routeIs('poli') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('poli') }}" >
                    <i class="fas fa-file-word"></i>
                    <span>Data Poli</span>
                </a>
            </li>

            <li class="nav-item {{ request()->routeIs('obat') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('obat') }}" >
                    <i class="fas fa-file-word"></i>
                    <span>Data Obat</span>
                </a>
            </li>

            <li class="nav-item {{ request()->routeIs('pasien') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('pasien') }}" >
                    <i class="fas fa-file-word"></i>
                    <span>Data Pasien</span>
                </a>
            </li>
            
            
            <li class="nav-item {{ request()->routeIs('dokter') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dokter') }}">
                    <i class="fas fa-file-word"></i>
                    <span>Data Dokter</span>
                </a>
            </li>

            <hr class="sidebar-divider">
            <div class="sidebar-heading">
              Data Transaksi
            </div>

            <li class="nav-item {{ request()->routeIs('pemeriksaan') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('pemeriksaan') }}">
                    <i class="fas fa-file-word"></i>
                    <span>Data Pemeriksaan</span>
                </a>
            </li>
             <li class="nav-item {{ request()->routeIs('farmasi.resep') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('farmasi.resep') }}">
                    <i class="fas fa-file-word"></i>
                    <span>Data Farmasi Obat</span>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('kasir.transaksi') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('kasir.transaksi') }}">
                    <i class="fas fa-file-word"></i>
                    <span>Data Pembayaran Kasir</span>
                </a>
            </li>

          
            
            
            {{-- <li class="nav-item">
                <a class="nav-link" href="{{ route('janjitemu') }}">
                    <i class="fas fa-file-word"></i>
                    <span>Data Janji Temu</span>
                </a>
            </li> --}}
            
            

            {{-- <li class="nav-item">
                <a class="nav-link" href="{{ route('buku') }}">
                    <i class="fas fa-file-word"></i>
                    <span>Data Users</span>
                </a>
            </li> --}}
           </div>

            <hr class="sidebar-divider">

            <li class="nav-item {{ request()->routeIs('pendaftaran') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('pendaftaran') }}">
                    <i class="fas fa-file-word"></i>
                    <span>Data Pendaftaran</span>
                </a>
            </li>
            <div class="version" id="version-ruangadmin"></div>
        </ul>
        <!-- Sidebar -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- TopBar -->
                <nav class="navbar navbar-expand navbar-light bg-navbar topbar mb-4 static-top">
                    <button id="sidebarToggleTop" class="btn btn-link rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <ul class="navbar-nav ml-auto">


                        <div class="topbar-divider d-none d-sm-block"></div>
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="img-profile rounded-circle" src="{{ asset('admin/assets/img/boy.png') }}"
                                    style="max-width: 60px">
                                <span class="ml-2 d-none d-lg-inline text-white small"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>

                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="" data-toggle="modal"
                                    data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- Topbar -->

                <!-- Container Fluid-->
                @yield('content')
                <!---Container Fluid-->
            </div>
            <!-- Footer -->
            <footer class="sticky-footer bg-white">


                <div class="container my-auto py-2">
                    <div class="copyright text-center my-auto">
                        <span>copyright &copy; <script>
                            document.write(new Date().getFullYear());
                            </script> -
                            <b><a href="/" target="_blank">SIM KLINIK</a></b>
                        </span>
                    </div>
                </div>
            </footer>
            <!-- Footer -->
        </div>
    </div>

    <!-- Scroll to top -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <!-- Vendor JS Files -->
    {{-- <script src="{{ asset('admin/assets/vendor/jquery/jquery.min.js') }} "></script> --}}
    <script src="{{ asset('admin/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}  "></script>
    <script src="{{ asset('admin/assets/vendor/jquery-easing/jquery.easing.min.js') }} "></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="{{ asset('admin/assets/vendor/tinymce/tinymce.min.js') }} "></script>


    <!-- Template Main JS File -->
    <script src="{{ asset('admin/assets/js/ruang-admin.min.js') }} "></script>



    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Livewire.on('swalSuccess', message => {
                Swal.fire({
                    title: 'Sukses!',
                    text: message,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            });
        });
    </script>
    

 


    <script>
    Livewire.on('openModal', () => {
        let modalElement = document.querySelector('.modal');
        if (modalElement) {
            var modal = new bootstrap.Modal(modalElement);
            modal.show();
        }
    });

    Livewire.on('closeModal', () => {
        let modalElement = document.querySelector('.modal');
        if (modalElement) {
            var modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) modal.hide();
        }
    });
    </script>

    @livewireScripts

</body>

</html>