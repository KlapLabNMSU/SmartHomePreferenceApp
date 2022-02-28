# -*- coding: utf-8 -*-

from context import sg
from sg import PrefMatrix
from sg import Node
from sg import Graph
from sg import Consumption
from sg import Dependency
from IPython import embed
import sys
import os
import time
import multiprocessing

def exe(appliances,times,result):
  NDEVICE = appliances
  NTIMESLOT = times
  THRESHOLD = 6.1*NDEVICE
  PERCENTAGE = 0.8
  CONSUMPTION_THRESHOLD = 1000
  
  pref = PrefMatrix(NDEVICE,NTIMESLOT)
  consumption = Consumption(NDEVICE,NTIMESLOT)
  
  dependency = Dependency(pref.ntimeslot, pref.ndevice, 10)
  
  for i in range(pref.ndevice):
    print(pref.matrix[i])
  print("")

  for i in range(pref.ndevice):
    print(pref.sorted_matrix[i])
  print("")

  for i in range(pref.ndevice):
    print(consumption.matrix[i])
  print("")
  
  node_list = []
  max_mean = 0
  min_variation = 1

  g = Graph(pref, pref.ntimeslot, node_list, THRESHOLD, PERCENTAGE, consumption, CONSUMPTION_THRESHOLD, True, dependency)

  # seeding components for graph
  for t in range(pref.ntimeslot):
    node = Node(pref.sorted_matrix[0][t][2],
      pref.sorted_matrix[0][t][3],
      pref.sorted_matrix[0][t][1],
      0,
      pref.ntimeslot)
  
    node_list.append(node)
    max_mean = max_mean if max_mean > pref.sorted_matrix[0][t][2] else pref.sorted_matrix[0][t][2]  
    min_variation = min_variation if min_variation < pref.sorted_matrix[0][t][3] else pref.sorted_matrix[0][t][3]
    g.addEdge(pref.ntimeslot, t)

  node = Node(
    max_mean + pref.sorted_matrix[1][0][2], 
    min_variation + pref.sorted_matrix[1][0][3], 
    pref.sorted_matrix[1][0][1], 
    1,
    pref.ntimeslot * 2)
  node_list.append(node)

  # build up
  for d in range(1,pref.ndevice):
    big_sibling = len(node_list) - 1
    parent = len(node_list) + pref.ntimeslot - 1
    prev_mm = max_mean
    prev_mv = min_variation
    min_variation = node_list[big_sibling].min_variation

    g.addEdge(parent, big_sibling)

    for t in range(1,pref.ntimeslot):
      node = Node(
        prev_mm + pref.sorted_matrix[d][t][2],
        prev_mv + pref.sorted_matrix[d][t][3], 
        pref.sorted_matrix[d][t][1],
        d,
        parent)
      node_list.append(node)
  
      g.addEdge(parent, len(node_list) - 1)
      min_variation = prev_mv + pref.sorted_matrix[d][t][3] if prev_mv + pref.sorted_matrix[d][t][3] < min_variation else min_variation
      
    max_mean = prev_mm + pref.sorted_matrix[d][0][2]
    
    if d == pref.ndevice - 1:
      node = Node(
        max_mean, 
        min_variation, 
        -1, 
        -1,
        -1)
    else:
      node = Node(
        max_mean + pref.sorted_matrix[d+1][0][2], 
        min_variation + pref.sorted_matrix[d+1][0][3], 
        pref.sorted_matrix[d+1][0][1], 
        d+1,
        parent + pref.ntimeslot)
    node_list.append(node)

  # dfs
  # embed()
  result.append( g.DFS( len(node_list) - 1 ) )
  
  # embed()
  # construct tree when traverse
  # stop early when traversing 
  # max mean, min xichma dynamic programming for pruning
    
if __name__ == '__main__':
  ntest = int(sys.argv[1])
  appliances = int(sys.argv[2])
  times = 24
  timeout = int(sys.argv[3])
  run_time = []
  manager = multiprocessing.Manager()
  result = manager.list()
  finished = 0
  total_finished_time = 0
  
  for i in range(0,ntest):
    path = "log/op{}a{}t/".format(appliances, times)
    if not os.path.exists(path): 
      os.makedirs(path)
    sys.stdout = open(path + str(i) + ".txt", 'w')
    report_file = open(path + "report.txt", 'w')
    start_time = time.time()
    
    p = multiprocessing.Process(target=exe, args=(appliances,times,result,))
    p.start()
    p.join(timeout)

    # If thread is active
    if p.is_alive():
      # Terminate
      p.terminate()
      p.join()
      result.append( None )
      rt = round(time.time() - start_time,5)
    else:
      rt = round(time.time() - start_time,5)
      finished += 1
      total_finished_time += rt
    
    run_time.append( rt )
  
  # sys.stdout = sys.__stdout__
  report_file.write("{:>4}{:>9}{:>10}\n".format("Test", "Result", "Time"))
  for idx,val in enumerate(run_time):
    report_file.write("{:>4}{:>9}{:>10}\n".format(idx, str(result[idx]), val))
  
  report_file.write("Finished: {}\n".format(finished))
  report_file.write("Avg run time: {}\n".format(total_finished_time/finished))
  report_file.close()
  
