<?php
/**
 * Tests for Webform Class.
 *
 * @package Webforms\Tests\Unit
 * @author  Caspar Green
 * @since   ver 1.0.0
 */

namespace Webforms\Tests\Unit;

use Webforms\Webform;
use PHPUnit\Framework\TestCase;

class WebformTest extends TestCase
{
    public function setUp(): void
    {
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

        $mailerSenderNotSet = new Webform();

        $this->assertEquals(
            'Send Failed: Sender email not provided.',
            $mailerSenderNotSet->send(),
            'Sender email check (sender not set) error.'
        );

        $_POST = [
            'from'    => '',
            'name'    => 'John Smith',
            'message' => 'Some message text.'
        ];

        $mailerSenderEmpty = new Webform();

        $this->assertEquals(
            'Send Failed: Sender email not provided.',
            $mailerSenderEmpty->send(),
            'Sender email check (sender empty) error.'
        );
    }

    public function testSendFailsWhenFormSenderNameNotProvided(): void
    {
        $_POST = [
            'from' => 'john.smith@example.com',
            'message' => 'Some message text.'
        ];

        $mailerSenderNotSet = new Webform();

        $this->assertEquals(
            'Send Failed: Sender name not provided.',
            $mailerSenderNotSet->send(),
            'Sender name check (name not set) error.'
        );

        $_POST = [
            'from' => 'john.smith@example.com',
            'name' => '',
            'message' => 'Some message text.'
        ];

        $mailerSenderEmpty = new Webform();

        $this->assertEquals(
            'Send Failed: Sender name not provided.',
            $mailerSenderEmpty->send(),
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

        $mailerMessageNotSet = new Webform();

        $this->assertEquals(
            'Send Failed: Message not provided.',
            $mailerMessageNotSet->send(),
            'Message check (message not set) error.'
        );

        $_POST = [
            'from'    => 'john.smith@example.com',
            'name'    => 'John Smith',
            'message' => ''
        ];

        $mailerMessageEmpty = new Webform();

        $this->assertEquals(
            'Send Failed: Message not provided.',
            $mailerMessageEmpty->send(),
            'Message check (message empty) error.'
        );
    }
}
