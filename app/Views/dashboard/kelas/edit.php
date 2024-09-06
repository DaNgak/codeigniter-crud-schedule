<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>
    Kelas
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Content Row -->
<div class="row">
    <div class="col-lg-9">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Edit Kelas</h6>
            </div>
            <div class="card-body">
                <!-- Alert Bootstrap Session Flash Script -->
                <?= showAlertBs(); ?>

                <form action="<?= site_url('/dashboard/kelas/update/' . esc($kelas['id'])) ?>" method="post">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-group">
                        <label for="nama">Nama:</label>
                        <input type="text" name="nama" id="nama" class="form-control" value="<?= old('nama', esc($kelas['nama'])) ?>">
                    </div>
                    <div class="form-group">
                        <label for="kode">Kode Kelas: <span class="text-danger small">Contoh: TI-A, TI-B, XX-XX </span></label>
                        <input type="text" name="kode" id="kode" class="form-control" value="<?= old('kode', esc($kelas['kode'])) ?>">
                    </div>
                    <div class="form-group">
                        <label for="program_studi_id">Program Studi: </label>
                        <select name="program_studi_id" id="program_studi_id" class="form-control">
                            <option value="">--- Pilih Program Studi ---</option>
                            <?php foreach ($programStudi as $ps): ?>
                                <option value="<?= esc($ps['id']) ?>" <?= old('program_studi_id', $kelas['program_studi_id']) == $ps['id'] ? 'selected' : '' ?>>
                                    <?= esc($ps['nama']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="d-flex mt-4" style="gap: 1rem;">
                        <a href="<?= site_url('/dashboard/kelas') ?>" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
