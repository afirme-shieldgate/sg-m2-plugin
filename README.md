# ShieldGate Magento module

This module is a solution that allows Magento users to easily process payments with ShieldGate.

## Download and Installation

**1. Execute this command for install our package:**

Install the latest version.  `composer require shieldgate/payment-gateway`

Install a specific version.  `composer require shieldgate/payment-gateway:2.2.1`

Once the installation finished, continue with the next commands in your bash terminal.


**2. Update dependency injection:**

`php bin/magento setup:di:compile`


**3. Update modules registry:**

`php bin/magento setup:upgrade`


**Optional.- This command is optional for production environments:**

`php bin/magento setup:static-content:deploy`


Now you can see the ShieldGate settings in this path `Stores > Configuration > Sales > Payment Methods` on your Magento admin dashboard.


## Maintenance
If you need update the plugin to latest version execute: `composer update shieldgate/payment-gateway` or `composer require shieldgate/payment-gateway:2.2.1` for specific version.

## Webhook Notifications and Order Updates
Every time a transaction changes their status you will get an HTTP POST request from Shieldgate to your webhook.

The URL that will be used for the order updates via webhook is:
`https://magentodomain.com/rest/V2/webhook/shieldgate`

This URL will be configured on Shieldgate.
