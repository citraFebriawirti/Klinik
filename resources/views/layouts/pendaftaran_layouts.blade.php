<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <link href="{{ asset('img/logo/logo.png') }}" rel="icon">
    <title>{{ env('APP_NAME') }}</title>

    <!-- Stylesheets -->
    <link href="{{ asset('admin/assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/ruang-admin.min.css') }}" rel="stylesheet">

    @livewireStyles
    @stack('scripts') <!-- Dipindahkan ke bagian akhir head -->
</head>

<body class="bg-gradient-login">
    @include('sweetalert::alert')

    <!-- Login Content -->
    <div class="container-login">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-12 col-md-12">
                <div class="">
                    <div class="">
                        <div class="row">
                            <div class="col-lg-12">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('admin/assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin/assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/ruang-admin.min.js') }}"></script>

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

    @livewireScripts
</body>

</html>
