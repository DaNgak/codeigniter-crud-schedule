<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mata Kuliah</title>
</head>

<body>
    <h1>Daftar Mata Kuliah</h1>
    <a href="<?= site_url('/dashboard/mata-kuliah/create') ?>">Tambah Mata Kuliah</a>
    <table border="1">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Kode</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($mata_kuliah as $item): ?>
            <tr>
                <td><?= esc($item['nama']) ?></td>
                <td><?= esc($item['kode']) ?></td>
                <td><?= esc($item['deskripsi']) ?></td>
                <td>
                    <a href="<?= site_url('/dashboard/mata-kuliah/edit/' . $item['id']) ?>">Edit</a>
                    <form action="<?= site_url('/dashboard/mata-kuliah/delete/' . $item['id']) ?>" method="post"
                        style="display:inline;">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>