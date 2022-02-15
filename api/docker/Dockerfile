FROM public.ecr.aws/debian/debian:bullseye
#FROM debian
ENV APPDIR /var/www/html/app
ENV LOGDIR /var/www/html/logs
RUN apt-get update -y
RUN apt-get install wget git -y
RUN apt-get install ca-certificates apt-transport-https software-properties-common -y
RUN echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | tee /etc/apt/sources.list.d/sury-php.list
RUN apt-get install -y gnupg2
RUN wget -qO - https://packages.sury.org/php/apt.gpg | apt-key add -
RUN apt-get update -y
RUN apt-get install php8.0 -y
RUN apt-get install php php-common php-curl php-mysql php-xml php-gd php-mbstring php-tokenizer php-json php-bcmath php-zip -y

COPY ./app $APPDIR
COPY ./core $APPDIR/zframework
RUN mkdir -p $LOGDIR


WORKDIR $APPDIR/public

#
#RUN a2enmod php8.0
#RUN a2enmod env
#ENV APACHE_LOG_DIR /var/www/html/logs
    
#RUN ln -s /var/www/html/app /ecs/FTask-luxura-api-production/laravel
#php -S 0.0.0.0:91 -t /var/www/html/app/public /index.php 
CMD  php -S 0.0.0.0:80 -t /var/www/html/app/public 

EXPOSE 80