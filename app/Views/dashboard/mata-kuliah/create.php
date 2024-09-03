<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>
    Mata Kuliah
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Content Row -->
<div class="row">
    <div class="col-lg-9">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Buat Mata Kuliah</h6>
            </div>
            <div class="card-body">
                <?php if(session()->getFlashdata('message')):?>
                    <div class="alert alert-<?= session()->getFlashdata('message')['type'] ?> alert-dismissible fade show mb-4" role="alert" style="font-size: 0.875rem;">
                        <strong><?= session()->getFlashdata('message')['title'] ?></strong><br/> <?= session()->getFlashdata('message')['data'] ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif;?>

                <form action="<?= site_url('/dashboard/mata-kuliah/store') ?>" method="post">
                    <div class="form-group">
                        <label for="nama">Nama:</label>
                        <input type="text" name="nama" id="nama" class="form-control" value="<?= old('nama') ?>">
                    </div>
                    <div class="form-group">
                        <label for="kode">Kode:</label>
                        <input type="text" name="kode" id="kode" class="form-control" value="<?= old('kode') ?>">
                    </div>
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi:</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4"><?= old('deskripsi') ?></textarea>
                    </div>
                    <div class="d-flex" style="gap: 1rem;">
                        <a href="<?= site_url('/dashboard/mata-kuliah') ?>" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
