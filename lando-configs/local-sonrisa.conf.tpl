server {
    listen 80;
    listen 443 ssl;
    server_name localhost;
    ssl_certificate           /certs/cert.crt;
	ssl_certificate_key       /certs/cert.key;
	ssl_verify_client         off;
	access_log /user/.lando/logs/sonrisa-access.log;
    error_log /user/.lando/logs/sonrisa-error.log;
    root /app/public;
    index index.html index.htm index.php;
    location / {
        try_files $uri $uri/ /index.html;
    }
    location ~ ^/(admin|api|docs|storage|nova|horizon) {
        try_files $uri $uri/ /index.php?$query_string;
    }
    location ~ \.php$ {
        try_files $uri /index.php =404;
        fastcgi_pass appserver:9000;
        fastcgi_index index.php;
        fastcgi_buffers 16 4k;
        fastcgi_buffer_size 4k;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        #fixes timeouts
        fastcgi_max_temp_file_size 0;
        fastcgi_connect_timeout 5m;
        fastcgi_send_timeout 5m;
        fastcgi_read_timeout 5m;
        include fastcgi_params;
    }
    location ~* \.(?:jpg|jpeg|gif|png|ico|cur|gz|svg|svgz|mp4|ogg|ogv|webm|htc|swf)$ {
        expires 1M;
        access_log off;
        add_header Cache-Control "public";
        try_files $uri $uri/ /index.php?$query_string;
    }
    location ~* \.(?:css|js)$ {
        expires 1M;
        access_log off;
        add_header Cache-Control "public";
    }
    location = /favicon.ico {
        log_not_found off;
        access_log off;
    }
    location ~ /\.ht {
        deny all;
    }
    location = /robots.txt {
        allow all;
        log_not_found off;
        access_log off;
    }
    location ~* \.(txt|log)$ {
        allow 127.0.0.1;
        deny all;
    }
    location /.well-known/acme-challenge/ {
        root /var/www/letsencrypt/;
        log_not_found off;
    }
    client_max_body_size 50m;
}
