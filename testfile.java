import java.io.*;
import java.util.ArrayList;
public class testfile {

	public static void main(String[] args) {
		String response = "";
		try {
			
			//curl -X GET --header "Accept: application/json" "http://{openHAB_IP}:8080/rest/items?recursive=false"
			String[] commands = {"curl", "-X", "GET", "http://192.168.1.148:8080/rest/items?recursive=false"};
			Process process = Runtime.getRuntime().exec(commands);
			BufferedReader reader = new BufferedReader(new 
			InputStreamReader(process.getInputStream()));
			String line;
			response = "";
			while ((line = reader.readLine()) != null) {
			    response = response + line;
			}//end while
			
			
			
			
			ArrayList<String> names = new ArrayList<String>();
			while(true) {
				int indexOfName = response.indexOf("\"name\"");
				if(indexOfName==-1)
					break;
				int indexOfLabel= response.indexOf("\"label\"");
				String substr = response.substring(indexOfName+8,indexOfLabel-2);
				
				names.add(substr);
				response = response.substring(indexOfLabel+5);
			}//end while
			
			for(int i=0;i<names.size();i++)
				System.out.println(names.get(i));
		}//end try
		catch(Exception e){
			System.out.print("Error: "+e);
		}//end catch
		
		
		
		
		
	}//end main

}//end class
