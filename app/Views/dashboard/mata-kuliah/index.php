<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>
    Mata Kuliah
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
                    <table class="table table-bordered" id="dataTable-id" width="100%" cellspacing="0">
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
                                    <button type="button" class="btn btn-danger btn-sm px-3 btn-delete" data-url="<?= site_url('/dashboard/mata-kuliah/delete/' . $item['id']) ?>" data-id="<?= $item['id'] ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
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
    <!-- Sweet Alert Session Flash Script -->
    <?= showSweetAlert() ?>

    <!-- DataTables and AJAX Script -->
    <script>
        $(document).ready(function() {
            $('#dataTable-id').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json"
                }
            });

            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                const deleteUrl = $(this).data('url');
                const idData = $(this).data('id');

                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Yakin akan menghapus data dengan id ' + idData,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: deleteUrl,
                            type: 'DELETE',
                            success: function(response) {
                                if (response.code === 200) {
                                    Swal.fire({
                                        title: response.message.title,
                                        text: response.message.description,
                                        icon: response.message.type,
                                    }).then(() => {
                                        // Reload DataTables instead of the entire page
                                        // $('#dataTable-id').DataTable().ajax.reload();
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Failed',
                                        text: 'Gagal melakukan aksi, silahkan coba lagi.',
                                        icon: 'warning',
                                    });
                                }
                            },
                            error: function(xhr) {
                                const response = xhr.responseJSON;
                                Swal.fire({
                                    title: response.message.title || 'Error',
                                    text: response.message.description || 'Terjadi kesalahan, silahkan coba lagi.',
                                    icon: response.message.type || 'error',
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
<?= $this->endSection() ?>