<?php
    session_start();

    if (isset($_SESSION['user_nick']))
    {
        header("Location: index.php");
        exit();
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
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <div class="login-port row margin">
        <div class="col-2 col-lg-4"></div>
        <div class="col-8 col-lg-4 border golgelendirme">
            <div class="row col-12">
                <div class="col-2"></div>
                <div class="col-8 ms-3">
                    <img src="images/logo/fifex-yazi.png"/>
                </div>
                <div class="col-2"></div>
            </div>
            <form action="" method="post" class="p-3 m-2">
                <div class="mb-3">
                    <input type="text" name="user_nick" class="form-control" placeholder="Username" value="<?php if($_POST){echo $_POST['user_nick'];} ?>" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" value="<?php if($_POST){echo $_POST['password'];} ?>" required>
                </div>
                <div class="d-grid gap-2 mb-3">
                    <button type="submit" name="lgn" class="btn button">Log in</button>
                </div>
                <div class="mb-4 col-12">
                    <a href="forget-password.php">Forget Password?</a>
                </div>
                <div class="login-border-bottom col-12"></div>
            </form>
            <form action="" class="p-3 m-2">
                <div class="d-grid gap-2 mb-3">
                    <button type="submit" name="cna" class="btn button">Create New Account</button>
                </div>
            </form>
        </div>
        <div class="col-2 col-lg-4 "></div>
    </div>
</body>
</html>
<?php
    include "connection.php";

    if(isset($_POST['lgn']))
    {
        try
        {
            $user_nick = filter_input(INPUT_POST, 'user_nick', FILTER_SANITIZE_STRING);
            $user_password = $_POST['password'];

            if (!preg_match("/^[a-zA-Z0-9]+$/", $user_nick))
            {
                echo "<script>alert('Kullanıcı Adı Sadece Harf ve Rakam İçerebilir!');</script>";
                die();
            }

            if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()])[A-Za-z\d!@#$%^&*()]{8,}$/", $user_password))
            {
                echo "<script>alert('Şifre En Az 8 Karakter Uzunluğunda Olmalı ve En Az Bir Küçük Harf, Bir Büyük Harf, Bir Rakam ve Bir Özel Karakter İçermelidir!');</script>";
                die();
            }

            $sql = "SELECT user_nick, user_pass, user_biography, user_photo_path FROM user_table WHERE user_nick = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $user_nick);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0)
            {
                $stmt->bind_result($db_user_nick, $db_user_pass, $db_user_biography, $db_user_photo_path);
                $stmt->fetch();

                if (password_verify($user_password, $db_user_pass))
                {
                    session_start();

                    $_SESSION['user_nick'] = $db_user_nick;
                    $_SESSION['user_biography'] = $db_user_biography;
                    $_SESSION['user_photo_path'] = $db_user_photo_path;

                    header("Location: index.php");
                    exit();
                }
                else
                {
                    echo "<script>alert('Yanlış Şifre!');</script>";
                    die();
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
    if(isset($_GET['cna']))
    {
        header("Location: sign-up.php");
        exit();
    }
?>