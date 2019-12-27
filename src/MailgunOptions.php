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
     * Enables or disables DKIM signatures
     * 
     * @param bool $enable
     *
     * @return $this
     */
    protected function dkimSignature($enable = true)
    {
        $this->addOption('Dkim', $enable ? 'yes' : 'no');

        return $this;
    }

    /**
     * Schedule the email to be delivered at a later time
     *
     * @param \DateTime $dateTime Future date. Should not more than 3 days in the future
     */
    protected function deliverBy(\DateTime $dateTime)
    {
        $this->addOption('Delivery-By', $dateTime->format(\DateTime::RFC2822));
    }

    /**
     * Sends the email in test mode. The email will be accepted by the API but it won't be delivered to the recipient
     */
    protected function testMode()
    {
        $this->addOption('Drop-Message', 'yes');
    }

    /**
     * Attach variables to the message
     *
     * @param array $vars
     *
     * @return $this
     */
    protected function variables(array $vars)
    {
        $this->addOption('Variables', json_encode($vars));

        return $this;
    }

    /**
     * Attach recipient specific variables
     *
     * @param array $vars
     *
     * @return $this
     */
    protected function recipientVariables(array $vars)
    {
        $this->addOption('Recipient-Variables', json_encode($vars));

        return $this;
    }

    /**
     * Adds an option for the email
     *
     * @param string $name  Option name
     * @param string $value Option value
     */
    private function addOption($name, $value)
    {
        $this->withSwiftMessage(
            function (\Swift_Message $message) use ($value, $name){
                foreach (Arr::wrap($value) as $singleValue) {
                    $message->getHeaders()->addTextHeader("X-Mailgun-{$name}", $singleValue);
                }
            });
    }
}
