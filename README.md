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
php bin/magento module:enable Saldanhakun_Mailtrap
php bin/magento setup:di:compile
php bin/magento cache:flush
```

### Using a specific version

This project is published on GitHub and syncronized with Packagist.org, so on every version update I release
composer will be able to update it automatically. Semantic version naming will be adopted, so it should be
safe to allow version bumping and even minor/major updates.

You may test the pre-release with `composer require saldanhakun/magento2-mailtrap:dev-master`.
You may also lock or profere a specfific version, depending on your project stability requirements, such as `composer req saldanhakun/magento2-mailtrap:=1.1.0` or `composer req saldanhakun/magento2-mailtrap:^1.1`
It is my intent to keep the 1.x branch compatible with Magento CE 2.4 and Magento CE 2.5 (no support, yet).

Perhaps I shall implement backward support for 2.3, if you need it, get in touch.

### Using with other Magento solutions
This plugin was only tested in Magento Community, so I don't known if it works on Adobe Commerce, either on premises or cloud. If you find out it does, please notify me. Or get in touch so we can work together in making it compatible.

## Contribute

Feel free to **fork** and contribute to this module. Simply create a pull request and we'll review and merge your changes to master branch.
The original project seemed abandoned and we needed a Magento 2.4+ compatible integration with Mailtrap.

## About Uttara E-commerce
Uttara is a e-commerce consultant agency located in Brazil. For more information, please visit [uttara.com.br](https://www.uttara.com.br/).

## About Codepeak

Codepeak is a Magento consultant agency located in Sweden. For more information, please visit [codepeak.se](https://codepeak.se). This project is a fork of the (abandoned) [codepeak/magento2-mailtrap](https://github.com/codepeak/magento2-mailtrap) published on GitHub.

