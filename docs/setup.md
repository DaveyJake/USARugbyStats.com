# Setting up the application


## The Easy Way

The application comes bundled with a Vagrant manifest for building a fully-functioning development environment.

(You will need to install [Vagrant](http://vagrantup.com/) and [VirtualBox](http://virtualbox.org/] for this to work))

### 1. Clone the repository

```bash
## Clone the repository
git clone https://github.com/adamlundrigan/USARugby-Stats.git usarugbystats
cd usarugbystats
```

### 2. (Optional) Development Fixtures

There are some additional database fixtures under the 'Development' namespace that need to be manually enabled if you want them.  These will insert some test entities into the database (teams, unions, competitions)

```
cp config/autoload/dev_fixtures.local.php.dist config/autoload/dev_fixtures.local.php
```

### 3. Boot it up

```
cd vagrant;
vagrant up
```

After a significant period of time the VM should be up and running

### 4. Add Host File Entry

Add the following to your host file
 - *nix: `/etc/hosts`
 - Windows: `C:\Windows\System32\drivers\etc\hosts`
 - Mac OSX: `/private/etc/hosts`

```
192.168.56.101  usarugbystats.dev
```

You should now be able to access the application through the URL: http://usarugbystats.dev/

(On Mac OSX you may also need to run: `dscacheutil -flushcache`)


## The Hard Way

### 1. Packages

 - NGINX (Mainline)
 -  PHP 5.5.x CLI and FPM, with extensions:
   - cURL
   - intl
   - json
   - mysql
   - gd
 - MySQL Server (latest)
 - Redis (latest)
 - Git (latest)
 - Supervisor

On Ubuntu this works out to be:

```bash
apt-get install nginx mysql-server redis-server php5-fpm php5-cli php5-curl php5-json php5-intl php5-mysql php5-gd git supervisor
```

### 2. Configure MySQL

```bash
mysql> CREATE USER usarugbystats@localhost IDENTIFIED BY 'enter_the_password_here';
mysql> GRANT ALL ON usarugbystats.* TO usarugbystats@localhost;
mysql> CREATE DATABASE usarugbystats;
```


### 3. Install Composer

```bash
cd /usr/local/bin
curl -sS https://getcomposer.org/installer | php
mv composer.phar composer
chmod +x composer
```

### 4. Install the application

```bash
## Clone the repository
git clone https://github.com/adamlundrigan/USARugby-Stats.git usarugbystats
cd usarugbystats

## Configure the application
cd config/autoload

cp doctrine.local.php.dist doctrine.local.php
# Edit file to add mysql user information

## Run initial application setup
bin/app_rebuild.sh

# If you want to import the extended test data fixtures, run:
bin/console data-importer run-fixtures --group=testing
```


