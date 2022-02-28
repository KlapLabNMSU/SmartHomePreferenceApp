import random

class PrefMatrix:
  # Constructor 
  def __init__(self, ndevice, ntimeslot):
    # default dictionary to store graph 
    self.matrix = []
    self.sorted_matrix = []
    self.ndevice = ndevice
    self.ntimeslot = ntimeslot
    
  def generate(self):    
    for i in range(self.ndevice):
      row = []
      high_pref = random.randint(5, 10)
      for j in range(self.ntimeslot):
        row.append( [i,j,random.randint(0, high_pref), round(random.uniform(0, 1),2)] )

      self.matrix.append(row)
    self.matrix.append( [[i,0,0,0]] )
    self.sort_pref()

  def import_from(self, file_path):
    f = open(file_path, "r")
    dev  = int(f.readline().split()[1])
    time = int(f.readline().split()[1])
    
    for i in range(0, dev):
      timeslots = []
      for j in range(0, time):
        cell = f.readline().split()
        d = int(cell[0])
        t = int(cell[1])
        m = int(cell[2])
        v = float(cell[3])
        attribute = [d, t, m, v]
        timeslots.append(attribute)
      self.matrix.append(timeslots)
      
    self.matrix.append([[dev, 0, 0, 0]]) 
    self.sort_pref()
    # self.matrix.append( [[0, 0, 10, 0.2], [0, 1, 9, 0.1], [0, 2, 6, 0.15]] )
    # self.matrix.append( [[1, 0, 6, 0.01], [1, 1, 8, 0.05], [1, 2, 2, 0.78]] )
    # self.matrix.append( [[2, 0, 6, 0.51], [2, 1, 7, 0.2], [2, 2, 7, 0.99]] )
    # self.matrix.append( [[3, 0, 2, 0.41], [3, 1, 6, 0.67], [3, 2, 6, 0.09]] )
    # self.matrix.append( [[4,0,0,0]] )

  def export_to(self, file_path):
    log_file = open(file_path, "w")
    log_file.write("dev: {}\n".format(self.ndevice))
    log_file.write("time: {}\n".format(self.ntimeslot))  
    for d in range(0, self.ndevice):
      for t in range(0, self.ntimeslot):
        for i in self.matrix[d][t]:
          log_file.write("{} ".format(i))
        log_file.write("\n")  
    log_file.close()
    
  def sort_pref(self):
    def sortMean(c):
      return c[2]

    for i in range(len(self.matrix)):
      x = self.matrix[i][:]
      x.sort(reverse=True, key=sortMean)
      self.sorted_matrix.append( x )
