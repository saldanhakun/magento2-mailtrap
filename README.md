# magento2-mailtrap

Integrate [mailtrap.io](https://mailtrap.io) with Magento CE 2.4+ to simplify e-mail testing.

**Note:** At this version, the integration uses the SMTP interface of MailTrap, not the API.

## Requirements

- Magento Community Edition 2.4+ (tested with 2.4.7-p4)
- PHP 8.0 (tested with 8.3)
- Mailtrap Account, either free or paid
- Mailtrap agent SMTP username
- Mailtrap agent SMTP password

## How to install

After installing Magento 2, run the following commands from your Magento 2 root directory:

```
composer require saldanhakun/magento2-mailtrap
php bin/magento cache:flush
```

## Contribute

Feel free to **fork** and contribute to this module. Simply create a pull request and we'll review and merge your changes to master branch.
The original project seemed abandoned and we needed a Magento 2.4+ compatible integration with Mailtrap.

## About Uttara E-commerce
Uttara is a e-commerce consultant agency located in Brazil. For more information, please visit [uttara.com.br](https://www.uttara.com.br/).

## About Codepeak

Codepeak is a Magento consultant agency located in Sweden. For more information, please visit [codepeak.se](https://codepeak.se). This project is a fork of the (abandoned) [codepeak/magento2-mailtrap](https://github.com/codepeak/magento2-mailtrap) published on GitHub.

