<?php
session_start();
session_unset();
session_destroy();
header("Location: ../category/account.php"); // Chuyển hướng về trang login
exit();
?>
