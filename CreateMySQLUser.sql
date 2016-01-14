#DROP USER 'p1spotUser'@'localhost';
CREATE USER 'p1spotUser'@'localhost' IDENTIFIED BY 'p1spotPassword';
GRANT DELETE,INSERT,SELECT,UPDATE ON P1spot.* TO 'p1spotUser'@'localhost';
FLUSH PRIVILEGES;
