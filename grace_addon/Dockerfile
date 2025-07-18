ARG BUILD_FROM=ghcr.io/home-assistant/amd64-base:latest
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

COPY files/general files/php83 /

RUN mkdir -p /data /var/www/public /var/log/nginx /var/log/php83 && \
    cp -r /www/public/* /var/www/public/ 2>/dev/null || true && \
    rm -rf /www && \
    chown -R php:nginx /data /var/www && \
    chown -R nginx:nginx /var/log/nginx && \
    chown -R php:php /var/log/php83 && \
    chmod -R 755 /data /var/www && \
    chmod +x /etc/cont-init.d/* 2>/dev/null || true && \
    chmod +x /etc/services.d/*/run 2>/dev/null || true && \
    chmod +x /etc/services.d/*/finish 2>/dev/null || true

ENTRYPOINT ["/init"]