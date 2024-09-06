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
                <h6 class="m-0 font-weight-bold text-primary">Edit Ruangan</h6>
            </div>
            <div class="card-body">
                <!-- Alert Bootstrap Session Flash Script -->
                <?= showAlertBs(); ?>

                <form action="<?= site_url('/dashboard/ruangan/update/' . esc($ruangan['id'])) ?>" method="post">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-group">
                        <label for="nama">Nama:</label>
                        <input type="text" name="nama" id="nama" class="form-control" value="<?= old('nama', esc($ruangan['nama'])) ?>">
                    </div>
                    <div class="form-group">
                        <label for="kode">Kode Ruangan: <span class="text-danger small">Contoh: RU1, RUXX </span></label> 
                        <input type="text" name="kode" id="kode" class="form-control" value="<?= old('kode', esc($ruangan['kode'])) ?>">
                    </div>
                    <div class="form-group">
                        <label for="kapasitas">Kapasitas Ruangan: </label> 
                        <input type="number" name="kapasitas" id="kapasitas" class="form-control" value="<?= old('kapasitas', esc($ruangan['kapasitas'])) ?>" min="10" max="50">
                    </div>
                    <div class="form-group">
                        <label for="program_studi_id">Program Studi:</label>
                        <select name="program_studi_id" id="program_studi_id" class="form-control">
                            <option value="">--- Pilih Program Studi ---</option>
                            <?php foreach ($programStudi as $prodi): ?>
                                <option value="<?= $prodi['id'] ?>" <?= $prodi['id'] == old('program_studi_id', $ruangan['program_studi_id']) ? 'selected' : '' ?>>
                                    <?= esc($prodi['nama']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan:</label>
                        <textarea name="keterangan" id="keterangan" class="form-control" rows="4"><?= old('keterangan', esc($ruangan['keterangan'])) ?></textarea>
                    </div>
                    <div class="d-flex mt-4" style="gap: 1rem;">
                        <a href="<?= site_url('/dashboard/ruangan') ?>" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
