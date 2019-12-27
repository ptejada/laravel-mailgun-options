# Laravel Mailgun Options

The package enhances the builtin Laravel Mailgun driver with helpers to manage the Mailgun specific features in a
per email message basis. The following options can be configures with the helper:

1. Toggle tracking email opens.
2. Toggle tracking email link clicks.
3. Add email tags.
4. Attach variables to email.

## Installation

Install package via composer
```
composer require ptejada/laravel-mailgun-options
```

## Usage

Add the `PabloTejada\MailgunOptions\MailgunOptions` trait to the mailable class you want to configure. Within the 
`build` method of the mailable you can configure the Mailgun options:
- **track()** - Track both email opens and clicks. 
- **trackClicks()** - Track when links in the email ar clicked.
- **trackOpens()** - Track when the email is opened.
- **tags()** - Adds one or more tags to the email.
- **attachVariables** - Attach variables to the email.


