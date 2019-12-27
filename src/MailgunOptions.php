<?php

namespace PabloTejada\MailgunOptions;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Arr;

/**
 * Trait MailgunOptions
 *
 * @package PabloTejada\MailgunOptions
 * @mixin Mailable
 */
trait MailgunOptions
{
    /**
     * Adds tags to the email
     *
     * @param mixed ...$tags
     *
     * @return $this
     */
    protected function tags(...$tags)
    {
        if (count($tags) === 1 && is_array($tags[0])) {
            $tags = $tags[0];
        }

        $this->addOption('Tag', $tags);
        return $this;
    }

    /**
     * Toggles tracking when the email is open and links are clicked
     *
     * @param bool $enable
     *
     * @return $this
     */
    protected function track($enable = true)
    {
        $this->addOption('Track', $enable ? 'yes' : 'no');
        return $this;
    }

    /**
     * Toggles tracking when the email message is opened
     *
     * @param bool $enable
     *
     * @return $this
     */
    protected function trackOpens($enable = true)
    {
        $this->addOption('Track-Opens', $enable ? 'yes' : 'no');
        return $this;
    }

    /**
     * Toggles tracking when links in the email are clicked
     * Note: Links will only be re-written or tracked in HTML messages
     *
     * @param bool $enable
     *
     * @return $this
     */
    protected function trackClicks($enable = true)
    {
        $this->addOption('Track-Clicks', $enable ? 'htmlonly' : 'no');
        return $this;
    }

    /**
     * Attach variables to the message
     *
     * @param array $vars
     *
     * @return $this
     */
    protected function attachVariables(array $vars)
    {
        $this->addOption('Variables', json_encode($vars));
        return $this;
    }

    /**
     * Adds an option for the email
     * @param string $name Option name
     * @param string $value Option value
     */
    private function addOption($name, $value)
    {
        $this->withSwiftMessage(function (\Swift_Message $message) use ($value, $name) {
            foreach (Arr::wrap($value) as $singleValue){
                $message->getHeaders()->addTextHeader("X-Mailgun-{$name}", $singleValue);
            }
        });
    }
}
