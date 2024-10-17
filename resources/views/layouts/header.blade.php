<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="../../index3.html" class="nav-link">Home</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Contact</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        <li class="nav-item">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="fas fa-search"></i>
            </a>
            <div class="navbar-search-block">
                <form class="form-inline">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" type="search" placeholder="Search"
                            aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        <!-- Messages Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-comments"></i>
                <span class="badge badge-danger navbar-badge">3</span>
            </a>
            <!-- Message Dropdown Content -->
            <!-- ... [Omitted for brevity] ... -->
        </li>

        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">15</span>
            </a>
            <!-- Notification Dropdown Content -->
            <!-- ... [Omitted for brevity] ... -->
        </li>

        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>

        <!-- Profile Icon -->
        <li class="nav-item">
            <a id="profile" class="nav-link" href="#" role="button">
                <i class="fas fa-user"></i>
            </a>
        </li>

        <!-- Logout Form -->
        <form id="logout-form" action="{{ url('logout') }}" method="get" style="display: none;">
            @csrf
        </form>
    </ul>
</nav>
<!-- Profile Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document"> <!-- Modal dialog centered -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profileModalLabel">User Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center"> <!-- Centered text -->
                <!-- Profile Information -->
                <img 
                    src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('foto.png') }}" 
                    alt="User Avatar" 
                    class="img-circle mb-3" 
                    width="100">
                <p><strong>Username:</strong> {{ auth()->user()->username }}</p>
                <p><strong>Nama:</strong> {{ auth()->user()->nama }}</p>
                <p><strong>Level:</strong> {{ auth()->user()->level ? auth()->user()->level->level_nama : 'Tidak ada level' }}</p>
            </div>
            <div class="modal-footer justify-content-center"> <!-- Centered footer -->
                <button type="button" class="btn btn-danger" data-dismiss="modal" id="logout-link">Logout</button>
                <a href="{{ url('profile/edit') }}" class="btn btn-primary">Edit Profile</a>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Profile Modal Script -->
<script>
    document.getElementById('profile').addEventListener('click', function(e) {
        e.preventDefault();
        $('#profileModal').modal('show');  // Trigger the modal
    });

    document.getElementById('logout-link').addEventListener('click', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Apakah Anda yakin ingin keluar?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, keluar!',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    });
</script>
