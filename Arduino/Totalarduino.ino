#include <dht.h> //Library needed for humidity and temperature sensors
#include <stdlib.h>
//defining device pins
#define bt_state 37
#define uv_pin 23
#define he_pin 25
#define dht_pin 4
#define fs_pin 2
#define sensor_interrupt 0
#define ls_pin 20
#define led_pin 53
#define fwfp_pin 22
#define bt Serial1                       //HC-05 bluetooth chip
#define esp_module Serial2               //ESP module 
                                         //all the information relating to the flow sensors
float flow_rate_ml;                      //keeps track of flow rate
float flow_rate_liters;                  //keeps track of volume
unsigned long flow_total_ml;             //keeps track of the total amount of flow
unsigned long old_flow_time;             //keeps track of time passed for the flow function
volatile byte flow_sensor_pulse_count;   //keeps track of the pulses the flow sensor has sent
const float flow_sensor_tick_rate = 1;   //flow_sensor_tick_rate on the flow sensor 1 tick/ml
const long recieve_time_trigger = 300;   //triggers after 1000 ms to run code
const long package_time_trigger = 1500;  //triggers after 4000 ms to run code
unsigned long recieve_previous_time = 0; //keeps time before the recieve_bt_data code runs
unsigned long package_previous_time = 0; //keeps time before the package and send code runs
bool uv_state_tracker = true;            //bool used to track if the UV light is on or off
dht DHT;                                 //make a dht sensor object to read humidity and temperature
int bt_error(int);                       //error handler
int inboard_error(int);                  //error handler
int internet_error(int);                 //error handler
int get_flow_info();                     //get all the information regarding the flow sensor
int package_internet_bundle();           //builds the query string that will be elevated to the esp module
int ascend_internet_bundle();            //send the internet bundle to the esp module
int recieve_internet_data();             //read the bundle from the espmodule
int get_he_state();                      //get hall effect state and return
int get_ls_state();                      //get level switch state and return
void turn_off_UV();                      //turns off the uv light does not return a value because the call to the pin is void
void turn_on_UV();                       //turns on the uv light does not return a value because the call to the pin is void
int state_check(int);                    //state handler for recieved data
float get_humidity();                    //read humidity and return
float get_temperature();                 //read temperature and return
void turn_on_fwf_pump();                 //turns on the fresh water fill pump
void turn_off_fwf_pump();                //turns off the freshwater fill pump
char *convert_float_to_string(float);    //converts a float to a string
void flow_sensor_pulse_counter();        //used to count pulses, keeps ticks for calculating the flow sensor
char query_string[255];                  //string that holds the post data
String holder;                           //string the converts the char array to string and posts on the esp
char *format_array[5];                   //string array for formatting floats into strings for easy building
void setup()
{
  pinMode(bt_state, INPUT);
  pinMode(led_pin, OUTPUT);
  pinMode(ls_pin, INPUT_PULLUP);
  pinMode(he_pin, INPUT);
  pinMode(uv_pin, OUTPUT);
  pinMode(fs_pin, INPUT);
  pinMode(fwfp_pin, OUTPUT);
  Serial.begin(9600);
  bt.begin(9600);
  esp_module.begin(9600);
  flow_rate_ml = 0;
  flow_rate_liters = 0;
  flow_total_ml = 0;
  old_flow_time = 0;
  flow_sensor_pulse_count = 0;
  attachInterrupt(sensor_interrupt, flow_sensor_pulse_counter, FALLING); //interrupt when voltage falls, and run the function pulseCounter
}

