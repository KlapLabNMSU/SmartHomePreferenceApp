# -*- coding: utf-8 -*-

from context import sg
from sg import PrefMatrix
from sg import Node
from sg import Graph
from sg import Consumption
from IPython import embed
import sys
import os
import time
import multiprocessing

if __name__ == '__main__':
  ntest = int(sys.argv[1])
  appliances = int(sys.argv[2])
  times = int(sys.argv[3])
  path = "log/{}a{}t/".format(appliances, times)

  # generate pref matrix
  pref_path = path + "pref/"
  if not os.path.exists(pref_path):
    os.makedirs(pref_path)
    
  for i in range(0, ntest):
    pref = PrefMatrix(appliances, times)
    pref.generate()
    
    file_path = pref_path + str(i) + ".txt"
    pref.export_to(file_path)
  
  # generate consumption  
  cons_path = path + "consumption/"
  if not os.path.exists(cons_path):
    os.makedirs(cons_path)
    
  for i in range(0, ntest):
    consumption = Consumption(appliances)
    consumption.generate()
    
    file_path = cons_path + str(i) + ".txt"
    consumption.export_to(file_path)
