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

    $sql = "SELECT user_name, message_text FROM group_message_table WHERE group_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_SESSION['group']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0)
    {
        while ($row = $result->fetch_assoc())
        {
            $user_name = $row["user_name"];
            $message = $row["message_text"];

            echo '<div class="row container-fluid">';

            if ($user_name == $s_user_nick)
            {
                echo '<div class="col-md-12 col-sm-12 col-12 menu-bio" style="text-align: right">(' . $user_name . '): ' . $message . '</div>';
            }

            else
            {
                echo '<div class="col-md-12 col-sm-12 col-12 menu-bio" style="text-align: left">(' . $user_name . '): ' . $message . '</div>';
            }

            echo '</div>';
        }
    }
?>