void loop()
{
  int bt_packagecheck, bt_sendcheck, bt_readcheck;                   //checks that make sure functions run properly
  int internet_packagecheck, internet_sendcheck, internet_readcheck; //checks that make sure functions run properly
  unsigned long current_time = millis();
  //recieve data timer
  if (current_time - recieve_previous_time >= recieve_time_trigger) //timer loop will run when the trigger time is surpassed
  {
    internet_readcheck = recieve_internet_data();
    if (internet_readcheck < 0)
    {
      internet_error(internet_readcheck);
      inboard_error(internet_readcheck);
    }
    state_check(internet_readcheck);
    recieve_previous_time = current_time;
  }
  //package and send timer
  if (current_time - package_previous_time >= package_time_trigger) //timer loop will run when the trigger time is surpassed
  {
    internet_packagecheck = package_internet_bundle();
    if (internet_packagecheck < 0)
    {
      internet_error(internet_packagecheck);
      inboard_error(internet_packagecheck);
    }
    ascend_internet_bundle();
    package_previous_time = current_time;
  }

} //eof
void flow_sensor_pulse_counter() //counts up everytime the flow sensor ticks gets reset after get_flow_info() is called
{
  flow_sensor_pulse_count++;
}
char *convert_float_to_string(float n, int stringlength, int precision) //formatting for the sprintf function it doesn't take in floats so floats need to be converted to cstrings
{
  char buff[16];
  char *float_to_be_converted = buff;
  dtostrf(n, stringlength, precision, float_to_be_converted);
  return float_to_be_converted;
}
float get_humidity() //prints and returns the humidity from a DHT11 sensor
{
  DHT.read11(dht_pin);
  float humidity = DHT.humidity;
  Serial.print("HUM:");
  Serial.print(humidity);
  Serial.print("\n");
  return humidity;
}
float get_temperature() //prints and returns the temperature from a DHT11 sensor
{
  DHT.read11(dht_pin);
  float temperature = DHT.temperature;
  Serial.print("TEMP:");
  Serial.print(temperature);
  Serial.print("\n");
  return temperature;
}
int get_he_state() //prints and return the hall effect state
{
  int hestate = digitalRead(he_pin);
  Serial.print("HESTATE:");
  Serial.print(hestate);
  Serial.print("\n");
  return hestate;
}
void turn_off_UV() //turns off the UV light through the relay
{
  Serial.println("Turning off UV light");
  digitalWrite(uv_pin, HIGH);
  uv_state_tracker = false;
}
void turn_on_UV() //turns on the UV light through the relay
{
  Serial.println("Turning on UV light");
  digitalWrite(uv_pin, LOW);
  uv_state_tracker = true;
}
int get_uv_state() //software tracker to see if the UV light is on or off default starts on
{
  int uvstate = uv_state_tracker;
  Serial.print("UVSTATE:");
  Serial.print(uvstate);
  Serial.print("\n");
  return uvstate;
}
int get_ls_state() //level switch reading code
{
  int lsstate = digitalRead(ls_pin);
  Serial.print("LSSTATE:");
  Serial.print(lsstate);
  Serial.print("\n");
  return lsstate;
}
int get_flow_info() //flow sensor information
{
  if (flow_sensor_pulse_count == 0) //no need to
  {
    Serial.print("---No changes to the flow information---");
    Serial.print("Flow Rate:");
    Serial.print(flow_rate_ml);
    Serial.print("mL/min\n");
    Serial.print("Output Liquid Quantity: ");
    Serial.print(flow_total_ml);
    Serial.print("mL");
    Serial.print("\t"); // Print tab space
    long totallitres = flow_total_ml / 1000;
    Serial.print(totallitres);
    Serial.print("L");
    Serial.print("\n");
    flow_sensor_pulse_count = 0;
  }
  if (flow_sensor_pulse_count > 0 && (millis() - old_flow_time > 1000)) //more than one second has passed and a pulse has been recorded
  {
    detachInterrupt(sensor_interrupt); //make sure this function doesn't get preempted
    unsigned long time_passed = millis() - old_flow_time;
    unsigned long seconds_passed = time_passed / 1000;
    unsigned long raw_flow_ml = flow_sensor_pulse_count * flow_sensor_tick_rate; //get the total amount of liquid to pass through sensor its one ml/pulse in the lab
    flow_rate_ml = raw_flow_ml / seconds_passed;
    old_flow_time = millis();
    flow_rate_liters = flow_rate_ml * 1000;
    flow_total_ml += raw_flow_ml; //add it to the running total
    Serial.print("Flow Rate:");
    Serial.print(flow_rate_ml);
    Serial.print("mL/sec\n");
    Serial.print("Output Liquid Quantity: ");
    Serial.print(flow_total_ml);
    Serial.print("mL");
    Serial.print("\t"); // Print tab space
    long totallitres = flow_total_ml / 1000;
    Serial.print(totallitres);
    Serial.print("L");
    Serial.print("\n");
    flow_sensor_pulse_count = 0;
    attachInterrupt(sensor_interrupt, flow_sensor_pulse_counter, FALLING);
  }
  else
  {
    Serial.println("Flow sensor Error");
    inboard_error(-25);
    return -25;
  }
}
void turn_on_fwf_pump() //turns on water pump through the relay
{
  digitalWrite(fwfp_pin, HIGH);
}
void turn_off_fwf_pump() //turns off water pump through the relay
{
  digitalWrite(fwfp_pin, LOW);
}

