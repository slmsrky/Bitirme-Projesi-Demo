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
                <div class="col-8 ms-3">
                    <img src="images/logo/fifex-yazi.png"/>
                </div>
                <div class="col-2"></div>
            </div>
            <form action="" method="post" class="p-3 m-2">
                <div class="mb-3">
                    <input type="text" name="name" class="form-control" placeholder="First Name" value = "<?php if($_POST){echo $_POST['name'];} ?>" required>
                </div>
                <div class="mb-3">
                    <input type="text" name="sur_name" class="form-control" placeholder="Surname" value = "<?php if($_POST){echo $_POST['sur_name'];} ?>" required>
                </div>
                <div class="mb-3">
                    <input type="text" name="user_nick" class="form-control" placeholder="Username" value = "<?php if($_POST){echo $_POST['user_nick'];} ?>" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password_1" class="form-control mb-3" placeholder="Password" value = "<?php if($_POST){echo $_POST['password_1'];} ?>" required>
                    <input type="password" name="password_2" class="form-control" placeholder="Password Control" value = "<?php if($_POST){echo $_POST['password_2'];} ?>" required>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" name="submit" class="btn button">Sign Up</button>
                </div>
            </form>
        </div>
        <div class="col-2 col-lg-4"></div>
    </div>
</body>
</html>
<?php
    include "connection.php";

    if(isset($_POST['submit'])) 
    {
        try
        {
            function random_code()
            {
                $chars = "123456789";
                $code = "";

                for ($i = 0; $i < 6; $i++)
                {
                    $code .= $chars[rand(0, strlen($chars) - 1)];
                }
                
                return $code;
            }

            $user_name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $user_surname = filter_input(INPUT_POST, 'sur_name', FILTER_SANITIZE_STRING);
            $user_nick = filter_input(INPUT_POST, 'user_nick', FILTER_SANITIZE_STRING);
            $user_password_1 = $_POST['password_1'];
            $user_password_2 = $_POST['password_2'];

            if (!preg_match("/^[a-zA-Z0-9]+$/", $user_nick))
            {
                echo "<script>alert('Kullanıcı Adı Sadece Harf ve Rakam İçerebilir!');</script>";
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

            $sql = "SELECT user_nick FROM user_table WHERE user_nick = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $user_nick);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0)
            {
                echo "<script>alert('Bu Kullanıcı Adını Kullanamazsınız!');</script>";
                die();
            }
            else
            {
                $code_1 = random_code();
                $code_2 = random_code();
                $code_3 = random_code();

                $sql = "INSERT INTO code_table (user_nick, code_1, code_2, code_3) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssss", $user_nick, $code_1, $code_2, $code_3);
                $stmt->execute();

                $code_id = $stmt->insert_id;

                $password = password_hash($user_password_1, PASSWORD_BCRYPT, ['cost' => 12]);

                $sql = "INSERT INTO user_table (user_name, user_surname, user_nick, user_pass, code_id) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssi", $user_name, $user_surname, $user_nick, $password, $code_id);

                if ($stmt->execute())
                {
                    echo "<script>";
                        echo "alert('Lütfen Güvenlik Kodlarını Saklayınız!');";
                        echo "alert('" . htmlspecialchars($code_1) . " - " . htmlspecialchars($code_2) . " - " . htmlspecialchars($code_3) . "');";
                        echo "setTimeout(function() {";
                        echo "  window.location.href = 'sign-in.php';";
                        echo "}, 1000);";
                    echo "</script>";
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
?>