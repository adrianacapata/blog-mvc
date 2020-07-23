# Simple MVC php project with OOP concepts

## Requerments
- conmposer
- php >= 7.2
- memcached installed and configured

## Installing

- run composer install
- create virtual host
``` <VirtualHost *:80>
    ServerName some-name-to-access-from-url
    ServerAdmin webmaster@localhost

    Options +FollowSymlinks +Indexes

    DocumentRoot /path/to/blog/public
    <Directory "/path/to/blog/public">
        AllowOverride All
        Order Deny,Allow
        Deny from All
        Allow from All
    </Directory>

    ErrorLog "/var/log/blog.local-error_log"
    CustomLog "/var/log/blog.local-access_log" combined
</VirtualHost>
```

- fill in parameters from ``` config/parameters.php```
- create database - you can import structure from the root of the project

### Available routes:
- ``` url-from-vhost/blog or url-from-vhost/blog/index```
- ``` url-from-vhost/blog/detail?id=x```
- ``` url-from-vhost/category/detail?id=x```

### Available commands:

- ``` php src/Command/archive.php ```
- ``` php src/Command/newsletter.php ```

