<?php

namespace PabloTejada\MailgunOptions\Tests\Unit;

use Illuminate\Support\Arr;
use PabloTejada\MailgunOptions\Tests\EmailNotification;
use PabloTejada\MailgunOptions\Tests\TestCase;

class MailgunOptionsTest extends TestCase
{
    /**
     * @dataProvider booleanFlagProvider
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
            [['attachVariables' => ['o' => 'one']], 'X-Mailgun-Variables', '{"o":"one"}'],
            [['track' => false], 'X-Mailgun-Track', 'no'],
            [['trackClicks' => false], 'X-Mailgun-Track-Clicks', 'no'],
            [['trackOpens' => false], 'X-Mailgun-Track-Opens', 'no'],
            [['tags' => ['TestMail', 'TwoMail']], 'X-Mailgun-Tag', 'TestMail'],
        ];
    }
}
