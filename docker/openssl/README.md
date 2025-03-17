* generate public and private keys
```
openssl genrsa -out oauth_private.key 2048
openssl genrsa -aes128 -passout pass:myTestPassword -out oauth_private.key 2048
openssl rsa -in oauth_private.key -passin pass:myTestPassword -pubout -out oauth_public.key
```
* generate encryption key
```
php -r 'echo base64_encode(random_bytes(32)), PHP_EOL;'
```

* source: https://oauth2.thephpleague.com/installation/

* generate client_secret

```
php -r 'echo bin2hex(random_bytes(32)), PHP_EOL;'
```

