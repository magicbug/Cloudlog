# Cloudlog

Cloudlog is a self-hosted PHP application that allows you to log your amateur radio contacts anywhere. All you need is a web browser and active internet connection.

Website: [http://www.cloudlog.co.uk](http://www.cloudlog.co.uk)

## Requirements

* PHP 5 & MySQL

## Versions

* Master - Current working copy

## Setup

You can set up the basics of Cloudlog by opening /install in your browser. Please note theres an issue with the demo account creation (password invalid) so after this process you must disable auth via the config.php file and manually create yourself a user in till this issue is fixed.

More information can be found in the [wiki](https://github.com/magicbug/Cloudlog/wiki).

## CAT Control

Its possible to interact with your radio if you have a K3, FT-950 or a ICOM radio using a nice application that Rob (M0VFC) has put together called [CloudlogCAT](https://github.com/rmc47/CloudlogCAT). The application is still in active development and lots of new features are due.

## SatPC32 Interface

If your into satellite operations I have written a application [SatPC32 to Cloud Interface](https://github.com/magicbug/SatPC32-To-Cloudlog) which allows automatic population of satellite fields, Cloudlog just sees this as another radio interface.

Code for this is BETA feel free to make modifications and do PRs.

## QSL Card Labels

I've started building out some scripts to generate labels for sticking on QSL Cards, at the moment it supports 24 label sheets, but theres no reason for it not to support more, these can be found at [Cloudlog-Labels](https://github.com/magicbug/cloudlog-labels)

## Contributing

If you would like to contributing in anyway to Cloudlog then its most appreciated, this has been developed in free time, help coding new features or writing documentation is always useful.

Please note that Cloudlog was built using [Codeigniter](https://ellislab.com/codeigniter/user-guide/) version 2 and uses a rather old version of the Bootstrap UI v1.4.0 documentation is available for this when building components.

If you are writing code all javascript and css should be pleased within the js & css folders not within the views where possible. 

## Copyright / Licence

The MIT License (MIT)

Copyright (c) 2016 Peter Goodhall, 2E0SQL

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

## Credits

* Andy (VE7CXZ)    [http://ve7cxz.ca](http://ve7cxz.ca/)
* Gavin (M1BXF)   [http://www.geekshed.co.uk](http://www.geekshed.co.uk/)
* Graham (W5ISP)  [http://www.w5isp.com](http://w5isp.com)
* Robert (M0VFC)  [http://github.com/rmc47](https://github.com/rmc47)
* Corby (K0SKW)	  [http://www.k0skw.net/](http://www.k0skw.net/)

![Clublog Logo](http://www.clublog.org/images/clublog.png "Clublog Logo")

We use the [Clublog](http://www.clublog.org/) cty.xml and APIs within the DXCC elements of Cloudlog. If you're not already a member of Clublog, we recommend you go sign up!

![HamQTH Logo](https://www.hamqth.com/images/hamqth_125x125.png "HamQTH Logo")

Cloudlog supports using the callsign lookup service from [HamQTH](https://www.hamqth.com)