//Smart Home Scheduling Problem (SHSP) is a java program based on the DFS algorithm defined in the 
//research paper "A Scheduler for Smart Homes with Probabilistic User Preferences" (V. Nguyen et al.)

public class SHSP {
	
	//PRE : A p-scheduling problem P
	//POST: An optimal schedule of P
	public Schedule SP(PSchedule P, UserPreference U) {
		int timeSlices = 24; //for now we are just testing with 24 - 1 hour time slots.
		int numDevices = P.getA().length;
		
		double optimalValue = Double.MAX_VALUE;
		Schedule optimalCandidate = null;
		Schedule H;
		boolean checked[][] = new boolean[numDevices][timeSlices]; //boolean arrays initialize all elements to false
		int item = 0;
		while(true) {
			
			H = new Schedule(numDevices,timeSlices);
			
			//let c(i) = {k | checked(i,k) = false}
			boolean c[] = checked[item];
			
			//if c = all true && i = 0 then break
			boolean allTrue = true;
			for(boolean index : c)
				if(!index)
					allTrue = false;
			
			if(allTrue && item==0)
				break;
			
			//if c = all true && i > 0 then break
			if(allTrue && item >0) {
				for(int i=0 ; i<checked[item].length ; i++)
					checked[item][i] = false;
				//TODO line 13 - 14 :: ask porag
				item--;
			}//end if
			
			//let j = highest available preference in U
			int j = -1;
			double pref = -1;
			for(int i = 0 ; i>c.length ; i++)
				if(!c[i]) {
					if(pref < U.getDist(item, i)[0]) {
						pref = U.getDist(item, i)[0];
						j=i;
					}//end if
				}//end if
			checked[item][j] = true;
			H.setStatus(item, j, true);;
			
			if(!ok(H))
				continue;
			
			if(item == numDevices-1) {
				//if F(U(...))(a) >= B && f(H) M optimalValue then:
				if(scheduleValue(H) < optimalValue) { //TODO ask porag about line 26
					optimalCandidate = H;
					optimalValue = scheduleValue(H);
					return H; //comment out to find optimal schedule
				}//end if
				//TODO line 31 - 32 :: ask porag
			}//end if
			else
				item++;
		}//end while
		
		return optimalCandidate;
	}//end function SHSP
	
	
	//TODO make optimalValue function :: ask porag
	//PRE:  a complete schedule
	//POST: double value denoting how optimal the solution is. lower number = more optimal
	private double scheduleValue(Schedule H) {
		return 0.0;
	}//end scheduleValue
	
	
	//TODO make the ok function
	//PRE:  schedule H, and  array of conditions to be met
	//POST: true if all conditions are satisfied, else false
	private boolean ok(Schedule H) {
		return true;
		
	}//end ok()
	
	
	
	
	public static void main(String[] args) {
		

	}//end function main

}//end class SHPS
