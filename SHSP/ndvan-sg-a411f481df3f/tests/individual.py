# -*- coding: utf-8 -*-

from context import sg
from sg import PrefMatrix
from sg import Consumption
from sg import Node
from sg import Graph
from sg import Dependency
from IPython import embed
import sys
import os
import time
import multiprocessing
import timeit

def exe(appliances,times,path,instance):
  NDEVICE = appliances
  NTIMESLOT = times
  NDEPENDENCY = 10
  THRESHOLD = 6.1*NDEVICE
  PERCENTAGE = 0.8
  CONSUMPTION_THRESHOLD = 4000
  
  # load instances
  pref = PrefMatrix(NDEVICE,NTIMESLOT)
  pref_path = path + "pref/{}.txt".format(instance)
  pref.import_from(pref_path)

  cons_path = path + "consumption/{}.txt".format(instance)
  consumption = Consumption(NDEVICE)
  consumption.import_from(cons_path)
  
  dependency = Dependency(NDEVICE,NTIMESLOT,NDEPENDENCY)
  
  for i in range(pref.ndevice):
    print(pref.matrix[i])
  print("")

  for i in range(pref.ndevice):
    print(pref.sorted_matrix[i])
  print("")

  print(consumption.matrix)

  config = {
    "pref_matrix"           : pref,
    "alpha"                 : THRESHOLD, 
    "percentage"            : PERCENTAGE, 
    "consumption"           : consumption, 
    "consumption_threshold" : CONSUMPTION_THRESHOLD, 
    "optimal"               : True, 
    "dependency"            : dependency
  }
  
  print(config)
  
  g = Graph(**config)

  start = timeit.default_timer()
  g.DFS( len(g.nodes) - 1 )  # start traversing from top of the init tree
  stop = timeit.default_timer()

  print('Time: ', stop - start) 
  
  
if __name__ == '__main__':
  instance = int(sys.argv[1])
  appliances = int(sys.argv[2])
  times = int(sys.argv[3])
  path = "log/{}a{}t/".format(appliances, times)

  exe(appliances,times,path,instance)
    

  
