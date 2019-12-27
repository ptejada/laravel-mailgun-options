<?php

namespace PabloTejada\MailgunOptions\Tests\Unit;

use Illuminate\Support\Arr;
use PabloTejada\MailgunOptions\Tests\EmailNotification;
use PabloTejada\MailgunOptions\Tests\TestCase;

class MailgunOptionsTest extends TestCase
{
    /**
     * @dataProvider booleanFlagProvider
     * @covers \PabloTejada\MailgunOptions\MailgunOptions::tags
     * @covers \PabloTejada\MailgunOptions\MailgunOptions::trackOpens
     * @covers \PabloTejada\MailgunOptions\MailgunOptions::track
     * @covers \PabloTejada\MailgunOptions\MailgunOptions::trackClicks
     * @covers \PabloTejada\MailgunOptions\MailgunOptions::dkimSignature
     * @covers \PabloTejada\MailgunOptions\MailgunOptions::deliverBy
     * @covers \PabloTejada\MailgunOptions\MailgunOptions::testMode
     * @covers \PabloTejada\MailgunOptions\MailgunOptions::variables
     * @covers \PabloTejada\MailgunOptions\MailgunOptions::recipientVariables
     * @covers \PabloTejada\MailgunOptions\MailgunOptions::addOption
     */
    public function test_options($options, $header, $expectedValue = 'yes')
    {
        $message = new EmailNotification(
            Arr::wrap($options), function (\Swift_Message $message) use ($expectedValue, $header){
            $this->assertTrue($message->getHeaders()->has($header), "Expected header {$header}");
            $this->assertEquals($expectedValue, $message->getHeaders()->get($header)->getFieldBody());
        });

        // Send the email
        \Mail::send($message);
    }

    public function booleanFlagProvider()
    {
        return [
            ['track', 'X-Mailgun-Track'],
            ['trackClicks', 'X-Mailgun-Track-Clicks', 'htmlonly'],
            ['trackOpens', 'X-Mailgun-Track-Opens'],
            ['dkimSignature', 'X-Mailgun-Dkim'],
            ['testMode', 'X-Mailgun-Drop-Message'],
            [['deliverBy' => new \DateTime('2019-12-27 09:48:50 EST')], 'X-Mailgun-Delivery-By', 'Fri, 27 Dec 2019 09:48:50 -0500'],
            [['variables' => ['o' => 'one']], 'X-Mailgun-Variables', '{"o":"one"}'],
            [['recipientVariables' => ['joe@doe.com' => ['o' => 'one']]], 'X-Mailgun-Recipient-Variables', '{"joe@doe.com":{"o":"one"}}'],
            [['track' => false], 'X-Mailgun-Track', 'no'],
            [['trackClicks' => false], 'X-Mailgun-Track-Clicks', 'no'],
            [['trackOpens' => false], 'X-Mailgun-Track-Opens', 'no'],
            [['tags' => ['TestMail', 'TwoMail']], 'X-Mailgun-Tag', 'TestMail'],
        ];
    }
}
