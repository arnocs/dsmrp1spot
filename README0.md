# DSMR P1spot
A first github project, 
Readout data from a DSMR smart meter P1 port via serial and store it in mysql database.
Using python/php/mysql.

## Note:
The P1 port telegram data is based uppon DSMR Verion 4.2.
All other versions are not compatible with this script.

## Crontab -e
// dsmrp1spot, read data from P1 port KWh meter
0,5,10,15,20,25,30,35,40,45,50,55 * * * * sudo /usr/local/bin/dsmrp1spot/p1spot.sh > /dev/null
58 23 * * * sudo /usr/local/bin/dsmrp1spot/p1spot.sh > /dev/null

## Symbolic links
ln -s /usr/local/bin/dsmrp1spot/www/p1 /var/www/p1
ln -s /usr/local/bin/dsmrp1spot/www/p1telegram /var/www/p1telegram
ln -s /usr/local/bin/dsmrp1spot/www/p2 /var/www/p2
ln -s /usr/local/bin/dsmrp1spot/www/p1daily /var/www/p1daily
ln -s /usr/local/bin/dsmrp1spot/www/p3 /var/www/p3
ln -s /usr/local/bin/dsmrp1spot/www/p1hourly /var/www/p1hourly
ln -s /usr/local/bin/dsmrp1spot/www/logs /var/www/logs