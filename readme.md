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
$enddate = new Carbonate('2017-10-30);
```

#### Get WeekendDates within two dates

```php
$result = $mydate->everyWeekendDays($enddate);

//
/*
* Result - A collection of dates in the weekend
*/
//

Illuminate\Support\Collection Object ( [items:protected] => Array ( 
[0] => Carbonate\Carbonate Object ( [date] => 2017-09-02 00:00:00.000000 [timezone_type] => 3 [timezone] => UTC ) 
[1] => Carbonate\Carbonate Object ( [date] => 2017-09-03 00:00:00.000000 [timezone_type] => 3 [timezone] => UTC ) 
[2] => Carbonate\Carbonate Object ( [date] => 2017-09-09 00:00:00.000000 [timezone_type] => 3 [timezone] => UTC )  
.......
```

#### Collections method can be further used on the result e.g

```php
// Verify if the last Carbon instance is actually weekend

$mydate->everyWeekendDays($enddate)->last()->isWeekend();
```

#### The result of the operations is shown as transformed from Carbon/Carbonate object to string using `stringify()`


```php
Carbonate::stringify($mydate->everyWeekendDays($enddate));

//
/*
* Result - A collection of dates in the weekends as string
*/
//

Illuminate\Support\Collection Object ( [items:protected] => Array ( 
[0] => 2017-09-02 00:00:00 
[1] => 2017-09-03 00:00:00 
[2] => 2017-09-09 00:00:00 
[3] => 2017-09-10 00:00:00 
[4] => 2017-09-16 00:00:00 
[5] => 2017-09-17 00:00:00 
[6] => 2017-09-23 00:00:00 
[7] => 2017-09-24 00:00:00 
[8] => 2017-09-30 00:00:00 
[9] => 2017-10-01 00:00:00 
[10] => 2017-10-07 00:00:00 
[11] => 2017-10-08 00:00:00 
[12] => 2017-10-14 00:00:00 
[13] => 2017-10-15 00:00:00 
[14] => 2017-10-21 00:00:00 
[15] => 2017-10-22 00:00:00 
[16] => 2017-10-28 00:00:00 
[17] => 2017-10-29 00:00:00 ) )

```

#### A string of dates can be converted whole into Carbonate/Carbon instances:

```php
Carbonate::carbonate(['today', 'tomorrow', '2017-10-30']);


Illuminate\Support\Collection Object ( [items:protected] => Array ( 
[0] => Carbonate\Carbonate Object ( [date] => 2017-10-21 00:00:00.000000 [timezone_type] => 3 [timezone] => Europe/Berlin ) 
[1] => Carbonate\Carbonate Object ( [date] => 2017-10-22 00:00:00.000000 [timezone_type] => 3 [timezone] => Europe/Berlin ) 
[2] => Carbonate\Carbonate Object ( [date] => 2017-10-30 00:00:00.000000 [timezone_type] => 3 [timezone] => Europe/Berlin ) ) )
```

#### Other methods

```php

$mydate->thisMonth();
$mydate->getDatesOfDaysInMonth(['Monday', 'Saturday']);
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
$mydate->stringify([Carbonate::now(), Carbonate::now()->addDay(1)]);
$mydate->carbonate(['today', 'tomorrow']);
$mydate->weekends(Carbonate::now()->addDay(7));

```

#### Info
Most of the functions are not YET adequately tested and there is heavy changes in order to ensure they are indeed useful so please use with care (not in production)

## Contributions
##### More suggestions of useful functionality is welcome to make this an indeed useful work

## version pre-Alpha 0.0.1a