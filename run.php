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
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    $webform = new WebformProcessor();

    return $webform->process();
}
