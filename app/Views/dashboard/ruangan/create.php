<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>
    Ruangan
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Content Row -->
<div class="row">
    <div class="col-lg-9">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Buat Ruangan</h6>
            </div>
            <div class="card-body">
                <!-- Alert Bootstrap Session Flash Script -->
                <?= showAlertBs(); ?>

                <form action="<?= site_url('/dashboard/ruangan/store') ?>" method="post">
                    <div class="form-group">
                        <label for="nama">Nama:</label>
                        <input type="text" name="nama" id="nama" class="form-control" value="<?= old('nama') ?>">
                    </div>
                    <div class="form-group">
                        <label for="kode">Kode Ruangan: <span class="text-danger small">Contoh: RU1, RUXX </span></label> 
                        <input type="text" name="kode" id="kode" class="form-control" value="<?= old('kode') ?>">
                    </div>
                    <div class="form-group">
                        <label for="kapasitas">Kapasitas Ruangan: </label> 
                        <input type="number" name="kapasitas" id="kapasitas" class="form-control" value="<?= old('kapasitas') ?>" min="10" max="50">
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan:</label>
                        <textarea name="keterangan" id="keterangan" class="form-control" rows="4"><?= old('keterangan') ?></textarea>
                    </div>
                    <div class="d-flex" style="gap: 1rem;">
                        <a href="<?= site_url('/dashboard/ruangan') ?>" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
