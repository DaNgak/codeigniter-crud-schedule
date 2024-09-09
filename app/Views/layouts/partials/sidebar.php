<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= site_url('/dashboard') ?>">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">ScheduleApp</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?= (uri_string() == 'dashboard') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= site_url('/dashboard') ?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Data
    </div>

    <!-- Nav Item - Program Studi -->
    <li class="nav-item <?= (uri_string() == 'dashboard/program-studi' || strpos(uri_string(), 'dashboard/program-studi') === 0) ? 'active' : '' ?>">
        <a class="nav-link" href="<?= site_url('/dashboard/program-studi') ?>">
            <i class="fas fa-fw fa-book"></i>
            <span>Program Studi</span>
        </a>
    </li>

    <!-- Nav Item - Mata Kuliah -->
    <li class="nav-item <?= (uri_string() == 'dashboard/mata-kuliah' || strpos(uri_string(), 'dashboard/mata-kuliah') === 0) ? 'active' : '' ?>">
        <a class="nav-link" href="<?= site_url('/dashboard/mata-kuliah') ?>">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Mata Kuliah</span>
        </a>
    </li>

    <!-- Nav Item - Ruangan -->
    <li class="nav-item <?= (uri_string() == 'dashboard/ruangan' || strpos(uri_string(), 'dashboard/ruangan') === 0) ? 'active' : '' ?>">
        <a class="nav-link" href="<?= site_url('/dashboard/ruangan') ?>">
            <i class="fas fa-fw fa-table"></i>
            <span>Ruangan</span></a>
    </li>

    <!-- Nav Item - Dosen -->
    <li class="nav-item <?= (uri_string() == 'dashboard/dosen' || strpos(uri_string(), 'dashboard/dosen') === 0) ? 'active' : '' ?>">
        <a class="nav-link" href="<?= site_url('/dashboard/dosen') ?>">
            <i class="fas fa-fw fa-users"></i>
            <span>Dosen</span>
        </a>
    </li>

    <!-- Nav Item - Mahasiswa -->
    <li class="nav-item <?= (uri_string() == 'dashboard/mahasiswa' || strpos(uri_string(), 'dashboard/mahasiswa') === 0) ? 'active' : '' ?>">
        <a class="nav-link" href="<?= site_url('/dashboard/mahasiswa') ?>">
            <i class="fas fa-fw fa-ghost"></i>
            <span>Mahasiswa</span>
        </a>
    </li>

    <!-- Nav Item - Kelas -->
    <li class="nav-item <?= (uri_string() == 'dashboard/kelas' || strpos(uri_string(), 'dashboard/kelas') === 0) ? 'active' : '' ?>">
        <a class="nav-link" href="<?= site_url('/dashboard/kelas') ?>">
            <i class="fab fa-fw fa-odnoklassniki"></i>
            <span>Kelas</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Waktu Kuliah -->
    <li class="nav-item <?= (uri_string() == 'dashboard/periode-kuliah' || strpos(uri_string(), 'dashboard/periode-kuliah') === 0) ? 'active' : '' ?>">
        <a class="nav-link" href="<?= site_url('/dashboard/periode-kuliah') ?>">
            <i class="fas fa-fw fa-folder"></i>
            <span>Periode Kuliah</span>
        </a>
    </li>

    <!-- Nav Item - Waktu Kuliah -->
    <li class="nav-item <?= (uri_string() == 'dashboard/waktu-kuliah' || strpos(uri_string(), 'dashboard/waktu-kuliah') === 0) ? 'active' : '' ?>">
        <a class="nav-link" href="<?= site_url('/dashboard/waktu-kuliah') ?>">
            <i class="fas fa-fw fa-clock"></i>
            <span>Waktu Kuliah</span>
        </a>
    </li>
    
    <!-- Nav Item - Jadwal Dropdown -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
            aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-fw fa-calendar"></i>
            <span>Jadwal</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="<?= site_url('/dashboard/jadwal/create') ?>">Buat Jadwal</a>
                <a class="collapse-item" href="<?= site_url('/dashboard/jadwal') ?>">Kelola Jadwal</a>
            </div>
        </div>
    </li>

    <!-- Heading -->
    <!-- <div class="sidebar-heading">
        Interface
    </div> -->

    <!-- Nav Item - Pages Collapse Menu -->
    <!-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-cog"></i>
            <span>Components</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Custom Components:</h6>
                <a class="collapse-item" href="buttons.html">Buttons</a>
                <a class="collapse-item" href="cards.html">Cards</a>
            </div>
        </div>
    </li> -->

    <!-- Nav Item - Utilities Collapse Menu -->
    <!-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
            aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-wrench"></i>
            <span>Utilities</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Custom Utilities:</h6>
                <a class="collapse-item" href="utilities-color.html">Colors</a>
                <a class="collapse-item" href="utilities-border.html">Borders</a>
                <a class="collapse-item" href="utilities-animation.html">Animations</a>
                <a class="collapse-item" href="utilities-other.html">Other</a>
            </div>
        </div>
    </li> -->

    <!-- Divider -->
    <!-- <hr class="sidebar-divider"> -->
    
    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->