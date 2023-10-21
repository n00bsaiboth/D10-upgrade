# Welcome to upgrading Drupal to version 10 and I'll be your guide for this flight, so sit back an enjoy the flight.

So basically I've been a bit feverish, and the upcoming flu is on my way. But let's get started.So basically the repository itself it's kind of useless, just follow these guidelines. This is a tutorial/demonstaration/step-by-step guide for novice Drupal-developers. 


create a folder/directory called D10-upgrade , then,


```
sudo chmod 777 -R D10-ugrade/ !note, you don't do that on production site
```
 

```
cd D10-upgrade
```


--

## Install Drupal 9:

Run the following command,

### Initialize a drupal9 recipe:

```
lando init \
    --source cwd \
    --recipe drupal9 \
    --webroot web \
    --name D10-upgrade
```


### Create latest drupal9 project via composer:

```
lando composer create-project drupal/recommended-project:9.x tmp && cp -r tmp/. . && rm -rf tmp

```

### Start it up:


```
lando start
```


### Install a site local drush:

```
lando composer require drush/drush

```

### Install drupal:

```
lando drush site:install --db-url=mysql://drupal9:drupal9@database/drupal9 -y

```

now you got a message in the console/terminal that says,

Installation complete: Username: admin: User password: <something, something>

Log into your local Drupal installation and change the password for admin, so it's easier to remember.

Concrats, now you have D9 installed.

--

## Optional composer packages:

### Install drupal/core-vendor-hardenning with the following command:

```
lando composer require drupal/core-vendor-hardening:^9.5
```


### Install drupal-core-dev:

```
lando composer require drupal/core-dev:^9.5 --dev --no-update

lando composer update

lando composer install
```


--

## Enable dblog module if not enabled:

```
lando drush en dblog

lando drush cr
```


So you can see the logs from console/terminal if something goes sideways, with the following command,

```
lando drush watchdog:show
```
  

--

## Optional Install admin toolbar, so it's easier to navigate:

```
lando composer require 'drupal/admin_toolbar:^3.4'

lando drush en admin_toolbar

lando drush cr
```


--

## Install upgrade status module:

```
lando composer require 'drupal/upgrade_status:^4.0'

lando drush en upgrade_status

lando drush cr
```


--

### Checking status:

First go to, 
```
/admin/reports/status
```


You can see that the Drupal version is now 9.5.11 and PHP-version is 8.0.27

Then go to, 
```
/admin/reports/upgrade-status
```


So PHP-version is giving as a problem. Check phase 1 on our documentation. 

--

## Fix PHP-version problem:

Open the .lando.yml file on your projects root folder and edit it,

So on line 5, you can see the PHP-version if you don't have that already, add it (of course you can set it right away to php 8.2)

```
name: drupal10-upgrade
recipe: drupal9
config:
  webroot: web
  php: '8.1'
```


save the file and run lando rebuild -y -command. 

See phase 2 documentation. So as you can see on the upgrade status report, now we are all good to make the D10-upgrade. But we don't want to go there yet.

--

## Lets make a bobo's:

Let's enable and install some modules that are not ready/ or to be removed from D10.

```
lando drush en quickedit

lando composer require 'drupal/context:^4.1'

lando drush en context

lando drush cr
```


Note: When messing with the modules and the database, maybe it's wise to run those config exports/imports every now and then and lando drush updb -y , must to make sure.

See the phase 3 documentation. So now we have two erros on the upgrade status. Quickedit will be removed from the D10-core, but if you want to use it still, you need to install that as a contrib module. Then we intentionally installed old version of context, that has no support for D10 yet. 

--

## Now that we have made some bobo's, we need to fix them:

```
lando drush pmu quickedit

lando composer require 'drupal/context:^5.0@RC'

lando drush cr
```


Just a reminder: When messing with the modules and the database, maybe it's wise to run those config exports/imports every now and then and lando drush updb -y , must to make sure. You might also want to rebuild your current setup, with lando rebuild -y for now. Because soon, what whe are about to witness, there is no turning back.

See the phase 4 documentation. Basically there was two options here, I could install the quickedit as a contrib module, but I disable it, so that was option two and then I upgraded the context module, so it supports D10.

--

## Now lets do the upgrade to D10 and hope for the best:

So basically now we have fully operating Drupal 9 site. You can always see the /admin/reports/status and dblog, /admin/reports/dblog . 

Now we are taking some manoeuvers, that might take some time (not actually). So be ready for them.

As you can see from the composer file, we have like,

```
- "drupal/core-composer-scaffold": "^9.5"
- "drupal/core-project-message": "^9.5"
- "drupal/core-recommended": "^9.5"
- "drupal/core-vendor-hardening": "^9.5"
- "drush/drush": "^11.6"
- "drupal/core-dev": "^9.5"
```


.. and because one ring can't rule them all, we have to upgrade them all.

## Upgrading to D10:

So let's start this thing, first run the following commands, 

```
lando composer require 'drupal/core-composer-scaffold:^10' 'drupal/core-recommended:^10' 'drupal/core-vendor-hardening:^10' 'drupal/core-project-message:^10' --update-with-dependencies --no-update
```


Then,

```
lando composer require 'drupal/core-dev:^10' --dev --update-with-dependencies --no-update
```


Then,

```
lando composer require 'drush/drush:^12' --no-update
```


Then again make sure, that everything is writable with the following command,

```
sudo chmod 777 -R D10-ugrade/ 
```


Then,

```
lando composer update
```


Then,

```
lando composer install 
```


So if anything has gone this far, so good. I don't recommend yet to run config imports/exports, but you can run the following commands,

```
lando drush cr

lando drush updb -y 

lando drush cr again.
```


At this point it didn't do no harm to run the lando rebuild -y command. Just for you to know. 

Check your local site again, it should be something like, https://d10-upgrade.site.lndo then go to /admin/reports/status and boom! You have installed/upgraded to Drupal10.

See the phase 5 documentation. You can see that the composer json has been changed and by look in at the image, now we are running D10, so hooray for you! 

--

## Finished:

After the installation, you now might want to export the configs to git/github and commit all of the rest changes that you have made. Enjoy your day and have a cup of tee, while everyone else is suffering the pain of upgrading Drupal, because you just upgraded Drupal. 