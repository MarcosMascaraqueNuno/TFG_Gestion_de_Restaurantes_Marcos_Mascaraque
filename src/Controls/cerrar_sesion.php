<?php
session_start();
session_unset();
session_destroy();
header("Location: ../Views/inicio.php");
exit;
