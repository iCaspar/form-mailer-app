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
        $dotenv = Dotenv::createImmutable(dirname(__DIR__));
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

        $_SERVER['HTTP_X_ICASPAR_KEY'] = getenv('FORM_KEY');

        $_POST = [
            'from'    => 'jsmith@example.com',
            'name'    => 'John Smith',
            'message' => 'Some message text.'
        ];
    }

    public function testProcessExitsSilentlyWhenAccessKeyNotProvided(): void
    {
        unset($_SERVER['HTTP_X_ICASPAR_KEY']);

        $processor = new WebformProcessor($this->mailer);

        $this->mailer->shouldNotReceive('send');

        $this->assertEquals(
            '',
            $processor->process(),
            'Process not terminated when no key provided.'
        );
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
        unset($_POST['from']);

        $processor = new WebformProcessor($this->mailer);

        $this->mailer->shouldNotReceive('send');

        $this->assertEquals(
            'Send Failed: Sender email not provided.',
            $processor->process(),
            'Sender email check (sender not set) error.'
        );

        $_POST['from'] = '';

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
        unset($_POST['name']);

        $processor = new WebformProcessor($this->mailer);

        $this->mailer->shouldNotReceive('send');

        $this->assertEquals(
            'Send Failed: Sender name not provided.',
            $processor->process(),
            'Sender name check (name not set) error.'
        );

        $_POST['name'] = '';

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
        unset($_POST['message']);

        $processor = new WebformProcessor($this->mailer);

        $this->mailer->shouldNotReceive('send');

        $this->assertEquals(
            'Send Failed: Message not provided.',
            $processor->process(),
            'Message check (message not set) error.'
        );

        $_POST['message'] = '';

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
