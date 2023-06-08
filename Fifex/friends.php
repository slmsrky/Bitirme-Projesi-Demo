<?php
    include 'connection.php';
    session_start();

    if (isset($_SESSION['user_nick']))
    {
        $s_user_nick = $_SESSION['user_nick'];
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function()
        {
            $(".friends-item").click(function()
            {
                var nick = $(this).data("nick");
                var page = $(this).data("page");
                $.ajax({ url: page + ".php", type: "POST", data: { nick: nick }, success: function(response) { window.location.href = page + ".php"; }, error: function() { } });
            });
        });
    </script>
</head>
<body>
    <ul class="row nav">
        <?php
            for ($i=0; $i < count($friends); $i++)
            {
                $sql = "SELECT user_nick, user_biography, user_photo_path FROM user_table WHERE user_nick = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $friends[$i]);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($db_user_nick, $db_user_biography, $db_user_photo_path);
                $stmt->fetch();
        ?>
        <li class="col-md-12 col-sm-12 col-12 border-bottom border-dark ortala nav-item friends-item" style="height: 20vh" data-page="friendschat" data-nick="<?php echo $db_user_nick; ?>">
            <div class="row">
                <div class="col-md-4 col-sm-5 col-5" style="max-height: 15vh">
                    <img src="<?php echo $db_user_photo_path ?>">
                </div>
                <div class="col-md-8 col-sm-7 col-7" style="text-align: center;">
                    <div class="col-md-12 col-sm-12 col-12 menu-bio">
                        <?php echo $db_user_nick; ?>
                    </div>
                    <div class="col-md-12 col-sm-12 col-12 menu-bio">
                        <?php echo $db_user_biography; ?>
                    </div>
                </div>
            </div>
        </li>
        <?php
            }
        ?>
    </ul>
</body>
</html>