<?php
    include "connection.php";

    session_start();

    if (isset($_SESSION['user_nick']))
    {
        $s_user_nick = $_SESSION['user_nick'];
    }

    if(isset($_POST['group']))
    {
        $_SESSION['group'] = $_POST['group'];
    }
    
    if (isset($_POST['submit']))
    {
        try
        {
            if ($_POST['text'] != null)
            {
                $message_text = $_POST['text'];
                $sql = "INSERT INTO group_message_table (group_name, user_name, message_text) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $_SESSION['group'], $s_user_nick, $message_text);
                $stmt->execute();
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
</head>
<body>
    <ul class="row nav">
    <?php
        $sql = "SELECT group_name, group_biography, group_photo_path FROM group_table WHERE group_name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $_SESSION['group']);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($db_group_name, $db_group_biography, $db_group_photo_path);
        $stmt->fetch();
    ?>
        <li class="col-md-12 col-sm-12 col-12 border-bottom border-dark ortala nav-item" style="height: 20vh">
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
        <li class="col-md-12 col-sm-12 col-12 border-bottom border-dark nav-item" style="height: 70vh; overflow-x: auto;" id="message-container"></li>
        <li class="col-md-12 col-sm-12 col-12 border-bottom border-dark ortala nav-item" style="height: 10vh">
            <form method="post" class="col-md-12 col-sm-12 col-12">
                <div class="col-md-8 col-sm-6 col-6" style="float: left">
                    <input type="text" name="text" class="form-control" placeholder="Enter Text">
                </div>
                <div class="col-md-2 col-sm-3 col-3" style="float: left">
                    <button type="submit" name="submit" class="col-md-12 col-sm-12 col-12 btn btn-m button" on_click="deneme">Submit</button>
                </div>
                <div class="col-md-2 col-sm-3 col-3" style="float: left">
                    <button type="submit" name="home" class="col-md-12 col-sm-12 col-12 btn btn-m button" style="background-color: #c44747;">Home</button>
                </div>
            </form>
        </li>
    </ul>
    <script>
        function fetchMessages() { $.ajax({ url: 'group_fetch_messages.php', method: 'POST', success: function(data) { $('#message-container').html(data); } }); }

        var messageContainer = document.getElementById("message-container");
        function AutoScroll() { messageContainer.scrollTop = messageContainer.scrollHeight;}

        setInterval(fetchMessages, 1);
        setInterval(AutoScroll, 1);
    </script>
</body>
</html>