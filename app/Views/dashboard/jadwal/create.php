<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>
    Buat Jadwal
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Buat Jadwal</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Dropdown Prodi -->
                    <div class="col-lg-3">
                        <div class="form-group">
                            <!-- <label for="prodi">Program Studi:</label> -->
                            <select name="prodi" id="prodi" class="form-control">
                                <option value="">--- Pilih Program Studi ---</option>
                                <?php foreach ($programStudi as $prodi): ?>
                                    <option value="<?= esc($prodi['id']) ?>"><?= esc($prodi['nama']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <!-- Dropdown Tahun Ajaran -->
                    <div class="col-lg-3">
                        <div class="form-group">
                            <!-- <label for="tahun_ajaran">Tahun Ajaran:</label> -->
                            <select name="tahun_ajaran" id="tahun_ajaran" class="form-control">
                                <option value="">--- Pilih Tahun Ajaran ---</option>
                                <?php foreach ($tahunAjaran as $tahun): ?>
                                    <option value="<?= esc($tahun['id']) ?>"><?= esc($tahun['periode']) ?> - <?= esc($tahun['semester']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <!-- Buttons -->
                    <div class="col-lg-6">
                        <div class="d-flex justify-content-between pl-0 pl-xl-5">
                            <button type="button" id="generateBtn" class="btn btn-primary">Generate Jadwal</button>
                            <button type="button" id="evaluasiBtn" class="btn btn-secondary" data-toggle="modal" data-target="#evaluasiModal" disabled>Evaluasi Jadwal</button>
                            <button type="button" id="perhitunganBtn" class="btn btn-info" data-toggle="modal" data-target="#perhitunganModal" disabled>Perhitungan</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <?= showAlertBs(); ?>
                        <!-- Alert Bootstrap Session Flash Script -->
                    </div>
                    <div class="col-12 mt-4">
                        <div class="text-center">
                            Belum ada data
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Evaluasi Jadwal -->
<div class="modal fade" id="evaluasiModal" tabindex="-1" role="dialog" aria-labelledby="evaluasiModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="evaluasiModalLabel">Evaluasi Jadwal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Isi modal evaluasi jadwal -->
                Evaluasi Jadwal akan dilakukan di sini.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <!-- <button type="button" class="btn btn-primary" id="submitEvaluasi">Kirim</button> -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Perhitungan Generate -->
<div class="modal fade" id="perhitunganModal" tabindex="-1" role="dialog" aria-labelledby="perhitunganModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="perhitunganModalLabel">Perhitungan Generate</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Isi modal perhitungan generate -->
                Perhitungan Generate akan dilakukan di sini.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <!-- <button type="button" class="btn btn-primary" id="submitPerhitungan">Kirim</button> -->
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(document).ready(function() {
        $('#generateBtn').click(function() {
            const prodi = $('#prodi').val();
            const tahunAjaran = $('#tahun_ajaran').val();

            if (!prodi || !tahunAjaran) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Harap isi Program Studi dan Tahun Ajaran dahulu!',
                });
            } else {
                // Kirim data ke server dengan AJAX
                $.ajax({
                    url: '<?= site_url('/dashboard/jadwal/generate') ?>',
                    type: 'POST',
                    data: {
                        program_studi_id: prodi,
                        tahun_ajaran_id: tahunAjaran
                    },
                    success: function(response) {
                        $('#evaluasiBtn').prop('disabled', false);
                        $('#perhitunganBtn').prop('disabled', false);
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal mengirim data ke server.',
                        });
                    }
                });
            }
        });
    });
</script>
<?= $this->endSection() ?>
