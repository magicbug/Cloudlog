[![Cypress Tests](https://github.com/magicbug/Cloudlog/actions/workflows/cypress-tests.yml/badge.svg)](https://github.com/magicbug/Cloudlog/actions/workflows/cypress-tests.yml)

# Cloudlog
> Important: Only accepting PRs on the "dev" branch.

Cloudlog is a self-hosted PHP application that allows you to log your amateur radio contacts anywhere. All you need is a web browser and active internet connection.

While Cloudlog as started by Peter Goodhall, MM9SQL, although since has received a lot of community code contributions. If you would like to contribute to Cloudlog please see the [Contributing](#contributing) section below.

Website: [https://cloudlog.org](https://cloudlog.org)

## Requirements

- Linux based Operating System
- Apache (Nginx should work)
- PHP 8.5 (current stable release)
- MySQL (MySQL 5.7 or higher)

Notes

- If you want to log microwave QSOs you will need to use a 64bit operating system.

## Setup

Installation and the operator guide are at [docs.cloudlog.org](https://docs.cloudlog.org/).

## Docker

Cloudlog ships a Docker setup, and it is the one used for development. Development is on the `dev` branch. The full notes are in [Installing Cloudlog with Docker](https://docs.cloudlog.org/Installing-Cloudlog-with-Docker/).

Install [Docker](https://docs.docker.com/get-docker/) with Compose, then:

```bash
git clone -b dev https://github.com/magicbug/Cloudlog.git
cd Cloudlog
cp .env.sample .env
docker compose up
```

Open [http://localhost](http://localhost) and log in with `m0abc` / `demo`. Set `BASE_LOCATOR` in `.env` to your own grid before the first start. Leave `MYSQL_HOST` as `db`, which is the database container's name.

## Running Cypress Tests Locally

Start the Docker setup above, then from the same folder:

```bash
npm install cypress
npx cypress run
```

## Support

Cloudlog has two support systems for code issues use Github issues, however if you have general issues with setting up your server please use our general discussion forum [https://github.com/magicbug/Cloudlog/discussions](https://github.com/magicbug/Cloudlog/discussions).

## Security Vulnerabilities

If you discover a security vulnerability within Cloudlog, please send an e-mail to Peter Goodhall, MM9SQL via [peter@magicbug.co.uk](mailto:peter@magicbug.co.uk). All security vulnerabilities will be promptly addressed.

## Want Cloudlog Hosting?

If you would prefer not to setup Cloudlog yourself [magicbug](https://magicbug.co.uk) offer hosted solutions, this is priced at £4 a month at the moment and they take care of keeping it updated.

## Contributing

If you would like to contribute in any way to Cloudlog, it is most appreciated. This has been developed in free time, help coding new features or writing documentation is always useful.

Please note that Cloudlog was built using [Codeigniter](https://www.codeigniter.com/userguide3/) version 3 and uses [Bootstrap 5](https://getbootstrap.com/docs/5.3/getting-started/introduction/) as the frontend toolkit.

We also [HTMX](https://htmx.org/) for AJAX requests and [jQuery](https://jquery.com/) for some of the frontend functionality. We use [Font Awesome](https://fontawesome.com/) for icons.

At the moment we use [Cypress](https://www.cypress.io/) for end-to-end testing.

When submitting PRs please make sure code is commented and includes one feature only, multiple features or bug fixes will not be accepted. Please include a description of what your PR does and why it is needed.

## Credits

Thanks to Andy (VE7CXZ), Gavin (M1BXF), Graham (W5ISP), Robert (M0VFC), Corby (K0SKW), Andy (GI0VGV), Sarah (DM4NA), Tony (G0WFV), Kim (DG9VH), Michael (G7VJR), Andreas (LA8AJA), Matthias (DL9MJ), Thomas (DO2TWE), Pat (KT3PJ), Flo (DF2ET), Joerg (DJ7NT) and Fabian (HB9HIL) for contributing code or help to Cloudlog.

## Patreons & Donors

Cloudlog is supported by Patreon and donations via PayPal, thanks to the following people:

Paul (M0TZO), Tim (G4VXE), Paul (N8HM), Michelle (W5NYV), Mitchell (AD0HJ), Dan (M0TCB), Martin (DK3ML), Juan Carlos (EA5WA), Iain (M0PCB), Charlie (GM1TGY), Ondrej (OK1CDJ), Trystan (G0KAY), Oliver (DL6KBG), Volkmar Schirmer, Jordan (M0PIR), Thomas Ziegler, Mathis (DB9MAT), Ken (VE3HLS), Tyler (WL7T), Jeremy Taylor, Ben Kuhn, Eric Thresher, Michael Cullen, Juuso (OH1JW), Anthony Castiglia, Fernando Ramirez-Ferrer, Robert Dixon, Mark Percival, Julia (KV1V), Timo Tomasini, Ant (NU1U), Christopher Williams, Danny Barnes, Vic, Tom (M0LTE), smurphboy, Lars (SM0TGU), Theo (PD9DP), Stefan (SM0RGM). Peter (G0ABI), Lou (KI5FTY), Michael (DG3NAB), Dragan (4O4A), minorsecond, Emily (W7AYQ), Steve (M0SKM), Rob (M0VFC), Doug (WA6L), Petr (OK1PKR), Fabian (HB9HIL), Daniel (OK2VLK), John (M5JFS), Johnathan (2E0DEF), Peter (M0LNB), Bernard (EI8FDB).

If you'd like to donate to Cloudlog to help allow @magicbug spend less time doing commercial work and more time coding Cloudlog then you can donate via [PayPal](https://paypal.me/PGoodhall), [Github Sponsor](https://github.com/sponsors/magicbug) or become a [Patreon](https://www.patreon.com/2m0sql)
