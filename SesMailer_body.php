<?php

class SesMailer {
  function wfSesMailer($headers, $to, $from, $subject, $body) {
    global $wgSesMailerRegion, $wgSesMailerKey, $wgSesMailerSecret;

    if (file_exists( __DIR__ . '/vendor/autoload.php')) {
      require_once __DIR__ . '/vendor/autoload.php';
    }

    $ses_factory_array = array(
      'version' => 'latest',
      'region' => $wgSesMailerRegion,
      'credentials' => array(
        'key' => $wgSesMailerKey,
        'secret' => $wgSesMailerSecret,
      )
    );
    $client = Aws\Ses\SesClient::factory($ses_factory_array);

    $param = array();
    $param['Source'] = (string)$from;
    $param['Destination'] = array(
      'ToAddresses' => array(),
    );
    foreach($to as $recipient) {
      $param['Destination']['ToAddresses'][] = (string)$recipient;
    }
    $param['Message'] = array(
      'Subject' => array(
        'Data' => $subject,
        'Charset' => 'utf-8',
      ),
      'Body' => array(
        'Text' => array(
          'Data' => $body,
          'Charset' => 'utf-8',
        ),
      ),
    );
    try {
      $result = $client->sendEmail($param);
      return false;
    }
    catch(Exception $e) {
      return "Error sending email";
    }
  }
}
