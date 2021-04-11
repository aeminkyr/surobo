#include <SocketIoClient.h> 
#include <Arduino.h>
#include <ESP8266WiFi.h>
#include <ESP8266httpUpdate.h>
#include "DHT.h"
#include <EEPROM.h>


#define DHTPIN 12
#define DHTTYPE DHT11
DHT dht(DHTPIN, DHTTYPE);

String espChipID = "\""+WiFi.macAddress()+"\"";
bool macSent = false;

SocketIoClient socketIO;

 String temp;
 String humid;



int minMoisEEPint=;
int maxMoisEEPint;

 int mois_sensor_pin = A0;
 const int relay = 5;

 
int mois_sensor_data;

void setup() {
  EEPROM.begin(512);  //Initialize EEPROM

  String strText;   
  for(int i=0;i<10;i++)  {
    strText = strText + char(EEPROM.read(i)); //Read one by one with starting address of 0x0F    
  }  
 
  Serial.print("EEP ROM MİN MOİS :"+strText);  //Print the text


  String strText2;   
  for(int i=10;i<0;i++)  {
    strText2 = strText2 + char(EEPROM.read(i)); //Read one by one with starting address of 0x0F    
  }  
 
  Serial.print("EEP ROM MİN MOİS :"+strText2);  //Print the text

  pinMode(BUILTIN_LED, OUTPUT);
  pinMode(2, OUTPUT); 
    pinMode(relay, OUTPUT);

  
  Serial.begin(115200);
  WiFi.begin("wifissid", "wifipass");

  Serial.print("Connecting");
  while (WiFi.status() != WL_CONNECTED) {
    digitalWrite(2, LOW); 
    delay(500);
    digitalWrite(2, HIGH); 
    delay(500);
    Serial.println("Connecting...");
  }
      Serial.println("Connected to wifi1!");

      // server address, port and URL
  socketIO.begin("47.254.131.220", 3000,"/socket.io/?transport=websocket");
 dht.begin(); 

/*
  t_httpUpdate_return ret = ESPhttpUpdate.update("nodemcu.aeminkyr.com", 80, "/update.php", "optional current version string here");
switch(ret) {
    case HTTP_UPDATE_FAILED:
        Serial.println("[update] Update failed.");
        break;
    case HTTP_UPDATE_NO_UPDATES:
        Serial.println("[update] Update no Update.");
        break;
    case HTTP_UPDATE_OK:
        Serial.println("[update] Update ok."); // may not called we reboot the ESP
        break;
}
*/

}

//###################################### CUSTOM VOIDS ###########################################

//POMP EVENT HANDLER
void messageEventHandler(const char * payload, size_t length) {
         Serial.printf("server says: %s\n", payload);
        String a = String(payload);
        
           if(a=="ACIK"){
             digitalWrite(BUILTIN_LED, LOW); 
               digitalWrite(relay, HIGH);

         //    Serial.print("ledon!!");
            }else if (a=="KAPALI"){
              digitalWrite(BUILTIN_LED, HIGH); 
                digitalWrite(relay, LOW);

         //     Serial.print("ledoff!!");
            }

}

 String getValue(String data, char separator, int index){
            int found = 0;
            int strIndex[] = {0, -1};
            int maxIndex = data.length()-1;
          
            for(int i=0; i<=maxIndex && found<=index; i++){
              if(data.charAt(i)==separator || i==maxIndex){
                  found++;
                  strIndex[0] = strIndex[1]+1;
                  strIndex[1] = (i == maxIndex) ? i+1 : i;
              }
            }
          
            return found>index ? data.substring(strIndex[0], strIndex[1]) : "";
        }

  void minmaxnMoisEEP(const char * payload, size_t length){

         
       String minmaxDATA = String(payload);
       String maxMois = getValue(minmaxDATA,';',0);
       String minMois = getValue(minmaxDATA,';',1);

    for(int i=0;i<minMois.length();i++){
        EEPROM.put(i, minMois[i]); //Write one by one with starting address of 0x0F
      }
  for(int i=0;i<maxMois.length();i++){
       EEPROM.put(i+10, maxMois[i]); //Write one by one with starting address of 0x0F
      }
      
      EEPROM.commit();    //Store data to EEPROM

      
       String maxMoisEEP;

       for(int i=10;i<15;i++){
        maxMoisEEP = maxMoisEEP + char(EEPROM.read(i)); //Read one by one with starting address of 0x0F    
      }  

      Serial.println("EEP ROM MAX MOİS :"+maxMoisEEP);  //Print the text

      String minMoisEEP;   
        for(int i=0;i<3;i++){
          minMoisEEP = minMoisEEP + char(EEPROM.read(i)); //Read one by one with starting address of 0x0F    
        }  
        Serial.println("EEP ROM MİN MOİS :"+minMoisEEP);  //Print the text

        minMoisEEPint = minMoisEEP.toInt();
        maxMoisEEPint = maxMoisEEP.toInt();

    
  }

