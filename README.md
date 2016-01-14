# dsmrp1spot
A first personal github project, readout data from a smart meter via serial P1 port and store it in mysql. Using python/php/mysql.


# Crontab -e
// dsmrp1spot, read data from P1 port KWh meter</BR>
0,5,10,15,20,25,30,35,40,45,50,55 * * * * sudo /usr/local/bin/dsmrp1spot/p1spot.sh > /dev/null</BR>
58 23 * * * sudo /usr/local/bin/dsmrp1spot/p1spot.sh > /dev/null</BR>

# Symbolic links
ln -s /usr/local/bin/dsmrp1spot/www/p1 /var/www/p1
ln -s /usr/local/bin/dsmrp1spot/www/p1telegram /var/www/p1telegram
ln -s /usr/local/bin/dsmrp1spot/www/p2 /var/www/p2
ln -s /usr/local/bin/dsmrp1spot/www/p1daily /var/www/p1daily
ln -s /usr/local/bin/dsmrp1spot/www/p3 /var/www/p3
ln -s /usr/local/bin/dsmrp1spot/www/p1hourly /var/www/p1hourly
