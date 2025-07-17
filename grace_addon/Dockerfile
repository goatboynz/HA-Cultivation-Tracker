ARG BUILD_FROM
FROM $BUILD_FROM

WORKDIR /data

RUN apk -U upgrade && apk add --no-cache \
    bash \
    curl \
    nginx \
    php83-fpm \
    php83-pdo \
    php83-pdo_sqlite \
    php83-session \
    sqlite \
    && ln -s /usr/sbin/php-fpm83 /usr/sbin/php-fpm \
    && addgroup -S php \
    && adduser -S -G php php \
    && rm -rf /var/cache/apk/* /etc/nginx/http.d/* /etc/php83/conf.d/* /etc/php83/php-fpm.d/* \
    && { \
        echo "extension=pdo.so"; \
        echo "extension=pdo_sqlite.so"; \
    } > /etc/php83/conf.d/20-pdo.ini \
    && { \
        echo "extension=session.so"; \
    } > /etc/php83/conf.d/21-session.ini

#RUN mkdir -p /grace_config && \
#    chown -R php:nginx /grace_config && \
#    chmod -R 660 /grace_config && \
#    touch /grace_config/grace.db && \
#    chown php:nginx /grace_config/grace.db

COPY files/general files/php83 /

RUN mkdir -p /data && \
    chown -R php:nginx /data && \
    chmod -R 755 /data && \
    mkdir -p /var/www/public && \
    chown -R php:nginx /var/www && \
    chmod -R 755 /var/www && \
    chown php:nginx /var/www/public/grace.db && \
    chmod 664 /var/www/public/grace.db && \
    chmod +x /etc/services.d/nginx/run && \
    chmod +x /etc/services.d/nginx/finish && \
    chmod +x /etc/services.d/php-fpm83/run && \
    chmod +x /etc/services.d/php-fpm83/finish && \
    find /etc/services.d -name "run" -exec chmod +x {} \; && \
    find /etc/services.d -name "finish" -exec chmod +x {} \;

ENTRYPOINT ["/init"]
