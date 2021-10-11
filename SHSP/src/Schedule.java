//Given a scheduling problem P = ⟨A, E, T, C, L, D⟩, a schedule for P is 
//an |A| × |T| matrix H, where each cell H(i,j) is either false (off) or true (on), 
//representing the status of the device i at time slot j, and

public class Schedule {
	private int numTimeSlots;
	private int numAppliances;
	private boolean H[][];
	
	
	//default constructor
	public Schedule() {
		numTimeSlots = 0;
		numAppliances = 0;
		H = null;
	}//end constructor
	
	//constructor
	public Schedule(int _numAppliances, int _numTimeSlots) {
		numTimeSlots = _numTimeSlots;
		numAppliances = _numAppliances;
		H = new boolean [numTimeSlots][numAppliances];
	}//end constructor
	
	public void setStatus(int appliance,int time,boolean status) {
		H[time][appliance] = status;
	}//
	
	public boolean getStatus(int appliance,int time) {
		return H[time][appliance];
	}//
}//end class Schedule
