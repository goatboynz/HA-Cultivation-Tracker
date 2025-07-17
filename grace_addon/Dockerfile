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

# Set up directories and copy application files
RUN mkdir -p /data /var/www/public /var/log/nginx /var/log/php83 && \
    cp -r /www/public/* /var/www/public/ 2>/dev/null || true && \
    rm -rf /www && \
    chown -R php:nginx /data /var/www && \
    chown -R nginx:nginx /var/log/nginx && \
    chown -R php:php /var/log/php83 && \
    chmod -R 755 /data /var/www && \
    if [ -f "/var/www/public/grace.db" ]; then chown php:nginx /var/www/public/grace.db && chmod 664 /var/www/public/grace.db; fi && \
    chmod +x /etc/cont-init.d/* 2>/dev/null || true && \
    chmod +x /etc/services.d/*/run 2>/dev/null || true && \
    chmod +x /etc/services.d/*/finish 2>/dev/null || true

ENTRYPOINT ["/init"]
