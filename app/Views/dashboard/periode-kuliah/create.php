<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>
    Periode Kuliah
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Content Row -->
<div class="row">
    <div class="col-lg-9">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Buat Periode Kuliah</h6>
            </div>
            <div class="card-body">
                <!-- Alert Bootstrap Session Flash Script -->
                <?= showAlertBs(); ?>

                <form action="<?= site_url('/dashboard/periode-kuliah/store') ?>" method="post">
                    <div class="form-group">
                        <label for="tahun_awal">Tahun Awal:</label>
                        <select name="tahun_awal" id="tahun_awal" class="form-control">
                            <option value="">--- Pilih Tahun Awal ---</option>
                            <?php for ($i = 2020; $i <= date('Y') + 5; $i++): ?>
                                <option value="<?= $i ?>" <?= old('tahun_awal') == $i ? 'selected' : '' ?>><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tahun_akhir">Tahun Akhir:</label>
                        <select name="tahun_akhir" id="tahun_akhir" class="form-control">
                            <option value="">--- Pilih Tahun Akhir ---</option>
                            <?php for ($i = 2020; $i <= date('Y') + 6; $i++): ?>
                                <option value="<?= $i ?>" <?= old('tahun_akhir') == $i ? 'selected' : '' ?>><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <!-- Dropdown Semester -->
                    <div class="form-group">
                        <label for="semester">Semester:</label>
                        <select name="semester" id="semester" class="form-control">
                            <option value="">--- Pilih Semester ---</option>
                            <option value="Ganjil" <?= old('semester') == 'Ganjil' ? 'selected' : '' ?>>Ganjil</option>
                            <option value="Genap" <?= old('semester') == 'Genap' ? 'selected' : '' ?>>Genap</option>
                        </select>
                    </div>

                    <div class="d-flex mt-4" style="gap: 1rem;">
                        <a href="<?= site_url('/dashboard/periode-kuliah') ?>" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
