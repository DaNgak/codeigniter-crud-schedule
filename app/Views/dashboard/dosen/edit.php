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
                <h6 class="m-0 font-weight-bold text-primary">Edit Dosen</h6>
            </div>
            <div class="card-body">
                <!-- Alert Bootstrap Session Flash Script -->
                <?= showAlertBs(); ?>

                <form action="<?= site_url('/dashboard/dosen/update/' . esc($dosen['id'])) ?>" method="post">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-group">
                        <label for="nama">Nama:</label>
                        <input type="text" name="nama" id="nama" class="form-control" value="<?= old('nama', esc($dosen['nama'])) ?>">
                    </div>
                    <div class="form-group">
                        <label for="nomer_pegawai">Nomer Pegawai:</label>
                        <input type="number" name="nomer_pegawai" id="nomer_pegawai" class="form-control" value="<?= old('nomer_pegawai', esc($dosen['nomer_pegawai'])) ?>" maxlength="10" minlength="10">
                    </div>
                    <div class="form-group">
                        <label for="program_studi_id">Program Studi:</label>
                        <select name="program_studi_id" id="program_studi_id" class="form-control">
                            <option value="">--- Pilih Program Studi ---</option>
                            <?php foreach ($programStudi as $prodi): ?>
                                <option value="<?= $prodi['id'] ?>" <?= $prodi['id'] == old('program_studi_id', $dosen['program_studi_id']) ? 'selected' : '' ?>>
                                    <?= esc($prodi['nama']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="d-flex mt-4" style="gap: 1rem;">
                        <a href="<?= site_url('/dashboard/dosen') ?>" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
