<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

?>


<?php if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header("Location: /index.php");
}
?>

<?php require($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>
<main class="content">
    <?php
    if (isset($_POST['password-reset-token']) && $_POST['email']) {
        require_once($_SERVER['DOCUMENT_ROOT'] . "/components/config.php");

        $emailId = $_POST['email'];

        $result = mysqli_query($conn, "SELECT * FROM users WHERE email='" . $emailId . "'");

        $row = mysqli_fetch_array($result);

        if ($row) {

            $token = md5($emailId) . rand(10, 9999);

            date_default_timezone_set("Asia/Calcutta");

            $expFormat = mktime(
                date("H"),
                date("i"),
                date("s"),
                date("m"),
                date("d") + 1,
                date("Y")
            );

            $expDate = date("Y-m-d H:i:s", $expFormat);

            $update = mysqli_query($conn, "UPDATE users set  password='" . $password . "', reset_link_token='" . $token . "' ,exp_date='" . $expDate . "' WHERE email='" . $emailId . "'");

            $link = "<a href='http://blog/accounts/reset-password.php?key=" . $emailId . "&token=" . $token . "'>Click To Reset password</a>";

            require '../vendor/autoload.php';

            $mail = new PHPMailer();

            $mail->CharSet =  "utf-8";
            $mail->IsSMTP();
            // enable SMTP authentication
            $mail->SMTPAuth = true;
            // GMAIL username
            $mail->Username = "beststatusonline@gmail.com";
            // GMAIL password
            $mail->Password = "@temporarypassword";
            $mail->SMTPSecure = "ssl";
            // sets GMAIL as the SMTP server
            $mail->Host = "smtp.gmail.com";
            // set the SMTP port for the GMAIL server
            $mail->Port = "465";
            $mail->From = 'beststatusonline@gmail.com';
            $mail->FromName = 'PHP Blog';
            $mail->AddAddress('beststatusonline@gmail.com', 'Temp User');
            $mail->Subject  =  'Reset Password';
            $mail->IsHTML(true);
            $mail->Body    = 'Click On This Link to Reset Password ' . $link . '';
            if ($mail->Send()) {
                echo "<div class='success'>Check Your Email and Click on the link sent to your email</div>";
            } else {
                echo "Mail Error - >" . $mail->ErrorInfo;
            }
        } else {
            echo "Invalid Email Address. Go back";
        }
    }
    ?>
</main>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
</body>

</html>