<?php
include '../../../config/connection.php';

$keyword = $_GET['keyword'] ?? '';

$query = "
    SELECT * FROM pelayanan
    WHERE judul LIKE '%$keyword%'
       OR deskripsi LIKE '%$keyword%'
       OR status LIKE '%$keyword%'
    ORDER BY id_pelayanan DESC
";

$result = mysqli_query($connect, $query);
?>

<div class="row g-3">
<?php while ($row = mysqli_fetch_assoc($result)) : ?>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 rounded-4 h-100">

            <img src="../../uploads/pelayanan/<?= $row['gambar'] ?>"
                 class="card-img-top rounded-top"
                 style="height:180px; object-fit:cover;">

            <div class="card-body">
                <h5 class="fw-bold"><?= $row['judul'] ?></h5>
                <p class="text-muted small"><?= substr($row['deskripsi'], 0, 120) ?>...</p>

                <span class="badge bg-info"><?= $row['status'] ?></span>
            </div>

            <div class="card-footer bg-light d-flex justify-content-between">
                <a href="detail.php?id=<?= $row['id_pelayanan'] ?>" class="btn btn-sm btn-info">
                    <i class="bx bx-show"></i> Lihat
                </a>
                <a href="edit.php?id=<?= $row['id_pelayanan'] ?>" class="btn btn-sm btn-warning">
                    <i class="bx bx-edit"></i> Edit
                </a>
                <a href="hapus.php?id=<?= $row['id_pelayanan'] ?>" 
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Hapus pelayanan ini?')">
                    <i class="bx bx-trash"></i> Hapus
                </a>
            </div>

        </div>
    </div>
<?php endwhile; ?>
</div>
