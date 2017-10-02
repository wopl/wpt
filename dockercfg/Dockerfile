######################################################################
##                                                                  ##
## Dockerfile                        (c) Wolfram Plettscher 10/2017 ##
## t (WPTools on Docker)                                            ##
##                                                                  ##
######################################################################

#FROM httpd:latest
FROM php:apache

MAINTAINER Wolfram Plettscher (woppl at webdotde)

### change apache document root path to adapt to symfony
ENV APACHE_DOCUMENT_ROOT /var/www/wpt/web
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

#RUN apt-get update && \
#    apt-get install -y \
#        zlib1g-dev \
#        git-core

### change apache user to root to access docker-volumes properly
#sudo gedit /etc/apache2/envvars
#export APACHE_RUN_USER=www-data
#export APACHE_RUN_GROUP=www-data 
RUN sed -ri -e 's!iwww-data!root!g' /etc/apache2/envvars
