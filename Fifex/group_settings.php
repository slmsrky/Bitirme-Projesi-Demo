<?php
    include "connection.php";

    session_start();

    if (isset($_SESSION['user_nick']))
    {
        $s_user_nick = $_SESSION['user_nick'];
    }
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fifex</title>
    <link rel="icon" type="image/x-icon" href="images/icon/favicon.ico">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-port row">
        <div class="col-2 col-lg-4"></div>
        <div class="col-8 col-lg-4 border golgelendirme">
            <form method="post" enctype="multipart/form-data" class="p-3 m-2">
                <label class="ortala" style="color: white;">Grup Seçin</label><br>
                <select name="group" class="col-md-12 col-sm-12 col-12 form-control" style="text-align: center" required>
                    <option value=''></option>
                    <?php
                        $sql = "SELECT group_name FROM group_table WHERE created_user_nick = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("s", $s_user_nick);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0)
                        {
                            while ($row = $result->fetch_assoc())
                            {
                                $group_name = $row["group_name"];
                                echo "<option value='$group_name'>$group_name</option>";
                            }
                        }
                    ?>
                </select><br>
                <input type="text" name="group_name" class="form-control" placeholder="Group Name" value = "<?php if($_POST){echo $_POST['group_name'];} ?>" style="text-align: center" required><br>
                <input type="text" name="group_biography" class="form-control" placeholder="Group Biography" value = "<?php if($_POST){echo $_POST['group_biography'];} ?>" style="text-align: center" required><br>
                <div class="ortala"><input type="file" id="file" name="file" accept=".jpg, .png"></div><br>
                <button type="submit" name="group_s" class="col-md-12 col-sm-12 col-12 btn button">Save</button>
            </form>
            <form method="post" class="p-3 m-2">
                <div class="d-grid gap-2">
                    <button type="submit" name="home" class="btn button" style="background-color: #c44747;">Home</button>
                </div>
            </form>
        </div>
        <div class="col-2 col-lg-4"></div>
    </div>
</body>
</html>
<?php
    if(isset($_POST['group_s']))
    {
        try
        {
            $group_c = $_POST['group'];
            $f_group_name = filter_input(INPUT_POST, 'group_name', FILTER_SANITIZE_STRING);
            $f_group_biography = filter_input(INPUT_POST, 'group_biography', FILTER_SANITIZE_STRING);

            $group_folder = "Groups/" . $s_user_nick . "-" . $group_name;
            
            if (!is_dir($group_folder))
            {
                mkdir($group_folder, 0777, true);
            }

            $file = $_FILES['file'];
            $file_name = $file['name'];

            if ($file_name != null)
            {
                $file_tmp = $file['tmp_name'];
                $file_path = $group_folder . "/" . $s_user_nick . "-" . $group_name . "." . pathinfo($file_name, PATHINFO_EXTENSION);
                move_uploaded_file($file_tmp, $file_path);

                $sql = "UPDATE group_table SET group_name = ?, group_biography = ?, group_photo_path = ? WHERE created_user_nick = ? AND group_name = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssss", $f_group_name, $f_group_biography, $file_path, $s_user_nick, $group_c);
            }
            else
            {
                $sql = "UPDATE group_table SET group_name = ?, group_biography = ? WHERE created_user_nick = ? AND group_name = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssss", $f_group_name, $f_group_biography, $s_user_nick, $group_c);
            }

            if ($stmt->execute())
            {
                $sql = "UPDATE group_users_table SET group_name = ? WHERE group_name = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $f_group_name, $group_c);

                if ($stmt->execute())
                {
                    echo "<script>alert('Grup Bilgileri Güncellendi!');</script>";
                    die();
                }
                else
                {
                    echo "<script>alert('Güncelleme İşlemi Başarısız Oldu!');</script>";
                    die();
                }
            }
        }
        catch (Exception $e)
        {
            error_log($e->getMessage(), 3, "error_log.txt");
            echo "<script>alert('Veritabanı Hatası!');</script>";
            die();
        }
    }
    if (isset($_POST['home']))
    {
        header("Location: index.php");
        exit();
    }
?>