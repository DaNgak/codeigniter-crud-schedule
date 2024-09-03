<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>
    Dosen
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Content Row -->
<div class="row">
    <div class="col-lg-9">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Buat Dosen</h6>
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

                <form action="<?= site_url('/dashboard/dosen/store') ?>" method="post">
                    <div class="form-group">
                        <label for="nama">Nama:</label>
                        <input type="text" name="nama" id="nama" class="form-control" value="<?= old('nama') ?>">
                    </div>
                    <div class="form-group">
                        <label for="nomer_pegawai">Nomer Pegawai:</label>
                        <input type="text" name="nomer_pegawai" id="nomer_pegawai" class="form-control" value="<?= old('nomer_pegawai') ?>">
                    </div>
                    <div class="d-flex" style="gap: 1rem;">
                        <a href="<?= site_url('/dashboard/dosen') ?>" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
