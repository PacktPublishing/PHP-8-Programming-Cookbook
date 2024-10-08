<?php
// Usage: update_wp_conf.php DEST_FN NGINX_CONF_FN SECRETS_FN
$dest_fn = $argv[1] ?? '/tmp/default.conf';
$src_fn  = $argv[2] ?? '/tmp/default.conf';
$sec_fn  = $argv[3] ?? '/tmp/secrets.sh';
$conf    = file_get_contents($src_fn);
$secrets = file($sec_fn);
foreach ($secrets as $line) {
    if (str_contains($line, '=')) {
        $line = trim(str_replace('export ', '', $line));
        [$key, $val] = explode('=', $line);
        $key = trim($key);
        $val = trim($val);
        $conf = str_replace($key, $val, $conf);
    }
}
echo $conf . PHP_EOL;
file_put_contents($dest_fn, $conf);
/*
server {
    listen                  80;
    root                    DOC_ROOT;
    index                   index.php;
    server_name             HOST_NAME;
    client_max_body_size    32m;
    error_page              500 502 503 504  /50x.html;
    location = /50x.html {
          root              /var/lib/nginx/html;
    }
    location ~ \.php$ {
          fastcgi_pass      127.0.0.1:9000;
          fastcgi_index     index.php;
          include           fastcgi.conf;
    }
}
*/