int state_check(int data) //commmand executor checks to see if the commands have changed if so it issues out the command
{
  Serial.println("state check");
  Serial.println(data);
  switch (data)
  {
  case 112:
    turn_on_UV();
    return 20;
    break;
  case 113:
    turn_off_UV();
    return 21;
    break;
  case 114:
    turn_on_fwf_pump();
    return 22;
    break;
  case 115:
    turn_off_fwf_pump();
    return 22;
    break;
  default:
    return -20;
    break;
  }
}

int inboard_error(int code) //error handler
{
  char message[21];
  sprintf(message, "Error code %i#", code);
  if (bt.availableForWrite() > 0)
  {
    Serial.println(message);
    bt.println(message);
    return 0;
  }
  else
  {
    Serial.println("Error with the error handler sending over bluetooth");
    Serial.println(message);
    return -53;
  }
}
int package_internet_bundle() //data formatter calls all the sensor functions and outputs it into a proper string to be sent to ESP
{
  get_flow_info();
  format_array[0] = convert_float_to_string(get_temperature(), 5, 2);
  format_array[1] = convert_float_to_string(get_humidity(), 5, 2);
  format_array[2] = convert_float_to_string(flow_rate_ml, 5, 2);
  format_array[3] = convert_float_to_string(flow_total_ml, 5, 2);
  format_array[4] = convert_float_to_string(flow_rate_liters, 5, 2);
  int n = sprintf(query_string,
                  "!temperature=%s&humidity=%s&flow_frequency=%s&flow_total_ml=%s&flow_rate_ml=%s&hall_effect_state=%i&level_switch_state=%i&uv_state=%i\r\n",
                  format_array[0], format_array[1], format_array[2], format_array[3], format_array[4], get_he_state(), get_ls_state(), get_uv_state()); //note query string is prefaced with a ! while building
  Serial.println(query_string);
  if (n < 0)
  {
    return -14;
  }
  else
    return 0;
}

int ascend_internet_bundle() //puts the query string into the ESP module stream
{
  Serial.println("Sending String to esp module:");
  Serial.println(query_string);
  String holder(query_string);
  holder.replace(" ", "");
  holder.toLowerCase();
  Serial.println(holder);
  esp_module.println(holder);
  return 2;
}
int internet_error(int code) //error handler
{
  char message[21];
  sprintf(message, "Error code with ESP module %i#", code);
  if (esp_module.availableForWrite())
  {
    esp_module.println(message);
    return 0;
  }
  else
  {
    Serial.println("Error with the error handler ascending data to esp module");
    return -54;
  }
}
int recieve_internet_data() //parse the serial communication with the esp module for the command int and response
{
  String ignore = Serial2.readStringUntil('#');
  int response = Serial2.parseInt();
  Serial.println("RESPONSE");
  Serial.println(response);
  Serial.println(ignore);
  return response;
}
