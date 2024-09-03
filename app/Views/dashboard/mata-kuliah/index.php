<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>
    Dashboard
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Mata Kuliah</h1>
    <a href="<?= site_url('/dashboard/mata-kuliah/create') ?>" class="d-flex align-items-center btn btn-sm btn-primary shadow-sm"><span class="mr-2 m-0" style="font-size: 18px !important;">+</span> Tambah Data</a>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Tabel Mata Kuliah</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Kode</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($mata_kuliah as $item): ?>
                            <tr>
                                <td><?= esc($item['nama']) ?></td>
                                <td><?= esc($item['kode']) ?></td>
                                <td><?= esc($item['deskripsi']) ?></td>
                                <td>
                                    <a href="<?= site_url('/dashboard/mata-kuliah/edit/' . $item['id']) ?>" class="btn btn-warning btn-sm px-3">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?= site_url('/dashboard/mata-kuliah/delete/' . $item['id']) ?>" method="post" style="display:inline;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-danger btn-sm px-3">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
    <!-- DataTables Script -->
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/Indonesian.json"
                }
            });
        });
    </script>
<?= $this->endSection() ?>