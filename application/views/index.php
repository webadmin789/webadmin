<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Dashboard - NiceAdmin Bootstrap Template</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">

    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="<?php echo base_url(); ?>assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/vendor/simple-datatables/style.css" rel="stylesheet">
    <!-- Template Main CSS File -->
    <link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js" rel="preload"></script>

    <!-- =======================================================
  * Template Name: NiceAdmin - v2.4.1
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>
<?php if ($this->session->flashdata('success')): ?>
<script type="text/javascript">
$(document).ready(function() {
    swal("Welcome!", "You have logged in successfully!", "success");

});
</script>
<?php endif;?>

<?php if ($this->session->flashdata('result') == 0) {?>
<script type="text/javascript">
$(document).ready(function() {
    swal("OOPS", "Please Upload CSV File only", "error");

});
</script>
<?php }?>

<?php if ($this->session->flashdata('result') == 1) {?>
<script type="text/javascript">
$(document).ready(function() {
    swal("OOPS", "File Sample Mismatched", "error");

});
</script>
<?php }?>

<?php if ($this->session->flashdata('result') == 2) {?>
<script type="text/javascript">
$(document).ready(function() {
    swal("Success", "Data Uploaded Successfully", "success");

});
</script>

<?php }if ($this->session->flashdata('result') == 3) {?>
<script type="text/javascript">
$(document).ready(function() {
    swal("OOPS", "Something Went Wrong", "error");

});
</script>
<?php }?>

<body>
    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">
            <a href="<?php echo base_url('/welcome/index'); ?>" class="logo d-flex align-items-center">
                <img src="assets/img/logo.png" alt="">
                <span class="d-none d-lg-block">WebAdmin</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div><!-- End Logo -->

        <div class="search-bar">
            <form class="search-form d-flex align-items-center" method="POST" action="#">
                <input type="text" name="query" placeholder="Search" title="Enter search keyword">
                <button type="submit" title="Search"><i class="bi bi-search"></i></button>
            </form>
        </div><!-- End Search Bar -->

        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">

                <li class="nav-item dropdown pe-3">

                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Profile"
                            class="rounded-circle">
                        <span
                            class="d-none d-md-block dropdown-toggle ps-2"><?php echo $this->session->userdata('username') ?></span>
                    </a><!-- End Profile Iamge Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">

                            <span>Admin</span>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                                <i class="bi bi-person"></i>
                                <span>My Profile</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center"
                                href="<?php echo base_url('/welcome/logout') ?>">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Sign Out</span>
                            </a>
                        </li>

                    </ul><!-- End Profile Dropdown Items -->
                </li><!-- End Profile Nav -->

            </ul>
        </nav><!-- End Icons Navigation -->

    </header><!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">

            <li class="nav-item">
                <a class="nav-link " href="<?php echo base_url('/welcome/index'); ?>">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li><!-- End Dashboard Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#upload-form" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-menu-button-wide"></i><span>Upload</span>
                </a>
            </li><!-- End Components Nav -->



    </aside><!-- End Sidebar-->

    <main id="main" class="main">
        <section id="upload-form">
            <div class="container py-5">
                <!-- For demo purpose -->
                <div class="row mb-4">
                    <div class="col-lg-8 mx-auto text-center">
                        <h1 class="display-10">Upload File</h1>
                    </div>
                </div> <!-- End -->
                <div class="row">
                    <div class="col-lg-6 mx-auto">
                        <div class="card border rounded"
                            style="box-shadow: rgba(0, 0, 0, 0.25) 0px 0.0625em 0.0625em, rgba(0, 0, 0, 0.25) 0px 0.125em 0.5em, rgba(255, 255, 255, 0.1) 0px 0px 0px 1px inset;">
                            <div class="card-header">
                                <form action="<?php echo base_url('/excelimport/spreadsheet_import'); ?>" method="post"
                                    enctype="multipart/form-data" id="uploadform">
                                    <div class="mb-3 text-center">
                                        <label for="formFileMultiple" class="form-label">Select Sample</label>
                                        <select class="form-select" aria-label="Select theme" name="sample" required>
                                            <option selected disabled>Choose one</option>
                                            <?php // if($party){
foreach ($party as $bank_name) {?>
                                            <option
                                                value="<?php echo $bank_name['party_id'] . '|' . $bank_name['no_of_columns'] . '|' . $bank_name['party_name'] ?>">
                                                <?php echo $bank_name['party_name'] ?></option>
                                            <?php }?>
                                        </select>
                                    </div><br>

                                    <div class="mb-3 text-center">
                                        <label for="formFileMultiple" class="form-label">Upload your
                                            file</label>
                                        <input class="form-control" type="file" id="formFileMultiple" name="file"
                                            accept=".xlsx, .xls, .csv, .txt" required>
                                    </div><br>
                                    <div class="mb-3 text-center">
                                        <input type="submit" name="Submit" class="btn btn-primary btn-lg">
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>

        </section>
    </main><!-- End #main -->

    <!-- <script>
    $(document).ready(function() {

        $('#uploadform').submit(function(e) {

            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "<?php echo base_url('/excelimport/spreadsheet_import'); ?>",
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                enctype: 'multipart/form-data',
                processData: false,
                dataType: 'json',
                success: function(data) {
                    console.log(JSON.stringify(data.result));

                    if (data.result == 1) {
                        swal("OOPS!", "File sample mismatched", "error");
                    }
                    if (data.result == 0) {
                        swal('please upload csv file only');
                    }
                    console.log(data.result);
                    if (data.result == 2) {
                        swal('Data Uploaded Successfully');
                    }

                },
                error: function() {
                    swal('oops!', "Somewething went wrong", 'error');
                }
            });

        });
    });
    </script> -->

    <!-- ======= Footer ======= -->
    <!-- <footer id="footer" class="footer fixed-bottom">
        <div class="copyright">
            &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
        </div>
        <div class="credits">
            <!-- All the links in the footer should remain intact. -->
    <!-- You can delete the links only if you purchased the pro version. -->
    <!-- Licensing information: https://bootstrapmade.com/license/ -->
    <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
    <!-- Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a> -->
    <!-- </div> -->
    <!-- </footer> --> -->
    <!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="<?php echo base_url(); ?>assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/chart.js/chart.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/echarts/echarts.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/quill/quill.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/php-email-form/validate.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>

</body>

</html>