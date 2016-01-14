### DSMR P1 uitlezen
versie = "0.2"
import sys
import serial

### Set COM port config
ser = serial.Serial()
ser.baudrate = 115200
ser.bytesize=serial.SEVENBITS
ser.parity=serial.PARITY_NONE
ser.stopbits=serial.STOPBITS_ONE
ser.xonxoff=0
ser.rtscts=0
ser.timeout=20
ser.port="/dev/ttyUSB0"

### p1_count counter for p1.datagram length in case of error read
p1_count=0
p1_line=''

##############################################################################
#Main program
##############################################################################

### Open COM port
try:
    ser.open()
except:
    sys.exit ("Error opening %s"  % ser.name)

### Read 1 line van de seriele poort
while p1_line[:1] <> "!":
    try:
        p1_raw = ser.readline()
    except:
        sys.exit ("Serial port %s cannot be read." % ser.name )

### put raw data in string and print
    p1_str=str(p1_raw)
    p1_line=p1_str.strip()
    print (p1_line)
    p1_count = p1_count +1

### Close port and show status
try:
    ser.close()
except:
    sys.exit ("Oops %s. program aborted. Could not clode serial port." % ser.name )

