import random

class Consumption:
  # Constructor 
  def __init__(self, ndevice):
    self.matrix = []
    self.ndevice = ndevice
    
  def generate(self):    
    for i in range(self.ndevice):
      self.matrix.append( random.randint(1, 10) * 100 )
          
  def import_from(self, file_path):
    f = open(file_path, "r")
    dev  = int(f.readline().split()[1])
    
    for i in range(0, dev):
      cell = int(f.readline())
      self.matrix.append(cell)
      
    self.matrix.append( 0 )
      
  def export_to(self, file_path):
    log_file = open(file_path, "w")
    log_file.write("dev: {}\n".format(self.ndevice))
    for d in range(0, self.ndevice):
      log_file.write("{}\n".format(self.matrix[d]))
    log_file.close()  