# Example to send a packet periodically between addressed nodes
# Author: Jerry Needell
#
import time
import board
import busio
import digitalio
import adafruit_rfm9x
import mysql.connector
import time
from datetime import date, datetime, timedelta
 
# Define radio parameters.
RADIO_FREQ_MHZ = 433.0  # Frequency of the radio in Mhz. Must match your
# module! Can be a value like 915.0, 433.0, etc.
 
# Define pins connected to the chip.
CS = digitalio.DigitalInOut(board.CE1)
RESET = digitalio.DigitalInOut(board.D25)
 
# Initialize SPI bus.
spi = busio.SPI(board.SCK, MOSI=board.MOSI, MISO=board.MISO)
 
# Initialze RFM radio
rfm9x = adafruit_rfm9x.RFM9x(spi, CS, RESET, RADIO_FREQ_MHZ)
 
# enable CRC checking
rfm9x.enable_crc = True
# set delay before sending ACK
rfm9x.ack_delay = 0.1
# set node addresses
rfm9x.node = 99
rfm9x.destination = 65

#Setting up database connection
cnx = mysql.connector.connect(host="localhost", user="phpmyadmin", password="raspberry", database="phpmyadmin", raise_on_warnings=True)
cursor = cnx.cursor()

# # send a broadcast message from my_node with ID = counter
# rfm9x.send(bytes("startup message from RPI {} ".format(rfm9x.arduino), "UTF-8"))
 
# # Wait to receive packets.
# print("Waiting for packets...")
# initialize flag and timer
time_now = time.monotonic()
i = 0
while i < 2 :
    # Look for a new packet: only accept if addresses to my_node
    packet = rfm9x.receive(with_header=True, with_ack=True, timeout=10)
    # If no packet was received during the timeout then None is returned.
    if packet is not None:
        # Received a packet!
        # Print out the raw bytes of the packet:
        print("Received (raw header):", [int(x) for x in packet[0:4]])
        print("Received (raw payload): {0}".format(packet[4:]))
        print("Received RSSI: {0}".format(rfm9x.last_rssi))
        # after 10 messages send a response to destination_node from my_node with ID = counter&0xff
        #if counter % 1 == 0:
        time.sleep(0.5)  # brief delay before responding
        #rfm9x.identifier = counter & 0xFF
        msg=str("allume")
        rfm9x.send_with_ack(
                bytes(
                    msg.format(rfm9x.destination),
                    "UTF-8",
                ),
        #keep_listening=True,
            )
        i += 1

print("Data start")
date_now = datetime.now()
add_status = ("INSERT INTO lumiere (mesure, temps) VALUES (%s, %s)")
data_status = (1, date_now)

cursor.execute(add_status, data_status)
emp_no = cursor.lastrowid

#Transmit data to the database, and close the connection
cnx.commit()

cursor.close()
cnx.close()
print("Data finish")
