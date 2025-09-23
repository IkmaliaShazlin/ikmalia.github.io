#include <iostream>
#include <iomanip>

using namespace std;

class Women;
class Men;
class Info{
    private:
        char category, gender; //data members
    public:
        Info(char c, char g) //parameterized constructor
		{
        	category = c;
        	gender = g;
        }
        	
        friend void details(Info &obj, Women &W, Men &M); //friend function 
        
};

class ItemCart{ //base class
	
	protected :
		int code; //data members
		float price;
	public:
		ItemCart() //default constructor
		{
			code=0;
			price=0;
		}
		void cart() //member function
        {
        	cout<<"\nItem added to shopping cart!\n";
		}
		
};



class Women : public ItemCart{ //derived class
	
    public:
    	
    	Women() //default constructor
    	{
    		code=0; //code and price data members inherited from class ItemCart
    		price=0.0;
		}
        void clothing() //member function to display Women's clothes
		{
            cout<<"\n+++++++++++++ WOMEN'S CLOTHES ++++++++++++++"<<endl;
            cout<<"\n[1] Dress           RM 59.00";
            cout<<"\n[2] Blouse          RM 54.90";
            cout<<"\n[3] Skirt           RM 54.95";
            cout<<"\n[4] Jeans           RM 28.00";
            cout<<"\n[5] Jacket          RM 59.90";
			cout<<"\n\nEnter clothing code : "; //prompt user to enter choice of clothes
            cin>>code;
            
	        while(code<1 || code>5) //prompt user to enter the code again if it is not on the list
	        {
	       	    cout<<"\nInvalid item code!\nPlease enter again : ";
                cin>>code;
		    }
			            
            if(code==1) //declaring price for each clothes
               price=59.00;
            else if(code==2)
               price=54.90;
            else if(code==3)
               price=54.95;
            else if(code==4)
               price=28.00;
            else
               price=59.90;
            
        }

        void accessory() //member function to display women's accessories
		{
            cout<<"\n+++++++++++ WOMEN'S ACCESSORIES +++++++++++++"<<endl;
            cout<<"\n[1] Bow             RM 6.55";
            cout<<"\n[2] Shawl           RM 17.99";
            cout<<"\n[3] Bracelet        RM 30.40";
            cout<<"\n[4] Watch           RM 40.75";
            cout<<"\n[5] Handbag         RM 70.90";
			cout<<"\n\nEnter accessory code : "; //prompt user to enter choice of accessory
            cin>>code;
            
	        while(code<1 || code>5) //prompt user to enter the code again if it is not on the list
	        {
	       	    cout<<"\nInvalid item code!\nPlease enter again : ";
                cin>>code;
		    }
            
            if(code==1) //declaring price for each accessory
               price=6.55;
            else if(code==2)
               price=17.99;
            else if(code==3)
               price=30.40;
            else if(code==4)
               price=40.75;
            else
               price=70.90;
            
        }
        
        float getPrice() //accessor function
        {
            return price;
		}
		
};

class Men : public ItemCart{ //derived class
    public:
    	
    	Men() //default constructor
    	{
    		code=0; //code and price data members inherited from class ItemCart
    		price=0.0;
		}
        void clothing() //member function to display men's clothes
		{
            cout<<"\n++++++++++++++ MEN'S CLOTHES +++++++++++++++"<<endl;
            cout<<"\n[1] Polo Shirt      RM 85.60";
            cout<<"\n[2] Sweater         RM 95.20";
            cout<<"\n[3] Cargo pants     RM 95.00";
            cout<<"\n[4] Cardigan        RM 63.20";
            cout<<"\n[5] Shorts          RM 47.40";
			cout<<"\n\nEnter clothing code : "; //prompt user to enter choice of clothes
            cin>>code;
            
	        while(code<1 || code>5) //prompt user to enter the code again if it is not on the list
	        {
	       	    cout<<"\nInvalid item code!\nPlease enter again : ";
                cin>>code;
		    }
            
            if(code==1) //declaring price for each clothes
               price=85.60;
            else if(code==2)
               price=95.20;
            else if(code==3)
               price=95.00;
            else if(code==4)
               price=63.20;
            else
               price=47.40;
        }

        void accessory() //member function to display men's accessory
		{
            cout<<"\n++++++++++++ MEN'S ACCESSORIES +++++++++++++"<<endl;
            cout<<"\n[1] Watch           RM 70.90";
            cout<<"\n[2] Belt            RM 40.90";
            cout<<"\n[3] Sunglasses      RM 25.00";
            cout<<"\n[4] Tie             RM 35.90";
            cout<<"\n[5] Cap             RM 33.95";
			cout<<"\n\nEnter accessory code : "; //prompt user to enter choice of accessory
            cin>>code;
            
	        while(code<1 || code>5) //prompt user to enter the code again if it is not on the list
	        {
	       	    cout<<"\nInvalid item code!\nPlease enter again : ";
                cin>>code;
		    }
            
            if(code==1) //declaring price for each accessory
               price=70.90;
            else if(code==2)
               price=40.90;
            else if(code==3)
               price=25.00;
            else if(code==4)
               price=35.90;
            else
               price=33.95;
        }
        
        float getPrice() //accessor function
        {
        	return price;
		}
};

