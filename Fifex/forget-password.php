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
    <div class="login-port row">
        <div class="col-2 col-lg-4"></div>
            <div class="col-8 col-lg-4 border golgelendirme">
                <div class="row col-12">
                    <div class="col-2"></div>
                    <div class="col-8 logo ms-3">
                        <img src="images/logo/fifex-yazi.png"/>
                    </div>
                    <div class="col-2"></div>
                </div>
                <form action="" method="post" class="p-3 m-2">
                    <div class="mb-3">
                        <input type="text" name="user_nick" class="form-control" placeholder="Username" value="<?php if($_POST){echo $_POST['user_nick'];} ?>" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="security_code_1" class="form-control" placeholder="Security Code 1" value="<?php if($_POST){echo $_POST['security_code_1'];} ?>" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="security_code_2" class="form-control" placeholder="Security Code 2" value="<?php if($_POST){echo $_POST['security_code_2'];} ?>" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="security_code_3" class="form-control" placeholder="Security Code 3" value="<?php if($_POST){echo $_POST['security_code_3'];} ?>" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" name="password_1" class="form-control mb-3" placeholder="Password" value = "<?php if($_POST){echo $_POST['password_1'];} ?>" required>
                        <input type="password" name="password_2" class="form-control" placeholder="Password Control" value = "<?php if($_POST){echo $_POST['password_2'];} ?>" required>
                    </div>
                    <div class=" d-grid gap-2">
                        <button type="submit" name="save" class="btn button">Save</button>
                    </div>
                </form>
            </div>
        <div class="col-2 col-lg-4"></div>
    </div>
</body>
</html>
<?php
    include "connection.php";
    
    if(isset($_POST['save']))
    {
        try
        {
            $user_nick = filter_input(INPUT_POST, 'user_nick', FILTER_SANITIZE_STRING);
            $security_code_1 = $_POST['security_code_1'];
            $security_code_2 = $_POST['security_code_2'];
            $security_code_3 = $_POST['security_code_3'];
            $user_password_1 = $_POST['password_1'];
            $user_password_2 = $_POST['password_2'];

            if (!preg_match("/^[a-zA-Z0-9]+$/", $user_nick))
            {
                echo "<script>alert('Kullanıcı Adı Sadece Harf ve Rakam İçerebilir!');</script>";
                die();
            }

            if (!preg_match("/^[0-9]{6,6}+$/", $security_code_1) || !preg_match("/^[0-9]{6,6}+$/", $security_code_2) || !preg_match("/^[0-9]{6,6}+$/", $security_code_3))
            {
                echo "<script>alert('Güvenlik Kodu 6 Hanelidir ve Rakamlardan Oluşur!');</script>";
                die();
            }

            if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()])[A-Za-z\d!@#$%^&*()]{8,}$/", $user_password_1))
            {
                echo "<script>alert('Şifre En Az 8 Karakter Uzunluğunda Olmalı ve En Az Bir Küçük Harf, Bir Büyük Harf, Bir Rakam ve Bir Özel Karakter İçermelidir!');</script>";
                die();
            }

            if ($user_password_1 != $user_password_2)
            {
                echo "<script>alert('Girilen Şifreler Aynı Olmalıdır!');</script>";
                die();
            }

            $sql = "SELECT code_1, code_2, code_3 FROM code_table WHERE user_nick = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $user_nick);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0)
            {
                $stmt->bind_result($db_code_1, $db_code_2, $db_code_3);
                $stmt->fetch();

                if ($db_code_1 == $security_code_1 && $db_code_2 == $security_code_2 && $db_code_3 == $security_code_3)
                {
                    $sql = "SELECT user_pass FROM user_table WHERE user_nick = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $user_nick);
                    $stmt->execute();
                    $stmt->store_result();
                    $stmt->bind_result($db_user_pass);
                    $stmt->fetch();

                    $user_password = password_hash($user_password_1, PASSWORD_BCRYPT, ['cost' => 12]);

                    if (!password_verify($user_password_1, $db_user_pass))
                    {
                        $sql = "UPDATE user_table SET user_pass = ? WHERE user_nick = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ss", $user_password, $user_nick);

                        if ($stmt->execute())
                        {
                            header("Location: sign-in.php");
                            exit();
                        }
                        else
                        {
                            echo "<script>alert('Güncelleme İşlemi Başarısız Oldu!');</script>";
                            die();
                        }
                    }
                    else
                    {
                        echo "<script>alert('Girilen Şifre Mevcut Şifre ile Aynıdır!');</script>";
                        die();
                    }
                }
                else
                {
                    echo "<script>alert('Kodları Kontrol Edin ve Sırası ile Girin!');</script>";
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
?>