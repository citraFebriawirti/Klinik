<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
    @stack('scripts')

    @livewireStyles

    <script src="{{ asset('assets/js/color-modes.js') }}"></script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIMKLINIK</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
    <link href="{{ asset('assets/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/headers.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>

    <main>
        <div class="b-example-divider">
            <h3 class="d-flex justify-content-center align-items-center">SIMKLINIK</h3>
        </div>

        <header class="p-3 mb-3 border-bottom">
            <div class="container">
                <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                    <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 link-body-emphasis text-decoration-none">
                        <i class="bi bi-bootstrap"></i>
                    </a>

                    <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    
                        <li><a href="{{ route('pasien') }}" class="nav-link px-2 link-body-emphasis">Klinik</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <div class="container">
            <div class="row">
                <div class="col-md-8 ms-4">
                    @yield('content')
                </div>
            </div>
        </div>

    </main>

    <script src="{{ asset('assets/dist/js/bootstrap.bundle.min.js') }}"></script>
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