//###################################### END OF CUSTOM VOIDS #######################################

uint64_t messageTimestamp;
uint64_t messageTimestamp2;
uint64_t pompTimestamp;



int mois_sensor_percent;
   

void loop() {
//millis cinsinden şimdi
    uint64_t now = millis();

//socket yapısı için gereken loop
    socketIO.loop();

//sunucudan gelen  yanıtları dinlemek için handlerlara başvuruluyor.
    socketIO.on("reply",messageEventHandler);
    socketIO.on("minmaxMois",minmaxnMoisEEP);

   if(now - pompTimestamp > 2000){
              pompTimestamp = now;

      mois_sensor_data = analogRead(mois_sensor_pin);
      mois_sensor_percent = map(mois_sensor_data,550,0,0,100);
      Serial.println(mois_sensor_percent);
      Serial.println("MOIS EEP INT MIN : " + minMoisEEPint);
      Serial.println("MOIS EEP INT MAX : " + maxMoisEEPint);


   
      if(mois_sensor_percent<minMoisEEPint && maxMoisEEPint>mois_sensor_percent){
 
                 digitalWrite(relay, HIGH);

      } else {
                 digitalWrite(relay, LOW);

        
      }

    
   }

      

//Bağlantı kontrolü için tek seferlik mac adresi sunucuya gönderildi.
  if(!macSent){
       socketIO.emit("macAddress", espChipID.c_str());   
       macSent = true;
   }
  
  //######################################-LIVE SENSOR DATA TO SERVER -###################################
  if(now - messageTimestamp > 6000) {
          messageTimestamp = now;

          float t = dht.readTemperature();
          float h = dht.readHumidity();
          
        if (!isnan(h) || !isnan(t)) { 
           temp = String(t);
           humid = String(h);
         } else if (isnan(h) || isnan(t)){
           String hata = "dht11";
           socketIO.emit("sensorfail","\"dht err!\"");
         }

        mois_sensor_data = analogRead(mois_sensor_pin);
        Serial.println("Mois sensor data :"+mois_sensor_data);

        String json = "{\"temp\":"+ temp + ",\"humid\":"+humid+",\"mois\":"+mois_sensor_data+"}";
        socketIO.emit("envData",json.c_str());
        Serial.println("humid:"+humid+" temp:"+temp);         
        Serial.println("MAC:" + espChipID);
        socketIO.emit("message", "\"I am a live!\"");   
  }

//######################################-SEND SENSOR DATA TO SERVER TO DATABASE -###################################
    if(now - messageTimestamp2 > 60000*10) {
          messageTimestamp2 = now;
          mois_sensor_data = analogRead(mois_sensor_pin);
          Serial.println("Mois sensor data :"+mois_sensor_data);
          String json2 = "{\"temp\":"+ temp + ",\"humid\":"+humid+",\"mois\":"+mois_sensor_data+"}";
          socketIO.emit("envDataSave",json2.c_str());
      
    }


      

  
}
