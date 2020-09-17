# Magento 2 Required Login


## Features


## Installation

Copy the content of the repo to the app/code/Zone/RequireLogin/ folder

Enable module:
```
php bin/magento module:enable Zone_RequireLogin
```

Disable module: (Optional)
```
php bin/magento module:disable Zone_RequireLogin --clear-static-content
```

Update system:
```
php bin/magento setup:upgrade
php bin/magento cache:flush
php bin/magento cache:clean
```
## Author

* **[Zekinah Lecaros](https://www.zekinahlecaros.com/)** - *Initial work* - 

## License

[MIT](http://opensource.org/licenses/MIT)
