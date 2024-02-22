<?php
    require_once '../includes/db_connection.php'; 

    function admin_login($email, $password) {
        global $conn;

        $stmt = $conn->prepare("SELECT email, password FROM admins WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        $stmt->bind_result($db_email, $db_password);
        $stmt->fetch();

        if ($stmt->num_rows > 0 && password_verify($password, $db_password)) {
            return true;
        } else {
            return false;
        }

        // $stmt->close();
        // $conn->close();
    }

?>