void details(Info &obj, Women &W, Men &M) //friend function that creates object for class Info, Women, and Men
{
    char size, colour; 
    
	if (obj.gender == 'M' || obj.gender == 'm' ) //has access to private data member 'gender' from class Info
	{
        if(obj.category == 'C' || obj.category == 'c') //has access to private data member 'category' from class Info
        {
		   M.clothing(); //will execute clothing() function from class 'Men'
	    }
        else
        {
		   M.accessory(); //will execute accessory() function from class 'Men'
		}    
    }
	else
	{
        if (obj.category == 'C'|| obj.category == 'c')
        {
		   W.clothing(); //will execute clothing() function from class 'Women'
	    }        
        else
        {
		   W.accessory(); //will execute accessory() function from class 'Women'
		}
    }
        cout<<"\n[A] Red   [B] White   [C] Black"; //displaying the colour options
	    cout<<"\n[D] Blue  [E] Pink    [F] Purple"<<endl;
	    cout<<"\nEnter colour code : "; //prompt user to enter choice of colour
	    cin>>colour;
	       
	    while(colour!='A' && colour!='B' && colour!='C' && colour!='D' && colour!='E' && colour!='F' && colour!='a' && colour!='b' && colour!='c' && colour!='d' && colour!='e' && colour!='f')
	    {
	       	cout<<"\nInvalid colour code!\nPlease enter again : "; //prompt user to enter the code again if it is not on the list
            cin>>colour;
		}
		
		if(obj.category=='C' || obj.category=='c')
	    {
            cout<<"\n[A] XS    [B] S       [C] M"; //displaying the size options
	        cout<<"\n[D] L     [E] XL      [F] XXL"<<endl;
	        cout<<"\nEnter size code : "; //prompt user to enter choice of size
	        cin>>size;
	       
	        while(size!='A' && size!='B' && size!='C' && size!='D' && size!='E' && size!='F' && colour!='a' && colour!='b' && colour!='c' && colour!='d' && colour!='e' && colour!='f')
	        {
	       	    cout<<"\nInvalid size code!\nPlease enter again : "; //prompt user to enter the code again if it is not on the list
                cin>>size;
		    }
	    }
	    
        W.cart(); //execute the cart() function from base class 'Info' that's derived from class 'Women'
    
    cout<<"\n++++++++++++++++++++++++++++++++++++++++++++"<<endl;
}

int main() {
	
	char category, gender, answer;
	float total=0; //set total to 0
	string name, address;
		
	cout<<"\n*****     *******    *     *         *******";     
	cout<<"\n*     *   *          **    *              *";	 
	cout<<"\n*         *          * *   *             *";   
	cout<<"\n*  ****   *******    *  *  *   *****    *";   
	cout<<"\n*     *   *          *   * *           *";    
	cout<<"\n*     *   *          *    **          *";      
	cout<<"\n*****     *******    *     *         *******";	  

	
	cout<<"\n\n===== WELCOME TO GEN-Z CLOTHING OUTLET =====\n"<< endl;
	
	int item=0; //set item to 0
	
	do{
		
	cout<< "\n       [M - MEN] || [W - WOMEN]"<<endl;
	cout<<"\nEnter gender code : "; //prompt user to enter gender
    cin >> gender;
    
	while(gender!='M' && gender!='W' && gender!='m' && gender!='w') //prompt user to enter the code again if it is not on an option
	{
		cout<<"\nInvalid gender code!\nPlease enter again : "; 
        cin>>gender;
	}
			
    cout << "\n [C - Clothings] || [A - Accessories]\n"; 
    cout << "\nEnter category code : "; //prompt user to enter category
    cin >> category;
            
    while(category!='C' && category!='A' && category!='c' && category!='a') //prompt user to enter the code again if it is not an option
	{
		cout<<"\nInvalid category code!\nPlease enter again : ";
        cin>>category;
	}
	
	ItemCart IC; //declaring object for class ItemCart
    Women *W; //declaring object for class Women using pointer
    W = new Women[item]; // creating memory allocation for class Women and turning it into array of object W
    Men *M; //declaring object for class Men using pointer
    M = new Men[item]; // creating memory allocation for class Men and turning it into array of object M
    Info obj(category, gender); //declaring object for class Info while passing category and gender as parameters
    details(obj, W[item], M[item]); //calling function details while passing obj, W[item], M[item] as parameters
    
    item++; //increase item to 1
        
    for(int i=0; i<item; i++) //using loop to calculate the total price
    {
    	    if(gender=='M' || gender=='m')
    	        total+= (M+i)->getPrice();
	        else
		        total+= W[i].getPrice();
	}
	
	delete [] W; //deleting memory from object W[item]
	delete [] M; //deleting memory from object M[item]
	
    cout<<"\nWould you like to add more items? Y/N : "; //ask user if they want to add more items
    cin>>answer;
    while(answer!='Y' && answer!='N' && answer!='y' && answer!='n' ) //prompt user to enter the code again if it is not an option
    {
    	cout<<"Invalid code!\nPlease enter again : ";
    	cin>>answer;
	}
    
	}while(answer=='Y' || answer=='y' ); //loop goes on if the user wants to add more items
	
	cin.ignore();
	cout<<"\nEnter Name          : "; //prompt user to enter name
	getline(cin, name);
	cout<<"Enter House Address : "; //prompt user to enter address
	getline(cin, address);
	
	cout<<"\n================ RECEIPT ==================="<<endl; //display receipt
	cout<<"\nBuyer's Name     : "<<name;
	cout<<"\nBuyer's Address  : "<<address;
	cout<<"\nAmmount of items : "<<item;
	cout<<"\nTotal            : RM"<<fixed<<setprecision(2)<<total<<endl;
	cout<<"\nThank you for shopping! Your order will be delivered in 3 to 5 business days <3";

    return 0;
}

