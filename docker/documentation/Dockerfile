FROM node:18-alpine

WORKDIR /var/www

# Tworzenie projektu Docusaurus w wersji classic z TypeScript
RUN npx create-docusaurus@latest documentation classic --typescript

WORKDIR /var/www/documentation

ENV PORT=3010

# Eksponowanie portu, na którym działa Docusaurus (domyślnie 3010)
EXPOSE 3010/tcp

COPY ./docusaurus.config.ts /var/www/documentation/docusaurus.config.ts
COPY ./package.json /var/www/documentation/package.json

RUN npm install --save @easyops-cn/docusaurus-search-local

RUN npm install --save @docusaurus/plugin-sitemap


# Polecenie uruchamiające serwer developerski Docusaurus
CMD ["npm", "run", "serve"]