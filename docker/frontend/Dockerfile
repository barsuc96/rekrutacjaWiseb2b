FROM node:18-alpine as builder
EXPOSE 3000/tcp
ENTRYPOINT [ "/entrypoint.sh" ]
CMD [ "tini", "--", "yarn", "start", "--port=3000" ]

WORKDIR /var/www/frontend

RUN apk add --no-cache \
      tini \
 && { \
      echo "#!/bin/sh"; \
      echo "set -e"; \
      # Automatycznie zainstaluj zależności
      echo "yarn install"; \
      # Uruchom komendę z dyrektywy CMD
      echo "exec \"\$@\""; \
    } > /entrypoint.sh \
 && chmod +x /entrypoint.sh
