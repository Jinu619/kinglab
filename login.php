<?php
include('./common/connection.php');
include('./common/session.php');

if (isset($_SESSION)) if (!empty($_SESSION['user_id'])) header('location:index.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KINGSLAB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card" style="max-width: 800px;">
            <div class="card-body">
                <div class="row d-flex justify-content-end align-items-end">
                    <a href="register.php" class="btn btn-primary">REGISTER</a>
                </div>

                <div class="row ">
                    <h5 class="d-flex justify-content-center align-items-center mt-5">LOGIN</h5>
                </div>

                <div class="row">
                    <div class="col-12">

                        <?php
                        if (isset($_GET['msg'])) {
                        ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo $_GET['msg'] ?>
                            </div>
                        <?php
                        }
                        ?>

                        <?php
                        if (isset($_GET['errmsg'])) {
                        ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $_GET['errmsg'] ?>
                            </div>
                        <?php
                        }
                        ?>

                        <div class="mb-3">
                            <form action="./loginvalidate.php" method="POST" id="loginvalidate">
                                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                <div class="row">
                                    <label for="uname" class="form-label">User Name <font color='red'>*</font> </label>
                                    <input type="text" class="form-control" id="uname" name="uname">
                                </div>
                                <div class="row">
                                    <label for="password" class="form-label">Password <font color='red'>*</font> </label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                            </form>

                            <button type="button" class="btn btn-success mt-2" id="login">Login</button>

                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#login').on('click', function() {
                var isValid = true;
                $('#loginvalidate input').each(function() {
                    if ($.trim($(this).val()) == '') {
                        isValid = false;
                        $(this).addClass('is-invalid');
                    } else $(this).removeClass('is-invalid');
                });

                if (isValid) {
                    $("#loginvalidate").submit();
                } else alert('Please fill all required fields.');
            });
        });
    </script>
</body>

</html>
<?php
