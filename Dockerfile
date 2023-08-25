FROM httpd:alpine3.17

EXPOSE 80

VOLUME [ “/sys/fs/cgroup” ]

# Install mysqli extension
RUN apk update && apk upgrade && apk add wget util-linux openrc

RUN wget http://alpine.adiscon.com/rsyslog@lists.adiscon.com-5a55e598.rsa.pub -o /etc/apk/keys/rsyslog@lists.adiscon.com-5a55e598.rsa.pub

RUN echo 'http://alpine.adiscon.com/3.7/stable' >> /etc/apk/repositories

RUN apk --allow-untrusted update && apk --allow-untrusted add rsyslog

RUN rc-update add rsyslog default\
  && mkdir /run/openrc\
  && touch /run/openrc/softlevel

RUN apk add mariadb mariadb-client apache2 php php81-apache2 php81-mysqli php81-session

# Copy files into container
COPY app /var/www/localhost/htdocs

# Copy entrypoint
COPY entrypoint.sh /entrypoint.sh

RUN chmod +x /entrypoint.sh

# Start MariaDB and Apache2
ENTRYPOINT ["/entrypoint.sh"]