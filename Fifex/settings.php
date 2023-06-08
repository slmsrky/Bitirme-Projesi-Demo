<?php
    include "connection.php";

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

    if (isset($_POST['home']))
    {
        header("Location: index.php");
        exit();
    }

    if (isset($_POST['exit']))
    {
        session_destroy();
        header("Location: index.php");
        exit();
    }

    $sql_u = "SELECT user_name, user_surname, user_biography, user_photo_path FROM user_table WHERE user_nick = ?";
    $stmt_u = $conn->prepare($sql_u);
    $stmt_u->bind_param("s", $s_user_nick);
    $stmt_u->execute();
    $stmt_u->store_result();
    $stmt_u->bind_result($db_user_name, $db_user_surname, $db_user_biography, $db_user_photo_path);
    $stmt_u->fetch();

    $sql_c = "SELECT code_1, code_2, code_3 FROM code_table WHERE user_nick = ?";
    $stmt_c = $conn->prepare($sql_c);
    $stmt_c->bind_param("s", $s_user_nick);
    $stmt_c->execute();
    $stmt_c->store_result();
    $stmt_c->bind_result($db_code_1, $db_code_2, $db_code_3);
    $stmt_c->fetch();

    if (isset($_POST['accept_f']))
    {
        try
        {
            $buttonValue = $_POST['accept_f'];

            $sql = "UPDATE friendship_table SET friend_role = 1 WHERE user_1 = ? AND user_2 = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $buttonValue, $s_user_nick);
            $stmt->execute();
        }
        catch (Exception $e)
        {
            error_log($e->getMessage(), 3, "error_log.txt");
            echo "<script>alert('Veritabanı Hatası!');</script>";
            die();
        }

        header("Location: settings.php");
        exit();
    }

    if (isset($_POST['reject_f']))
    {
        try
        {
            $buttonValue = $_POST['reject_f'];

            $sql = "DELETE FROM friendship_table WHERE user_1 = ? AND user_2 = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $buttonValue, $s_user_nick);
            $stmt->execute();
        }
        catch (Exception $e)
        {
            error_log($e->getMessage(), 3, "error_log.txt");
            echo "<script>alert('Veritabanı Hatası!');</script>";
            die();
        }

        header("Location: settings.php");
        exit();
    }

    if (isset($_POST['accept_g']))
    {
        try
        {
            $buttonValue = $_POST['accept_g'];

            $sql = "UPDATE group_users_table SET user_role = 1 WHERE group_name = ? AND user_name = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $buttonValue, $s_user_nick);
            $stmt->execute();
        }
        catch (Exception $e)
        {
            error_log($e->getMessage(), 3, "error_log.txt");
            echo "<script>alert('Veritabanı Hatası!');</script>";
            die();
        }

        header("Location: settings.php");
        exit();
    }

    if (isset($_POST['reject_g']))
    {
        try
        {
            $buttonValue = $_POST['reject_g'];

            $sql = "DELETE FROM group_users_table WHERE group_name = ? AND user_name = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $buttonValue, $s_user_nick);
            $stmt->execute();
        }
        catch (Exception $e)
        {
            error_log($e->getMessage(), 3, "error_log.txt");
            echo "<script>alert('Veritabanı Hatası!');</script>";
            die();
        }

        header("Location: settings.php");
        exit();
    }

    if (isset($_POST['accept_c']))
    {
        try
        {
            $buttonValues = explode(",", $_POST['accept_c']);

            $sql = "DELETE FROM call_request WHERE call_user = ? AND called_user = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $buttonValues[0], $s_user_nick);
            $stmt->execute();

            header("Location: " . $buttonValues[1]);
            exit();
        }
        catch (Exception $e)
        {
            error_log($e->getMessage(), 3, "error_log.txt");
            echo "<script>alert('Veritabanı Hatası!');</script>";
            die();
        }

        header("Location: settings.php");
        exit();
    }

    if (isset($_POST['reject_c']))
    {
        try
        {
            $buttonValue = $_POST['reject_c'];

            $sql = "DELETE FROM call_request WHERE call_user = ? AND called_user = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $buttonValue, $s_user_nick);
            $stmt->execute();
        }
        catch (Exception $e)
        {
            error_log($e->getMessage(), 3, "error_log.txt");
            echo "<script>alert('Veritabanı Hatası!');</script>";
            die();
        }

        header("Location: settings.php");
        exit();
    }

    if (isset($_POST['unblock']))
    {
        try
        {
            $buttonValue = $_POST['unblock'];

            $sql = "DELETE FROM blocked_table WHERE blocking_user = ? AND blocked_user = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $s_user_nick, $buttonValue);
            $stmt->execute();
        }
        catch (Exception $e)
        {
            error_log($e->getMessage(), 3, "error_log.txt");
            echo "<script>alert('Veritabanı Hatası!');</script>";
            die();
        }

        header("Location: settings.php");
        exit();
    }

    if (isset($_POST['exit_group']))
    {
        try
        {
            $buttonValue = $_POST['exit_group'];

            $sql = "DELETE FROM group_users_table WHERE group_name = ? AND user_name = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $buttonValue, $s_user_nick);
            $stmt->execute();
        }
        catch (Exception $e)
        {
            error_log($e->getMessage(), 3, "error_log.txt");
            echo "<script>alert('Veritabanı Hatası!');</script>";
            die();
        }

        header("Location: settings.php");
        exit();
    }

    if (isset($_POST['unfollow']))
    {
        try
        {
            $buttonValue = $_POST['unfollow'];

            $sql = "DELETE FROM friendship_table WHERE (user_1 = ? AND user_2 = ?) OR (user_1 = ? AND user_2 = ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $buttonValue, $s_user_nick, $s_user_nick, $buttonValue);
            $stmt->execute();
        }
        catch (Exception $e)
        {
            error_log($e->getMessage(), 3, "error_log.txt");
            echo "<script>alert('Veritabanı Hatası!');</script>";
            die();
        }

        header("Location: settings.php");
        exit();
    }

    if (isset($_POST['new_group']))
    {
        header("Location: new_group.php");
        exit();
    }

    if (isset($_POST['group_settings']))
    {
        header("Location: group_settings.php");
        exit();
    }
    
    if (isset($_POST['add_user']))
    {
        header("Location: add_user.php");
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
    <script src="js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-12" style="height: 100vh;">
            <ul class="nav nav-fill border-bottom border-dark" role="tablist">
                <li class="nav-item border-bottom border-dark" role="presentation">
                    <a class="nav-link active" id="fill-tab-0" data-bs-toggle="tab" href="#fill-tabpanel-0" role="tab" aria-controls="fill-tabpanel-0" aria-selected="true" style="background-color: #383e42; color: white;"> User Settings </a>
                </li>
                <li class="nav-item border-bottom border-dark" role="presentation">
                    <a class="nav-link" id="fill-tab-1" data-bs-toggle="tab" href="#fill-tabpanel-1" role="tab" aria-controls="fill-tabpanel-1" aria-selected="false" style="background-color: #383e42; color: white;"> Password Settings </a>
                </li>
                <li class="nav-item border-bottom border-dark" role="presentation">
                    <a class="nav-link" id="fill-tab-2" data-bs-toggle="tab" href="#fill-tabpanel-2" role="tab" aria-controls="fill-tabpanel-2" aria-selected="false" style="background-color: #383e42; color: white;"> Friend Request </a>
                </li>
                <li class="nav-item border-bottom border-dark" role="presentation">
                    <a class="nav-link" id="fill-tab-3" data-bs-toggle="tab" href="#fill-tabpanel-3" role="tab" aria-controls="fill-tabpanel-3" aria-selected="false" style="background-color: #383e42; color: white;"> Group Request </a>
                </li>
                <li class="nav-item border-bottom border-dark" role="presentation">
                    <a class="nav-link" id="fill-tab-4" data-bs-toggle="tab" href="#fill-tabpanel-4" role="tab" aria-controls="fill-tabpanel-4" aria-selected="false" style="background-color: #383e42; color: white;"> Call Request </a>
                </li>
                <li class="nav-item border-bottom border-dark" role="presentation">
                    <a class="nav-link" id="fill-tab-5" data-bs-toggle="tab" href="#fill-tabpanel-5" role="tab" aria-controls="fill-tabpanel-5" aria-selected="false" style="background-color: #383e42; color: white;"> Friends </a>
                </li>
                <li class="nav-item border-bottom border-dark" role="presentation">
                    <a class="nav-link" id="fill-tab-6" data-bs-toggle="tab" href="#fill-tabpanel-6" role="tab" aria-controls="fill-tabpanel-6" aria-selected="false" style="background-color: #383e42; color: white;"> Groups </a>
                </li>
                <li class="nav-item border-bottom border-dark" role="presentation">
                    <a class="nav-link" id="fill-tab-7" data-bs-toggle="tab" href="#fill-tabpanel-7" role="tab" aria-controls="fill-tabpanel-7" aria-selected="false" style="background-color: #383e42; color: white;"> Block </a>
                </li>
                <li class="nav-item border-bottom border-dark" role="presentation">
                    <form method="post"><button type="submit" name="home" class="col-md-12 col-sm-12 col-12 btn btn-m button" style="background-color: #383e42; color: white;">Home</button></form>
                </li>
                <li class="nav-item border-bottom border-dark" role="presentation">
                    <form method="post"><button type="submit" name="exit" class="col-md-12 col-sm-12 col-12 btn btn-m button" style="background-color: #383e42; color: white;">Exit</button></form>
                </li>
            </ul>
            <div class="tab-content" id="tab-content">
                <div class="tab-pane active" id="fill-tabpanel-0" role="tabpanel" aria-labelledby="fill-tab-0">
                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-4"></div>
                        <div class="col-md-4 col-sm-4 col-4" style="text-align: center; color: white; padding-top: 10%">
                            <form method="post" enctype="multipart/form-data">
                                <label class="ortala">User Name</label><input type="text" name="user_name" class="form-control" value="<?php echo $db_user_name; ?>" required><br>
                                <label class="ortala">User Surname</label><input type="text" name="user_surname" class="form-control" value="<?php echo $db_user_surname; ?>" required><br>
                                <label class="ortala">User Biography</label><input type="text" name="user_biography" class="form-control" value="<?php echo $db_user_biography; ?>" required><br>
                                <label class="ortala">User Photo</label><input type="file" id="file" name="file" accept=".jpg, .png"><br><br><br>
                                <label class="ortala">Security Codes</label><label class="ortala"><?php echo $db_code_1; ?></label><label class="ortala"><?php echo $db_code_2; ?></label><label class="ortala"><?php echo $db_code_3; ?></label><br><br>
                                <button type="submit" name="user_info" class="btn button" style="min-width: 100%;">Save</button>
                            </form>
                        </div>
                        <div class="col-md-4 col-sm-4 col-4"></div>
                    </div>
                </div>
                <div class="tab-pane" id="fill-tabpanel-1" role="tabpanel" aria-labelledby="fill-tab-1">
                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-4"></div>
                        <div class="col-md-4 col-sm-4 col-4" style="text-align: center; color: white; padding-top: 10%">
                            <form method="post">
                                <label class="ortala">Security Code 1</label><input type="text" name="security_code_1" class="form-control" required><br>
                                <label class="ortala">Security Code 2</label><input type="text" name="security_code_2" class="form-control" required><br>
                                <label class="ortala">Security Code 3</label><input type="text" name="security_code_3" class="form-control" required><br>
                                <label class="ortala">Password</label><input type="password" name="password" class="form-control" required><br>
                                <label class="ortala">Password Control</label><input type="password" name="password_control" class="form-control" required><br><br>
                                <button type="submit" name="password_info" class="btn button" style="min-width: 100%;">Save</button>
                            </form>
                        </div>
                        <div class="col-md-4 col-sm-4 col-4"></div>
                    </div>
                </div>
                <div class="tab-pane" id="fill-tabpanel-2" role="tabpanel" aria-labelledby="fill-tab-2">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-12" style="height: 10vh;"></div>
                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-3"></div>
                            <div class="col-md-6 col-sm-6 col-6" style="overflow-x: auto;">
                                <form method="post">
                                    <table><?php
                                    $friend_role = 0;
                                    $sql = "SELECT * FROM friendship_table WHERE user_2 = ? and friend_role = ?";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bind_param("si", $s_user_nick, $friend_role);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    if ($result->num_rows != 0)
                                    {
                                        while ($row = $result->fetch_assoc())
                                        {
                                            $user_1 = $row["user_1"];
                                            $greenButtonStyle = "display: block";
                                            $redButtonStyle = "display: block";

                                            echo "<tr style='text-align: center; color: white;'>";
                                                echo "<td style='min-width: 40vh;'>$user_1</td>";
                                                echo "<td style='min-width: 30vh;'><button type='submit' name='accept_f' class='col-md-12 col-sm-12 col-12 btn btn-m button' style='$greenButtonStyle' value='$user_1'>Accept</button></td>";
                                                echo "<td style='min-width: 30vh;'><button type='submit' name='reject_f' class='col-md-12 col-sm-12 col-12 btn btn-m button' style='background-color: #c44747; $redButtonStyle' value='$user_1'>Reject</button></td>";
                                            echo "</tr>";
                                        }
                                    }
                                    ?></table>
                                </form>
                            </div>
                            <div class="col-md-3 col-sm-3 col-3"></div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-12" style="height: 10vh;"></div>
                    </div>
                </div>
                <div class="tab-pane" id="fill-tabpanel-3" role="tabpanel" aria-labelledby="fill-tab-3">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-12" style="height: 10vh;"></div>
                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-3"></div>
                            <div class="col-md-6 col-sm-6 col-6" style="overflow-x: auto;">
                                <form method="post">
                                    <table><?php
                                    $user_role = 0;
                                    $sql = "SELECT group_name FROM group_users_table WHERE user_name = ? and user_role = ?";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bind_param("si", $s_user_nick, $user_role);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    if ($result->num_rows != 0)
                                    {
                                        while ($row = $result->fetch_assoc())
                                        {
                                            $group_name = $row["group_name"];
                                            $greenButtonStyle = "display: block";
                                            $redButtonStyle = "display: block";

                                            echo "<tr style='text-align: center; color: white;'>";
                                                echo "<td style='min-width: 40vh;'>$group_name</td>";
                                                echo "<td style='min-width: 30vh;'><button type='submit' name='accept_g' class='col-md-12 col-sm-12 col-12 btn btn-m button' style='$greenButtonStyle' value='$group_name'>Accept</button></td>";
                                                echo "<td style='min-width: 30vh;'><button type='submit' name='reject_g' class='col-md-12 col-sm-12 col-12 btn btn-m button' style='background-color: #c44747; $redButtonStyle' value='$group_name'>Reject</button></td>";
                                            echo "</tr>";
                                        }
                                    }
                                    ?></table>
                                </form>
                            </div>
                            <div class="col-md-3 col-sm-3 col-3"></div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-12" style="height: 10vh;"></div>
                    </div>
                </div>
                <div class="tab-pane" id="fill-tabpanel-4" role="tabpanel" aria-labelledby="fill-tab-4">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-12" style="height: 10vh;"></div>
                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-3"></div>
                            <div class="col-md-6 col-sm-6 col-6" style="overflow-x: auto;">
                                <form method="post">
                                    <table><?php
                                    $call_value = 0;
                                    $sql = "SELECT call_user, room_url FROM call_request WHERE called_user = ? AND call_value = ?";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bind_param("si", $s_user_nick, $call_value);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    if ($result->num_rows != 0)
                                    {
                                        while ($row = $result->fetch_assoc())
                                        {
                                            $call_user = $row["call_user"];
                                            $room_url = $row["room_url"];

                                            $greenButtonStyle = "display: block";
                                            $redButtonStyle = "display: block";

                                            echo "<tr style='text-align: center; color: white;'>";
                                                echo "<td style='min-width: 40vh;'>$call_user</td>";
                                                echo "<td style='min-width: 30vh;'><button type='submit' name='accept_c' class='col-md-12 col-sm-12 col-12 btn btn-m button' style='$greenButtonStyle' value='$call_user,$room_url'>Accept</button></td>";
                                                echo "<td style='min-width: 30vh;'><button type='submit' name='reject_c' class='col-md-12 col-sm-12 col-12 btn btn-m button' style='background-color: #c44747; $redButtonStyle' value='$call_user'>Reject</button></td>";
                                            echo "</tr>";
                                        }
                                    }
                                    ?></table>
                                </form>
                            </div>
                            <div class="col-md-3 col-sm-3 col-3"></div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-12" style="height: 10vh;"></div>
                    </div>
                </div>
                <div class="tab-pane" id="fill-tabpanel-5" role="tabpanel" aria-labelledby="fill-tab-5">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-12" style="height: 10vh;"></div>
                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-3"></div>
                            <div class="col-md-6 col-sm-6 col-6" style="overflow-x: auto;">
                                <form method="post">
                                    <table><?php
                                    $friend_role = 1;
                                    $sql = "SELECT * FROM friendship_table WHERE (user_1 = ? OR user_2 = ?) and friend_role = ?";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bind_param("ssi", $s_user_nick, $s_user_nick, $friend_role);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    if ($result->num_rows != 0)
                                    {
                                        while ($row = $result->fetch_assoc())
                                        {
                                            if($s_user_nick == $row["user_1"])
                                            {
                                                $user = $row["user_2"];
                                            }
                                            else
                                            {
                                                $user = $row["user_1"];
                                            }

                                            echo "<tr style='text-align: center; color: white;'>";
                                                echo "<td style='min-width: 40vh;'>$user</td>";
                                                echo "<td style='min-width: 30vh;'><button type='submit' name='unfollow' class='col-md-12 col-sm-12 col-12 btn btn-m button' style='background-color: #c44747;' value='$user'>Unfollow</button></td>";
                                            echo "</tr>";
                                        }
                                    }
                                    ?></table>
                                </form>
                            </div>
                            <div class="col-md-3 col-sm-3 col-3"></div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-12" style="height: 10vh;"></div>
                    </div>
                </div>
                <div class="tab-pane" id="fill-tabpanel-6" role="tabpanel" aria-labelledby="fill-tab-6">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-12" style="height: 10vh"></div>
                        <div class="col-md-12 col-sm-12 col-12">
                            <form method="post" class="col-md-12 col-sm-12 col-12 ortala">
                                <div class="col-md-2 col-sm-2 col-2" style="float: left;"></div>
                                <div class="col-md-2 col-sm-2 col-2" style="float: left;"><button type="submit" name="new_group" class="col-md-12 col-sm-12 col-12 btn btn-m button">New Group</button></div>
                                <div class="col-md-1 col-sm-1 col-1" style="float: left;"></div>
                                <div class="col-md-2 col-sm-2 col-2" style="float: left;"><button type="submit" name="group_settings" class="col-md-12 col-sm-12 col-12 btn btn-m button">Group Settings</button></div>
                                <div class="col-md-1 col-sm-1 col-1" style="float: left;"></div>
                                <div class="col-md-2 col-sm-2 col-2" style="float: left;"><button type="submit" name="add_user" class="col-md-12 col-sm-12 col-12 btn btn-m button">Invite a User</button></div>
                                <div class="col-md-2 col-sm-2 col-2" style="float: left;"></div>
                            </form>
                        </div>
                        <div class="col-md-12 col-sm-12 col-12" style="height: 10vh"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-3"></div>
                        <div class="col-md-6 col-sm-6 col-6" style="overflow-x: auto;">
                            <form method="post">
                                <table><?php
                                $user_role = 1;
                                $sql = "SELECT group_name FROM group_users_table WHERE user_name = ? and user_role = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("si", $s_user_nick, $user_role);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                if ($result->num_rows != 0)
                                {
                                    while ($row = $result->fetch_assoc())
                                    {
                                        $group_name = $row["group_name"];

                                        echo "<tr style='text-align: center; color: white;'>";
                                            echo "<td style='min-width: 60vh;'>$group_name</td>";
                                            echo "<td style='min-width: 40vh;'><button type='submit' name='exit_group' class='col-md-12 col-sm-12 col-12 btn btn-m button' style='background-color: #c44747;' value='$group_name'>Exit Group</button></td>";
                                        echo "</tr>";
                                    }
                                }
                                ?></table>
                            </form>
                        </div>
                        <div class="col-md-3 col-sm-3 col-3"></div>
                    </div>
                </div>
                <div class="tab-pane" id="fill-tabpanel-7" role="tabpanel" aria-labelledby="fill-tab-7">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-12" style="height: 10vh;"></div>
                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-3"></div>
                            <div class="col-md-6 col-sm-6 col-6" style="overflow-x: auto;">
                                <form method="post">
                                    <table><?php
                                    $sql = "SELECT blocked_user FROM blocked_table WHERE blocking_user = ?";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bind_param("s", $s_user_nick);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    if ($result->num_rows != 0)
                                    {
                                        while ($row = $result->fetch_assoc())
                                        {
                                            $blocked_user = $row["blocked_user"];

                                            echo "<tr style='text-align: center; color: white;'>";
                                                echo "<td style='min-width: 60vh;'>$blocked_user</td>";
                                                echo "<td style='min-width: 40vh;'><button type='submit' name='unblock' class='col-md-12 col-sm-12 col-12 btn btn-m button' style='background-color: #c44747;' value='$blocked_user'>Unblock</button></td>";
                                            echo "</tr>";
                                        }
                                    }
                                    ?></table>
                                </form>
                            </div>
                            <div class="col-md-3 col-sm-3 col-3"></div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-12" style="height: 10vh;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php
    if (isset($_POST['user_info']))
    {
        try
        {
            $user_name = filter_input(INPUT_POST, 'user_name', FILTER_SANITIZE_STRING);
            $user_surname = filter_input(INPUT_POST, 'user_surname', FILTER_SANITIZE_STRING);
            $user_biography = filter_input(INPUT_POST, 'user_biography', FILTER_SANITIZE_STRING);

            $user_folder = "Users/" . $s_user_nick;
            if (!is_dir($user_folder))
            {
                mkdir($user_folder, 0777, true);
            }

            $file = $_FILES['file'];
            $file_name = $file['name'];

            if ($file_name != null)
            {
                $file_tmp = $file['tmp_name'];
                $file_path = $user_folder . "/" . $s_user_nick . "." . pathinfo($file_name, PATHINFO_EXTENSION);
                move_uploaded_file($file_tmp, $file_path);

                $sql = "UPDATE user_table SET user_name = ?, user_surname = ?, user_biography = ?, user_photo_path = ? WHERE user_nick = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssss", $user_name, $user_surname, $user_biography, $file_path, $s_user_nick);

                $_SESSION['user_photo_path'] = $file_path;
            }
            else
            {
                $sql = "UPDATE user_table SET user_name = ?, user_surname = ?, user_biography = ? WHERE user_nick = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssss", $user_name, $user_surname, $user_biography, $s_user_nick);
            }

            if ($stmt->execute())
            {
                $_SESSION['user_biography'] = $user_biography;

                echo "<script>alert('Kullanıcı Bilgileri Güncellendi!');</script>";
                die();
            }
            else
            {
                echo "<script>alert('Güncelleme İşlemi Başarısız Oldu!');</script>";
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
    if(isset($_POST['password_info']))
    {
        try
        {
            $security_code_1 = $_POST['security_code_1'];
            $security_code_2 = $_POST['security_code_2'];
            $security_code_3 = $_POST['security_code_3'];
            $user_password_1 = $_POST['password'];
            $user_password_2 = $_POST['password_control'];

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
            $stmt->bind_param("s", $s_user_nick);
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
                    $stmt->bind_param("s", $s_user_nick);
                    $stmt->execute();
                    $stmt->store_result();
                    $stmt->bind_result($db_user_pass);
                    $stmt->fetch();

                    $user_password = password_hash($user_password_1, PASSWORD_BCRYPT, ['cost' => 12]);

                    if (!password_verify($user_password_1, $db_user_pass))
                    {
                        $sql = "UPDATE user_table SET user_pass = ? WHERE user_nick = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ss", $user_password, $s_user_nick);

                        if ($stmt->execute())
                        {
                            echo "<script>alert('Şifre Güncellendi!');</script>";
                            die();
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