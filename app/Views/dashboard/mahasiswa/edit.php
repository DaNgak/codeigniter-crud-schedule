<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>
    Mahasiswa
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Content Row -->
<div class="row">
    <div class="col-lg-9">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Edit Mahasiswa</h6>
            </div>
            <div class="card-body">
                <!-- Alert Bootstrap Session Flash Script -->
                <?= showAlertBs(); ?>

                <form action="<?= site_url('/dashboard/mahasiswa/update/' . $mahasiswa['id']) ?>" method="post">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-group">
                        <label for="nama">Nama:</label>
                        <input type="text" name="nama" id="nama" class="form-control" value="<?= old('nama', esc($mahasiswa['nama'])) ?>">
                    </div>
                    <div class="form-group">
                        <label for="nomer_identitas">Nomer Identitas:</label>
                        <input type="number" name="nomer_identitas" id="nomer_identitas" class="form-control" value="<?= old('nomer_identitas', esc($mahasiswa['nomer_identitas'])) ?>">
                    </div>
                    <div class="form-group">
                        <label for="program_studi_id">Program Studi:</label>
                        <select name="program_studi_id" id="program_studi_id" class="form-control">
                            <option value="">--- Pilih Program Studi ---</option>
                            <?php foreach ($programStudi as $ps): ?>
                                <option value="<?= esc($ps['id']) ?>" <?= old('program_studi_id', $mahasiswa['program_studi_id']) == $ps['id'] ? 'selected' : '' ?>>
                                    <?= esc($ps['nama']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="kelas_id">Kelas:</label>
                        <select name="kelas_id" id="kelas_id" class="form-control">
                            <option value="">--- Pilih Kelas ---</option>
                            <?php foreach ($kelas as $kls): ?>
                                <option value="<?= esc($kls['id']) ?>" <?= old('kelas_id', $mahasiswa['kelas_id']) == $kls['id'] ? 'selected' : '' ?>>
                                    <?= esc($kls['nama']) ?> (<?= esc($kls['kode']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="d-flex mt-4" style="gap: 1rem;">
                        <a href="<?= site_url('/dashboard/mahasiswa') ?>" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Update</button>
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
            // Set the old values for program_studi_id and kelas_id from form input or existing mahasiswa data
            var oldProgramStudi = "<?= old('program_studi_id', esc($mahasiswa['program_studi_id'])) ?>";
            var oldKelas = "<?= old('kelas_id', esc($mahasiswa['kelas_id'])) ?>";

            // If there is an oldProgramStudi, trigger the AJAX call to fetch and populate the kelas options
            if (oldProgramStudi) {
                $('#program_studi_id').val(oldProgramStudi).trigger('change'); // Set and trigger change event for oldProgramStudi
                
                // Fetch kelas data based on oldProgramStudi and select oldKelas
                $.ajax({
                    url: "<?= site_url('/dashboard/mahasiswa/dropdown/getKelasByProgramStudi') ?>/" + oldProgramStudi,
                    type: "GET",
                    dataType: "json",
                    success: function (response) {
                        if (response.code === 200) {
                            $('#kelas_id').empty(); // Clear the current options
                            $('#kelas_id').append('<option value="">--- Pilih Kelas ---</option>');

                            // Populate kelas options
                            $.each(response.data, function (key, value) {
                                $('#kelas_id').append('<option value="' + value.id + '">' + value.nama + ' (' + value.kode + ')</option>');
                            });

                            // Set the oldKelas as selected if it exists
                            if (oldKelas) {
                                $('#kelas_id').val(oldKelas); // Select the old kelas_id
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

            $('#program_studi_id').change(function () {
                var programStudiId = $(this).val();
                if (programStudiId) {
                    $.ajax({
                        url: "<?= site_url('/dashboard/mahasiswa/dropdown/getKelasByProgramStudi') ?>/" + programStudiId,
                        type: "GET",
                        dataType: "json",
                        success: function (response) {
                            if (response.code === 200) {
                                $('#kelas_id').empty(); // Clear the current options
                                $('#kelas_id').append('<option value="">--- Pilih Kelas ---</option>');
                                $.each(response.data, function (key, value) {
                                    $('#kelas_id').append('<option value="' + value.id + '">' + value.nama + ' (' + value.kode + ')</option>');
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
                    $('#kelas_id').empty();
                    $('#kelas_id').append('<option value="">--- Pilih Kelas ---</option>');
                }
            });

            // Trigger change to load the correct 'kelas' on page load if 'program_studi_id' is already selected
            // $('#program_studi_id').trigger('change');
        });
    </script>
<?= $this->endSection() ?>
