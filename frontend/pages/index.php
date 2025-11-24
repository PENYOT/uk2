<?php
include '../partials/header.php';
include '../partials/navbar.php';

include 'home.php';
include 'promo.php';
?>

<!-- Fact Start -->
<div class="container-xxl py-5">
  <div class="container py-5">
    <div class="row g-5">
      <?php
      $qPelanggan = mysqli_query($connect, "SELECT COUNT(*) AS total FROM pelanggan");
      $totalPelanggan = mysqli_fetch_assoc($qPelanggan)['total'];

      // Hitung total penggunaan
      $qPenggunaan = mysqli_query($connect, "SELECT COUNT(*) AS total FROM penggunaan");
      $totalPenggunaan = mysqli_fetch_assoc($qPenggunaan)['total'];

      $qPromo = mysqli_query($connect, "SELECT COUNT(*) AS total FROM promo");
      $totalPromo = mysqli_fetch_assoc($qPromo)['total'];
      ?>
      <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
        <h6 class="text-warning text-uppercase mb-3">Informasi Penting</h6>
        <h1 class="mb-4">Solusi Mudah Untuk Mengelola Pembayaran Listrik Anda</h1>

        <p class="mb-5">
          Platform kami membantu pelanggan mengelola penggunaan listrik, melihat tagihan bulanan,
          serta melakukan pembayaran dengan cepat dan aman. Semua data tercatat otomatis dalam sistem
          sehingga Anda dapat memantau riwayat pembayaran kapan saja.
        </p>

        <div class="d-flex align-items-center">
          <i class="fa fa-bolt fa-2x flex-shrink-0 bg-warning p-3 text-white rounded"></i>
          <div class="ps-4">
            <h6>Layanan Pengaduan & Bantuan</h6>
            <h3 class="text-warning m-0">0857-2750-6003</h3>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="row g-4 align-items-center">

          <!-- Total Pelanggan -->
          <div class="col-sm-6">
            <div class="bg-warning p-4 mb-4 rounded wow fadeIn" data-wow-delay="0.3s">
              <i class="fa fa-users fa-2x text-white mb-3"></i>
              <h2 class="text-white mb-2" data-toggle="counter-up"><?= $totalPelanggan ?></h2>
              <p class="text-white mb-0">Pelanggan Terdaftar</p>
            </div>

            <!-- Total Penggunaan -->
            <div class="bg-primary p-4 rounded wow fadeIn" data-wow-delay="0.5s">
              <i class="fa fa-bolt fa-2x text-white mb-3"></i>
              <h2 class="text-white mb-2" data-toggle="counter-up"><?= $totalPenggunaan ?></h2>
              <p class="text-white mb-0">Data Penggunaan</p>
            </div>
          </div>
          <div class="col-sm-6">

            <!-- Total Tagihan -->
            <div class="bg-danger p-4 rounded mb-4 wow fadeIn" data-wow-delay="0.7s">
              <i class="fa fa-file-invoice-dollar fa-2x text-white mb-3"></i>
              <h2 class="text-white mb-2" data-toggle="counter-up"><?= $totalPromo ?></h2>
              <p class="text-white mb-0">Total Promo</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Fact End -->

<?php
include 'layanan.php';
include 'tarif.php';
?>

<!-- Quote Start -->
<!-- <div class="container-xxl py-5">
  <div class="container py-5">
    <div class="row g-5 align-items-center">
      <div class="col-lg-5 wow fadeInUp" data-wow-delay="0.1s">
        <h6 class="text-secondary text-uppercase mb-3">Get A Quote</h6>
        <h1 class="mb-5">Request A Free Qoute!</h1>
        <p class="mb-5">Tempor erat elitr rebum at clita. Diam dolor diam ipsum sit. Aliqu diam amet diam et eos. Clita erat ipsum et lorem et sit, sed stet lorem sit clita duo justo erat amet</p>
        <div class="d-flex align-items-center">
          <i class="fa fa-headphones fa-2x flex-shrink-0 bg-primary p-3 text-white"></i>
          <div class="ps-4">
            <h6>Call for any query!</h6>
            <h3 class="text-primary m-0">+012 345 6789</h3>
          </div>
        </div>
      </div>
      <div class="col-lg-7">
        <div class="bg-light text-center p-5 wow fadeIn" data-wow-delay="0.5s">
          <form>
            <div class="row g-3">
              <div class="col-12 col-sm-6">
                <input type="text" class="form-control border-0" placeholder="Your Name" style="height: 55px;">
              </div>
              <div class="col-12 col-sm-6">
                <input type="email" class="form-control border-0" placeholder="Your Email" style="height: 55px;">
              </div>
              <div class="col-12 col-sm-6">
                <input type="text" class="form-control border-0" placeholder="Your Mobile" style="height: 55px;">
              </div>
              <div class="col-12 col-sm-6">
                <select class="form-select border-0" style="height: 55px;">
                  <option selected>Select A Freight</option>
                  <option value="1">Freight 1</option>
                  <option value="2">Freight 2</option>
                  <option value="3">Freight 3</option>
                </select>
              </div>
              <div class="col-12">
                <textarea class="form-control border-0" placeholder="Special Note"></textarea>
              </div>
              <div class="col-12">
                <button class="btn btn-primary w-100 py-3" type="submit">Submit</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div> -->
