# schedule-setup.py
# 5/7/2023
# Authors: Jacob Yoder. Diego Terrazas
# This program queries the MongoDB database for all preferences
# and puts them in the required format for the scheduler(dep.py)

import pymongo
import subprocess

# setup connections for MongoDB 
client = pymongo.MongoClient('mongodb://localhost:27017')
db = client.KlapLab
collection = db.preferences
devCollection = db.devices

# Find all prefs in Mongo
prefs = collection.find()

# array declarations
onTimes = []
offTimes = []
names = []
devTypes = []

# number of time slots
time = 1440

# loop through all devices
for pref in prefs:
    tempName = pref['dev_name']
    # find the device object in device table
    dev = devCollection.find_one({'name': tempName})

    # if the device isn't registered in the MongoDB database, don't put it in the scheduler
    if(dev == None):
        print(f"{tempName} Device not registered with SHDS")
        continue

    # loop through all the ON times for that device
    for times in pref['ON']:
        onTimes.append(times)
        names.append(tempName)
        devTypes.append(dev['type'])

    # loop through all the ON times for that device
    for times in pref['OFF']:
        offTimes.append(times)


numDevs = len(names)

# call the generate_instances.py to create the folders
subprocess.call(["python", "E:/xampp/htdocs/SmartHomePreferenceApp/SHSP/ndvan-sg-a411f481df3f/tests/generate_instances.py", "1", str(numDevs), str(time)], shell = True)

# open files for names and types
devNamesFile = open("E:/xampp/htdocs/SmartHomePreferenceApp/SHSP/ndvan-sg-a411f481df3f/tests/Devices/DeviceNames.txt", "w")
devTypesFile = open("E:/xampp/htdocs/SmartHomePreferenceApp/SHSP/ndvan-sg-a411f481df3f/tests/Devices/DeviceTypes.txt", "w")
consumption = open("E:/xampp/htdocs/SmartHomePreferenceApp/SHSP/ndvan-sg-a411f481df3f/tests/log/{}a{}t/consumption/0.txt".format(numDevs, time), "w")
pref_matrix = open("E:/xampp/htdocs/SmartHomePreferenceApp/SHSP/ndvan-sg-a411f481df3f/tests/log/{}a{}t/pref/0.txt".format(numDevs, time), "w")

# format consumption and pref_matrix
consumption.write("dev: {}\n".format(numDevs))
pref_matrix.write("dev: {}\ntime: {}\n".format(numDevs, time))

# loop through number of devices
for i in range(numDevs):
    # save names and types
    devNamesFile.write(names[i] + "\n")
    devTypesFile.write(devTypes[i] + "\n")

    # save consumption
    consumption.write("100\n")
    # save preferences for each device
    for j in range(time):
        # format the times into minutes (0 - 1439)
        arr = onTimes[i].split(":")
        timeon = int(arr[0]) * 60 + int(arr[1])
        arr = offTimes[i].split(":")
        timeoff = int(arr[0]) * 60 + int(arr[1])

        # if time off happens before time on, do the inverse
        if(timeoff < timeon):
            if(timeon <= j or j <= timeoff):
                pref_matrix.write("{} {} 10 0.01\n".format(i, j)) # user wants device on (set pref_matrix to max value)
            else:
                pref_matrix.write("{} {} 0 1.0\n".format(i, j)) # user wants device off (set pref_matrix to min value)
        # if time off is after time on, assign pref_matrix normally
        else:
            if(timeon <= j and j <= timeoff):
                pref_matrix.write("{} {} 10 0.01\n".format(i, j)) # user wants device on (set pref_matrix to max value)
            else:
                pref_matrix.write("{} {} 1 1.0\n".format(i, j)) # user wants device off (set pref_matrix to min value)


# close all files
devNamesFile.close()
devTypesFile.close()
consumption.close()
pref_matrix.close()

# call dep.py to build the schedule
subprocess.call(["python", "E:/xampp/htdocs/SmartHomePreferenceApp/SHSP/ndvan-sg-a411f481df3f/tests/dep.py", "1", str(numDevs), str(time)], shell = True)
