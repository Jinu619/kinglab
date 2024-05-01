<?php
include('./common/connection.php');
include('./common/session.php');

$role = $_SESSION['role'];
if ($role == '2') header('location:index.php');
if (empty($_SESSION['user_id'])) header('location:login.php');

$mode = isset($_GET['mode']) ? ($_GET['mode'] == 'edit' ? 'edit' : 'add') : 'add';

$pid = isset($_GET['pid']) ? $_GET['pid'] : '';

if ($pid) {
    $stmt1 = $pdo->prepare("SELECT * FROM product WHERE id=:pid AND deleted_at is null");
    $stmt1->execute(['pid' => $pid]);
    $prduct = $stmt1->fetch(PDO::FETCH_OBJ);
    $name = $prduct->name;
    $price = $prduct->price;
} else {
    $name = '';
    $price = '';
}
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
                    <li class="nav-item">
                        <a class="nav-link" href="./product.php">Products</a>
                    </li>
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

    <div class="container d-flex justify-content-center  vh-100">
        <div class="card" style="width: fit-content; height:fit-content ">
            <div class="card-body">
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

                        <h3>ADD PRODUCT</h3>
                        <div class="mb-3">
                            <form action="./productvalidate.php" method="POST" id="product">
                                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                <input type="hidden" name="mode" value="<?php echo $mode ?>">
                                <input type="hidden" id="pid" name="pid" value="<?php echo $pid ?>">
                                <div class="row">
                                    <label for="name" class="form-label">Product Name <font color='red'>*</font> </label>
                                    <input type="text" class="form-control" id="name" value="<?php echo $name ?>" name="name">
                                </div>
                                <div class="row">
                                    <label for="price" class="form-label">Price <font color='red'>*</font> </label>
                                    <input type="number" class="form-control" value="<?php echo $price ?>" id="price" name="price">
                                </div>
                            </form>

                            <button type="button" class="btn btn-success mt-2" id="add"><?php echo $mode == 'add' ? 'Add' : 'Update' ?></button>

                        </div>
                    </div>
                </div>
                <?php
                if ($mode == 'add') {
                ?>
                    <div class="row">
                        <div class="col-12">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Sl/No</th>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $stmt = $pdo->prepare("SELECT * FROM product WHERE deleted_at is null");
                                    $stmt->execute();
                                    $i = 0;
                                    while ($product = $stmt->fetch(PDO::FETCH_OBJ)) {
                                    ?>
                                        <tr>
                                            <td><?php echo ++$i; ?></td>
                                            <td><?php echo $product->name; ?></td>
                                            <td><?php echo $product->price; ?></td>
                                            <td>
                                                <a class="btn btn-warning" id="edit" onclick="editProduct(<?php echo $product->id; ?>)">Edit</a>
                                                <a class="btn btn-danger" id="delete" onclick="deleteProduct(<?php echo $product->id; ?>)">Deleted</a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            function editProduct(id) {
                window.location.href = `product.php?mode=edit&pid=${id}`;
            }

            function deleteProduct(id) {
                if (confirm("Are you sure you want to delete this item?")) window.location.href = `productdeleted.php?pid=${id}`;
            }
            $(document).ready(function() {
                $('#add').on('click', function() {
                    var isValid = true;
                    $('#product input').each(function() {
                        if ($.trim($(this).val()) == '' && $(this).attr('id') != 'pid') {
                            isValid = false;
                            $(this).addClass('is-invalid');
                        } else $(this).removeClass('is-invalid');
                    });

                    if (isValid) {
                        $("#product").submit();
                    } else alert('Please fill all required fields.');
                });
            });
        </script>
</body>

</html>
<?php
