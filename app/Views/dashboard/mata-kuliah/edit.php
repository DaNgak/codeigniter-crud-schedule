<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mata Kuliah</title>
</head>

<body>
    <h1>Edit Mata Kuliah</h1>
    <?php if(session()->getFlashdata('message')):?>
    <div><?= session()->getFlashdata('message') ?></div>
    <?php endif;?>
    <form action="<?= site_url('/dashboard/mata-kuliah/update/' . $mata_kuliah['id']) ?>" method="post">
        <input type="hidden" name="_method" value="PUT">
        <label for="nama">Nama:</label>
        <input type="text" name="nama" id="nama" value="<?= esc($mata_kuliah['nama']) ?>">
        <br>
        <label for="kode">Kode:</label>
        <input type="text" name="kode" id="kode" value="<?= esc($mata_kuliah['kode']) ?>">
        <br>
        <label for="deskripsi">Deskripsi:</label>
        <textarea name="deskripsi" id="deskripsi"><?= esc($mata_kuliah['deskripsi']) ?></textarea>
        <br>
        <button type="submit">Update</button>
    </form>
    <a href="<?= site_url('/dashboard/mata-kuliah') ?>">Kembali</a>
</body>

</html>