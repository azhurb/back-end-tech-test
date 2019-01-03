# Simple container to run the app. Not for production.

FROM composer

WORKDIR /app

COPY ./app /app

# Install dependencies.
RUN composer install

EXPOSE 80

# Run the app.
CMD php /app/bin/console server:run 0.0.0.0:80