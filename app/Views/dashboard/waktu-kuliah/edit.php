<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>
    Waktu Kuliah
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Content Row -->
<div class="row">
    <div class="col-lg-9">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Edit Waktu Kuliah</h6>
            </div>
            <div class="card-body">
                <!-- Alert Bootstrap Session Flash Script -->
                <?= showAlertBs(); ?>

                <form action="<?= site_url('/dashboard/waktu-kuliah/update/' . esc($waktuKuliah['id'])) ?>" method="post">
                    <input type="hidden" name="_method" value="PUT">
                    <!-- Dropdown Hari -->
                    <div class="form-group">
                        <label for="hari">Hari:</label>
                        <select name="hari" id="hari" class="form-control">
                            <option value="">--- Pilih Hari ---</option>
                            <option value="Senin" <?= old('hari', $waktuKuliah['hari']) == 'Senin' ? 'selected' : '' ?>>Senin</option>
                            <option value="Selasa" <?= old('hari', $waktuKuliah['hari']) == 'Selasa' ? 'selected' : '' ?>>Selasa</option>
                            <option value="Rabu" <?= old('hari', $waktuKuliah['hari']) == 'Rabu' ? 'selected' : '' ?>>Rabu</option>
                            <option value="Kamis" <?= old('hari', $waktuKuliah['hari']) == 'Kamis' ? 'selected' : '' ?>>Kamis</option>
                            <option value="Jumat" <?= old('hari', $waktuKuliah['hari']) == 'Jumat' ? 'selected' : '' ?>>Jumat</option>
                            <option value="Sabtu" <?= old('hari', $waktuKuliah['hari']) == 'Sabtu' ? 'selected' : '' ?>>Sabtu</option>
                            <option value="Minggu" <?= old('hari', $waktuKuliah['hari']) == 'Minggu' ? 'selected' : '' ?>>Minggu</option>
                        </select>
                    </div>
                    <!-- Input Jam Mulai dan Jam Selesai -->
                    <div class="form-group">
                        <div class="mb-2">
                            <label class="m-0" for="jam_mulai">Jam Mulai dan Jam Selesai:</label>
                            <!-- Buat deskripsi -->
                            <p class="small text-danger m-0">AM dimulai dari 12:00 AM (tengah malam) sampai 11:59 AM (sebelum siang)</p>
                            <p class="small text-danger m-0">PM dimulai dari 12:00 PM (siang) sampai 11:59 PM (sebelum tengah malam).</p>
                        </div>
                        <div class="d-flex" style="gap: 1rem;">
                            <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" value="<?= old('jam_mulai', date('H:i', strtotime($waktuKuliah['jam_mulai']))) ?>">
                            <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" value="<?= old('jam_selesai', date('H:i', strtotime($waktuKuliah['jam_selesai']))) ?>">
                        </div>
                    </div>
                    <div class="d-flex mt-4" style="gap: 1rem;">
                        <a href="<?= site_url('/dashboard/waktu-kuliah') ?>" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
