# SesMailer

## About

The [SesMailer extension](https://www.mediawiki.org/wiki/Extension:SesMailer) uses the [AlternateUserMailer hook](https://www.mediawiki.org/wiki/Manual:Hooks/AlternateUserMailer)
to send emails with Amazon SES API, instead of php mail() or PEAR SMTP classes.

## Installation

1. [Add AWS SDK with composer](http://docs.aws.amazon.com/aws-sdk-php/v3/guide/getting-started/installation.html)
2. Copy SesMailer folder into `extensions` folder of your MediaWiki instance
3. To enable the extension and set your credentials, add the following lines to your `LocalSettings.php`:

```php
wfLoadExtension("SesMailer");
$wgSesMailerRegion = "eu-west-1"; // AWS Region
$wgSesMailerKey = "xxx"; // Access Key ID for IAM user with ses:SendEmail permission
$wgSesMailerSecret = "xxx"; // Secret Access Key
```

## Credit

A similar plugin for Question2Answer: [qa-mail-ses](https://github.com/fauguste/qa-mail-ses)
