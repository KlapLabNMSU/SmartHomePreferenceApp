class Node:
  # Constructor 
  def __init__(self, max_mean, max_variation, min_variation, timeslot, device, parent):
    # default dictionary to store graph 
    self.max_mean = max_mean
    self.min_variation = min_variation
    self.max_variation = max_variation
    self.timeslot = timeslot
    self.device = device
    self.visited = False
    self.parent = parent
    