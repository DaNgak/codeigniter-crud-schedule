<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mata Kuliah</title>
</head>

<body>
    <h1>Tambah Mata Kuliah</h1>
    <?php if(session()->getFlashdata('message')):?>
    <div><?= session()->getFlashdata('message') ?></div>
    <?php endif;?>
    <form action="<?= site_url('/dashboard/mata-kuliah/store') ?>" method="post">
        <label for="nama">Nama:</label>
        <input type="text" name="nama" id="nama" value="<?= old('nama') ?>">
        <br>
        <label for="kode">Kode:</label>
        <input type="text" name="kode" id="kode" value="<?= old('kode') ?>">
        <br>
        <label for="deskripsi">Deskripsi:</label>
        <textarea name="deskripsi" id="deskripsi"><?= old('deskripsi') ?></textarea>
        <br>
        <button type="submit">Simpan</button>
    </form>
    <a href="<?= site_url('/dashboard/mata-kuliah') ?>">Kembali</a>
</body>

</html>