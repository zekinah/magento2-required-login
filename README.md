# Magento 2 Required Login

This module requires the customer to login first before accessing the whole site.

## Features

__Backend__
* Go to Store > Configuration > Zone > Customer Required Login
    * General
        * Module Enable _(On and Off the module)_
    * Configuration
        * Base Target URL _(default url of login site)_
        * White List CMS Pages
    * Warning Message
        * Notification _(Output a notification when the direct access on the other page was triggered.)_

__Default Whitelisted Pages and Action__
* Customer's Registration Page
* Forgot Password Page
* Administrator Login Page
* Logout Redirection
* Stripe Webhooks

## Installation

Copy the content of the repository to the app/code/Zone/RequiredLogin/ folder

Enable module:
```
php bin/magento module:enable Zone_RequiredLogin
```

Disable module: (Optional)
```
php bin/magento module:disable Zone_RequiredLogin --clear-static-content
```

Update system:
```
php bin/magento setup:upgrade
php bin/magento cache:flush
php bin/magento cache:clean
```

## Debugging
* To check the values setting:
    * Disable the module through backend, and visit [yoursite.com]/requiredlogin/index/config
* To check the page that the customers trying to access directly:
    * Go to var/log/ and open system.log and you will see, `Zone_RequiredLogin Blocked : [url]`

## Author

* **[Zekinah Lecaros](https://www.zekinahlecaros.com/)** - *Initial work* - 

## License

[Open Software License (OSL 3.0)](https://github.com/zekinah/magento2-required-login/blob/master/LICENSE)
