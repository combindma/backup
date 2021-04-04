# Backup Laravel Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/combindma/backup.svg?style=flat-square)](https://packagist.org/packages/combindma/backup)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/combindma/backup/Check%20&%20fix%20styling?label=code%20style)](https://github.com/combindma/backup/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/combindma/backup.svg?style=flat-square)](https://packagist.org/packages/combindma/backup)

## Installation

You can install the package via composer:

```bash
composer require combindma/backup
```

You must publish the config file with:
```bash
php artisan vendor:publish --provider="Combindma\Backup\BackupServiceProvider" --tag="backup-config"
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Combind](https://github.com/combindma)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
