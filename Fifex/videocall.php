<?php
    include 'connection.php';

    session_start();

    if (isset($_SESSION['user_nick']))
    {
        $s_user_nick = $_SESSION['user_nick'];
    }
    else
    {
        header("Location: sign-in.php");
        exit();
    }

    $friends = array();
    $friend_role = 1;

    $sql = "SELECT * FROM friendship_table WHERE (user_1 = ? OR user_2 = ?) AND friend_role = ? ORDER BY user_1, user_2 ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $s_user_nick, $s_user_nick, $friend_role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0)
    {
        while ($row = $result->fetch_assoc())
        {
            if ($row["user_1"] == $s_user_nick)
            {
                $friends[] = $row["user_2"];
            }
            else
            {
                $friends[] = $row["user_1"];
            }
        }
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
                <label class="ortala" style="color: white;">Arkadaş Seçin</label><br>
                <select name="friends" class="col-md-12 col-sm-12 col-12 form-control" style="text-align: center" required>
                    <option value=''></option>
                    <?php
                        foreach ($friends as $item)
                        {
                            echo "<option value='$item'>$item</option>";
                        }
                    ?>
                </select><br>
                <button type="submit" name="call" class="col-md-12 col-sm-12 col-12 btn btn-m button">Call</button>
            </form>
            <form method="post" class="p-3 m-2">
                <button type="submit" name="home" class="col-md-12 col-sm-12 col-12 btn btn-m button" style="background-color: #c44747;">Home</button>
            </form>
        </div>
        <div class="col-2 col-lg-4"></div>
    </div>
</body>
</html>
<?php
    function generateRandomString($length = 8)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $randomString = '';

        for ($i = 0; $i < $length; $i++)
        {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomString;
    }
    if (isset($_POST['call']))
    {
        $randomRoom = generateRandomString();
        $redirectURL = "https://videolink2me.com/#".$randomRoom;

        try
        {
            $f_friends = $_POST['friends'];

            $call_value = 0;
            $sql = "INSERT INTO call_request (call_user, called_user, call_value, room_url) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $s_user_nick, $f_friends, $call_value, $redirectURL);
            $stmt->execute();
        }
        catch (Exception $e)
        {
            error_log($e->getMessage(), 3, "error_log.txt");
            echo "<script>alert('Veritabanı Hatası!');</script>";
            die();
        }

        header("Location: " . $redirectURL);
        exit();
    }
    if (isset($_POST['home']))
    {
        header("Location: index.php");
        exit();
    }
?>