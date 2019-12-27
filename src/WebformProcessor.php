<?php
/**
 * Webform Main Class.
 *
 * @package Webforms
 * @author  Caspar Green
 * @since   ver 1.0.0
 */

namespace Webforms;

use Swift_Mailer;
use Swift_Message;

class WebformProcessor
{
    const SUBJECT = 'Website Form Submission';

    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var array Data from $_POST.
     */
    private $postData;

    /**
     * @var array|false|string Recipient email address.
     */
    private $to;

    /**
     * @var array|false|string This webmailer's email address.
     */
    private $from;

    /**
     * @var string Reply-to email address.
     */
    private $replyTo;

    /**
     * @var string The form message.
     */
    private $message;

    /**
     * @var string The sender's name.
     */
    private $senderName;

    /**
     * WebformProcessor constructor.
     *
     * @param Swift_Mailer $mailer
     */
    public function __construct(Swift_Mailer $mailer)
    {
        $this->mailer     = $mailer;
        $this->postData   = $_POST;
        $this->to         = getenv('RECIPIENT');
        $this->from       = getenv('USER_EMAIL');
        $this->senderName = $this->getSenderName();
        $this->replyTo    = $this->getReplyToAddress();
        $this->message    = $this->getMessage();
    }

    /**
     * Process a form submission.
     *
     * @return string
     * @since  ver 1.0.0
     *
     * @author Caspar Green
     */
    public function process(): string
    {
        if (empty($this->replyTo)) {
            return 'Send Failed: Sender email not provided.';
        }

        if (empty($this->senderName)) {
            return 'Send Failed: Sender name not provided.';
        }

        if (empty($this->message)) {
            return 'Send Failed: Message not provided.';
        }

        $message = $this->prepareEmail();
        $result = $this->mailer->send($message);

        return 'Send Succeeded: ' . $result;
    }

    /**
     * Get a Reply-to address.
     *
     * @return string
     * @since  ver 1.0.0
     *
     * @author Caspar Green
     */
    private function getReplyToAddress(): string
    {
        if (! isset($this->postData['from']) || empty($this->postData['from'])) {
            return '';
        }

        return filter_var($this->postData['from'], FILTER_SANITIZE_EMAIL);
    }

    /**
     * Get the submitted message.
     *
     * @return string
     * @since  ver 1.0.0
     *
     * @author Caspar Green
     */
    private function getMessage(): string
    {
        if (! isset($this->postData['message']) || empty($this->postData['message'])) {
            return '';
        }

        return filter_var($this->postData['message'], FILTER_SANITIZE_STRING);
    }

    /**
     * Get the form submitter's name.
     *
     * @return string
     * @since  ver 1.0.0
     *
     * @author Caspar Green
     */
    private function getSenderName(): string
    {
        if (! isset($this->postData['name']) || empty($this->postData['name'])) {
            return '';
        }

        return filter_var($this->postData['name'], FILTER_SANITIZE_STRING);
    }

    /**
     * Prepare the email for sending.
     *
     * @return string
     * @since  ver 1.0.0
     *
     * @author Caspar Green
     */
    private function prepareEmail(): Swift_Message
    {
        $body = $this->senderName . " wrote:\r\n";
        $body .= $this->message;

        return (new Swift_Message(self::SUBJECT))
            ->setFrom($this->from)
            ->setTo($this->to)
            ->setReplyTo($this->replyTo)
            ->setBody($body);
    }
}
