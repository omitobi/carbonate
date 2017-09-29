[![Build Status](https://travis-ci.org/omitobi/carbonate.svg?branch=master)](https://travis-ci.org/omitobi/carbonate)
[![Latest Stable Version](https://poser.pugx.org/omitobisam/carbonate/version)](https://packagist.org/packages/omitobisam/carbonate)
[![Total Downloads](https://poser.pugx.org/omitobisam/carbonate/downloads)](https://packagist.org/packages/omitobisam/carbonate)
[![Latest Unstable Version](https://poser.pugx.org/omitobisam/carbonate/v/unstable)](//packagist.org/packages/omitobisam/carbonate)
[![Monthly Downloads](https://poser.pugx.org/omitobisam/carbonate/d/monthly)](https://packagist.org/packages/omitobisam/carbonate)


## Carbonate - a muscle to PHP Carbon with Collections
###### - dev-master or bleeding edge
#### Installation
- `composer require omitobisam/carbonate`

If the above does not work then simply include the latest release version as in:

- `composer require omitobisam/carbonate ^0.2.1`

For the `dev-master` simply change the version to `dev-master`

Another option is to clone the dev-master branch and you can have the bleeding version

#### Requirements
Minimum: PHP 5.6.4

#### Usage:

```$php
$mydate = new Carbonate('2017-09-01');

$mydate->thisMonth();
$mydate->getDatesOfDaysInMonth(['Monday', 'Saturday']);
$mydate->everyWeekend();
$mydate->everyWeekDays();
$mydate->everyDay('Monday');
$mydate->random(2);
$mydate->randomOne();
$mydate->anyMonday();
$mydate->anyTuesday();
$mydate->anyWednesday();
$mydate->anyThursday();
$mydate->anyFriday();
$mydate->anySaturday();
$mydate->anySunday();
$mydate->any();
$mydate->anyOne('Monday');
$mydate->stringify(collect([Carbonate::now(), Carbonate::now()->addDay(1)]));
$mydate->carbonate(['today', 'tomorrow']);
$mydate->weekends(Carbonate::now()->addDay(7);

```

#### Info
Most of the functions are not YET adequately tested and there is heavy changes in order to ensure they are indeed useful so please use with care (not in production)

## Contributions
##### More suggestions of useful functionality is welcome to make this an indeed useful work

## version pre-Alpha 0.0.1a