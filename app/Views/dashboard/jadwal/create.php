<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>
    Jadwal
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Content Row -->
<div class="row">
    <div class="col-lg-9">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Tambah Jadwal</h6>
            </div>
            <div class="card-body">
                <?= showAlertBs(); ?>

                <form action="<?= site_url('/dashboard/jadwal/store') ?>" method="post">
                    <div class="form-group">
                        <label for="periode_kuliah_id">Periode Kuliah:</label>
                        <select name="periode_kuliah_id" id="periode_kuliah_id" class="form-control">
                            <option value="">--- Pilih Periode Kuliah ---</option>
                            <?php foreach ($periodeKuliah as $pk): ?>
                                <option value="<?= esc($pk['id']) ?>" <?= old('periode_kuliah_id') == $pk['id'] ? 'selected' : '' ?>>
                                    <?= esc($pk['tahun_awal'] . '/' . $pk['tahun_akhir']) ?> - <?= esc($pk['semester']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="program_studi_id">Program Studi:</label>
                        <select name="program_studi_id" id="program_studi_id" class="form-control">
                            <option value="">--- Pilih Program Studi ---</option>
                            <?php foreach ($programStudi as $ps): ?>
                                <option value="<?= esc($ps['id']) ?>" <?= old('program_studi_id') == $ps['id'] ? 'selected' : '' ?>>
                                    <?= esc($ps['nama']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="kelas_id">Kelas:</label>
                        <select name="kelas_id" id="kelas_id" class="form-control">
                            <option value="">--- Pilih Kelas ---</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="mata_kuliah_id">Mata Kuliah:</label>
                        <select name="mata_kuliah_id" id="mata_kuliah_id" class="form-control">
                            <option value="">--- Pilih Mata Kuliah ---</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ruangan_id">Ruangan:</label>
                        <select name="ruangan_id" id="ruangan_id" class="form-control">
                            <option value="">--- Pilih Ruangan ---</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="waktu_kuliah_id">Waktu Kuliah:</label>
                        <select name="waktu_kuliah_id" id="waktu_kuliah_id" class="form-control">
                            <option value="">--- Pilih Waktu Kuliah ---</option>
                            <?php foreach ($waktuKuliah as $wk): ?>
                                <option value="<?= esc($wk['id']) ?>" <?= old('waktu_kuliah_id') == $wk['id'] ? 'selected' : '' ?>>
                                    <?= esc($wk['hari']) ?> (<?= date('H:i', strtotime($wk['jam_mulai'])) ?> - <?= date('H:i', strtotime($wk['jam_selesai'])) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="dosen_id">Dosen:</label>
                        <select name="dosen_id" id="dosen_id" class="form-control">
                            <option value="">--- Pilih Dosen ---</option>
                        </select>
                    </div>
                    <div class="d-flex mt-4" style="gap: 1rem;">
                        <a href="<?= site_url('/dashboard/jadwal') ?>" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script type="text/javascript">
    $(document).ready(function () {
        // Set nilai lama untuk dropdown
        var oldProgramStudi = "<?= old('program_studi_id') ?>";
        var oldKelas = "<?= old('kelas_id') ?>";
        var oldMataKuliah = "<?= old('mata_kuliah_id') ?>";
        var oldRuangan = "<?= old('ruangan_id') ?>";
        var oldDosen = "<?= old('dosen_id') ?>";

        // Set nilai lama untuk program_studi_id dan trigger perubahan jika ada nilai lama
        if (oldProgramStudi) {
            $('#program_studi_id').val(oldProgramStudi);
            $.ajax({
                url: "<?= site_url('/dashboard/jadwal/dropdown/getOptionsByProgramStudi') ?>/" + oldProgramStudi,
                type: "GET",
                dataType: "json",
                success: function (response) {
                    if (response.code === 200) {
                        // Populate Kelas dropdown
                        $('#kelas_id').empty().append('<option value="">--- Pilih Kelas ---</option>');
                        $.each(response.data.kelas, function (key, value) {
                            $('#kelas_id').append('<option value="' + value.id + '">' + '(' + value.kode + ') ' + value.nama + '</option>');
                        });

                        // Populate Mata Kuliah dropdown
                        $('#mata_kuliah_id').empty().append('<option value="">--- Pilih Mata Kuliah ---</option>');
                        $.each(response.data.mata_kuliah, function (key, value) {
                            $('#mata_kuliah_id').append('<option value="' + value.id + '">' + '(' + value.kode + ') ' + value.nama + '</option>');
                        });

                        // Populate Ruangan dropdown
                        $('#ruangan_id').empty().append('<option value="">--- Pilih Ruangan ---</option>');
                        $.each(response.data.ruangan, function (key, value) {
                            $('#ruangan_id').append('<option value="' + value.id + '">' + '(' + value.kode + ') ' + value.nama + '</option>');
                        });

                        // Populate Dosen dropdown
                        $('#dosen_id').empty().append('<option value="">--- Pilih Dosen ---</option>');
                        $.each(response.data.dosen, function (key, value) {
                            $('#dosen_id').append('<option value="' + value.id + '">' + '(' + value.nomer_pegawai + ') ' + value.nama + '</option>');
                        });

                           // Set nilai lama untuk old value
                        if (oldKelas) {
                            $('#kelas_id').val(oldKelas);
                        }
                        if (oldMataKuliah) {
                            $('#mata_kuliah_id').val(oldMataKuliah);
                        }
                        if (oldRuangan) {
                            $('#ruangan_id').val(oldRuangan);
                        }
                        if (oldDosen) {
                            $('#dosen_id').val(oldDosen);
                        }
                    } else {
                        Swal.fire({
                            title: 'Failed',
                            text: 'Gagal mendapatkan data, silahkan coba lagi.',
                            icon: 'warning',
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        title: 'Error',
                        text: 'Terjadi kesalahan, silahkan coba lagi.',
                        icon: 'error',
                    });
                }
            });
        }

        // Load dependent dropdowns when program_studi_id changes
        $('#program_studi_id').change(function () {
            var programStudiId = $(this).val();
            if (programStudiId) {
                $.ajax({
                    url: "<?= site_url('/dashboard/jadwal/dropdown/getOptionsByProgramStudi') ?>/" + programStudiId,
                    type: "GET",
                    dataType: "json",
                    success: function (response) {
                        if (response.code === 200) {
                            // Populate Kelas dropdown
                            $('#kelas_id').empty().append('<option value="">--- Pilih Kelas ---</option>');
                            $.each(response.data.kelas, function (key, value) {
                                $('#kelas_id').append('<option value="' + value.id + '">' + '(' + value.kode + ') ' + value.nama + '</option>');
                            });

                            // Populate Mata Kuliah dropdown
                            $('#mata_kuliah_id').empty().append('<option value="">--- Pilih Mata Kuliah ---</option>');
                            $.each(response.data.mata_kuliah, function (key, value) {
                                $('#mata_kuliah_id').append('<option value="' + value.id + '">' + '(' + value.kode + ') ' + value.nama + '</option>');
                            });

                            // Populate Ruangan dropdown
                            $('#ruangan_id').empty().append('<option value="">--- Pilih Ruangan ---</option>');
                            $.each(response.data.ruangan, function (key, value) {
                                $('#ruangan_id').append('<option value="' + value.id + '">' + '(' + value.kode + ') ' + value.nama + '</option>');
                            });

                            // Populate Dosen dropdown
                            $('#dosen_id').empty().append('<option value="">--- Pilih Dosen ---</option>');
                            $.each(response.data.dosen, function (key, value) {
                                $('#dosen_id').append('<option value="' + value.id + '">' + '(' + value.nomer_pegawai + ') ' + value.nama + '</option>');
                            });
                        } else {
                            Swal.fire({
                                title: 'Failed',
                                text: 'Gagal mendapatkan data, silahkan coba lagi.',
                                icon: 'warning',
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            title: 'Error',
                            text: 'Terjadi kesalahan, silahkan coba lagi.',
                            icon: 'error',
                        });
                    }
                });
            } else {
                $('#kelas_id, #mata_kuliah_id, #ruangan_id, #dosen_id').empty().append('<option value="">--- Pilih ---</option>');
            }
        });
    });
</script>
<?= $this->endSection() ?>
