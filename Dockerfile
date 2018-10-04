FROM ubuntu:16.04

# Install apache, PHP, and supplimentary programs. openssh-server, curl, 
# and lynx-cur are for debugging the container.
RUN apt-get update && apt-get -y upgrade && apt-get -y install apache2 \
 libapache2-mod-php \ 
 php7.0 \ 
 php7.0-mcrypt \ 
 php7.0-mysql \  
 php7.0-cli \ 
 php7.0-mbstring \ 
 php7.0-curl \
 php7.0-mysqli \
 php7.0-pdo \
 php7.0-json \
 php7.0-soap \
 php7.0-xsl \
 php7.0-intl \ 
 php7.0-zip \
 php7.0-bcmath \
 php7.0-xml \
 php7.0-ctype \ 
 php7.0-dom \ 
 php7.0-gd \
 php7.0-iconv \
 php7.0-bz2 \
 php7.0-sqlite3 \
 php7.0-tidy \
 php7.0-dev \ 
 php-igbinary \
 php-msgpack \
 php-memcached \
 unzip \
 curl \
 pkg-config \ 
 libssl-dev \ 
 libsslcommon2-dev \ 
 libfreetype6-dev \
 libmcrypt-dev \
 libpng-dev \
 libcurl4-nss-dev \
 libxml2-dev \
 libxslt-dev \
 apt-utils \
 libjpeg-dev \
 libwebp-dev \	
 libpng-dev libxpm-dev \
 vim

#RUN docker-php-ext-configure gd --with-freetype-dir=/usr/lib/x86_64-linux-gnu --with-jpeg-dir=/usr/lib/x86_64-linux-gnu && docker-php-ext-install gd

# Enable apache mods.
RUN a2enmod rewrite
RUN a2enmod headers

# Manually set up the apache environment variables
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_LOCK_DIR /var/lock/apache2
ENV APACHE_PID_FILE /var/run/apache2.pid

# install nodejs, npm, bower
RUN apt-get -y install nodejs npm git composer
RUN npm install -g bower
RUN npm install --global gulp-cli
RUN apt-get install -y ruby-full rubygems
RUN gem install sass
RUN ln -s /usr/bin/nodejs /usr/bin/node

RUN mkdir /app
WORKDIR /app

# Set permission for work dir
RUN chown -R :www-data /app 
RUN chmod -R o+r /app
RUN chmod -R g+w /app
RUN find /app -type d -exec chmod g+s {} \; 

# Expose apache.
EXPOSE 80 443 3306

# Update the default apache site with the config we created.
# ADD conf.d/vhosts.conf /etc/apache2/sites-enabled/000-default.conf

# By default start up apache in the foreground, override with /bin/bash for interative.
CMD systemctl restart apache2
CMD /usr/sbin/apache2ctl -D FOREGROUND