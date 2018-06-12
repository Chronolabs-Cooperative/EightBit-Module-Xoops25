## Chronolabs Cooperative Presents
# 8 Bit Collective SVN/REPO Client
## Author: Dr. Simon Antony Roberts <simon@snails.email>
### 8 Bit Collective Gaming Music Genre ~ SVN/REPO Client

This XOOPS 2.5 module is for a client for the 8 Bit Collective Repositories/SVN resources to be browsed and utilises it also mails the music required to the user and provides search capabilities!

## Installation

Copy the files to your XOOPS 2.5 root path, namely the contents of the /modules folder in the repository here, then goto the XOOPS module installation within your xoops client yourself and install!

## Cron Job / Scheduled Tasks
The following cronjobs need to be set for the module you can do this by executing in the root or user for the client the following

    $ crontab -e

The following settings need to approximately be set

    */5 * * * * /usr/bin/php -q /var/www/8bit.snails.email/modules/eightbit/crons/crawl.repositories.php
    */2 * * * * /usr/bin/php -q /var/www/8bit.snails.email/modules/eightbit/crons/crawl.sha1bytes.php
    */2 * * * * /usr/bin/php -q /var/www/8bit.snails.email/modules/eightbit/crons/crawl.sha1matches.php

