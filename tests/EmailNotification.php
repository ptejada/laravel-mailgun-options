<?php

namespace PabloTejada\MailgunOptions\Tests;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PabloTejada\MailgunOptions\MailgunOptions;

/**
 * Class EmailNotification
 *
 * @package PabloTejada\MailgunOptions\Tests
 */
class EmailNotification extends Mailable
{
    use Queueable, SerializesModels, MailgunOptions;

    /**
     * @var callable later mail callback
     */
    private $handler;

    /**
     * @var array
     */
    private $options;

    /**
     * EmailNotification constructor.
     *
     * @param array    $options
     * @param callable $handler
     */
    public function __construct(array $options, $handler)
    {
        $this->handler = $handler;
        $this->options = $options;
    }

    /**
     * @return EmailNotification
     */
    public function build()
    {
        $this->to('pablo@tejada.dev', 'Pablo Tejada');

        foreach($this->options as $namedAction => $action) {
            $method = is_numeric($namedAction) ? $action : $namedAction;
            $flag = is_numeric($namedAction) ? true : $action;

            if (method_exists($this, $method)) {
                call_user_func([$this, $method], $flag);
            }
        }

        if ($this->handler) {
            $this->withSwiftMessage($this->handler);
        }

        return $this->markdown('email');
    }
}
