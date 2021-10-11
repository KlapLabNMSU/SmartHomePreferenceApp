
public class UserPreference {
	private int numTimeSlots;
	private int numAppliances;
	private double H[][][];
	
	
	//default constructor
	public UserPreference() {
		numTimeSlots = 0;
		numAppliances = 0;
		H = null;
	}//end constructor
	
	//constructor
	public UserPreference(int _numAppliances, int _numTimeSlots) {
		numTimeSlots = _numTimeSlots;
		numAppliances = _numAppliances;
		H = new double [numTimeSlots][numAppliances][2];
	}//end constructor
	
	//PRE:  timeSlot, applianceNumber, preference level, standard Deviation
	//POST: the preference and standard deviation are stored in the appliance & time slot
	public void setDist(int appliance, int time, int preference, double standardDeviation) {
		H[time][appliance][0] = (double)preference;
		H[time][appliance][1] = standardDeviation;
	}//end setDist()
	
	//PRE:  timeSlot and appliance number
	//POST: array containing preference and standard deviation.
	public double[] getDist(int appliance,int time) {
		return H[time][appliance];
	}//end getDist()
}
