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

    $transport = (new Swift_SmtpTransport(getenv('SERVER'), getenv('PORT')))
        ->setUsername(getenv('USER_ID'))
        ->setPassword(getenv('PASSWD'));

    $mailer = new Swift_Mailer($transport);

    $webform = new WebformProcessor($mailer);

    return $webform->process();
}
