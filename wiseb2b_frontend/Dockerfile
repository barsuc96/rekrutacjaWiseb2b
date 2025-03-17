# get the base node image
FROM node:alpine as builder

# set the working dir for container
WORKDIR /frontend

# copy the json file first
COPY ./package.json /frontend

# install npm dependencies
# RUN npm install --legacy-peer-deps
RUN yarn install

# copy other project files
COPY . .

# build the folder
# RUN npm run build
RUN yarn build

# Handle Nginx
FROM nginx

COPY --from=builder /frontend/build /usr/share/nginx/html
COPY ./nginx.conf /etc/nginx/conf.d/default.conf
