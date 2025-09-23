#include <iostream>
#include <string>
using namespace std;

// Structure to hold equipment details
struct Equipment {
    int id;
    int quantity;
    int rentalDays;
    double rentalCost;
};

// Array of equipment names (corresponding to IDs 1-16)
const string equipmentNames[16] = {
    "Badminton Racket", "Shuttlecock", "Baseball Bat", "Batting Helmet", "Baseball Glove", "Baseball",
    "Basketball", "Hockey Stick", "Hockey Helmet", "Hockey Gloves", "Hockey Ball", "Tennis Racket",
    "Tennis Ball", "Football Helmet", "Football Pads", "Football"
};

// Stack and Queue size
const int MAX = 100;

// Stack implementation
struct Stack {
    Equipment equipmentStack[MAX];
    int top = -1;
    
    // Return 1 if empty, 0 if not
    int isEmpty() { 
        return top == -1 ? 1 : 0; 
    }
    
    // Return 1 if full, 0 if not
    int isFull() { 
        return top == MAX - 1 ? 1 : 0; 
    }
    
    void push(Equipment e) {
        if (isFull() == 0) {  // Check if stack is not full
            equipmentStack[++top] = e;
            cout << "Equipment pushed to stack!\n";
        } else {
            cout << "Stack is full!\n";
        }
    }
    
    void pop() {
        if (isEmpty() == 0) {  // Check if stack is not empty
            cout << "Popped Equipment ID: " << equipmentStack[top].id << endl;
            top--;
        } else {
            cout << "Stack is empty!\n";
        }
    }
    
    Equipment peek() {
        if (isEmpty() == 0) {  // Check if stack is not empty
            return equipmentStack[top];
        } else {
            cout << "Stack is empty!\n";
            return {};  // Return an empty Equipment
        }
    }
};

// Queue implementation
struct Queue {
    Equipment equipmentQueue[MAX];
    int front = -1, rear = -1;
    
    // Return 1 if empty, 0 if not
    int isEmpty() { 
        return front == -1 || front > rear ? 1 : 0; 
    }
    
    // Return 1 if full, 0 if not
    int isFull() { 
        return rear == MAX - 1 ? 1 : 0; 
    }
    
    void enqueue(Equipment e) {
        if (isFull() == 0) {  // Check if queue is not full
            if (front == -1) front = 0;
            equipmentQueue[++rear] = e;
            cout << "Equipment enqueued!\n";
        } else {
            cout << "Queue is full!\n";
        }
    }
    
    void dequeue() {
        if (isEmpty() == 0) {  // Check if queue is not empty
            cout << "Dequeued Equipment ID: " << equipmentQueue[front].id << endl;
            front++;
        } else {
            cout << "Queue is empty!\n";
        }
    }
};

// Function to calculate rental cost
double calculateRentalCost(int rentalDays) {
    return rentalDays * 20;
}

// Function to add equipment
void addEquipment(Equipment equipment[], Stack &s, Queue &q, int &count) {
    int id;
    cout << "Enter Equipment ID (1-16): ";
    cin >> id;
    
    while(id < 1 || id > 16) {
        cout << "Invalid Equipment ID! Please enter a valid ID (1-16): ";
        cin >> id;
    }

    cout << "Enter Quantity: ";
    cin >> equipment[count].quantity;

    cout << "Enter Number of Rental Days (RM20/days): ";
    cin >> equipment[count].rentalDays;
    cout<<endl;

    equipment[count].id = id;
    equipment[count].rentalCost = calculateRentalCost(equipment[count].rentalDays);

    // Add equipment to stack and queue
    s.push(equipment[count]);
    q.enqueue(equipment[count]);

    count++;
    cout << "Equipment added successfully!\n";
}

// Function to edit equipment details by ID
void editEquipment(Equipment equipment[], int count, int id) {
    int found = 0;
    for (int i = 0; i < count; i++) {
        if (equipment[i].id == id) {
            found = 1;
            cout << "Editing Equipment ID: " << id << ", Name: " << equipmentNames[id - 1] << endl;

            // Get new quantity and rental days
            cout << "Enter new Quantity: ";
            cin >> equipment[i].quantity;
            cout << "Enter new Number of Rental Days (RM20/days): ";
            cin >> equipment[i].rentalDays;

            // Recalculate the rental cost
            equipment[i].rentalCost = calculateRentalCost(equipment[i].rentalDays);

            cout <<"\nEquipment updated successfully!\n";
            break;
        }
    }
    if (found == 0) {
        cout << "Equipment with ID " << id << " not found.\n";
    }
}

// Function to delete equipment by ID
void deleteEquipment(Equipment equipment[], int &count, int id) {
    int found = 0;
    for (int i = 0; i < count; i++) {
        if (equipment[i].id == id) {
            found = 1;
            // Shift the array elements to the left to overwrite the deleted equipment
            for (int j = i; j < count - 1; j++) {
                equipment[j] = equipment[j + 1];
            }
            count--;  // Reduce the count of equipment
            cout << "Equipment with ID " << id << " deleted successfully!\n";
            break;
        }
    }
    if (found == 0) {
        cout << "Equipment with ID " << id << " not found.\n";
    }
}

