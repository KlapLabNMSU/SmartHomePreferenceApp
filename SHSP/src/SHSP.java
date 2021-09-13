//Smart Home Scheduling Problem (SHSP) is a java program based on the DFS algorithm defined in the 
//research paper "A Scheduler for Smart Homes with Probabilistic User Preferences" (V. Nguyen et al.)

public class SHSP {
	
	//PRE : A p-scheduling problem P
	//POST: An optimal schedule of P
	public void SP(PSchedule P) {
		
		//TODO line 1
		//TODO line 2
		Schedule H = null;
		boolean[][] checked = new boolean[P.getA().length][P.getT().length];
		int i=1; 
		int k=1;//TODO figure out what k is
		while(true) {
			P.setC(i, k);
			checked[i][k] = false;
			if(P.getC()[i] == -1 && i==1)
				break;
			if(P.getC()[i] == -1 && i>1) {
				for(int k2 = 0;k2<checked[i].length ; k2++)
					checked[i][k2] = false;
				//TODO line 13+
			}//end if
			
		}//end while
	}//end function SHSP
	
	
	
	
	
	
	public static void main(String[] args) {
		//TODO

	}//end function main

}//end class SHPS
