# Web servers
It is important that your webserver allows rewriting for xTend to work. Below are some example configrations. (In short, just rewrite everything to `index.php`)

## Apache
```
RewriteEngine On
RewriteBase /
RewriteCond %{HTTP_HOST} ^www\.(.*)$ [NC]
RewriteRule ^(.*)$ http://%1/$1 [R=301,L]
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)/$ /$1 [L,R=301]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [L,QSA]
```

## Lighttpd
```
fastcgi.server = (
    ".php" => ((
        "bin-path" => "/usr/sbin/php-cgi",
        "socket" => "/run/php/php7.0-fpm.sock"
    ))
)
$HTTP["host"] == "yourdomain.com" {
    url.rewrite-if-not-file = ( "^(.*)$" => "/index.php" )
}
```

## Hiawatha
```
FastCGIServer {
    FastCGIid = PHP7
    ConnectTo = 127.0.0.1:9000
    Extension = php
    SessionTimeout = 15
}
UrlToolkit {
    ToolkitID = alltoindex
    RequestUri exists Return
    Match .* Rewrite /index.php
}
VirtualHost {
    Hostname = yourdomain.com
    WebsiteRoot = /var/www/yourdomain.com/public
    UseToolkit = alltoindex
    StartFile = index.php
    ExecuteCGI = yes
    UseFastCGI = PHP7
}
```