<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>
    Jadwal
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Jadwal</h1>
    <div class="d-flex align-items-center">
        <a href="<?= site_url('/dashboard/jadwal/generate') ?>" class="d-flex align-items-center btn btn-sm btn-info shadow-sm mr-3">
            <span class="mr-2 m-0" style="font-size: 18px !important;">+</span> Generate Jadwal
        </a>
        <a href="<?= site_url('/dashboard/jadwal/create') ?>" class="d-flex align-items-center btn btn-sm btn-primary shadow-sm">
            <span class="mr-2 m-0" style="font-size: 18px !important;">+</span> Tambah Jadwal
        </a>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Tabel Jadwal</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable-id" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Prodi</th>
                                <th>Matkul</th>
                                <th>Kelas</th>
                                <th>Ruangan</th>
                                <th>Dosen</th>
                                <th>Waktu</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($jadwal as $item): ?>
                            <tr>
                                <td><?= esc($item['program_studi']['kode']) ?></td>
                                <td>(<?= esc($item['mata_kuliah']['kode']) ?>)<?= esc($item['mata_kuliah']['nama']) ?></td>
                                <td><?= esc($item['kelas']['kode']) ?></td>
                                <td><?= esc($item['ruangan']['kode']) ?></td>
                                <td>(<?= esc($item['dosen']['nomer_pegawai']) ?>)<br/><?= esc($item['dosen']['nama']) ?></td>
                                <td><?= esc($item['waktu_kuliah']['hari']) ?><br/><?= date('H:i', strtotime(esc($item['waktu_kuliah']['jam_mulai']))) ?> - <?= date('H:i', strtotime(esc($item['waktu_kuliah']['jam_selesai']))) ?></td>
                                <td>
                                    <a href="<?= site_url('/dashboard/jadwal/edit/' . $item['id']) ?>" class="btn btn-warning btn-sm px-3">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm px-3 btn-delete" data-url="<?= site_url('/dashboard/jadwal/delete/' . $item['id']) ?>" data-id="<?= esc($item['id']) ?>">
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

