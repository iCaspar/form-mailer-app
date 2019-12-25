<?php
/**
 * Run the webform processor.
 *
 * @author  Caspar Green
 * @since   ver 1.0.0
 */

namespace Webforms;

use Dotenv\Dotenv;

function run(): string
{
    require_once './vendor/autoload.php';

    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    $webform = new Webform();

    return $webform->send();
}

echo run();
