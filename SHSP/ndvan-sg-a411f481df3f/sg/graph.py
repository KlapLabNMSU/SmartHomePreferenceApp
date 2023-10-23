import collections
import math
import copy 
import sys

from sg import Node
from IPython import embed

# This class represents a directed graph using 
# adjacency list representation 
class Graph: 
  
  # Constructor 
  def __init__(self, **kwargs):
    valid_keys = ["pref_matrix", "alpha", "percentage", "consumption", "consumption_threshold", "optimal", "dependency"]

    # default dictionary to store graph 
    self.graph = collections.defaultdict(list)
    self.terminate     = False
    self.found         = False
    self.min_cost      = sys.maxsize
    self.nodes         = []
    self.min_cost_sche = None
    self.op_leaf       = -1
    
    # price in cents/kwh
    self.eprice = [40.36, 37.65, 36.21, 37.19, 38.05, 39.89, 51.44, 56.36, 57.75, 67.54, 68.89, 66.00, 66.00, 66.00, 63.47, 63.46, 59.91, 94.19, 92.79, 70.17, 60.55, 52.84, 46.50, 44.43]
    
    for key in valid_keys:
      setattr(self, key, kwargs.get(key))
  
    max_mean = 0   
    min_variation = 1
    max_variation = 0
    
    order = list(range(0, self.pref_matrix.ndevice))
    if self.dependency is not None:
      order = self.dependency.attention_order
    seed       = order[0]
    upper_seed = order[1]
      
    for t in range(self.pref_matrix.ntimeslot):
      node_max_mean      = self.pref_matrix.sorted_matrix[seed][t][2]
      node_min_variation = self.pref_matrix.sorted_matrix[seed][t][3]
      node_max_variation = self.pref_matrix.sorted_matrix[seed][t][3]
      node_timeslot      = self.pref_matrix.sorted_matrix[seed][t][1]
      node_device        = seed
      node_parent        = self.pref_matrix.ntimeslot
      node = Node(node_max_mean, node_max_variation, node_min_variation, node_timeslot, node_device, node_parent)

      self.nodes.append(node)
      max_mean = max_mean if max_mean > node_max_mean else node_max_mean
      min_variation = min_variation if min_variation < node_min_variation else node_min_variation
      max_variation = max_variation if max_variation > node_max_variation else node_max_variation
      self.addEdge(self.pref_matrix.ntimeslot, t)

    node = Node(
      max_mean + self.pref_matrix.sorted_matrix[upper_seed][0][2],
      max_variation + self.pref_matrix.sorted_matrix[upper_seed][0][3],
      min_variation + self.pref_matrix.sorted_matrix[upper_seed][0][3],
      self.pref_matrix.sorted_matrix[upper_seed][0][1],
      upper_seed,
      self.pref_matrix.ntimeslot * 2)
    self.nodes.append(node)

    # build up
    for ind, d in enumerate(order[1:]):
      big_sibling = len(self.nodes) - 1
      parent  = len(self.nodes) + self.pref_matrix.ntimeslot - 1
      prev_mm = max_mean
      prev_mv = min_variation
      prev_mav = max_variation
      min_variation = self.nodes[big_sibling].min_variation
      max_mean = self.nodes[big_sibling].max_mean
      max_variation = self.nodes[big_sibling].max_variation
      self.addEdge(parent, big_sibling)
      global numberOfTimeSlots #added by Theo for the print function
      numberOfTimeSlots = self.pref_matrix.ntimeslot #added by Theo for the print function
      for t in range(1, self.pref_matrix.ntimeslot):
        node = Node(
          prev_mm + self.pref_matrix.sorted_matrix[d][t][2],
          prev_mav + self.pref_matrix.sorted_matrix[d][t][3],
          prev_mv + self.pref_matrix.sorted_matrix[d][t][3],
          self.pref_matrix.sorted_matrix[d][t][1],
          d,
          parent)
        self.nodes.append(node)

        self.addEdge(parent, len(self.nodes) - 1)
        min_variation = prev_mv + self.pref_matrix.sorted_matrix[d][t][3] if prev_mv + self.pref_matrix.sorted_matrix[d][t][3] < min_variation else min_variation
        max_mean = prev_mm + self.pref_matrix.sorted_matrix[d][t][2] if prev_mm + self.pref_matrix.sorted_matrix[d][t][2] > max_mean else max_mean
        max_variation = prev_mav + self.pref_matrix.sorted_matrix[d][t][3] if prev_mav + self.pref_matrix.sorted_matrix[d][t][3] > max_variation else max_variation

      if d == order[-1]:
        node = Node(
          max_mean,
          max_variation,
          min_variation,
          -1,
          -1,
          -1)
      else:
        node = Node(
          max_mean + self.pref_matrix.sorted_matrix[order[ind+2]][0][2],
          max_variation + self.pref_matrix.sorted_matrix[order[ind+2]][0][3],
          min_variation + self.pref_matrix.sorted_matrix[order[ind+2]][0][3],
          self.pref_matrix.sorted_matrix[order[ind+2]][0][1],
          order[ind+2],
          parent + self.pref_matrix.ntimeslot)
      self.nodes.append(node)
  
  # function to add an edge to graph 
  def addEdge(self,u,v): 
    self.graph[u].append(v)
    # self.graph[v]
  
  #'Cumulative distribution function for the standard normal distribution'
  def phi(self,x):
    return (1.0 + math.erf(x / math.sqrt(2.0))) / 2.0
  
  def backtrace(self,v):
    if v == -1:
      return []
    
    x = self.backtrace(self.nodes[v].parent)
    
    return [v] + x
  
  # check if preference is satisfied or not
  # 'sche' is an array of node index
  def pref_sat(self,sche):
    # {'max_mean': 5,
    # 'max_variation': 3,
    # 'min_variation': 0.51,
    # 'timeslot': 2,
    # 'device': 0,
    # 'visited': True}
    schedule_by_node = list(map(lambda x: self.nodes[x], sche))
    
    if len(schedule_by_node) == 1:
      sum_mean = schedule_by_node[0].max_mean
      # sum_variation = schedule_by_node[0].min_variation
      sum_variation = schedule_by_node[0].max_variation
    else:
      del schedule_by_node[-1]
      first = schedule_by_node.pop(0)
      # [[0, 0, 0, 0.44], [0, 1, 1, 0.95], [0, 2, 5, 0.51]]
      schedule_by_cell = list(map(lambda x: self.pref_matrix.matrix[x.device][x.timeslot], schedule_by_node))
      # print(schedule_by_cell)
      sum_mean = sum(list(map(lambda x: x[2], schedule_by_cell))) + first.max_mean
      # sum_variation = sum(list(map(lambda x: x[3], schedule_by_cell))) + first.min_variation
      sum_variation = sum(list(map(lambda x: x[3], schedule_by_cell))) + first.max_variation

    if sum_variation < 1:
      sum_variation = 1
    # print(sum_mean, sum_variation, self.phi((self.alpha - sum_mean)/sum_variation))
    return self.phi((self.alpha - sum_mean)/sum_variation) <= 1-self.percentage

  def check_consumption(self,sche):
    schedule_by_node = list(map(lambda x: self.nodes[x], sche))
    del schedule_by_node[-1]
    
    # if current node is root, no need to check
    if not schedule_by_node:
      return True
    
    total_consumption = [0] * self.pref_matrix.ntimeslot

    for n in schedule_by_node:
      total_consumption[n.timeslot] += self.consumption.matrix[n.device]
      if total_consumption[n.timeslot] > self.consumption_threshold:
        return False
    
    # print(total_consumption)
    return True
    
  def check_dependency(self,sche):
    # ignore if the graph is for independent devices.
    if self.dependency is None:
      return True
      
    schedule_by_node = list(map(lambda x: self.nodes[x], sche))
    del schedule_by_node[-1]
    
    # if current node is root, no need to check
    if not schedule_by_node:
      return True
    
    schedule = {}
    for n in schedule_by_node:
      schedule[ n.device ] = n.timeslot
    
    # print(schedule)
    upper_devices = list(map(lambda x: x.device, schedule_by_node))
    
    # only check the current node dependency
    current_device = schedule_by_node[0].device
    dep = self.dependency.set[current_device]
    # print("dev {} dep {}".format(current_device,dep))
    for d in dep:
      if d in upper_devices:
        relation = dep[d]
        if relation == '=':
          if schedule[current_device] != schedule[d]:
            return False
          # if schedule[current_device] == schedule[d]:
          #   print("pass dep {}".format(dep[d]))
          # else:
          #   print("fail dep {}".format(dep[d]))
          #   return False
        elif relation == '!':
          if schedule[current_device] == schedule[d]:
            return False
          # if schedule[current_device] != schedule[d]:
