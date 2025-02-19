<?php 
include 'template/db.php';
include 'template/header.php';
include 'template/nav_customer.php';
$message = '';
$dir = 'img/'; //Место для размещения прикрепленных файлов
session_start();
if(!empty($_POST)){
    $id_user = $_SESSION['id_user'];
    $id_category = $_POST['id_category'];
    $name_zayavki = $_POST['name_zayavki'];
    $description = $_POST['description'];
    $insertid = $mysqli->insert_id;
    $file = $dir.$insertid.'_'.basename($_FILES['userfile']['name']); //Имя файла
    $allowedext = ['jpg', 'jpeg','png'];
    $extention = pathinfo($_FILES['userfile']['name'], PATHINFO_EXTENSION);
    if(!in_array($extention, $allowedext)){
        $message = 'Неподдерживаемый тип файла';
    }
    else{
        if(move_uploaded_file($_FILES['userfile']['tmp_name'], $file)){
            $sql = "insert into zayavki (id_user, id_category, name_zayavki, description, date_zayavki) values ($id_user, $id_category, '$name_zayavki', '$description', now())";
            $result3 = $mysqli->query($sql);
            $sql = "update zayavki set img_issue = '$file' where id_zayavki = $insertid";
            $result4 = $mysqli->query($sql);
            header("Location: zayavka_customer.php");
        }
    }
}
?>
<div class="container">
    <div class="row">
    <div class="col-lg-12">
        <h2 style="text-align: center">Введите новую заявку</h2><br>
        <form class="form" enctype="multipart/form-data" action="" method="POST">
            <div class="mb-3">
                <label for="category" class="form-label">Выберите категорию</label>
            <select name="id_category" class="form-select">
                <?php 
                $sql = "select * from categories";
                $categories = $mysqli->query($sql);
                if(!empty($categories)){
                    foreach($categories as $select){
                        echo '<option value="'.$select['id_category'].'">'.$select['name_category'].'</option>';
                    }
                }
                ?>
            </select>
            </div>
            <div class="mb-3">
                <label for="name_zayavki">Введите название проблемы</label>
                <input type="text" class="form-control" name="name_zayavki" required>
            </div>
            <div class="mb-3">
                <label for="description">Описание</label>
                <textarea name="description" id="" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label for="img_issue" class="form-label">Загрузите изображение</label>
                <input type="hidden" name="MAX_FILE_SIZE" class="form-control" value="10000000" />
                <input class="form-control" type="file" id="userfile" name="userfile">
            </div>
            <div class="mb-3">
                <input type="submit" class="btn btn-primary" value="Сохранить">
            </div>
        </form><br>
        <p><?php echo $message; ?></p>
    </div>
    </div>
</div>