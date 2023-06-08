<?php
    session_start();

    if (isset($_SESSION['user_nick']))
    {
        $s_user_nick = $_SESSION['user_nick'];
        $s_user_biography = $_SESSION['user_biography'];
        $s_user_photo_path = $_SESSION['user_photo_path'];
    }
    else
    {
        header("Location: sign-in.php");
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
    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function()
        {
            $(".menu-item").click(function()
            {
                var page = $(this).data("page");

                if(page == "settings")
                {
                    $.ajax({ url: page + ".php", type: "POST", success: function(response) { window.location.href = page + ".php"; }, error: function() { } });
                }
                else if(page == "videocall")
                {
                    $.ajax({ url: page + ".php", type: "POST", success: function(response) { window.location.href = page + ".php"; }, error: function() { } });
                }
                else
                {
                    $.ajax({ url: page + ".php", type: "POST", success: function(response) { $("#box").html(response); }, error: function() { } });
                }
            });
        });
    </script>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-sm-6 col-6">
                <ul class="row menu nav nav-tab">
                    <li class="col-md-12 col-sm-12 col-12 border-bottom border-dark ortala nav-item" style="height: 20%;">
                        <div class="row">
                            <div class="col-md-4 col-sm-5 col-5">
                                <img src="<?php echo $s_user_photo_path ?>">
                            </div>
                            <div class="col-md-8 col-sm-7 col-7" style="text-align: center;">
                                <div class="col-md-12 col-sm-12 col-12 menu-bio">
                                    <?php echo $s_user_nick; ?>
                                </div>
                                <div class="col-md-12 col-sm-12 col-12 menu-bio">
                                    <?php echo $s_user_biography; ?>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="col-md-12 col-sm-12 col-12 border-bottom border-dark ortala nav-item" style="height: 10%;">
                        <form action="" method="post" class="col-md-12 col-sm-12 col-12">
                            <div class="col-md-8 col-sm-7 col-7" style="float: left">
                                <input type="text" name="add_friend" class="form-control" placeholder="Username" required>
                            </div>
                            <div class="col-md-4 col-sm-5 col-5" style="float: left">
                                <button type="submit" name="add" class="col-md-12 col-sm-12 col-12 btn btn-m button">Add</button>
                            </div>
                        </form>
                    </li>
                    <li class="col-md-12 col-sm-12 col-12 border-bottom border-dark ortala nav-item" style="height: 10%;">
                        <form action="" method="post" class="col-md-12 col-sm-12 col-12">
                            <div class="col-md-8 col-sm-7 col-7" style="float: left">
                                <input type="text" name="block_user" class="form-control" placeholder="Username" required>
                            </div>
                            <div class="col-md-4 col-sm-5 col-5" style="float: left">
                                <button type="submit" name="block" class="col-md-12 col-sm-12 col-12 btn btn-m button" style="background-color: #c44747;">Block</button>
                            </div>
                        </form>
                    </li>
                    <li class="col-md-12 col-sm-12 col-12 border-bottom border-dark ortala nav-item menu-item" style="height: 15%;" data-page="settings">
                        <div class="row">
                            <center>
                                <div class="col-md-2 col-sm-2 col-2">
                                    <img src="images/icon/menu/gear.png">
                                </div>
                                <div class="col-md-10 col-sm-10 col-10">
                                    <i name="settings">Settings</i>
                                </div>
                            </center>
                        </div>
                    </li>
                    <li class="col-md-12 col-sm-12 col-12 border-bottom border-dark ortala nav-item menu-item" style="height: 15%;" data-page="friends">
                        <div class="row">
                            <center>
                                <div class="col-md-2 col-sm-2 col-2">
                                    <img src="images/icon/menu/add-user.png">
                                </div>
                                <div class="col-md-10 col-sm-10 col-10">
                                    <i>Friends</i>
                                </div>
                            </center>
                        </div>
                    </li>
                    <li class="col-md-12 col-sm-12 col-12 border-bottom border-dark ortala nav-item menu-item" style="height: 15%;" data-page="groups">
                        <div class="row">
                            <center>
                                <div class="col-md-2 col-sm-2 col-2">
                                    <img src="images/icon/menu/add-friend.png">
                                </div>
                                <div class="col-md-10 col-sm-10 col-10">
                                    <i>Groups</i>
                                </div>
                            </center>
                        </div>
                    </li>
                    <li class="col-md-12 col-sm-12 col-12 border-bottom border-dark ortala nav-item menu-item" style="height: 15%;" data-page="videocall">
                        <div class="row">
                            <center>
                                <div class="col-md-2 col-sm-2 col-2">
                                    <img src="images/icon/menu/zoom.png">
                                </div>
                                <div class="col-md-10 col-sm-10 col-10">
                                    <i>Video Call</i>
                                </div>
                            </center>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="col-md-9 col-sm-6 col-6 border-start border-dark">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-12" id="box" style="overflow-x: auto;"></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php
    include "connection.php";

    if(isset($_POST['add']))
    {
        try
        {
            $user_nick = filter_input(INPUT_POST, 'add_friend', FILTER_SANITIZE_STRING);

            if (!preg_match("/^[a-zA-Z0-9]+$/", $user_nick))
            {
                echo "<script>alert('Kullanıcı Adı Sadece Harf ve Rakam İçerebilir!');</script>";
                die();
            }

            if ($s_user_nick == $user_nick)
            {
                echo "<script>alert('Kendinizi Arkadaş Ekleyemezsiniz!');</script>";
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
                    $sql = "SELECT friend_role FROM friendship_table WHERE (user_1 = ? AND user_2 = ?) or (user_1 = ? AND user_2 = ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssss", $s_user_nick, $user_nick, $user_nick, $s_user_nick);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows == 0)
                    {
                        $friend_role = 0;
                        $sql = "INSERT INTO friendship_table (user_1, user_2, friend_role) VALUES (?, ?, ?)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ssi", $s_user_nick, $user_nick, $friend_role);

                        if ($stmt->execute())
                        {
                            echo "<script>alert('Arkadaşlık İsteği Gönderildi!');</script>";
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
                        $db_friend_role = $row['friend_role'];

                        if ($db_friend_role == 0)
                        {
                            echo "<script>alert('Daha Önce Arkadaşlık İsteği Gönderilmiş! Lütfen Arkadaşlık İsteğini Kontrol Edin.');</script>";
                            die();
                        }

                        if($db_friend_role == 1)
                        {
                            echo "<script>alert('$user_nick ile Arkadaşınız!');</script>";
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
    if(isset($_POST['block']))
    {
        try
        {
            $user_nick = filter_input(INPUT_POST, 'block_user', FILTER_SANITIZE_STRING);

            if (!preg_match("/^[a-zA-Z0-9]+$/", $user_nick))
            {
                echo "<script>alert('Kullanıcı Adı Sadece Harf ve Rakam İçerebilir!');</script>";
                die();
            }

            if ($s_user_nick == $user_nick)
            {
                echo "<script>alert('Kendinizi Engelleyemezsiniz!');</script>";
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
                    $sql = "INSERT INTO blocked_table (blocking_user, blocked_user) VALUES (?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ss", $s_user_nick, $user_nick);
                    $stmt->execute();

                    $sql = "SELECT friend_role FROM friendship_table WHERE (user_1 = ? AND user_2 = ?) or (user_1 = ? AND user_2 = ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssss", $s_user_nick, $user_nick, $user_nick, $s_user_nick);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows != 0)
                    {
                        $sql = "DELETE FROM friendship_table WHERE (user_1 = ? AND user_2 = ?) or (user_1 = ? AND user_2 = ?)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ssss", $s_user_nick, $user_nick, $user_nick, $s_user_nick);

                        if ($stmt->execute())
                        {
                            echo "<script>alert('Kişi Engellendi!');</script>";
                            die();
                        }
                        else
                        {
                            echo "<script>alert('Kayıt İşlemi Başarısız Oldu!');</script>";
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
?>