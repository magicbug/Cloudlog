# Cloudlog

Cloudlog is a self-hosted PHP application that allows you to log your amateur radio contacts anywhere. All you need is a web browser and active internet connection.

Website: [http://www.cloudlog.co.uk](http://www.cloudlog.co.uk)

## Requirements
* Linux based Operating System
* Apache (Nignx should work)
* PHP (Version 7 or higher) & MySQL

You will also needthe following PHP modules installed

php-curl, php-mbstrings, php-xml

## Versions

* Master - Current working copy (Commit wise frozen till v2 is ready for release, unless bug fixes needed)
* cloudlog-v2 - This is the current development copy, while to work on bringing multiclient to Cloudlog without breaking users stable coplies, master now has a freeze on it.

## Setup

You can set up the basics of Cloudlog by opening /install in your browser. Please note theres an issue with the demo account creation (password invalid) so after this process you must disable auth via the config.php file and manually create yourself a user in till this issue is fixed.

More information can be found in the [wiki](https://github.com/magicbug/Cloudlog/wiki).

Cloudlog now has a [Change Log](https://github.com/magicbug/Cloudlog/wiki/Change-Log) to go along with the commit history please consult this when updating.

## Support

Cloudlog support is handled via Issues on GitHub.

## CAT Control

Cloudlog supports pushing radio information just like you would with a desktop operating system this is via a desktop app called [CloudlogCAT](https://github.com/magicbug/CloudlogCAT/releases) this application uses Omni-Rig thus supports most of the radios on the market.

If you use Linux, Mac or just hate Omni-Rig then Tobias (DL4TMA) has made a script called [cloudlog-rigctl-interface](https://github.com/Manawyrm/cloudlog-rigctl-interface) this interfaces rigctl to Cloudlogs CAT API. This script requires PHP-CLI to be installed.  If you would like a pure Bash version, Tony (G0WFV) has you covered with [CloudlogBashCat](https://github.com/g0wfv/CloudlogBashCat) which also synchronises Cloudlog with rigctld.

## SatPC32 Interface

If your into satellite operations I have written a application [SatPC32 to Cloud Interface](https://github.com/magicbug/SatPC32-To-Cloudlog) which allows automatic population of satellite fields, Cloudlog just sees this as another radio interface.

## QSL Card Labels

I've started building out some scripts to generate labels for sticking on QSL Cards, at the moment it supports 24 label sheets, but theres no reason for it not to support more, these can be found at [Cloudlog-Labels](https://github.com/magicbug/cloudlog-labels)

## Want Cloudlog Hosting?

If you would prefer not to setup Cloudlog yourself [magicbug](https://magicbug.co.uk) offer hosted solutions, this is priced at £4 a month at the moment and they take care of keeping it updated.

## Contributing

If you would like to contributing in anyway to Cloudlog then its most appreciated, this has been developed in free time, help coding new features or writing documentation is always useful.

Please note that Cloudlog was built using [Codeigniter](https://www.codeigniter.com/docs) version 3 and uses Bootstrap 4 for the user CSS framework documentation is available for this when building components.

## Credits

Thanks to Andy (VE7CXZ), Gavin (M1BXF), Graham (W5ISP), Robert (M0VFC), Corby (K0SKW), Andy (GI0VGV), Tobias (DL4TMA), Tony (G0WFV), Kim (DG9VH), Michael (G7VJR) for contributing code or help to Cloudlog.

## Patreons & Donors

Cloudlog is supported by Patreon and donations via PayPal, thanks to the following people:

Paul (M0TZO), Tim (G4VXE), Paul (N8HM), Michelle (W5NYV), Mitchell (AD0HJ), Dan (M0TCB), Martin (DK3ML), Juan Carlos (EA5WA), Iain (M0PCB), Charlie (GM1TGY)

If you'd like to donate to Cloudlog to help allow @magicbug spend less time doing commerical work and more time coding Cloudlog then you can donate via [PayPal](https://paypal.me/PGoodhall) or become a [Patreon](https://www.patreon.com/2m0sql)
