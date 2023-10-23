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

def exe(appliances,times,path,instance,result,no_nodes):
  NDEVICE = appliances
  NTIMESLOT = times
  NDEPENDENCY = 10
  THRESHOLD = 5*NDEVICE
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
  
  print(str(dependency))

  #for i in range(pref.ndevice):
    #print(pref.sorted_matrix[i])
  #print("")

  #print(consumption.matrix)

  config = {
    "pref_matrix"           : pref,
    "alpha"                 : THRESHOLD, 
    "percentage"            : PERCENTAGE, 
    "consumption"           : consumption, 
    "consumption_threshold" : CONSUMPTION_THRESHOLD, 
    "optimal"               : False, 
    "dependency"            : dependency
  }
  
  # print(config)
  g = Graph(**config)

  # start traversing from top of the init tree
  result[instance]   = g.DFS( len(g.nodes) - 1 )
  no_nodes[instance] = g.size()
  
if __name__ == '__main__':
  ntest = int(sys.argv[1]) #num of tests
  appliances = int(sys.argv[2]) #num devices
  times = int(sys.argv[3])  #timeslots per day
  #path = "log/{}a{}t/".format(appliances, times)"
  path = "/xampp/htdocs/SmartHomePreferenceApp/SHSP/ndvan-sg-a411f481df3f/tests/log/{}a{}t/".format(appliances, times)

  run_time = []
  finished = 0
  total_finished_time = 0
  timeout = 600
  manager = multiprocessing.Manager()
  result = manager.dict()
  no_nodes = manager.dict()
  
  for t in range(ntest):
    result[t]   = None
    no_nodes[t] = None
    
    # set log file
    dep_log_path = path + "dep/"
    if not os.path.exists(dep_log_path):
      os.makedirs(dep_log_path)
      
    sys.stdout = open(dep_log_path + str(t) + ".txt", 'w')
    report_file = open(dep_log_path + "report.txt", 'w')
    
    start_time = time.time()
    p = multiprocessing.Process(target=exe, args=(appliances,times,path,t,result,no_nodes,))
    p.start()
    p.join(timeout)

    # If thread is active
    if p.is_alive():
      # Terminate
      p.terminate()
      p.join()
      rt = round(time.time() - start_time,5)
    else:
      rt = round(time.time() - start_time,5)
      finished += 1
      total_finished_time += rt

    run_time.append( rt )

  sys.stdout = sys.__stdout__
  report_file.write("{:>4}{:>9}{:>9}{:>10}\n".format("Test", "Result", "Nodes", "Time"))
  for idx,val in enumerate(run_time):
    report_file.write("{:>4}{:>9}{:>9}{:>10}\n".format(idx, str(result[idx]), str(no_nodes[idx]), val))

  report_file.write("Finished: {}\n".format(finished))
  report_file.write("Avg run time: {}\n".format(total_finished_time/finished))
  
    

  
