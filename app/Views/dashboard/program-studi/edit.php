<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>
    Program Studi
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Content Row -->
<div class="row">
    <div class="col-lg-9">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Edit Program Studi</h6>
            </div>
            <div class="card-body">
                <!-- Alert Bootstrap Session Flash Script -->
                <?= showAlertBs(); ?>

                <form action="<?= site_url('/dashboard/program-studi/update/' . esc($programStudi['id'])) ?>" method="post">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-group">
                        <label for="nama">Nama:</label>
                        <input type="text" name="nama" id="nama" class="form-control" value="<?= old('nama', $programStudi['nama']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="kode">Kode: <span class="text-danger small">Contoh: TI, TE, XX </span></label> 
                        <input type="text" name="kode" id="kode" class="form-control" value="<?= old('kode', $programStudi['kode']) ?>">
                    </div>
                    <div class="d-flex" style="gap: 1rem;">
                        <a href="<?= site_url('/dashboard/program-studi') ?>" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
