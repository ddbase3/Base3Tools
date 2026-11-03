# Base3Tools
Plugin with Base3Framework tools.

The module's `base3.json` declares its portable BASE3 name and namespace. The
initial manifest version is `1.0.0`; this is not a reconstruction of past
release tags. See [PRIVACY.md](PRIVACY.md) for source-verified processing
boundaries, including configurable mail delivery and file utilities.

## Unit tests

The PHPUnit suite lives under `test/` with its own isolated test bootstrap.
Run it from the BASE3 Framework root with PHPUnit 11.5 or 12.5:

```sh
phpunit -c plugin/Base3Tools/phpunit.xml
```

To collect line coverage, enable a PHP coverage driver (Xdebug or PCOV) and run:

```sh
XDEBUG_MODE=coverage phpunit -c plugin/Base3Tools/phpunit.xml --coverage-text
```

The tests do not require a live database, SMTP server, or external notification
service. The bundled third-party PHPMailer libraries are not independently
tested; unit coverage focuses on Base3Tools behavior and integration boundaries.

`MobileDetect` safely handles requests without a User-Agent; its regression tests
cover classification, version lookup, and mobile-only HTTP headers.
