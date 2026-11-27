<?php

class SesMailer {
  public static function wfSesMailer($headers, $to, $from, $subject, $body) {
    global $wgSesMailerRegion, $wgSesMailerKey, $wgSesMailerSecret;
    global $wgSesMailerSourceArn, $wgSesMailerReturnPath, $wgSesMailerReturnPathArn;

    if (file_exists( __DIR__ . '/vendor/autoload.php')) {
      require_once __DIR__ . '/vendor/autoload.php';
    }

    $ses_factory_array = array(
      'version' => 'latest',
      'region' => $wgSesMailerRegion,
    );
    // Use static credentials when configured; otherwise fall back to the SDK default provider chain
    if ($wgSesMailerKey && $wgSesMailerSecret) {
      $ses_factory_array['credentials'] = array(
        'key' => $wgSesMailerKey,
        'secret' => $wgSesMailerSecret,
      );
    }
    $client = Aws\Ses\SesClient::factory($ses_factory_array);

    $param = array();
    $param['Source'] = (string)$from;
    // Identities owned by another account: SourceArn/ReturnPathArn point at the identity, not the sender
    if ($wgSesMailerSourceArn) {
      $param['SourceArn'] = $wgSesMailerSourceArn;
    }
    if ($wgSesMailerReturnPath) {
      $param['ReturnPath'] = $wgSesMailerReturnPath;
    }
    if ($wgSesMailerReturnPathArn) {
      $param['ReturnPathArn'] = $wgSesMailerReturnPathArn;
    }
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
      wfLogWarning("Error sending email: $e");
      return false;
    }
  }
}
