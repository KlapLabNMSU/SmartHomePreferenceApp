import re
import random 
from IPython import embed
from pymongo import MongoClient

class Dependency:
  relation_type = ['=', '!', '<', '>']
  
  
  # Constructor 
  def __init__(self, device, timeslot, count = 0):
    # default dictionary to store graph 
    self.timeslot = timeslot
    self.device = device
    self.set = self.generate(count)
    self.attention_degree = self.count()
    self.attention_order = self.sort_by_attention_degree()
    
    
  def inverse(self, r):
    if r == '=' or r == '!':
      return r
    elif r == '<':
      return '>'
    elif r == '>':
      return '<'

  def precedence(self):
    return []  

  def generate(self, count):
    relations = {}
    for i in range(0, self.device):
      relations[i] = {}
      
    # parallel, 1 before 2, 1 after 3, 2 not parallel 3
    # if you want to say dev d has to run before time slot t, there are 2 way to model
    #   depending on the type of constain you put forward
    #   if it is a soft constrain, change mean of time slot t' > t of d to 1
    #   if it is a hard constrain, remove branch t'>t of d in tree
    # dep = ["0=2", "0<10", "0>9", "0!8", "1>3", "2>3", "4>19", "5<8", "6!15", "7<15"]
    #dep = ["0=2", "1!=3", "2<3"]
    
    dep = []

    # Queries mongo for all relational preferences
    client = MongoClient("mongodb://localhost:27017/")
    db = client["KlapLab"]
    collection = db["relational_preferences"]
    query = collection.find()
    
    for pref in query:
      dep.append(pref["pref"]) 
    
    # for i in range(count):
    #   rel = self.relation_type[ random.randint(0, 3) ]
    #   d1 = random.randint(0, self.device-1)
    #   d2 = random.randint(0, self.device-1)
    #   while d1 == d2:
    #     d2 = random.randint(0, self.device-1)
    #   dep.append( str(d1) + rel + str(d2) )
    
    #print("Dependencies: ", end = " ")
    #for d in dep:
    #  print(d)
      
    for d in dep:
      devices = list(map(lambda x: int(x), re.findall("\d+", d)))
      relation = d[ re.search("[=><!]", d).start() ]
      relations[devices[0]][devices[1]] = relation
      relations[devices[1]][devices[0]] = self.inverse(relation)
    return relations
    
  # how many dependency of a device 
  def count(self):
    return {k: len(v) for k, v in self.set.items()}
      
  def sort_by_attention_degree(self):
    sorted_attention = sorted(self.attention_degree.items(), key=lambda kv: kv[1])
    return list(map(lambda x: x[0] , sorted_attention))

  def __str__(self):
    return f"{self.set}"