<?php
include('./common/session.php');
include('./common/connection.php');
if (empty($_SESSION['user_id'])) header('location:login.php');

$role = $_SESSION['role'];
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

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="./index.php">
                <font class="text-primary">KINGS</font>LAB
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="./index.php">Home</a>
                    </li>
                    <?php if ($role == 1) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="./product.php">Products</a>
                        </li>
                    <?php } ?>
                </ul>
                <form class="d-flex">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php echo $_SESSION['username'] ?>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="./logout.php">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="card" style="width:fit-content;height:fit-content">
                <div class="card-body">
                    <p class="text-primary d-flex justify-content-center align-items-center">Welcome Back <?php echo $_SESSION['fullname'] ?></p>
                </div>
            </div>
        </div>

        <div class="row mt-2">
            <?php
            $stmt = $pdo->prepare("SELECT * FROM product WHERE deleted_at is null");
            $stmt->execute();
            $i = 0;
            while ($product = $stmt->fetch(PDO::FETCH_OBJ)) {
            ?>

                <div class="col-3 ml-4">
                    <div class="card" style="width: 18rem;">
                        <img src="" class="card-img-top" alt="">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $product->name ?></h5>
                            <a class="btn btn-primary"><?php echo $product->price ?></a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

    </div>




    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
<?php
