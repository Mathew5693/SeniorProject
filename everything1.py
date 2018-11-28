import sys
import os
import time
from sense_hat import SenseHat
from time import sleep 
from time import asctime
import MySQLdb
import requests

global mydb
global mysursor
sense = SenseHat()
sense.set_rotation(270)


def weatherInfo():
    #Request API data
    api_address = 'http://api.openweathermap.org/data/2.5/weather?appid=29f1e0460bd791efd57e2d8a16b7bfbe&q=Tampa'
    #convert API data to Json
    json_data = requests.get(api_address).json()
    weatherDesc = json_data['weather'][0]['description']
    windSpeed = json_data['wind']['speed']
    windDir = json_data['wind']['deg']
    #convert winddir to direction
    val = int((windDir/22.5)+.5)
    compass = ["N","NNE","NE","ENE","E","ESE", "SE", "SSE","S","SSW","SW","WSW","W","WNW","NW","NNW"]
    windir = compass[(val % 16)]
    return (weatherDesc, windSpeed, windir)

#Updates Humidity
def hum():
    humidity=round(sense.get_humidity())
    return(humidity)

#Updates Pressure
def pres():
    pressure=round(sense.get_pressure())
    return(pressure)

# get CPU temperature
def get_cpu_temp():
  res = os.popen("vcgencmd measure_temp").readline()
  t = float(res.replace("temp=","").replace("'C\n",""))
  return(t)

# Correction for Temp
def cTemp():
    t_cpu = get_cpu_temp()*1.8+32
    t1 = sense.get_temperature_from_humidity()
    t2 = sense.get_temperature_from_pressure()
    t = (t1 + t2) / 2
    t=round(t*1.8+32, 1)
    #corr = t - ((t_cpu-t)/1.5)
    return(t)

#Inserts data into DB
def insertDB():
    sleep(300)
    wDesc, wSpeed, wDir = weatherInfo()
    sql = "INSERT INTO weather (temp, humidity, pressure, windSpeed, windDir, whatsitlike) VALUES (%s, %s, %s, %s, %s, %s)"
    try:
        mycursor.execute (sql,(str(cTemp()), str(hum()), str(pres()), str(wSpeed), str(wDir), str(wDesc)))
        mydb.commit()
    except:
        mydb.rollback()
    
#Creates raw txt backup & promts output
def backUP():
    wDesc, wSpeed, wDir = weatherInfo()
    out= " Temperature = %.1f F, Humidity = %d percent, Pressure = %d mbars, wind speed = %s, wind direction = %s, wheather description = %s " %(cTemp(), hum(), pres(), wSpeed, wDir, wDesc)
    sense.show_message(out, scroll_speed=(0.01),text_colour=[200,200,200], back_colour=[0,0,0])
    sense.clear()
    log=open('weather.txt', 'a')
    current=str(asctime())
    log.write(current + " " +out+ '\n')
    print(out)
    log.close()
    
    
def main():
    while True:
        insertDB()
        backUP()
        #more stuff later

if __name__ == '__main__':
    try:
        #Connect to DB or send error
        mydb = MySQLdb.connect("localhost","dbadmin","password1","example",autocommit=True)
        mycursor = mydb.cursor()
    except Exception as e:
        print("Something's Wrong!!")
        print(e)
    
    try:
        #Call main if connected to DB or manually stop program with Ctrl + C
        main()
    except KeyboardInterrupt:
        print("\nAww I'm ded")
        pass
        #sys.exit();