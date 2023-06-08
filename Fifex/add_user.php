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
            <form method="post" class="p-3 m-2">
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
                <input type="text" name="add_user" class="form-control" placeholder="Username" style="text-align: center" required><br>
                <button type="submit" name="add_u_g" class="col-md-12 col-sm-12 col-12 btn btn-m button">Add</button>
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
    if (isset($_POST['add_u_g']))
    {
        try
        {
            $group = filter_input(INPUT_POST, 'group', FILTER_SANITIZE_STRING);
            $user_nick = $_POST['add_user'];

            if (!preg_match("/^[a-zA-Z0-9]+$/", $user_nick))
            {
                echo "<script>alert('Kullanıcı Adı Sadece Harf ve Rakam İçerebilir!');</script>";
                die();
            }

            if ($s_user_nick == $user_nick)
            {
                echo "<script>alert('Grubun Kurucusu Sizsiniz!');</script>";
                die();
            }

            $sql = "SELECT user_nick FROM user_table WHERE user_nick = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $user_nick);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0)
            {
                $sql = "SELECT blocked_id FROM blocked_table WHERE (blocking_user = ? AND blocked_user = ?) or (blocking_user = ? AND blocked_user = ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssss", $s_user_nick, $user_nick, $user_nick, $s_user_nick);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows == 0)
                {
                    $sql = "SELECT user_role FROM group_users_table WHERE group_name = ? AND user_name = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ss", $group, $user_nick);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows == 0)
                    {
                        $user_role = 0;
                        $sql = "INSERT INTO group_users_table (group_name, user_name, user_role) VALUES (?, ?, ?)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ssi", $group, $user_nick, $user_role);
                        
                        if ($stmt->execute())
                        {
                            echo "<script>alert('İstek Gönderildi!');</script>";
                            die();
                        }
                        else
                        {
                            echo "<script>alert('Kayıt İşlemi Başarısız Oldu!');</script>";
                            die();
                        }
                    }
                    else
                    {
                        $row = $result->fetch_assoc();
                        $db_friend_role = $row['user_role'];

                        if ($db_friend_role == 0)
                        {
                            echo "<script>alert('Daha Önce İstek Gönderilmiş!');</script>";
                            die();
                        }

                        if($db_friend_role == 1)
                        {
                            echo "<script>alert('$user_nick Grup Üyesi!');</script>";
                            die();
                        }
                    }
                }
            }
            else
            {
                echo "<script>alert('Kullanıcı Bulunamadı!');</script>";
                die();
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