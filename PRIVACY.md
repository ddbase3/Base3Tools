# Base3Tools privacy and processing inventory

Review: 2026-10-26. Scope: supplied `Base3Tools/src` snapshot and the new
`base3.json` module declaration. This document describes observed technical
behavior, not a universal deployment privacy notice or a compliance claim.

## Roles, purposes, categories and entry points

Base3Tools provides optional utilities for mail delivery, notifications, file
operations, status, script locks, downloading, search and desktop integrations.
The application that instantiates an implementation determines its purpose,
controller/processor roles, data origin and authorized users. Depending on
usage, caller-provided data may contain account identifiers, email addresses,
message bodies, attachments, search terms, file paths and document content.
`Base3ToolsPlugin::init()` only registers its plugin instance.

## Storage, persistence and processing boundaries

`Mailer/Full/FullMailer.php` reads optional mailer configuration, including an
SMTP host, username and password, and passes message fields and attachments to
PHPMailer. The source also captures a mail delivery debug string in its
instance property. File and status utilities have filesystem and session
implementations, so persistence depends on the implementation the application
selects and the paths/session provider it supplies. No universal storage
backend, schema, database table, queue, TTL, backup policy or log retention can
be established for this module from this source snapshot. A manifest file
contains module metadata, not user records.

## Recipients, security, transfer and retention

When selected, `FullMailer` sends content to its configured SMTP server and
message recipients. `Notification/Pushfleet/PushfleetNotification.php` contains
only commented sample HTTP code; its current `send()` method does not perform
that request. Other utilities must be assessed at their actual call sites.
The supplied `FullMailer` source includes an SMTP setting disabling TLS peer
and hostname verification; this is a material security limitation and must be
reviewed by deployments using that implementation. The actual external
recipients, processor agreements, security configuration and international
transfer arrangements depend on the host. No module-wide cleanup, erasure,
export, correction or backup re-deletion mechanism can be established.

## Rights, controls and unresolved deployment facts

Callers and hosting services must enforce authorization and implement relevant
subject-rights procedures, including deletion of active records and copies
where applicable. The module manifest does not change data flows, retention
behavior, recipient selection or existing permissions. Before deployment,
verify which implementations are active, destinations and credentials, logs,
sessions, file ownership and permissions, retention, backups, accessibility,
consent applicability and international transfer conditions. Unreviewed source
and external services remain explicit gaps.

References: `src/Base3ToolsPlugin.php`, `src/Mailer/Full/FullMailer.php`,
`src/Notification/Pushfleet/PushfleetNotification.php`, `src/File`, `src/Status`,
`src/Downloader`, `README.md`.

Change history: 2026-10-26, initial manifest-related technical inventory.
