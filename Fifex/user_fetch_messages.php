<?php
    include "connection.php";

    session_start();

    if (isset($_SESSION['user_nick']))
    {
        $s_user_nick = $_SESSION['user_nick'];
    }

    if(isset($_POST['nick']))
    {
        $_SESSION['nick'] = $_POST['nick'];
    }

    $sql = "SELECT user_1, user_2, message_text FROM user_message_table WHERE (user_1 = ? AND user_2 = ?) OR (user_1 = ? AND user_2 = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $s_user_nick, $_SESSION['nick'], $_SESSION['nick'], $s_user_nick);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0)
    {
        while ($row = $result->fetch_assoc())
        {
            $user1 = $row["user_1"];
            $user2 = $row["user_2"];
            $message = $row["message_text"];

            echo '<div class="row container-fluid">';

            if ($user1 == $s_user_nick)
            {
                echo '<div class="col-md-12 col-sm-12 col-12 menu-bio" style="text-align: right">(' . $user1 . '): ' . $message . '</div>';
            }

            if ($user1 == $_SESSION['nick'])
            {
                echo '<div class="col-md-12 col-sm-12 col-12 menu-bio" style="text-align: left">(' . $user1 . '): ' . $message . '</div>';
            }

            echo '</div>';
        }
    }
?>