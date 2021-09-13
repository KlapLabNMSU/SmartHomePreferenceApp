public class PSchedule {
	private int[]    A; // A is a set of appliances (or devices), usually written as the set of integers {1, . . . , |A|}.
	private double[] E; // E = (e1, e2, ..., e|A|) is a vector of positive real numbers, where each ei represents the energy consumption of device i.
	private int[]    T; // T is a set of time slots, usually written as the set of integers {1, . . . , |T|}.
	private double[] C; // C[i] is cost of 1 kWh at time i
	//TODO finish PSchedule
	
	
	public PSchedule() {
		
	}//end constructor
	
	
	
	//mutators
	//mutators will return 0 on success, and 1 on fail.
	public int setA(int i,int a){
		if(i>=A.length)
			return 1;
		A[i]=a;
		return 0;
	}
	
	public int setE(int i,double a){
		if(i>=E.length)
			return 1;
		E[i]=a;
		return 0;
	}
	
	public int setT(int i,int a){
		if(i>=T.length)
			return 1;
		T[i]=a;
		return 0;
	}
	
	public int setC(int i,double a){
		if(i>=C.length)
			return 1;
		C[i]=a;
		return 0;
	}
	
	
	
	
	//accessors
	public int[] getA() 
	{return A;}
	
	public double[] getE()
	{return E;}
	
	public int[] getT()
	{return T;}
	
	public double[] getC()
	{return C;}
	
}//end class PSchedule
