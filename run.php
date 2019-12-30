<?php
/**
 * Run the webform processor.
 *
 * @author  Caspar Green
 * @since   ver 1.0.0
 */

namespace Webforms;

use Dotenv\Dotenv;
use Swift_Mailer;
use Swift_SmtpTransport;

function run(): string
{
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    $allowedOrigin = getenv('ALLOW_ORIGIN');
    $accessKeyName = getenv('FORM_KEYNAME');

    header("Access-Control-Allow-Origin: {$allowedOrigin}");
    header("Access-Control-Allow-Headers: {$accessKeyName}");

    $transport = (new Swift_SmtpTransport(getenv('SERVER'), getenv('PORT'), 'ssl'))
        ->setUsername(getenv('USER_ID'))
        ->setPassword(getenv('PASSWD'));

    $mailer = new Swift_Mailer($transport);

    $webform = new WebformProcessor($mailer);

    return $webform->process();
}
