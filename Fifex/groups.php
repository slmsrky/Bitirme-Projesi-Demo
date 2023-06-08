<?php
    include 'connection.php';
    
    session_start();

    if (isset($_SESSION['user_nick']))
    {
        $s_user_nick = $_SESSION['user_nick'];
    }

    $groups = array();
    $group_role = 1;

    $sql = "SELECT group_name FROM group_users_table WHERE user_name = ? AND user_role = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $s_user_nick, $group_role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0)
    {
        while ($row = $result->fetch_assoc())
        {
            $groups[] = $row["group_name"];
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
            $(".groups-item").click(function()
            {
                var group = $(this).data("group");
                var page = $(this).data("page");
                $.ajax({ url: page + ".php", type: "POST", data: { group: group }, success: function(response) { window.location.href = page + ".php"; }, error: function() { } });
            });
        });
    </script>
</head>
<body>
    <ul class="row nav">
        <?php
            for ($i=0; $i < count($groups); $i++)
            {
                $sql = "SELECT group_name, group_biography, group_photo_path FROM group_table WHERE group_name = ? ORDER BY group_name ASC";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $groups[$i]);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($db_group_name, $db_group_biography, $db_group_photo_path);
                $stmt->fetch();
        ?>
        <li class="col-md-12 col-sm-12 col-12 border-bottom border-dark ortala nav-item groups-item" style="height: 20vh;" data-page="groupschat" data-group="<?php echo $db_group_name; ?>">
            <div class="row">
                <div class="col-md-4 col-sm-5 col-5" style="max-height: 15vh">
                    <img src="<?php echo $db_group_photo_path ?>">
                </div>
                <div class="col-md-8 col-sm-7 col-7" style="text-align: center;">
                    <div class="col-md-12 col-sm-12 col-12 menu-bio">
                        <?php echo $db_group_name; ?>
                    </div>
                    <div class="col-md-12 col-sm-12 col-12 menu-bio">
                        <?php echo $db_group_biography; ?>
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