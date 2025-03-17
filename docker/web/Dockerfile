FROM nginx:1-alpine
ENTRYPOINT [ "/entrypoint.sh" ]

RUN { \
      echo "#!/bin/sh"; \
      echo "set -e"; \
      # Sprawdzenie czy kontener z frontem już działa
      echo "while ! nc -z frontend 3000; do"; \
      echo "  sleep 1"; \
      echo "done"; \
      # Uruchomienie nginx
      echo "exec /docker-entrypoint.sh nginx -g 'daemon off;'"; \
    } > /entrypoint.sh \
 && chmod +x /entrypoint.sh

COPY maps.conf /etc/nginx/conf.d/maps.conf
COPY front_back.conf /etc/nginx/conf.d/default.conf