<!-- Quote End -->

<!-- Testimonial Start -->
<!-- <div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
  <div class="container py-5">
    <div class="text-center">
      <h6 class="text-secondary text-uppercase">Testimonial</h6>
      <h1 class="mb-0">Our Clients Say!</h1>
    </div>
    <div class="owl-carousel testimonial-carousel wow fadeInUp" data-wow-delay="0.1s">
      <div class="testimonial-item p-4 my-5">
        <i class="fa fa-quote-right fa-3x text-light position-absolute top-0 end-0 mt-n3 me-4"></i>
        <div class="d-flex align-items-end mb-4">
          <img class="img-fluid flex-shrink-0" src="img/testimonial-1.jpg" style="width: 80px; height: 80px;">
          <div class="ms-4">
            <h5 class="mb-1">Client Name</h5>
            <p class="m-0">Profession</p>
          </div>
        </div>
        <p class="mb-0">Tempor erat elitr rebum at clita. Diam dolor diam ipsum sit diam amet diam et eos. Clita erat ipsum et lorem et sit.</p>
      </div>
      <div class="testimonial-item p-4 my-5">
        <i class="fa fa-quote-right fa-3x text-light position-absolute top-0 end-0 mt-n3 me-4"></i>
        <div class="d-flex align-items-end mb-4">
          <img class="img-fluid flex-shrink-0" src="img/testimonial-2.jpg" style="width: 80px; height: 80px;">
          <div class="ms-4">
            <h5 class="mb-1">Client Name</h5>
            <p class="m-0">Profession</p>
          </div>
        </div>
        <p class="mb-0">Tempor erat elitr rebum at clita. Diam dolor diam ipsum sit diam amet diam et eos. Clita erat ipsum et lorem et sit.</p>
      </div>
      <div class="testimonial-item p-4 my-5">
        <i class="fa fa-quote-right fa-3x text-light position-absolute top-0 end-0 mt-n3 me-4"></i>
        <div class="d-flex align-items-end mb-4">
          <img class="img-fluid flex-shrink-0" src="img/testimonial-3.jpg" style="width: 80px; height: 80px;">
          <div class="ms-4">
            <h5 class="mb-1">Client Name</h5>
            <p class="m-0">Profession</p>
          </div>
        </div>
        <p class="mb-0">Tempor erat elitr rebum at clita. Diam dolor diam ipsum sit diam amet diam et eos. Clita erat ipsum et lorem et sit.</p>
      </div>
      <div class="testimonial-item p-4 my-5">
        <i class="fa fa-quote-right fa-3x text-light position-absolute top-0 end-0 mt-n3 me-4"></i>
        <div class="d-flex align-items-end mb-4">
          <img class="img-fluid flex-shrink-0" src="img/testimonial-4.jpg" style="width: 80px; height: 80px;">
          <div class="ms-4">
            <h5 class="mb-1">Client Name</h5>
            <p class="m-0">Profession</p>
          </div>
        </div>
        <p class="mb-0">Tempor erat elitr rebum at clita. Diam dolor diam ipsum sit diam amet diam et eos. Clita erat ipsum et lorem et sit.</p>
      </div>
    </div>
  </div>
</div> -->
<!-- Testimonial End -->
<?php
include '../partials/footer.php';
include '../partials/script.php';
?>