<?php
/**
 * Tests for WebformProcessor Class.
 *
 * @package Webforms\Tests\Unit
 * @author  Caspar Green
 * @since   ver 1.0.0
 */

namespace Webforms\Tests\Unit;

use Dotenv\Dotenv;
use Webforms\WebformProcessor;
use PHPUnit\Framework\TestCase;

class WebformProcessorTest extends TestCase
{
    /**
     * Set up before running tests.
     *
     * @return void
     * @since  ver 1.0.0
     *
     * @author Caspar Green
     */
    public static function setUpBeforeClass(): void
    {
        $dotenv = Dotenv::createImmutable(dirname(dirname(__DIR__)));
        $dotenv->load();
    }

    /**
     * Test send() fails when form sender address is not provided.
     *
     * @return void
     * @since  ver 1.0.0
     *
     * @author Caspar Green
     */
    public function testSendFailsWhenFormSenderAddressNotProvided(): void
    {
        $_POST = [
            'name'    => 'John Smith',
            'message' => 'Some message text.'
        ];

        $mailerSenderNotSet = new WebformProcessor();

        $this->assertEquals(
            'Send Failed: Sender email not provided.',
            $mailerSenderNotSet->process(),
            'Sender email check (sender not set) error.'
        );

        $_POST = [
            'from'    => '',
            'name'    => 'John Smith',
            'message' => 'Some message text.'
        ];

        $mailerSenderEmpty = new WebformProcessor();

        $this->assertEquals(
            'Send Failed: Sender email not provided.',
            $mailerSenderEmpty->process(),
            'Sender email check (sender empty) error.'
        );
    }

    public function testSendFailsWhenFormSenderNameNotProvided(): void
    {
        $_POST = [
            'from'    => 'john.smith@example.com',
            'message' => 'Some message text.'
        ];

        $mailerSenderNotSet = new WebformProcessor();

        $this->assertEquals(
            'Send Failed: Sender name not provided.',
            $mailerSenderNotSet->process(),
            'Sender name check (name not set) error.'
        );

        $_POST = [
            'from'    => 'john.smith@example.com',
            'name'    => '',
            'message' => 'Some message text.'
        ];

        $mailerSenderEmpty = new WebformProcessor();

        $this->assertEquals(
            'Send Failed: Sender name not provided.',
            $mailerSenderEmpty->process(),
            'Sender name check (name empty) error.'
        );
    }

    /**
     * Test send() fails when message is not provided.
     *
     * @return void
     * @since  ver 1.0.0
     *
     * @author Caspar Green
     */
    public function testSendFailsWhenMessageNotProvided(): void
    {
        $_POST = [
            'from' => 'john.smith@example.com',
            'name' => 'John Smith'
        ];

        $mailerMessageNotSet = new WebformProcessor();

        $this->assertEquals(
            'Send Failed: Message not provided.',
            $mailerMessageNotSet->process(),
            'Message check (message not set) error.'
        );

        $_POST = [
            'from'    => 'john.smith@example.com',
            'name'    => 'John Smith',
            'message' => ''
        ];

        $mailerMessageEmpty = new WebformProcessor();

        $this->assertEquals(
            'Send Failed: Message not provided.',
            $mailerMessageEmpty->process(),
            'Message check (message empty) error.'
        );
    }

    public function testTendSucceedsWhenNoErrors(): void
    {
        $_POST = [
            'from'    => 'john.smith@example.com',
            'name'    => 'John',
            'message' => 'My message to you.'
        ];

        $mailer = new WebformProcessor();

        $this->assertEquals(
            'Send Succeeded.',
            $mailer->process(),
            'Send failed without cause.'
        );
    }
}
