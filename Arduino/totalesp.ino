#include <ESP8266WiFi.h>
#define mega_board Serial
const char *ssid = "Lens's iPhone";                  //hotspot info
const char *password = "coraldefense";               //hotspot info
const char *host = "coraldefense.000webhostapp.com"; //website host link
const int httpPort = 80;                             //port used to send http request
String posturl = "/recieve_arduino_data.php?";       //url to php that handles post requests
String geturl = "/command_file.txt";                 //url that echos commands from the website
String query_string = "";                            //string that contains sensor data from the arduino
int internet_command;                                //int read from web host
WiFiClient client;                                   //client that connects to server
const long send_get_time_trigger = 200;              //triggers after 200 ms to run code
const long send_post_time_trigger = 2000;            //triggers after 2000 ms to run code
unsigned long send_get_previous_time = 0;            //keeps time before the recieve_bt_data code runs
unsigned long send_post_previous_time = 0;           //keeps time before the package and send code runs
int esp_error(int &);                                //Error handler for the esp module
int recieve_query_string();                          //recieves the string that contains all the data info from the mega board
int send_data_request();                             //connects to the server and sends a request to the posturl to send data to the website
int send_get_request();                              //connects to the server and sends a request for the commands from the internet
int descend_data_to_board();                         //sends that command down to the mega board
void setup()
{
    pinMode(LED_BUILTIN, OUTPUT); // Initialize the LED_BUILTIN pin as an output
    mega_board.begin(9600);
    delay(10);
    /* Explicitly set the ESP8266 to be a WiFi-client, otherwise, it by default,
     would try to act as both a client and an access-point and could cause network-issues with other WiFi-devices and WiFi-network. */
    WiFi.mode(WIFI_STA); //setup as Wifi client mode
    WiFi.begin(ssid, password); //connect to wifi network
    while (WiFi.status() != WL_CONNECTED) //don't run until connected to the internet
    {
        delay(500);
    }
}
void loop()
{
    int board_readcheck, querycheck, postcheck, getcheck, descendcheck; //ints keep track if functions work right
    unsigned long current_time = millis();
    //triggers the time to recieve a query string from the arduino board
    if (current_time - send_get_previous_time >= send_get_time_trigger) //loop timing works the same as arduino board 
    {
        digitalWrite(LED_BUILTIN, LOW); // Turn the LED on:note that LOW is the voltage level, easier to tell what is happening and when it is running functions
        if ((getcheck = send_get_request()) < 0)
        {
            esp_error(getcheck);
        }
        if ((descendcheck = descend_data_to_board()) < 0)
        {
            esp_error(descendcheck);
        }
        send_get_previous_time = current_time;
    }
    if (current_time - send_post_previous_time >= send_post_time_trigger)
    {
        digitalWrite(LED_BUILTIN, HIGH); // Turn the LED off by making the voltage HIGH, easier to tell what is happening and when it is running functions
        if ((board_readcheck = recieve_query_string()) >= 0)
        {
            if ((querycheck = send_data_request()) < 0)
            {
                esp_error(querycheck);
            }
        }
        else
        {
            esp_error(board_readcheck);
        }
        send_post_previous_time = current_time;
    }
}
int recieve_query_string() 
{
    String ignore = mega_board.readStringUntil('!'); //char that prefixes the query string sent by the arduino
    query_string = mega_board.readString();
    Serial.println("---ignore");
    Serial.println(ignore);
    query_string.toLowerCase();  //this is all reformatting because these special characters mess up requests 
    query_string.replace(" ", "");  
    query_string.replace("\r", ""); 
    query_string.replace("\n", "");
    Serial.println(query_string);
    return 1;
}

int send_data_request()
{
    if (query_string.equals(""))
    {
        return -1;
    }
    if (!client.connect(host, httpPort))
    {
        return -26;
    }
    String poststring = posturl + query_string; //create the poststring by Concatenation because otherwise it fails to connect properly
    Serial.println(String("--post") + poststring);
    client.print(String("GET ") + poststring + " HTTP/1.1\r\n" + "Host: " + host + "\r\n" + "Connection: close\r\n\r\n"); //I wanted this to be a post method but time constraints and getting it working was more important
    unsigned long timeout = millis();
    while (client.available() == 0)
    {
        if (millis() - timeout > 5000)
        {
            client.stop();
            return 0;
        }
        char c = client.read();
        Serial.print(c);
    }
    while (client.available()) //ignore response unless you're debugging 
    {
        char line = client.read();
        Serial.print(line);
    }
    query_string = "";
    client.stop();
    return 1;
}
int send_get_request()
{
    if (!client.connect(host, httpPort)) //if you can't connect throw a error
    {
        return -27;
    }
    client.print(String("GET ") + geturl + " HTTP/1.1\r\n" + "Host: " + host + "\r\n" + "Connection: close\r\n\r\n"); //send the get request to the server for the commandfile
    unsigned long timeout = millis();
    while (client.available() == 0)
    {
        if (millis() - timeout > 5000)
        {
            client.stop();
            return -31;
        }
    }
    while (client.available())
    {
        String ignore = client.readStringUntil('#'); // ignore the http reply until the command
        internet_command = client.parseInt();
    }
    client.stop();
    return 1;
}
int descend_data_to_board()
{
    if (mega_board.availableForWrite())
    {
        mega_board.print('#'); //preface with special char for internet commands
        mega_board.println(internet_command);
        return 1;
    }
    else
        return -2;
}
int esp_error(int &code)
{
    char message[21];
    sprintf(message, "ESP Error code #%i", code);
    mega_board.println(message);
    return 0;
}