#             print("pass dep {}".format(dep[d]))
#           else:
#             print("fail dep {}".format(dep[d]))
#             return False
        elif relation == '<':
          if schedule[current_device] >= schedule[d]:
            return False
          # if schedule[current_device] < schedule[d]:
#             print("pass dep {}".format(dep[d]))
#           else:
#             print("fail dep {}".format(dep[d]))
#             return False
        elif relation == '>':
          if schedule[current_device] <= schedule[d]:
            return False
          # if schedule[current_device] > schedule[d]:
#             print("pass dep {}".format(dep[d]))
#           else:
#             print("fail dep {}".format(dep[d]))
#             return False
          
    return True
          

  
    
  def print_schedule(self,nodes):
    #predeclare the variables
    deviceNames = open('/xampp/htdocs/SmartHomePreferenceApp/SHSP/ndvan-sg-a411f481df3f/tests/Devices/DeviceNames.txt','r')
    deviceTypes = open('/xampp/htdocs/SmartHomePreferenceApp/SHSP/ndvan-sg-a411f481df3f/tests/Devices/DeviceTypes.txt','r')
    deviceName = ""
    deviceType = ""
    deviceOnHour = 0
    deviceOnMin = 0
    deviceOffHour = 0
    deviceOffMin = 0
    del nodes[-1]
    print("\nDevice Controls:\n")
    for i in nodes:
      print("Device #%d: %d" % (self.nodes[i].device, self.nodes[i].timeslot))
      #get directory path for where files will
      # E:\xampp\htdocs\SmartHomePreferenceApp\SHDS\items
      direct = "/xampp/htdocs/SmartHomePreferenceApp/SHDS/items"
      #set device name dir
      fileName = direct + "/DeviceControl"+str(self.nodes[i].device)+".json"
      f = open(fileName,"w+")
      if(numberOfTimeSlots == 24): #only hours are being used
        deviceName = deviceNames.readline().rstrip('\n')
        deviceType = deviceTypes.readline().rstrip('\n')
        deviceOnHour = self.nodes[i].timeslot 
        deviceOnMin = 00
        deviceOffHour = self.nodes[i].timeslot +1 
        deviceOffMin = 00
      elif(numberOfTimeSlots == 1440): #hours and minutes in use
        deviceName = deviceNames.readline().rstrip('\n')
        deviceType = deviceTypes.readline().rstrip('\n')
        deviceOnHour = ((self.nodes[i].timeslot - (self.nodes[i].timeslot % 60))/60)  # because time slots are 1-1440, the time slot hour is ((ts - ts%60)/60)+1 for ts=time slot
        deviceOnMin = ((self.nodes[i].timeslot)%60)
        deviceOffHour = deviceOnHour+1
        deviceOffMin = deviceOnMin
      else:
        deviceName = deviceNames.readline().rstrip('\n')
        deviceType = deviceTypes.readline().rstrip('\n')
        deviceOnHour = 00
        deviceOnMin = 00
        deviceOffHour = 00
        deviceOffMin = 00
      jsonStruct = "{\n\t\"items\": [\n\t\t{\n\t\t\t\"name\": \"" + deviceName + "\",\n\t\t\t\"type\": \"" + deviceType + "\",\n\t\t\t\"ON\": [\"" + str(int(deviceOnHour)).rjust(2,'0') + ":" + str(int(deviceOnMin)).rjust(2,'0') + "\"],\n\t\t\t\"OFF\": [\"" + str(int(deviceOffHour)).rjust(2,'0') + ":" + str(int(deviceOffMin)).rjust(2,'0') + "\"]\n\t\t}\n\t]\n}"
      print(jsonStruct,file = f)
    print("")

    

  
    
  def DFSUtil(self,v): 
    if self.terminate:
      return
      
    # print(v)
    self.nodes[v].visited = True
    
    sche = self.backtrace(v)
    # print(v, sche)

    # just for debugging
    # print(self.pref_sat(sche))
    # print("")
    
    # don't go further if 
    #   not sat consumption threshold
    #   not sat dependency or 
    #   not sat pref threshold requirement 
    if (not self.pref_sat(sche) or
        not self.check_consumption(sche) or
        not self.check_dependency(sche)):
      return
    
    # print("")
    # terminate if the search reaches a leaf and sat
    if self.dependency is None:
      leaf_device = 0
    else:
      leaf_device = self.dependency.attention_order[0]
      
    if self.nodes[v].device == leaf_device:
      self.terminate = True
      # print out the schedule
      self.print_schedule(self.backtrace(v))
      self.found = True
      return
    
    # grow the tree
    if not self.graph[v]:
      # print(self.nodes[v].__dict__)
      if self.dependency is None:
        start_child_node = (self.nodes[v].device-1) * self.pref_matrix.ntimeslot
      else:
        ind = self.dependency.attention_order.index( self.nodes[v].device )
        start_child_node = (ind-1) * self.pref_matrix.ntimeslot
        # print("start_child_node {}".format(start_child_node))
      end_child_node   = start_child_node + self.pref_matrix.ntimeslot
      for i in range(start_child_node,end_child_node):
        child = copy.deepcopy( self.nodes[i] )
        child.parent = v
        child.visited = False
        self.nodes.append(child)
        self.addEdge(v, len(self.nodes) - 1)

    # get out of recur when error
    # if len(self.nodes) > 20:
    #   return
      
    # recur for all the vertices adjacent to this vertex 
    for i in self.graph[v]: 
      if self.nodes[i].visited == False:
        self.DFSUtil(i) 
  
  def cost(self,sche):
    schedule_by_node = list(map(lambda x: self.nodes[x], sche))
    del schedule_by_node[-1]
    
    total_consumption = [0] * self.pref_matrix.ntimeslot
    for n in schedule_by_node:
      total_consumption[n.timeslot] += self.consumption.matrix[n.device]
    
    cost_by_hour = [a*b for a,b in zip(total_consumption, self.eprice)]
    
    # print(total_consumption)
    # print("Cost by hour {}, sum = {}\n".format(cost_by_hour, sum(cost_by_hour)))
    return sum(cost_by_hour)
    
  def DFSUtil_op(self,v): 
    if self.terminate:
      return
      
    # print(v)
    self.nodes[v].visited = True
    
    sche = self.backtrace(v)
    # print(v, sche)

    # just for debugging
    # print(self.pref_sat(sche))
    # print("")
    
    # don't go further if 
    #   not sat consumption threshold
    #   not sat dependency or 
    #   not sat pref threshold requirement 
    if (not self.pref_sat(sche) or
        not self.check_dependency(sche) or
        (self.min_cost < self.cost(sche))):
      return
    
    # print("")
    # terminate if the search reaches a leaf and sat
    if self.dependency is None:
      leaf_device = 0
    else:
      leaf_device = self.dependency.attention_order[0]
      
    if self.nodes[v].device == leaf_device:
      # print out the schedule
      # self.print_schedule(self.backtrace(v))
      self.op_leaf = v
      self.found = True
      cost_of_current_schedule = self.cost(sche)
      if cost_of_current_schedule < self.min_cost:
        self.min_cost = cost_of_current_schedule
        self.min_cost_sche = sche
      return
      
    # grow the tree
    if not self.graph[v]:
      # print(self.nodes[v].__dict__)
      if self.dependency is None:
        start_child_node = (self.nodes[v].device-1) * self.pref_matrix.ntimeslot
      else:
        ind = self.dependency.attention_order.index( self.nodes[v].device )
        start_child_node = (ind-1) * self.pref_matrix.ntimeslot
        # print("start_child_node {}".format(start_child_node))
              
      end_child_node   = start_child_node + self.pref_matrix.ntimeslot
      for i in range(start_child_node,end_child_node):
        child = copy.deepcopy( self.nodes[i] )
        child.parent = v
        child.visited = False
        self.nodes.append(child)
        self.addEdge(v, len(self.nodes) - 1)

    # get out of recur when error
    # if len(self.nodes) > 20:
    #   return
      
    # recur for all the vertices adjacent to this vertex 
    for i in self.graph[v]: 
      if self.nodes[i].visited == False:
        self.DFSUtil_op(i) 
      
  def DFS(self,v): 
    if self.optimal:
      self.DFSUtil_op(v)
      if self.found:
        print("\nFound optimal sche with cost {}".format(self.min_cost))
        self.print_schedule(self.backtrace(self.op_leaf))
      return self.found
    else:
      self.DFSUtil(v)
      #self.print_schedule(self.backtrace(v))  # prints empty schedule??
      return self.found
      
  # graph size 
  def size(self): 
    s = 0
    for i in self.graph.keys():
      s = s + len(self.graph[i])
    return s
      