// Function to display equipment
void displayEquipment(Equipment equipment[], int count) {
    if (count == 0) {
        cout << "No equipment available!\n";
        return;
    }
    for (int i = 0; i < count; i++) {
        cout << "ID: " << equipment[i].id << ", Name: " << equipmentNames[equipment[i].id - 1]
             << ", Quantity: " << equipment[i].quantity
             << ", Rental Days: " << equipment[i].rentalDays
             << ", Rental Cost: RM " << equipment[i].rentalCost << endl;
    }
}

// Function to sort equipment by rental cost using Bubble Sort
void bubbleSort(Equipment equipment[], int count) {
    for (int i = 0; i < count - 1; i++) {
        for (int j = 0; j < count - i - 1; j++) {
            if (equipment[j].rentalCost > equipment[j + 1].rentalCost) {
                // Swap
                Equipment temp = equipment[j];
                equipment[j] = equipment[j + 1];
                equipment[j + 1] = temp;
            }
        }
    }
    cout << "Equipment sorted by rental cost!\n";
}

// Function to search for equipment by ID (Linear Search)
void searchEquipment(Equipment equipment[], int count, int id) {
    int found = 0;
    for (int i = 0; i < count; i++) {
        if (equipment[i].id == id) {
            cout << "Equipment found: ID: " << equipment[i].id << ", Name: " << equipmentNames[equipment[i].id - 1]
                 << ", Quantity: " << equipment[i].quantity
                 << ", Rental Days: " << equipment[i].rentalDays
                 << ", Rental Cost: RM " << equipment[i].rentalCost << endl;
            found = 1;
            break;
        }
    }
    if (found == 0) {
        cout << "Equipment not found!\n";
    }
}

// Function to display receipt and total payment
void displayReceipt(Equipment equipment[], int count) {
    double totalCost = 0;
    cout << "\n-------------------- Receipt --------------------\n";
    for (int i = 0; i < count; i++) {
        cout << "\nID: " << equipment[i].id
             << "\n Name: " << equipmentNames[equipment[i].id - 1]
             << "\n Quantity: " << equipment[i].quantity
             << "\n Rental Days: " << equipment[i].rentalDays
             << "\n Rental Cost: RM " << equipment[i].rentalCost << endl;
        totalCost += equipment[i].rentalCost;
    }
    cout << "-------------------------------------------------\n";
    cout << "Total Payment: RM " << totalCost << "\n";
    cout << "-------------------------------------------------\n";
}

int main() {
    Equipment equipment[100];
    Stack equipmentStack;
    Queue equipmentQueue;
    int count = 0;
    int choice, id;

    cout << "----------Sports Equipment Rental Program----------\n\n";
    cout << "Available equipments:\n\n";
    cout << "------------------------------------------------------------------------------------------------------\n";
    cout << "BADMINTON               BASEBALL              BASKETBALL        \n";
    cout << "[1] Badminton Racket    [3] Baseball Bat      [7] Basketball    \n";
    cout << "[2] Shuttlecock         [4] Batting Helmet                      \n";
    cout << "[5] Baseball Glove      [6] Baseball                          \n\n\n";

    cout << "HOCKEY                  TENNIS                FOOTBALL\n";
    cout << "[8] Hockey Stick        [12] Tennis Racket    [14] Football Helmet\n";
    cout << "[9] Hockey Helmet       [13] Tennis Ball      [15] Football Pads\n";
    cout << "[10] Hockey Gloves                            [16] Football\n";
    cout << "------------------------------------------------------------------------------------------------------\n";

    while (true) {
        cout << "\n1. Add Equipment\n";
        cout << "2. Edit Equipment\n";
        cout << "3. Delete Equipment\n";
        cout << "4. Display Equipment\n";
        cout << "5. Search Equipment\n";
        cout << "6. Sort Equipment by Rental Cost (lowest to highest price)\n";
        cout << "7. Pop from Stack\n";
        cout << "8. Dequeue from Queue\n";
        cout << "9. Exit\n";
        cout << "\nChoose an option: ";
        cin >> choice;

        switch (choice) {
        case 1:
            addEquipment(equipment, equipmentStack, equipmentQueue, count);
            break;
        case 2:
            cout << "Enter Equipment ID to edit: ";
            cin >> id;
            editEquipment(equipment, count, id);
            break;
        case 3:
            cout << "Enter Equipment ID to delete: ";
            cin >> id;
            deleteEquipment(equipment, count, id);
            break;
        case 4:
            displayEquipment(equipment, count);
            break;
        case 5:
            cout << "Enter Equipment ID to search: ";
            cin >> id;
            searchEquipment(equipment, count, id);
            break;
        case 6:
            bubbleSort(equipment, count);
            break;
        case 7:
            equipmentStack.pop();
            break;
        case 8:
            equipmentQueue.dequeue();
            break;
        case 9:
            displayReceipt(equipment, count);  // Display receipt before exiting
            cout << "Thank you for renting with us!\n";
            return 0;
        default:
            cout << "Invalid choice! Please try again.\n";
        }
    }
}
