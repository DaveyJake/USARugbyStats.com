# Setting up the application


## The Easy Way

The application comes bundled with a Vagrant manifest for building a fully-functioning development environment.

(You will need to install [Vagrant](http://vagrantup.com/) and [VirtualBox](http://virtualbox.org/] for this to work))

### 1. Clone the repository

```bash
## Clone the repository
git clone git@github.com:USARugbyCMS/USARugbyStats.com.git
cd USARugbyStats.com
```

### 2. Boot it up

```
cd vagrant;
vagrant up
```

After a significant period of time the VM should be up and running.  You can SSH into it by running `vagrant ssh`

### 3. Add Host File Entry

Add the following to your host file
 - *nix: `/etc/hosts`
 - Windows: `C:\Windows\System32\drivers\etc\hosts`
 - Mac OSX: `/private/etc/hosts`

```
192.168.56.101  usarugbystats.dev
```

You should now be able to access the application through the URL: http://usarugbystats.dev/

(On MacOS you may also need to run: `dscacheutil -flushcache`)

### 4. Import testing data

If you want to import the extended test data fixtures, SSh into the VM and run:

```
cd /project;
bin/console data-importer run-fixtures --group=testing;
```

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

### 2. Clone the Repository

```bash
## Clone the repository
git clone https://github.com/USARugbyCMS/USARugbyStats.com.git
cd USARugbyStats.com 
```

### 3. Configure NGINX

Configure a standard NGINX + PHP-FPM vhost and point the webroot to the `public` folder of your repository clone.

### 4. Configure MySQL

```bash
mysql> CREATE USER usarugbystats@localhost IDENTIFIED BY 'enter_the_password_here';
mysql> GRANT ALL ON usarugbystats.* TO usarugbystats@localhost;
mysql> CREATE DATABASE usarugbystats;
```

### 5. Install Composer

```bash
cd /usr/local/bin
curl -sS https://getcomposer.org/installer | php
mv composer.phar composer
chmod +x composer
```

### 6. Install the application

```bash
cd config/autoload

cp doctrine.local.php.dist doctrine.local.php
# Edit file to add mysql user information

cp htsession.local.php.dist htsession.local.php
cp zfcuserredirect.local.php.dist zfcuserredirect.local.php
# You shouldn't need to edit these files unless you choose a different local domain name

## Run initial application setup
bin/app_rebuild.sh

# If you want to import the extended test data fixtures, run:
bin/console data-importer run-fixtures --group=testing
```


