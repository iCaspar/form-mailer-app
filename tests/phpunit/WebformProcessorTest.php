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
use Mockery;
use Swift_Mailer;
use Webforms\WebformProcessor;
use PHPUnit\Framework\TestCase;

class WebformProcessorTest extends TestCase
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;

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
     * Set up before each test.
     *
     * @return void
     * @since  ver 1.0.0
     *
     * @author Caspar Green
     */
    public function setUp(): void
    {
        $this->mailer = Mockery::mock('Swift_Mailer');
    }

    /**
     * Test process() fails when form sender address is not provided.
     *
     * @return void
     * @since  ver 1.0.0
     *
     * @author Caspar Green
     */
    public function testProcessFailsWhenFormSenderAddressNotProvided(): void
    {
        $_POST = [
            'name'    => 'John Smith',
            'message' => 'Some message text.'
        ];

        $processor = new WebformProcessor($this->mailer);

        $this->mailer->shouldNotReceive('send');

        $this->assertEquals(
            'Send Failed: Sender email not provided.',
            $processor->process(),
            'Sender email check (sender not set) error.'
        );

        $_POST = [
            'from'    => '',
            'name'    => 'John Smith',
            'message' => 'Some message text.'
        ];

        $processor = new WebformProcessor($this->mailer);

        $this->mailer->shouldNotReceive('send');

        $this->assertEquals(
            'Send Failed: Sender email not provided.',
            $processor->process(),
            'Sender email check (sender empty) error.'
        );
    }

    /**
     * Test process() fails when form sender's name is not provided.
     *
     * @return void
     * @since  ver 1.0.0
     *
     * @author Caspar Green
     */
    public function testProcessFailsWhenFormSenderNameNotProvided(): void
    {
        $_POST = [
            'from'    => 'john.smith@example.com',
            'message' => 'Some message text.'
        ];

        $processor = new WebformProcessor($this->mailer);

        $this->mailer->shouldNotReceive('send');

        $this->assertEquals(
            'Send Failed: Sender name not provided.',
            $processor->process(),
            'Sender name check (name not set) error.'
        );

        $_POST = [
            'from'    => 'john.smith@example.com',
            'name'    => '',
            'message' => 'Some message text.'
        ];

        $processor = new WebformProcessor($this->mailer);

        $this->mailer->shouldNotReceive('send');

        $this->assertEquals(
            'Send Failed: Sender name not provided.',
            $processor->process(),
            'Sender name check (name empty) error.'
        );
    }

    /**
     * Test process() fails when message is not provided.
     *
     * @return void
     * @since  ver 1.0.0
     *
     * @author Caspar Green
     */
    public function testProcessFailsWhenMessageNotProvided(): void
    {
        $_POST = [
            'from' => 'john.smith@example.com',
            'name' => 'John Smith'
        ];

        $processor = new WebformProcessor($this->mailer);

        $this->mailer->shouldNotReceive('send');

        $this->assertEquals(
            'Send Failed: Message not provided.',
            $processor->process(),
            'Message check (message not set) error.'
        );

        $_POST = [
            'from'    => 'john.smith@example.com',
            'name'    => 'John Smith',
            'message' => ''
        ];

        $processor = new WebformProcessor($this->mailer);

        $this->mailer->shouldNotReceive('send');

        $this->assertEquals(
            'Send Failed: Message not provided.',
            $processor->process(),
            'Message check (message empty) error.'
        );
    }

    /**
     * Test process() should call mailer::send() when No Errors.
     *
     * @return void
     * @since  ver 1.0.0
     *
     * @author Caspar Green
     */
    public function testProcessSucceedsWhenNoErrors(): void
    {
        $_POST = [
            'from'    => 'john.smith@example.com',
            'name'    => 'John',
            'message' => 'My message to you.'
        ];

        $processor = new WebformProcessor($this->mailer);

        $this->mailer
            ->shouldReceive('send')
            ->once()
            ->andReturn(1);

        $this->assertEquals(
            'Send Succeeded: 1',
            $processor->process(),
            'Send failed without cause.'
        );
    }
}
