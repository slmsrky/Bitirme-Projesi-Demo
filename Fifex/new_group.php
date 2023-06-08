<?php
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
            <form method="post" class="p-3 m-2">
                <div class="mb-3" style="color: white;">
                    <label class="ortala">Oluşturan Kullanıcı: <?php echo $s_user_nick; ?></label>
                </div>
                <div class="mb-3">
                    <input type="text" name="group_name" class="form-control" placeholder="Group Name" value = "<?php if($_POST){echo $_POST['group_name'];} ?>" required>
                </div>
                <div class="mb-3">
                    <input type="text" name="group_biography" class="form-control" placeholder="Group Biography" value = "<?php if($_POST){echo $_POST['group_biography'];} ?>" required>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" name="new_g" class="btn button">Save</button>
                </div>
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
    include "connection.php";
    
    if(isset($_POST['new_g']))
    {
        try
        {
            $group_name = filter_input(INPUT_POST, 'group_name', FILTER_SANITIZE_STRING);
            $group_biography = filter_input(INPUT_POST, 'group_biography', FILTER_SANITIZE_STRING);

            $sql = "SELECT group_id FROM group_table WHERE created_user_nick = ? and group_name = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $s_user_nick, $group_name);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0)
            {
                echo "<script>alert('Bu Grup Adını Kullanamazsınız!');</script>";
                die();
            }
            else
            {
                $sql = "INSERT INTO group_table (created_user_nick, group_name, group_biography) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $s_user_nick, $group_name, $group_biography);
                $stmt->execute();

                $user_role = 1;
                $sql = "INSERT INTO group_users_table (group_name, user_name, user_role) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $group_name, $s_user_nick, $user_role);

                if ($stmt->execute())
                {
                    echo "<script>alert('Grup Oluşturuldu!');</script>";
                    die();
                }
                else
                {
                    echo "<script>alert('Kayıt İşlemi Başarısız Oldu!');</script>";
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