<?php
require_once "../config.php";
$nomor = 1;
$querySiswa = mysqli_query($koneksi, "SELECT * FROM tbl_siswa");
while ($data = mysqli_fetch_array($querySiswa)) { ?>
    <tr>
        <th scope="row"><?= $nomor++ ?></th>
        <td align="center"><img src="../asset/image/<?= $data['foto'] ?>" class="rounded-circle" width="60px" alt=""></td>
        <td><?= $data['nis'] ?></td>
        <td><?= $data['nama'] ?></td>
        <td><?= $data['kelas'] ?></td>
        <td><?= $data['jenjang'] ?></td>
        <td><?= $data['kamar'] ?></td>
        <td align="center">
            <a href="edit-santri.php?id=<?= $data['id'] ?>" class="btn btn-sm btn-warning"><i class="fa-solid fa-pen" title="Update santri"></i></a>
            <button type="button" class="btn btn-sm btn-danger" id="btnDelete" title="delete santri" data-id="<?= $data['id'] ?>" data-foto="<?= $data['foto'] ?>">
            <i class="fa-solid fa-trash"></i></button>
        </td>
    </tr>
<?php } ?>
