#include <sofmo.h>
sofmo modem;

#define receiveLED 2
uint8_t hello[14] = "Hello world!\n";
uint8_t lets[29] = "Let's communicate in sound!\n";
void setup() {
  modem.begin();
  pinMode(receiveLED, OUTPUT);
}
void loop() {
  while (modem.available() >= 1) {//sofmoが受信すると
    //    digitalWrite(receiveLED, HIGH);
    //    delay(500);
    //    digitalWrite(receiveLED, LOW);
    char c = modem.read();
    if (c == 'H') {
      modem.write(hello, 13);
    } else if (c == 'S') {
      modem.write(lets, 29);
    } else {
      modem.write(c);//オウム返し
      delay(10);
    }
  }
}


