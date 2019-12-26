<?php
/**
 * Webform Main Class.
 *
 * @package Webforms
 * @version 1.0.0-Alpha1
 * @author  Caspar Green
 * @since   ver 1.0.0
 */

namespace Webforms;

class Webform
{
    const SUBJECT = 'Website Form Submission';

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

    public function __construct()
    {
        $this->postData   = $_POST;
        $this->to         = getenv('RECIPIENT');
        $this->from       = getenv('USER_EMAIL');
        $this->senderName = $this->getSenderName();
        $this->replyTo    = $this->getReplyToAddress();
        $this->message    = $this->getMessage();
    }

    public function send(): string
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

        $message = $this->prepareMessage();

        $success = mail(
            $this->to,
            self::SUBJECT,
            $this->message,
            [
                'from'     => $this->from,
                'reply-to' => $this->replyTo
            ]
        );

        return 'Send Succeeded.';
    }

    private function getReplyToAddress(): string
    {
        if ( ! isset($this->postData['from']) || empty($this->postData['from'])) {
            return '';
        }

        return filter_var($this->postData['from'], FILTER_SANITIZE_EMAIL);
    }

    private function getMessage(): string
    {
        if ( ! isset($this->postData['message']) || empty($this->postData['message'])) {
            return '';
        }

        return filter_var($this->postData['message'], FILTER_SANITIZE_STRING);
    }

    private function getSenderName(): string
    {
        if ( ! isset($this->postData['name']) || empty($this->postData['name'])) {
            return '';
        }

        return filter_var($this->postData['name'], FILTER_SANITIZE_STRING);
    }

    private function prepareMessage(): string
    {
        $message = $this->senderName . " wrote:\r\n";
        $message .= $this->message;

        return $message;
    }
}
