# Laravel Mailgun Options

The package enhances the builtin Laravel Mailgun driver with helpers to manage the Mailgun specific features in a
per email message basis. The following options can be configures with the helper:

1. Toggle tracking email opens.
2. Toggle tracking email link clicks.
3. Add email tags.
4. Attach variables and recipient variables to email.
5. Enable test mode.
6. Schedule email to be delivery in the future.

Refer to the [Send via SMTP](https://documentation.mailgun.com/en/latest/user_manual.html#sending-via-smtp) section of 
the Mailgun API docs for more information.

## Installation

Install package via composer
```
composer require ptejada/laravel-mailgun-options
```

## Usage

Add the `PabloTejada\MailgunOptions\MailgunOptions` trait to any mailable class you want to configure. Within the 
`build` method of the mailable you can configure the Mailgun options using the following helpers:
- **track()** - Track both email opens and clicks. 
- **trackClicks()** - Track when links in the email ar clicked.
- **trackOpens()** - Track when the email is opened.
- **deliverBy()** - Schedules the email to be deliver at a later time.
- **testMode()** - Sends the email in test mode.
- **tags()** - Adds one or more tags to the email.
- **variables** - Attach variables to the email.
- **recipientVariables** - Attach recipient specific variables to the email.
- **dkimSignature** - Toggles the DKIM signatures.

Example mailable class
```php
<?php

namespace App\Email;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PabloTejada\MailgunOptions\MailgunOptions;

class EmailNotification extends Mailable
{
    use Queueable, SerializesModels, MailgunOptions;

    /**
     * @return EmailNotification
     */
    public function build()
    {
        $this->to('pablo@tejada.dev', 'Pablo Tejada');
        
        // Track email opens and clicks
        $this->track();
        
        // Add email tag or campaign    
        $this->tags('Test Campaign');
        
        // Enables the test mode if the app is not in production
        if ( ! app()->environement('production') ) {
        	$this->testMode();
        }

        return $this->markdown('email');
    }    
}

```

## License
Laravel Mailgun Options is open-source software licensed under the [MIT license](LICENSE).
